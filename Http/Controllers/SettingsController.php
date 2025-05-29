<?php

namespace Modules\PageEmbedder\Http\Controllers;

use App\Option;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('roles:admin');
    }
    
    /**
     * Show the Page Embedder settings page.
     */
    public function index()
    {
        // Get stored pages
        $embeddedPagesJson = Option::get('pageembedder_pages', '[]');
        $embeddedPages = is_array($embeddedPagesJson) ? $embeddedPagesJson : json_decode($embeddedPagesJson, true);
        
        if (!is_array($embeddedPages)) {
            $embeddedPages = [];
        }
        
        // Get module version from module.json
        $moduleInfo = json_decode(file_get_contents(base_path('Modules/PageEmbedder/module.json')), true);
        $moduleVersion = isset($moduleInfo['version']) ? $moduleInfo['version'] : '1.0.0';
        
        return view('pageembedder::settings', [
            'embeddedPages' => $embeddedPages,
            'moduleVersion' => $moduleVersion
        ]);
    }
    
    /**
     * Save the Page Embedder settings.
     */
    public function save(Request $request)
    {
        $request->validate([
            'titles' => 'required|array',
            'titles.*' => 'required|string|max:100',
            'urls' => 'array',
            'urls.*' => 'nullable|url|max:1000',
            'custom_html' => 'array',
            'custom_html.*' => 'nullable|string|max:20000',
            'paths' => 'required|array',
            'paths.*' => 'required|string|max:100|regex:/^[a-zA-Z0-9\-_\/]+$/',
            'hide_headers' => 'array',
            'hide_title' => 'array',
            'seamless' => 'array',
            'forward_params' => 'array',
            'auto_login' => 'array',
            'in_navbar' => 'array',
            'admin_only' => 'array',
            'icon_class' => 'array',
            'icon_class.*' => 'nullable|string|max:50',
            'custom_css' => 'array',
            'custom_css.*' => 'nullable|string|max:5000',
            'custom_js' => 'array',
            'custom_js.*' => 'nullable|string|max:5000',
        ], [
            'titles.*.required' => __('Title is required'),
            'urls.*.url' => __('URL must be valid'),
            'paths.*.required' => __('Path is required'),
            'paths.*.regex' => __('Path must contain only letters, numbers, hyphens, underscores, and forward slashes'),
            'custom_html.*.max' => __('Custom HTML content is too long (max 20000 characters)'),
            'custom_css.*.max' => __('Custom CSS is too long'),
            'custom_js.*.max' => __('Custom JavaScript is too long'),
        ]);
        
        $titles = $request->input('titles', []);
        $urls = $request->input('urls', []);
        $customHTML = $request->input('custom_html', []);
        $paths = $request->input('paths', []);
        $hideHeaders = $request->input('hide_headers', []);
        $hideTitle = $request->input('hide_title', []);
        $seamless = $request->input('seamless', []);
        $forwardParams = $request->input('forward_params', []);
        $autoLogin = $request->input('auto_login', []);
        $inNavbar = $request->input('in_navbar', []);
        $adminOnly = $request->input('admin_only', []);
        $iconClass = $request->input('icon_class', []);
        $customCSS = $request->input('custom_css', []);
        $customJS = $request->input('custom_js', []);
        
        $pages = [];
        
        // Combine the arrays into a structured format
        for ($i = 0; $i < count($titles); $i++) {
            if (isset($titles[$i]) && isset($paths[$i])) {
                // Determine if using custom HTML based on content type radio selection
                $contentType = $request->input('content_type_' . $i, 'url');
                $isCustomHTML = ($contentType === 'html');
                
                // Ensure path starts with embedded/ to match our route
                $path = 'embedded/' . ltrim(trim($paths[$i]), '/');
                
                $pages[] = [
                    'title' => trim($titles[$i]),
                    'url' => !$isCustomHTML && isset($urls[$i]) ? trim($urls[$i]) : '',
                    'custom_html' => $isCustomHTML && isset($customHTML[$i]) ? trim($customHTML[$i]) : '',
                    'is_custom_html' => $isCustomHTML,
                    'path' => $path,
                    'hide_header' => isset($hideHeaders[$i]) ? true : false,
                    'hide_title' => isset($hideTitle[$i]) ? true : false,
                    'seamless' => isset($seamless[$i]) ? true : false,
                    'forward_params' => isset($forwardParams[$i]) ? true : false,
                    'auto_login' => isset($autoLogin[$i]) ? true : false,
                    'in_navbar' => isset($inNavbar[$i]) ? true : false,
                    'admin_only' => isset($adminOnly[$i]) ? true : false,
                    'icon_class' => isset($iconClass[$i]) ? trim($iconClass[$i]) : 'glyphicon-bookmark',
                    'custom_css' => isset($customCSS[$i]) ? trim($customCSS[$i]) : '',
                    'custom_js' => isset($customJS[$i]) ? trim($customJS[$i]) : '',
                ];
            }
        }
        
        // Save to database
        Option::set('pageembedder_pages', $pages);
        
        \Session::flash('flash_success_floating', __('Settings saved'));
        
        return redirect()->route('pageembedder.settings');
    }
} 