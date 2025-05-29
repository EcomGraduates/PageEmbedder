<div class="embedded-page-item" data-index="{{ $index }}">
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
            <button type="button" class="btn btn-danger remove-embedded-page" data-index="{{ $index }}">
                <i class="glyphicon glyphicon-trash"></i> {{ __('Remove') }}
            </button>
        </div>
    </div>
    
    <hr>
</div> 