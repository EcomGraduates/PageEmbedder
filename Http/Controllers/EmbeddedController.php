<?php

namespace Modules\PageEmbedder\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmbeddedController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Show the embedded page.
     */
    public function show(Request $request, $path = '')
    {
        // Get requested path and prepend embedded/ to match our stored format
        $fullPath = 'embedded/' . $path;
        
        // Get all embedded pages from database
        $embeddedPagesJson = Option::get('pageembedder_pages', '[]');
        $pages = is_array($embeddedPagesJson) ? $embeddedPagesJson : json_decode($embeddedPagesJson, true);
        
        // Find the matching page
        $matchedPage = null;
        foreach ($pages as $page) {
            if (!empty($page['path']) && $page['path'] === $fullPath) {
                $matchedPage = $page;
                break;
            }
        }
        
        // If no matching page found, return 404
        if (!$matchedPage) {
            abort(404, __('Embedded page not found'));
        }
        
        // Check if the page is admin-only and if the current user has admin permissions
        if (!empty($matchedPage['admin_only']) && $matchedPage['admin_only']) {
            // Check if user is an admin
            if (!\Auth::user()->isAdmin()) {
                abort(403, __('You do not have permission to view this page'));
            }
        }
        
        // Check if this is a custom HTML page
        $isCustomHTML = isset($matchedPage['is_custom_html']) && $matchedPage['is_custom_html'];
        $customHTML = $isCustomHTML && isset($matchedPage['custom_html']) ? $matchedPage['custom_html'] : '';
        
        // If it's custom HTML, add CSP nonce to script tags
        if ($isCustomHTML && !empty($customHTML)) {
            $customHTML = $this->addCspNonceToScripts($customHTML);
        }
        
        // If it's not custom HTML, prepare the URL with parameters
        $url = '';
        if (!$isCustomHTML) {
            // Check if we need to pass any query parameters
            $url = isset($matchedPage['url']) ? $matchedPage['url'] : '';
            
            // URL must be provided for non-custom HTML pages
            if (empty($url)) {
                abort(500, __('Embedded page URL is missing'));
            }
            
            // Forward query parameters if configured to do so
            $forwardParams = $request->except(['_token']);
            if (!empty($forwardParams) && isset($matchedPage['forward_params']) && $matchedPage['forward_params']) {
                $separator = (strpos($url, '?') !== false) ? '&' : '?';
                $url .= $separator . http_build_query($forwardParams);
            }
            
            // Handle auto-login if configured (this would need to be implemented per service)
            $autoLogin = isset($matchedPage['auto_login']) && $matchedPage['auto_login'];
            $currentUser = \Auth::user();
            
            // Basic auto-login via URL parameters (if supported by the target system)
            if ($autoLogin && $currentUser) {
                $separator = (strpos($url, '?') !== false) ? '&' : '?';
                $url .= $separator . 'email=' . urlencode($currentUser->email) . '&name=' . urlencode($currentUser->name);
            }
        }
        
        return view('pageembedder::embedded', [
            'title' => $matchedPage['title'],
            'url' => $url,
            'hideHeader' => isset($matchedPage['hide_header']) ? (bool)$matchedPage['hide_header'] : false,
            'hideTitle' => isset($matchedPage['hide_title']) ? (bool)$matchedPage['hide_title'] : false,
            'customCSS' => isset($matchedPage['custom_css']) ? $matchedPage['custom_css'] : '',
            'customJS' => isset($matchedPage['custom_js']) ? $matchedPage['custom_js'] : '',
            'seamless' => isset($matchedPage['seamless']) ? (bool)$matchedPage['seamless'] : true,
            'isCustomHTML' => $isCustomHTML,
            'customHTML' => $customHTML,
        ]);
    }
    
    /**
     * Add CSP nonce attribute to script tags in custom HTML.
     * 
     * @param string $html The HTML content to process
     * @return string The processed HTML with CSP nonce attributes added
     */
    protected function addCspNonceToScripts($html)
    {
        // Rather than trying to add the Blade directive directly (which won't be processed),
        // we'll add a marker that will be replaced in the view
        $processed = preg_replace_callback(
            '/<script(.*?)>/i',
            function($matches) {
                $attributes = $matches[1];
                
                // Skip if it already has a nonce attribute
                if (strpos($attributes, 'nonce=') !== false) {
                    return $matches[0];
                }
                
                // Mark it for nonce addition in the blade view
                return '<script' . $attributes . ' data-csp-nonce="true">';
            },
            $html
        );
        
        return $processed;
    }
} 