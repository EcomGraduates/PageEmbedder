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
                        {{ __('Configure external pages to embed in FreeScout. Each page will appear in the navigation menu.') }}
                    </p>
                    <div class="alert alert-info">
                        <p><strong>{{ __('JavaScript Security:') }}</strong> {{ __('When using Custom HTML Content with <script> tags, Content Security Policy (CSP) nonce attributes will be automatically added for security compliance.') }}</p>
                    </div>
                    
                    <form class="form-horizontal" method="POST" action="{{ route('pageembedder.settings.save') }}">
                        {{ csrf_field() }}
                        
                        <!-- Hidden input to track deleted pages -->
                        <input type="hidden" name="deleted_pages" id="deleted-pages" value="">
                        
                        <div id="embedded-pages-container">
                            @if (!empty($embeddedPages))
                                @foreach ($embeddedPages as $index => $page)
                                    <div class="embedded-page-item" data-index="{{ $index }}">
                                        <!-- Hidden input to mark if this page should be kept -->
                                        <input type="hidden" name="keep_page[]" value="1" class="keep-page-input">
                                        
                                        <div class="form-group{{ $errors->has('titles.'.$index) ? ' has-error' : '' }}">
                                            <label class="col-sm-2 control-label">{{ __('Menu Title') }}</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="titles[]" value="{{ old('titles.'.$index, $page['title']) }}" placeholder="{{ __('Page Title') }}" required maxlength="100">
                                                @if ($errors->has('titles.'.$index))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('titles.'.$index) }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">{{ __('Content Type') }}</label>
                                            <div class="col-sm-6">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="content_type_{{ $index }}" value="url" class="content-type-selector" data-index="{{ $index }}" {{ old('content_type_'.$index, !isset($page['is_custom_html']) || !$page['is_custom_html'] ? 'checked' : '') }}>
                                                        {{ __('External URL') }}
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="content_type_{{ $index }}" value="html" class="content-type-selector" data-index="{{ $index }}" {{ old('content_type_'.$index, isset($page['is_custom_html']) && $page['is_custom_html'] ? 'checked' : '') }}>
                                                        {{ __('Custom HTML Content') }}
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="url-input-group-{{ $index }} {{ (isset($page['is_custom_html']) && $page['is_custom_html']) ? 'hidden' : '' }}">
                                            <div class="form-group{{ $errors->has('urls.'.$index) ? ' has-error' : '' }}">
                                                <label class="col-sm-2 control-label">{{ __('URL to Embed') }}</label>
                                                <div class="col-sm-6">
                                                    <input type="url" class="form-control" name="urls[]" value="{{ old('urls.'.$index, $page['url']) }}" placeholder="https://example.com" {{ (isset($page['is_custom_html']) && $page['is_custom_html']) ? '' : 'required' }} maxlength="1000">
                                                    @if ($errors->has('urls.'.$index))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('urls.'.$index) }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="html-input-group-{{ $index }} {{ (!isset($page['is_custom_html']) || !$page['is_custom_html']) ? 'hidden' : '' }}">
                                            <div class="form-group{{ $errors->has('custom_html.'.$index) ? ' has-error' : '' }}">
                                                <label class="col-sm-2 control-label">{{ __('Custom HTML') }}</label>
                                                <div class="col-sm-6">
                                                    <textarea class="form-control custom-html-textarea" name="custom_html[]" rows="8" placeholder="<div>Your custom HTML content here...</div>">{{ old('custom_html.'.$index, isset($page['custom_html']) ? $page['custom_html'] : '') }}</textarea>
                                                    @if ($errors->has('custom_html.'.$index))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('custom_html.'.$index) }}</strong>
                                                        </span>
                                                    @endif
                                                    <p class="help-block">
                                                        {{ __('Enter your custom HTML, CSS, and JavaScript. You can include <style> and <script> tags. CSP nonce attributes will be automatically added to script tags for security.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group{{ $errors->has('paths.'.$index) ? ' has-error' : '' }}">
                                            <label class="col-sm-2 control-label">{{ __('Custom Path') }}</label>
                                            <div class="col-sm-6">
                                                <div class="input-group">
                                                    <span class="input-group-addon">{{ url('/embedded/') }}/</span>
                                                    <input type="text" class="form-control" name="paths[]" value="{{ old('paths.'.$index, str_replace('embedded/', '', $page['path'])) }}" placeholder="my-page" required maxlength="100" pattern="[a-zA-Z0-9\-_/]+">
                                                </div>
                                                @if ($errors->has('paths.'.$index))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('paths.'.$index) }}</strong>
                                                    </span>
                                                @endif
                                                <p class="help-block">
                                                    {{ __('Use only letters, numbers, hyphens, underscores, and forward slashes.') }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">{{ __('Menu Icon') }}</label>
                                            <div class="col-sm-6">
                                                <select name="icon_class[]" class="form-control">
                                                    <option value="glyphicon-bookmark" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-bookmark' ? 'selected' : '') }}>{{ __('Bookmark (default)') }}</option>
                                                    <option value="glyphicon-globe" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-globe' ? 'selected' : '') }}>{{ __('Globe') }}</option>
                                                    <option value="glyphicon-dashboard" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-dashboard' ? 'selected' : '') }}>{{ __('Dashboard') }}</option>
                                                    <option value="glyphicon-stats" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-stats' ? 'selected' : '') }}>{{ __('Statistics') }}</option>
                                                    <option value="glyphicon-list-alt" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-list-alt' ? 'selected' : '') }}>{{ __('List') }}</option>
                                                    <option value="glyphicon-file" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-file' ? 'selected' : '') }}>{{ __('Document') }}</option>
                                                    <option value="glyphicon-calendar" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-calendar' ? 'selected' : '') }}>{{ __('Calendar') }}</option>
                                                    <option value="glyphicon-bell" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-bell' ? 'selected' : '') }}>{{ __('Notification') }}</option>
                                                    <option value="glyphicon-cog" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-cog' ? 'selected' : '') }}>{{ __('Settings') }}</option>
                                                    <option value="glyphicon-user" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-user' ? 'selected' : '') }}>{{ __('User') }}</option>
                                                    <option value="glyphicon-home" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-home' ? 'selected' : '') }}>{{ __('Home') }}</option>
                                                    <option value="glyphicon-briefcase" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-briefcase' ? 'selected' : '') }}>{{ __('Business') }}</option>
                                                    <option value="glyphicon-time" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-time' ? 'selected' : '') }}>{{ __('Time') }}</option>
                                                    <option value="glyphicon-shopping-cart" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-shopping-cart' ? 'selected' : '') }}>{{ __('Shopping') }}</option>
                                                    <option value="glyphicon-search" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-search' ? 'selected' : '') }}>{{ __('Search') }}</option>
                                                    <option value="glyphicon-heart" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-heart' ? 'selected' : '') }}>{{ __('Heart') }}</option>
                                                    <option value="glyphicon-star" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-star' ? 'selected' : '') }}>{{ __('Star') }}</option>
                                                </select>
                                                <p class="help-block">
                                                    {{ __('Icon to display in the menu or navbar next to the page title.') }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group{{ $errors->has('seamless.'.$index) ? ' has-error' : '' }}">
                                            <label class="col-sm-2 control-label">{{ __('Display Options') }}</label>
                                            <div class="col-sm-6">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="seamless[{{ $index }}]" value="1" {{ old('seamless.'.$index, isset($page['seamless']) && $page['seamless'] ? 'checked' : '') }}>
                                                        {{ __('Seamless Mode - Minimize spacing for a more integrated look') }}
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hide_headers[{{ $index }}]" value="1" {{ old('hide_headers.'.$index, isset($page['hide_header']) && $page['hide_header'] ? 'checked' : '') }}>
                                                        {{ __('Hide FreeScout Header - Hide the FreeScout page header for full-width embedding') }}
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="in_navbar[{{ $index }}]" value="1" {{ old('in_navbar.'.$index, isset($page['in_navbar']) && $page['in_navbar'] ? 'checked' : '') }}>
                                                        {{ __('Show in Navbar - Display as a navbar item instead of in the main menu') }}
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="hide_title[{{ $index }}]" value="1" {{ old('hide_title.'.$index, isset($page['hide_title']) && $page['hide_title'] ? 'checked' : '') }}>
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
                                                        <input type="checkbox" name="forward_params[{{ $index }}]" value="1" {{ old('forward_params.'.$index, isset($page['forward_params']) && $page['forward_params'] ? 'checked' : '') }}>
                                                        {{ __('Forward URL Parameters - Pass URL parameters to the embedded page') }}
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="auto_login[{{ $index }}]" value="1" {{ old('auto_login.'.$index, isset($page['auto_login']) && $page['auto_login'] ? 'checked' : '') }}>
                                                        {{ __('Auto-Login Attempt - Try to pass user information to the embedded page') }}
                                                    </label>
                                                </div>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="admin_only[{{ $index }}]" value="1" {{ old('admin_only.'.$index, isset($page['admin_only']) && $page['admin_only'] ? 'checked' : '') }}>
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
                                            <div class="form-group{{ $errors->has('custom_css.'.$index) ? ' has-error' : '' }}">
                                                <label class="col-sm-2 control-label">{{ __('Custom CSS') }}</label>
                                                <div class="col-sm-6">
                                                    <textarea class="form-control" name="custom_css[]" rows="4" placeholder=".my-class { property: value; }">{{ old('custom_css.'.$index, isset($page['custom_css']) ? $page['custom_css'] : '') }}</textarea>
                                                    @if ($errors->has('custom_css.'.$index))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('custom_css.'.$index) }}</strong>
                                                        </span>
                                                    @endif
                                                    <p class="help-block">
                                                        {{ __('Custom CSS to apply to the embedding page (not the iframe content).') }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group{{ $errors->has('custom_js.'.$index) ? ' has-error' : '' }}">
                                                <label class="col-sm-2 control-label">{{ __('Custom JavaScript') }}</label>
                                                <div class="col-sm-6">
                                                    <textarea class="form-control" name="custom_js[]" rows="4" placeholder="document.addEventListener('iframeLoaded', function(e) { /* code */ });">{{ old('custom_js.'.$index, isset($page['custom_js']) ? $page['custom_js'] : '') }}</textarea>
                                                    @if ($errors->has('custom_js.'.$index))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('custom_js.'.$index) }}</strong>
                                                        </span>
                                                    @endif
                                                    <p class="help-block">
                                                        {{ __('Custom JavaScript to execute on the embedding page. The "iframeLoaded" event is triggered when the iframe has finished loading.') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-6">
                                                <button type="button" class="btn btn-danger remove-page">
                                                    <i class="glyphicon glyphicon-trash"></i> {{ __('Remove') }}
                                                </button>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                    </div>
                                @endforeach
                            @else
                                <div class="embedded-page-item" data-index="0">
                                    <!-- Hidden input to mark if this page should be kept -->
                                    <input type="hidden" name="keep_page[]" value="1" class="keep-page-input">
                                    
                                    <div class="form-group{{ $errors->has('titles.0') ? ' has-error' : '' }}">
                                        <label class="col-sm-2 control-label">{{ __('Menu Title') }}</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="titles[]" value="{{ old('titles.0') }}" placeholder="{{ __('Page Title') }}" required maxlength="100">
                                            @if ($errors->has('titles.0'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('titles.0') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">{{ __('Content Type') }}</label>
                                        <div class="col-sm-6">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="content_type_0" value="url" class="content-type-selector" data-index="0" {{ old('content_type_0', 'checked') }}>
                                                    {{ __('External URL') }}
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="content_type_0" value="html" class="content-type-selector" data-index="0" {{ old('content_type_0') }}>
                                                    {{ __('Custom HTML Content') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="url-input-group-0">
                                        <div class="form-group{{ $errors->has('urls.0') ? ' has-error' : '' }}">
                                            <label class="col-sm-2 control-label">{{ __('URL to Embed') }}</label>
                                            <div class="col-sm-6">
                                                <input type="url" class="form-control" name="urls[]" value="{{ old('urls.0') }}" placeholder="https://example.com" maxlength="1000">
                                                @if ($errors->has('urls.0'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('urls.0') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="html-input-group-0 hidden">
                                        <div class="form-group{{ $errors->has('custom_html.0') ? ' has-error' : '' }}">
                                            <label class="col-sm-2 control-label">{{ __('Custom HTML') }}</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control custom-html-textarea" name="custom_html[]" rows="8" placeholder="<div>Your custom HTML content here...</div>">{{ old('custom_html.0') }}</textarea>
                                                @if ($errors->has('custom_html.0'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('custom_html.0') }}</strong>
                                                    </span>
                                                @endif
                                                <p class="help-block">
                                                    {{ __('Enter your custom HTML, CSS, and JavaScript. You can include <style> and <script> tags. CSP nonce attributes will be automatically added to script tags for security.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group{{ $errors->has('paths.0') ? ' has-error' : '' }}">
                                        <label class="col-sm-2 control-label">{{ __('Custom Path') }}</label>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <span class="input-group-addon">{{ url('/embedded/') }}/</span>
                                                <input type="text" class="form-control" name="paths[]" value="{{ old('paths.0') }}" placeholder="my-page" required maxlength="100" pattern="[a-zA-Z0-9\-_/]+">
                                            </div>
                                            @if ($errors->has('paths.0'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('paths.0') }}</strong>
                                                </span>
                                            @endif
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
                                    
                                    <div class="form-group{{ $errors->has('seamless.0') ? ' has-error' : '' }}">
                                        <label class="col-sm-2 control-label">{{ __('Display Options') }}</label>
                                        <div class="col-sm-6">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="seamless[0]" value="1" {{ old('seamless.0', 'checked') }}>
                                                    {{ __('Seamless Mode - Minimize spacing for a more integrated look') }}
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="hide_headers[0]" value="1" {{ old('hide_headers.0') }}>
                                                    {{ __('Hide FreeScout Header - Hide the FreeScout page header for full-width embedding') }}
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="in_navbar[0]" value="1" {{ old('in_navbar.0') }}>
                                                    {{ __('Show in Navbar - Display as a navbar item instead of in the main menu') }}
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="hide_title[0]" value="1" {{ old('hide_title.0') }}>
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
                                                    <input type="checkbox" name="forward_params[0]" value="1" {{ old('forward_params.0') }}>
                                                    {{ __('Forward URL Parameters - Pass URL parameters to the embedded page') }}
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="auto_login[0]" value="1" {{ old('auto_login.0') }}>
                                                    {{ __('Auto-Login Attempt - Try to pass user information to the embedded page') }}
                                                </label>
                                            </div>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" name="admin_only[0]" value="1" {{ old('admin_only.0') }}>
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
                                        <div class="form-group{{ $errors->has('custom_css.0') ? ' has-error' : '' }}">
                                            <label class="col-sm-2 control-label">{{ __('Custom CSS') }}</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="custom_css[]" rows="4" placeholder=".my-class { property: value; }">{{ old('custom_css.0') }}</textarea>
                                                @if ($errors->has('custom_css.0'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('custom_css.0') }}</strong>
                                                    </span>
                                                @endif
                                                <p class="help-block">
                                                    {{ __('Custom CSS to apply to the embedding page (not the iframe content).') }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group{{ $errors->has('custom_js.0') ? ' has-error' : '' }}">
                                            <label class="col-sm-2 control-label">{{ __('Custom JavaScript') }}</label>
                                            <div class="col-sm-6">
                                                <textarea class="form-control" name="custom_js[]" rows="4" placeholder="document.addEventListener('iframeLoaded', function(e) { /* code */ });">{{ old('custom_js.0') }}</textarea>
                                                @if ($errors->has('custom_js.0'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('custom_js.0') }}</strong>
                                                    </span>
                                                @endif
                                                <p class="help-block">
                                                    {{ __('Custom JavaScript to execute on the embedding page. The "iframeLoaded" event is triggered when the iframe has finished loading.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-6">
                                            <button type="button" class="btn btn-danger remove-page">
                                                <i class="glyphicon glyphicon-trash"></i> {{ __('Remove') }}
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-6 col-sm-offset-2">
                                <button type="button" class="btn btn-success" id="add-page">
                                    <i class="glyphicon glyphicon-plus"></i> {{ __('Add Page') }}
                                </button>
                            </div>
                        </div>
                        
                        <div class="form-group margin-top">
                            <div class="col-sm-6 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Save Settings') }}
                                </button>
                            </div>
                        </div>
                    </form>
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

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deletePageModal" tabindex="-1" role="dialog" aria-labelledby="deletePageModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="deletePageModalLabel">{{ __('Confirm Delete') }}</h4>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this embedded page?') }}</p>
                <p class="text-danger"><strong>{{ __('This action cannot be undone.') }}</strong></p>
                <div id="delete-loading" style="display: none;">
                    <p class="text-center">
                        <i class="glyphicon glyphicon-refresh spinning"></i> {{ __('Deleting...') }}
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeletePage">{{ __('Delete Page') }}</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" {!! \Helper::cspNonceAttr() !!}>
    document.addEventListener('DOMContentLoaded', function() {
        // Store CodeMirror instances
        const codeMirrorInstances = {};
        let pageItemToDelete = null;
        
        // Function to initialize CodeMirror
        function initCodeMirror(textarea) {
            if (!textarea) return null;
            
            const index = textarea.closest('.embedded-page-item').getAttribute('data-index');
            
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
        
        // Add page button
        document.getElementById('add-page').addEventListener('click', function() {
            const container = document.getElementById('embedded-pages-container');
            const items = container.querySelectorAll('.embedded-page-item');
            const newIndex = items.length;
            
            // Clone the first item
            const template = items[0].cloneNode(true);
            template.setAttribute('data-index', newIndex);
            
            // Make sure the keep_page input is set to 1
            const keepPageInput = template.querySelector('.keep-page-input');
            if (keepPageInput) {
                keepPageInput.name = 'keep_page[]';
                keepPageInput.value = '1';
            }
            
            // Update content type radio names and reset to URL
            template.querySelectorAll('.content-type-selector').forEach(function(radio) {
                radio.name = 'content_type_' + newIndex;
                radio.setAttribute('data-index', newIndex);
                radio.checked = radio.value === 'url';
            });
            
            // Update content group classes
            const urlGroup = template.querySelector('.url-input-group-0');
            const htmlGroup = template.querySelector('.html-input-group-0');
            if (urlGroup) {
                urlGroup.className = 'url-input-group-' + newIndex;
                urlGroup.classList.remove('hidden');
            }
            if (htmlGroup) {
                htmlGroup.className = 'html-input-group-' + newIndex;
                htmlGroup.classList.add('hidden');
            }
            
            // Update names for new index
            template.querySelectorAll('input[type="text"], input[type="url"], textarea').forEach(function(input) {
                input.value = '';
            });
            
            // Update checkbox names and reset them
            template.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                const name = checkbox.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                checkbox.name = name;
                checkbox.checked = (name.includes('seamless')); // Only seamless is checked by default
            });
            
            // Update textarea names
            template.querySelectorAll('textarea, select').forEach(function(element) {
                const name = element.name.replace(/\[\d+\]/, '[' + newIndex + ']');
                element.name = name;
                
                // Reset dropdown selection to default for selects
                if (element.tagName === 'SELECT') {
                    const defaultOption = element.querySelector('option[value="glyphicon-bookmark"]');
                    if (defaultOption) {
                        defaultOption.selected = true;
                    }
                }
            });
            
            // Reset advanced options display
            template.querySelector('.advanced-options').style.display = 'none';
            
            // Update labels and error handling
            template.querySelectorAll('.form-group').forEach(function(group) {
                group.classList.remove('has-error');
                const helpBlocks = group.querySelectorAll('.help-block');
                helpBlocks.forEach(function(block) {
                    if (block.querySelector('strong')) {
                        block.remove();
                    }
                });
            });
            
            container.appendChild(template);
            setupRemoveButtons();
            setupAdvancedOptions();
            setupContentTypeSelectors();
            
            // Initialize CodeMirror for the new textarea
            const newTextarea = template.querySelector('.custom-html-textarea');
            if (newTextarea) {
                // Clear any existing CodeMirror
                if (codeMirrorInstances[newIndex]) {
                    delete codeMirrorInstances[newIndex];
                }
                
                // We need to wait a moment for DOM to update
                setTimeout(function() {
                    initCodeMirror(newTextarea);
                }, 100);
            }
        });
        
        function setupRemoveButtons() {
            const removeButtons = document.querySelectorAll('.remove-page');
            removeButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const item = this.closest('.embedded-page-item');
                    pageItemToDelete = item;
                    
                    // Show the confirmation modal
                    $('#deletePageModal').modal('show');
                });
            });
            
            // Confirm delete button in modal
            document.getElementById('confirmDeletePage').addEventListener('click', function() {
                if (!pageItemToDelete) return;
                
                // Show the loading indicator
                document.getElementById('delete-loading').style.display = 'block';
                
                // Disable the buttons to prevent multiple clicks
                this.disabled = true;
                document.querySelector('#deletePageModal .btn-default').disabled = true;
                
                const index = pageItemToDelete.getAttribute('data-index');
                
                // Instead of removing the element, hide it and mark it as deleted
                pageItemToDelete.style.display = 'none';
                
                // Mark the page as not kept in the form
                const keepPageInput = pageItemToDelete.querySelector('.keep-page-input');
                if (keepPageInput) {
                    keepPageInput.value = '0';
                }
                
                // Track deleted indices
                const deletedPagesInput = document.getElementById('deleted-pages');
                let deletedIndices = deletedPagesInput.value ? deletedPagesInput.value.split(',') : [];
                deletedIndices.push(index);
                deletedPagesInput.value = deletedIndices.join(',');
                
                // Remove CodeMirror instance
                if (codeMirrorInstances[index]) {
                    delete codeMirrorInstances[index];
                }
                
                // Add a small delay for better user experience
                setTimeout(function() {
                    // Automatically submit the form to save changes immediately
                    document.querySelector('form.form-horizontal').submit();
                }, 500);
            });
        }
        
        function setupAdvancedOptions() {
            const toggleButtons = document.querySelectorAll('.toggle-advanced-options');
            toggleButtons.forEach(function(button) {
                button.removeEventListener('click', toggleAdvancedOptions);
                button.addEventListener('click', toggleAdvancedOptions);
            });
        }
        
        function toggleAdvancedOptions() {
            const item = this.closest('.embedded-page-item');
            const advancedOptions = item.querySelector('.advanced-options');
            
            if (advancedOptions.style.display === 'none') {
                advancedOptions.style.display = 'block';
                this.innerHTML = '<i class="glyphicon glyphicon-chevron-up"></i> ' + '{{ __("Hide Advanced Options") }}';
            } else {
                advancedOptions.style.display = 'none';
                this.innerHTML = '<i class="glyphicon glyphicon-cog"></i> ' + '{{ __("Advanced Options") }}';
            }
        }
        
        function setupContentTypeSelectors() {
            const contentTypeSelectors = document.querySelectorAll('.content-type-selector');
            contentTypeSelectors.forEach(function(radio) {
                radio.addEventListener('change', toggleContentType);
            });
        }
        
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
        
        // Setup remove buttons on page load
        setupRemoveButtons();
        setupAdvancedOptions();
        setupContentTypeSelectors();
    });
</script>
@endsection 