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
        
        // Get navbar links separately
        $navbarLinksJson = Option::get('pageembedder_navbar_links', '[]');
        $navbarLinks = is_array($navbarLinksJson) ? $navbarLinksJson : json_decode($navbarLinksJson, true);
        
        if (!is_array($navbarLinks)) {
            $navbarLinks = [];
        }
        
        // Merge them for backward compatibility in the view
        // Give navbar links high indices to avoid conflicts
        $startIndex = 1000;
        foreach ($navbarLinks as $link) {
            $link['is_navbar_link'] = true;
            $embeddedPages[$startIndex] = $link;
            $startIndex++;
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
     * Save embedded pages only.
     */
    public function saveEmbedded(Request $request)
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
        
        // Get existing pages
        $existingPagesJson = Option::get('pageembedder_pages', '[]');
        $existingPages = is_array($existingPagesJson) ? $existingPagesJson : json_decode($existingPagesJson, true);
        
        if (!is_array($existingPages)) {
            $existingPages = [];
        }
        
        // Keep only navbar links from existing pages
        $navbarLinks = array_filter($existingPages, function($page) {
            return isset($page['is_navbar_link']) && $page['is_navbar_link'];
        });
        
        // Process embedded pages from form
        $embeddedPages = $this->processEmbeddedPagesFromRequest($request);
        
        // Merge navbar links with new embedded pages
        $allPages = array_values(array_merge($embeddedPages, $navbarLinks));
        
        // Save to database
        Option::set('pageembedder_pages', $allPages);
        
        \Session::flash('flash_success_floating', __('Embedded pages saved'));
        
        return redirect()->route('pageembedder.settings');
    }
    
    /**
     * Save navbar links only.
     */
    public function saveNavbar(Request $request)
    {
        // Get form data
        $titles = $request->input('titles', []);
        $externalUrls = $request->input('external_url', []);
        $iconClasses = $request->input('icon_class', []);
        $openInNewTab = $request->input('open_in_new_tab', []);
        $adminOnly = $request->input('admin_only', []);
        $linkIndices = $request->input('link_indices', []);
        
        // If no data provided, clear navbar links
        if (empty($titles)) {
            Option::set('pageembedder_navbar_links', []);
            \Session::flash('flash_success_floating', __('Navbar links saved'));
            return redirect()->route('pageembedder.settings') . '#navbar-links-tab';
        }
        
        // Validate each entry
        $errors = [];
        $navbarLinks = [];
        
        for ($i = 0; $i < count($titles); $i++) {
            // Skip empty entries
            if (empty(trim($titles[$i])) && empty(trim($externalUrls[$i] ?? ''))) {
                continue;
            }
            
            // Validate non-empty entries
            if (empty(trim($titles[$i]))) {
                $errors[] = __('Title is required for link #') . ($i + 1);
                continue;
            }
            
            if (empty($externalUrls[$i]) || !filter_var($externalUrls[$i], FILTER_VALIDATE_URL)) {
                $errors[] = __('Valid URL is required for link "') . $titles[$i] . '"';
                continue;
            }
            
            // Get the original index if available
            $originalIndex = isset($linkIndices[$i]) ? $linkIndices[$i] : null;
            
            // Add valid link
            $navbarLinks[] = [
                'title' => trim($titles[$i]),
                'external_url' => trim($externalUrls[$i]),
                'icon_class' => isset($iconClasses[$i]) ? trim($iconClasses[$i]) : 'glyphicon-link',
                'open_in_new_tab' => isset($openInNewTab[$originalIndex]) && $openInNewTab[$originalIndex] == '1',
                'admin_only' => isset($adminOnly[$originalIndex]) && $adminOnly[$originalIndex] == '1',
                'is_navbar_link' => true,
                'path' => '', // No path needed for navbar links
                'url' => '',
                'in_navbar' => true,
            ];
        }
        
        if (!empty($errors)) {
            \Session::flash('flash_error_floating', implode('<br>', $errors));
            return redirect()->route('pageembedder.settings')->withInput()->with('active_tab', 'navbar-links-tab');
        }
        
        // Save navbar links separately
        Option::set('pageembedder_navbar_links', $navbarLinks);
        
        \Session::flash('flash_success_floating', __('Navbar links saved'));
        
        return redirect()->route('pageembedder.settings') . '#navbar-links-tab';
    }
    
    /**
     * Delete an embedded page.
     */
    public function deleteEmbedded(Request $request)
    {
        $pageIndex = $request->input('page_index');
        
        // Get existing pages
        $existingPagesJson = Option::get('pageembedder_pages', '[]');
        $existingPages = is_array($existingPagesJson) ? $existingPagesJson : json_decode($existingPagesJson, true);
        
        if (!is_array($existingPages)) {
            $existingPages = [];
        }
        
        // Remove the page at the specified index
        $filteredPages = [];
        foreach ($existingPages as $index => $page) {
            if ($index != $pageIndex) {
                $filteredPages[] = $page;
            }
        }
        
        // Save to database
        Option::set('pageembedder_pages', array_values($filteredPages));
        
        \Session::flash('flash_success_floating', __('Page deleted'));
        
        return redirect()->route('pageembedder.settings');
    }
    
    /**
     * Delete a navbar link.
     */
    public function deleteNavbar(Request $request)
    {
        $linkIndex = $request->input('link_index');
        
        // Get existing navbar links
        $navbarLinksJson = Option::get('pageembedder_navbar_links', '[]');
        $navbarLinks = is_array($navbarLinksJson) ? $navbarLinksJson : json_decode($navbarLinksJson, true);
        
        if (!is_array($navbarLinks)) {
            $navbarLinks = [];
        }
        
        // Since navbar links start at index 1000 in the view, adjust the index
        $actualIndex = $linkIndex - 1000;
        
        // Remove the link at the specified index
        if (isset($navbarLinks[$actualIndex])) {
            array_splice($navbarLinks, $actualIndex, 1);
        }
        
        // Save to database
        Option::set('pageembedder_navbar_links', array_values($navbarLinks));
        
        \Session::flash('flash_success_floating', __('Link deleted'));
        
        return redirect()->route('pageembedder.settings') . '#navbar-links-tab';
    }
    
    /**
     * Process embedded pages from request.
     */
    private function processEmbeddedPagesFromRequest(Request $request)
    {
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
                    'is_navbar_link' => false,
                ];
            }
        }
        
        return $pages;
    }
    
    /**
     * Save the Page Embedder settings (legacy method for backward compatibility).
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
            'keep_page' => 'array',
            'keep_page.*' => 'nullable|in:0,1',
            'external_url' => 'array',
            'external_url.*' => 'nullable|url|max:1000',
            'open_in_new_tab' => 'array',
            'is_navbar_link' => 'array',
        ], [
            'titles.*.required' => __('Title is required'),
            'urls.*.url' => __('URL must be valid'),
            'paths.*.required' => __('Path is required'),
            'paths.*.regex' => __('Path must contain only letters, numbers, hyphens, underscores, and forward slashes'),
            'custom_html.*.max' => __('Custom HTML content is too long (max 20000 characters)'),
            'custom_css.*.max' => __('Custom CSS is too long'),
            'custom_js.*.max' => __('Custom JavaScript is too long'),
            'external_url.*.url' => __('External URL must be valid'),
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
        $keepPage = $request->input('keep_page', []);
        $externalUrl = $request->input('external_url', []);
        $openInNewTab = $request->input('open_in_new_tab', []);
        $isNavbarLink = $request->input('is_navbar_link', []);
        
        $pages = [];
        
        // Combine the arrays into a structured format
        for ($i = 0; $i < count($titles); $i++) {
            // Skip pages marked for deletion
            if (!isset($keepPage[$i]) || $keepPage[$i] != '1') {
                continue;
            }
            
            if (isset($titles[$i]) && isset($paths[$i])) {
                // Determine if using custom HTML based on content type radio selection
                $contentType = $request->input('content_type_' . $i, 'url');
                $isCustomHTML = ($contentType === 'html');
                
                // Check if this is a standalone navbar link
                $isStandaloneNavbarLink = isset($isNavbarLink[$i]) && $isNavbarLink[$i];
                
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
                    'external_url' => isset($externalUrl[$i]) ? trim($externalUrl[$i]) : '',
                    'open_in_new_tab' => isset($openInNewTab[$i]) ? true : false,
                    'is_navbar_link' => $isStandaloneNavbarLink,
                ];
            }
        }
        
        // Save to database
        Option::set('pageembedder_pages', $pages);
        
        \Session::flash('flash_success_floating', __('Settings saved'));
        
        return redirect()->route('pageembedder.settings');
    }
} 