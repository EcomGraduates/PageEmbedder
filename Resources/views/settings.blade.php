@extends('layouts.app')

@section('title', __('Page Embedder Settings'))

@section('content')
<div class="section-heading">
    {{ __('Page Embedder Settings') }}
</div>
<!-- Add CodeMirror CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css" integrity="sha512-uf06llspW44/LZpHzHT6qBOIVODjWtv4MxCricRxkzvopAlSWnTf6hpZTFxuuZcuNE9CBQhqE0Seu1CoRk84nQ==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!} />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css" integrity="sha512-R6PH4vSzF2Yxjdvb2p2FA06yWul+U0PDDav4b/od/oXf9Iw37zl10plvwOXelrjV2Ai7Eo3vyHeyFUjhXdBCVQ==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!} />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.css" integrity="sha512-OmcLQEy8iGiD7PSm85s06dnR7G7C9C0VqahIPAj/KHk5RpOCmnC6R2ob1oK4/uwYhWa9BF1GC6tzxsC8TIx7Jg==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!} />

<style {!! \Helper::cspNonceAttr() !!}>
    .CodeMirror {
        height: auto;
        min-height: 150px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    .spinning {
        animation: spin 1s infinite linear;
        display: inline-block;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-xs-12 margin-top">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ __('Embedded Pages') }}</h3> <span class="badge badge-info">v{{ $moduleVersion }}</span>
                </div>
                <div class="panel-body">
                    <p class="text-help">
                        {{ __('Configure external pages to embed in FreeScout or add standalone links to the navbar.') }}
                    </p>
                    
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs margin-bottom" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#embedded-pages-tab" aria-controls="embedded-pages-tab" role="tab" data-toggle="tab">
                                <i class="glyphicon glyphicon-globe"></i> {{ __('Embedded Pages') }}
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#navbar-links-tab" aria-controls="navbar-links-tab" role="tab" data-toggle="tab">
                                <i class="glyphicon glyphicon-link"></i> {{ __('Navbar Links') }}
                            </a>
                        </li>
                    </ul>
                    
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <!-- Embedded Pages Tab -->
                            <div role="tabpanel" class="tab-pane active" id="embedded-pages-tab">
                            @include('pageembedder::partials.embedded-pages-form')
                                </div>
                                
                        <!-- Navbar Links Tab -->
                        <div role="tabpanel" class="tab-pane" id="navbar-links-tab">
                            @include('pageembedder::partials.navbar-links-form')
                                                        </div>
                                                    </div>
                                                            </div>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js" integrity="sha512-xwrAU5yhWwdTvvmMNheFn9IyuDbl/Kyghz2J3wQRDR8tyNmT8ZIYOd0V3iPYY/g4XdNPy0n/g0NvqGu9f0fPJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/xml/xml.min.js" integrity="sha512-UWfBe6aiZInvbBlm91IURVHHTwigTPtM3M4B73a8AykmxhDWq4EC/V2rgUNiLgmd/i0y0KWHolqmVQyJ35JsNA==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/htmlmixed/htmlmixed.min.js" integrity="sha512-0IM15+FEzmvrcePHk/gDCLbZnmja9DhCDUrESXPYLM4r+eDtNadxDUa5Fd/tNQGCbCoxu75TaVuvJkdmq0uh/w==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/css/css.min.js" integrity="sha512-2gAMyrBfWPuTJDA2ZNIWVrBBe9eN6/hOjyvewDd0bsk2Zg06sUla/nPPlqQs75MQMvJ+S5AmfKmq9q3+W2qeKw==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/javascript/javascript.min.js" integrity="sha512-IS1FyxQkiJHT1SAvLXBaJu1UTFSIw0GT/DuzxHl69djqyLoEwGmR2davcZUnB8M7ppi9nfTGZR/vdfqmWt+i6A==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/edit/closetag.min.js" integrity="sha512-ZUq/bxUHwC35d3oP1hC5lshiHFCnI3dDtDPtSp9+CQDy/YU0LQu2ujDd603LuWho0G4XH8MSvyLV47x2Zcd8Jw==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/show-hint.min.js" integrity="sha512-kCn9g92k3GM90eTPGMNwvpCAtLmvyqbpvrdnhm0NMt6UEHYs+DjRO4me8VcwInLWQ9azmamS1U1lbQV627/TBQ==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!}></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/addon/hint/html-hint.min.js" integrity="sha512-oxBKDzXElkyh3mQC/bKA/se1Stg1Q6fm7jz7PPY2kL01jRHQ64IwjpZVsuZojcaj5g8eKSMY9UJamtB1QR7Dmw==" crossorigin="anonymous" referrerpolicy="no-referrer" {!! \Helper::cspNonceAttr() !!}></script>

<script type="text/javascript" {!! \Helper::cspNonceAttr() !!}>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle active tab from session
        @if(session('active_tab'))
            var activeTab = '{{ session('active_tab') }}';
            $('.nav-tabs a[href="#' + activeTab + '"]').tab('show');
        @elseif(strpos(request()->url(), '#navbar-links-tab') !== false)
            $('.nav-tabs a[href="#navbar-links-tab"]').tab('show');
        @endif
        
        // Store CodeMirror instances
        const codeMirrorInstances = {};
        
        // Function to initialize CodeMirror
        function initCodeMirror(textarea) {
            if (!textarea) return null;
            
            const pageItem = textarea.closest('.embedded-page-item');
            if (!pageItem) return null;
            
            const index = pageItem.getAttribute('data-index');
            
            // Check if we already have an instance for this textarea
            if (codeMirrorInstances[index]) {
                return codeMirrorInstances[index];
            }
            
            // Create new CodeMirror instance
            const editor = CodeMirror.fromTextArea(textarea, {
                mode: 'htmlmixed',
                theme: 'monokai',
                lineNumbers: true,
                indentUnit: 4,
                autoCloseTags: true,
                matchBrackets: true,
                extraKeys: {"Ctrl-Space": "autocomplete"},
                lineWrapping: true
            });
            
            // Save the instance
            codeMirrorInstances[index] = editor;
            
            // Make sure CodeMirror updates the textarea value before form submit
            editor.on('change', function() {
                editor.save();
            });
            
            return editor;
        }
        
        // Initialize CodeMirror for all custom HTML textareas
        function initAllCodeMirrors() {
            document.querySelectorAll('.custom-html-textarea').forEach(function(textarea) {
                initCodeMirror(textarea);
            });
        }
        
        // Initialize on page load
        initAllCodeMirrors();
        
        // Function to get next index for embedded pages
        function getNextEmbeddedIndex() {
            const allItems = document.querySelectorAll('.embedded-page-item');
            let maxIndex = -1;
            allItems.forEach(item => {
                const index = parseInt(item.getAttribute('data-index'), 10);
                if (index > maxIndex) maxIndex = index;
            });
            return maxIndex + 1;
        }
        
        // Function to get next index for navbar links
        function getNextNavbarIndex() {
            const allItems = document.querySelectorAll('.navbar-link-item');
            let maxIndex = -1;
            allItems.forEach(item => {
                const index = parseInt(item.getAttribute('data-index'), 10);
                if (index > maxIndex) maxIndex = index;
            });
            // Start navbar links at a high index to avoid conflicts with embedded pages
            return maxIndex >= 1000 ? maxIndex + 1 : 1000;
        }
        
        // Add embedded page button
        document.getElementById('add-embedded-page')?.addEventListener('click', function() {
            const container = document.getElementById('embedded-pages-container');
            const newIndex = getNextEmbeddedIndex();
            
            // Clear any "no pages" warning messages
            const warningMessages = container.querySelectorAll('.alert-warning');
            warningMessages.forEach(warning => warning.remove());
            
            // Clone the first item or create from template
            const template = createEmbeddedPageTemplate(newIndex);
            
            container.appendChild(template);
            setupEmbeddedPageHandlers();
            
            // Initialize CodeMirror for the new textarea
            const newTextarea = template.querySelector('.custom-html-textarea');
            if (newTextarea) {
                setTimeout(function() {
                    initCodeMirror(newTextarea);
                }, 100);
            }
        });
        
        // Add navbar link button
        document.getElementById('add-navbar-link')?.addEventListener('click', function() {
            const container = document.getElementById('navbar-links-container');
            const newIndex = getNextNavbarIndex();
            
            // Clear any "no links" warning messages
            const warningMessages = container.querySelectorAll('.alert-warning');
            warningMessages.forEach(warning => warning.remove());
            
            // Create new navbar link item
            const template = createNavbarLinkTemplate(newIndex);
            
            container.appendChild(template);
            setupNavbarLinkHandlers();
        });
        
        // Setup handlers for embedded pages
        function setupEmbeddedPageHandlers() {
            // Remove buttons
            document.querySelectorAll('.remove-embedded-page').forEach(function(button) {
                button.removeEventListener('click', handleEmbeddedPageRemove);
                button.addEventListener('click', handleEmbeddedPageRemove);
            });
            
            // Advanced options toggle
            document.querySelectorAll('.toggle-advanced-options').forEach(function(button) {
                button.removeEventListener('click', toggleAdvancedOptions);
                button.addEventListener('click', toggleAdvancedOptions);
            });
            
            // Content type selectors
            document.querySelectorAll('.content-type-selector').forEach(function(radio) {
                radio.removeEventListener('change', toggleContentType);
                radio.addEventListener('change', toggleContentType);
            });
        }
        
        // Setup handlers for navbar links
        function setupNavbarLinkHandlers() {
            document.querySelectorAll('.remove-navbar-link').forEach(function(button) {
                button.removeEventListener('click', handleNavbarLinkRemove);
                button.addEventListener('click', handleNavbarLinkRemove);
            });
        }
        
        // Handle embedded page removal
        function handleEmbeddedPageRemove() {
            const index = this.getAttribute('data-index');
            document.getElementById('delete-embedded-index').value = index;
            $('#deleteEmbeddedPageModal').modal('show');
        }
        
        // Handle navbar link removal
        function handleNavbarLinkRemove() {
            const index = this.getAttribute('data-index');
            document.getElementById('delete-navbar-index').value = index;
            $('#deleteNavbarLinkModal').modal('show');
        }
        
        // Toggle advanced options
        function toggleAdvancedOptions() {
            const item = this.closest('.embedded-page-item');
            const advancedOptions = item.querySelector('.advanced-options');
            
            if (advancedOptions.style.display === 'none') {
                advancedOptions.style.display = 'block';
                this.innerHTML = '<i class="glyphicon glyphicon-chevron-up"></i> {{ __('Hide Advanced Options') }}';
            } else {
                advancedOptions.style.display = 'none';
                this.innerHTML = '<i class="glyphicon glyphicon-cog"></i> {{ __('Advanced Options') }}';
            }
        }
        
        // Toggle content type
        function toggleContentType() {
            const index = this.getAttribute('data-index');
            const contentType = this.value;
            
            const urlGroup = document.querySelector('.url-input-group-' + index);
            const htmlGroup = document.querySelector('.html-input-group-' + index);
            
            if (!urlGroup || !htmlGroup) {
                console.error('Could not find content groups for index: ' + index);
                return;
            }
            
            if (contentType === 'url') {
                urlGroup.classList.remove('hidden');
                htmlGroup.classList.add('hidden');
                const urlInput = urlGroup.querySelector('input[type="url"]');
                const htmlTextarea = htmlGroup.querySelector('textarea');
                
                if (urlInput) urlInput.required = true;
                if (htmlTextarea) htmlTextarea.required = false;
                
                // Hide CodeMirror if it exists
                if (codeMirrorInstances[index]) {
                    codeMirrorInstances[index].getWrapperElement().style.display = 'none';
                }
            } else {
                urlGroup.classList.add('hidden');
                htmlGroup.classList.remove('hidden');
                const urlInput = urlGroup.querySelector('input[type="url"]');
                const htmlTextarea = htmlGroup.querySelector('textarea');
                
                if (urlInput) urlInput.required = false;
                if (htmlTextarea) htmlTextarea.required = true;
                
                // Show and refresh CodeMirror
                if (codeMirrorInstances[index]) {
                    codeMirrorInstances[index].getWrapperElement().style.display = '';
                    codeMirrorInstances[index].refresh();
                } else if (htmlTextarea) {
                    // Initialize CodeMirror if it doesn't exist
                    initCodeMirror(htmlTextarea);
                }
            }
        }
        
        // Create embedded page template
        function createEmbeddedPageTemplate(index) {
            const div = document.createElement('div');
            div.className = 'embedded-page-item';
            div.setAttribute('data-index', index);
            div.innerHTML = `
                <div class="form-group">
                                                <label class="col-sm-2 control-label">{{ __('Menu Title') }}</label>
                                                <div class="col-sm-6">
                        <input type="text" class="form-control" name="titles[]" value="" placeholder="{{ __('Page Title') }}" required maxlength="100">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">{{ __('Content Type') }}</label>
                                                <div class="col-sm-6">
                                                    <div class="radio">
                                                        <label>
                                <input type="radio" name="content_type_${index}" value="url" class="content-type-selector" data-index="${index}" checked>
                                                            {{ __('External URL') }}
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                <input type="radio" name="content_type_${index}" value="html" class="content-type-selector" data-index="${index}">
                                                            {{ __('Custom HTML Content') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                <div class="url-input-group-${index}">
                    <div class="form-group">
                                                    <label class="col-sm-2 control-label">{{ __('URL to Embed') }}</label>
                                                    <div class="col-sm-6">
                            <input type="url" class="form-control" name="urls[]" value="" placeholder="https://example.com" required maxlength="1000">
                                                    </div>
                                                </div>
                                            </div>
                                            
                <div class="html-input-group-${index} hidden">
                    <div class="form-group">
                                                    <label class="col-sm-2 control-label">{{ __('Custom HTML') }}</label>
                                                    <div class="col-sm-6">
                            <textarea class="form-control custom-html-textarea" name="custom_html[]" rows="8" placeholder="<div>Your custom HTML content here...</div>"></textarea>
                                                        <p class="help-block">
                                                            {{ __('Enter your custom HTML, CSS, and JavaScript. You can include <style> and <script> tags. CSP nonce attributes will be automatically added to script tags for security.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                <div class="form-group">
                                                <label class="col-sm-2 control-label">{{ __('Custom Path') }}</label>
                                                <div class="col-sm-6">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">{{ url('/embedded/') }}/</span>
                            <input type="text" class="form-control" name="paths[]" value="" placeholder="my-page" required maxlength="100" pattern="[a-zA-Z0-9\-_/]+">
                                                    </div>
                                                    <p class="help-block">
                                                        {{ __('Use only letters, numbers, hyphens, underscores, and forward slashes.') }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">{{ __('Menu Icon') }}</label>
                                                <div class="col-sm-6">
                                                    <select name="icon_class[]" class="form-control">
                                                        <option value="glyphicon-bookmark" selected>{{ __('Bookmark (default)') }}</option>
                                                        <option value="glyphicon-globe">{{ __('Globe') }}</option>
                                                        <option value="glyphicon-dashboard">{{ __('Dashboard') }}</option>
                                                        <option value="glyphicon-stats">{{ __('Statistics') }}</option>
                                                        <option value="glyphicon-list-alt">{{ __('List') }}</option>
                                                        <option value="glyphicon-file">{{ __('Document') }}</option>
                                                        <option value="glyphicon-calendar">{{ __('Calendar') }}</option>
                                                        <option value="glyphicon-bell">{{ __('Notification') }}</option>
                                                        <option value="glyphicon-cog">{{ __('Settings') }}</option>
                                                        <option value="glyphicon-user">{{ __('User') }}</option>
                                                        <option value="glyphicon-home">{{ __('Home') }}</option>
                                                        <option value="glyphicon-briefcase">{{ __('Business') }}</option>
                                                        <option value="glyphicon-time">{{ __('Time') }}</option>
                                                        <option value="glyphicon-shopping-cart">{{ __('Shopping') }}</option>
                                                        <option value="glyphicon-search">{{ __('Search') }}</option>
                                                        <option value="glyphicon-heart">{{ __('Heart') }}</option>
                                                        <option value="glyphicon-star">{{ __('Star') }}</option>
                                                    </select>
                                                    <p class="help-block">
                                                        {{ __('Icon to display in the menu or navbar next to the page title.') }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                <div class="form-group">
                                                <label class="col-sm-2 control-label">{{ __('Display Options') }}</label>
                                                <div class="col-sm-6">
                                                    <div class="checkbox">
                                                        <label>
                                <input type="checkbox" name="seamless[${index}]" value="1" checked>
                                                            {{ __('Seamless Mode - Minimize spacing for a more integrated look') }}
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                <input type="checkbox" name="hide_headers[${index}]" value="1">
                                                            {{ __('Hide FreeScout Header - Hide the FreeScout page header for full-width embedding') }}
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                <input type="checkbox" name="in_navbar[${index}]" value="1">
                                                            {{ __('Show in Navbar - Display as a navbar item instead of in the main menu') }}
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                <input type="checkbox" name="hide_title[${index}]" value="1">
                                                            {{ __('Hide Page Title - Remove the title from the top of the embedded page') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">{{ __('Integration Options') }}</label>
                                                <div class="col-sm-6">
                                                    <div class="checkbox">
                                                        <label>
                                <input type="checkbox" name="forward_params[${index}]" value="1">
                                                            {{ __('Forward URL Parameters - Pass URL parameters to the embedded page') }}
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                <input type="checkbox" name="auto_login[${index}]" value="1">
                                                            {{ __('Auto-Login Attempt - Try to pass user information to the embedded page') }}
                                                        </label>
                                                    </div>
                                                    <div class="checkbox">
                                                        <label>
                                <input type="checkbox" name="admin_only[${index}]" value="1">
                                                            {{ __('Admin Only - Restrict this page to admin users only') }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group advanced-toggle">
                                                <div class="col-sm-offset-2 col-sm-6">
                                                    <button type="button" class="btn btn-default toggle-advanced-options">
                                                        <i class="glyphicon glyphicon-cog"></i> {{ __('Advanced Options') }}
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <div class="advanced-options" style="display: none;">
                    <div class="form-group">
                                                    <label class="col-sm-2 control-label">{{ __('Custom CSS') }}</label>
                                                    <div class="col-sm-6">
                            <textarea class="form-control" name="custom_css[]" rows="4" placeholder=".my-class { property: value; }"></textarea>
                                                        <p class="help-block">
                                                            {{ __('Custom CSS to apply to the embedding page (not the iframe content).') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                
                    <div class="form-group">
                                                    <label class="col-sm-2 control-label">{{ __('Custom JavaScript') }}</label>
                                                    <div class="col-sm-6">
                            <textarea class="form-control" name="custom_js[]" rows="4" placeholder="document.addEventListener('iframeLoaded', function(e) { /* code */ });"></textarea>
                                                        <p class="help-block">
                                                            {{ __('Custom JavaScript to execute on the embedding page. The "iframeLoaded" event is triggered when the iframe has finished loading.') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-6">
                        <button type="button" class="btn btn-danger remove-embedded-page" data-index="${index}">
                                                        <i class="glyphicon glyphicon-trash"></i> {{ __('Remove') }}
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <hr>
            `;
            return div;
        }
        
        // Create navbar link template
        function createNavbarLinkTemplate(index) {
            const div = document.createElement('div');
            div.className = 'navbar-link-item';
            div.setAttribute('data-index', index);
            div.innerHTML = `
                <!-- Hidden input to track the original index -->
                <input type="hidden" name="link_indices[]" value="${index}">
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __('Link Title') }}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" name="titles[]" value="" placeholder="{{ __('Link Title') }}" required maxlength="100">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __('External URL') }}</label>
                    <div class="col-sm-6">
                        <input type="url" class="form-control" name="external_url[]" value="" placeholder="https://example.com" required maxlength="1000">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __('Icon') }}</label>
                    <div class="col-sm-6">
                        <select name="icon_class[]" class="form-control">
                            <option value="glyphicon-link" selected>{{ __('Link (default)') }}</option>
                            <option value="glyphicon-bookmark">{{ __('Bookmark') }}</option>
                            <option value="glyphicon-globe">{{ __('Globe') }}</option>
                            <option value="glyphicon-dashboard">{{ __('Dashboard') }}</option>
                            <option value="glyphicon-stats">{{ __('Statistics') }}</option>
                            <option value="glyphicon-list-alt">{{ __('List') }}</option>
                            <option value="glyphicon-file">{{ __('Document') }}</option>
                            <option value="glyphicon-calendar">{{ __('Calendar') }}</option>
                            <option value="glyphicon-bell">{{ __('Notification') }}</option>
                            <option value="glyphicon-cog">{{ __('Settings') }}</option>
                            <option value="glyphicon-user">{{ __('User') }}</option>
                            <option value="glyphicon-home">{{ __('Home') }}</option>
                            <option value="glyphicon-briefcase">{{ __('Business') }}</option>
                            <option value="glyphicon-time">{{ __('Time') }}</option>
                            <option value="glyphicon-shopping-cart">{{ __('Shopping') }}</option>
                            <option value="glyphicon-search">{{ __('Search') }}</option>
                            <option value="glyphicon-heart">{{ __('Heart') }}</option>
                            <option value="glyphicon-star">{{ __('Star') }}</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="col-sm-2 control-label">{{ __('Options') }}</label>
                    <div class="col-sm-6">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="open_in_new_tab[${index}]" value="1">
                                {{ __('Open in New Tab') }}
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="admin_only[${index}]" value="1">
                                {{ __('Admin Only - Restrict to admin users only') }}
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-6">
                        <button type="button" class="btn btn-danger remove-navbar-link" data-index="${index}">
                            <i class="glyphicon glyphicon-trash"></i> {{ __('Remove') }}
                        </button>
                    </div>
                </div>
                
                <hr>
            `;
            return div;
        }
        
        // Setup initial handlers
        setupEmbeddedPageHandlers();
        setupNavbarLinkHandlers();
    });
</script>
@endsection 