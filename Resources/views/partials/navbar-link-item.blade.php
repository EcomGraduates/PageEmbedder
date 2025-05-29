<div class="navbar-link-item" data-index="{{ $index }}">
    <!-- Hidden input to track the original index -->
    <input type="hidden" name="link_indices[]" value="{{ $index }}">
    
    <div class="form-group{{ $errors->has('titles.'.$index) ? ' has-error' : '' }}">
        <label class="col-sm-2 control-label">{{ __('Link Title') }}</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="titles[]" value="{{ old('titles.'.$index, $page['title']) }}" placeholder="{{ __('Link Title') }}" required maxlength="100">
            @if ($errors->has('titles.'.$index))
                <span class="help-block">
                    <strong>{{ $errors->first('titles.'.$index) }}</strong>
                </span>
            @endif
        </div>
    </div>
    
    <div class="form-group{{ $errors->has('external_url.'.$index) ? ' has-error' : '' }}">
        <label class="col-sm-2 control-label">{{ __('External URL') }}</label>
        <div class="col-sm-6">
            <input type="url" class="form-control" name="external_url[]" value="{{ old('external_url.'.$index, isset($page['external_url']) ? $page['external_url'] : '') }}" placeholder="https://example.com" required maxlength="1000">
            @if ($errors->has('external_url.'.$index))
                <span class="help-block">
                    <strong>{{ $errors->first('external_url.'.$index) }}</strong>
                </span>
            @endif
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">{{ __('Icon') }}</label>
        <div class="col-sm-6">
            <select name="icon_class[]" class="form-control">
                <option value="glyphicon-link" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-link' ? 'selected' : '') }}>{{ __('Link (default)') }}</option>
                <option value="glyphicon-bookmark" {{ old('icon_class.'.$index, isset($page['icon_class']) && $page['icon_class'] == 'glyphicon-bookmark' ? 'selected' : '') }}>{{ __('Bookmark') }}</option>
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
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-2 control-label">{{ __('Options') }}</label>
        <div class="col-sm-6">
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="open_in_new_tab[{{ $index }}]" value="1" {{ old('open_in_new_tab.'.$index, isset($page['open_in_new_tab']) && $page['open_in_new_tab'] ? 'checked' : '') }}>
                    {{ __('Open in New Tab') }}
                </label>
            </div>
            <div class="checkbox">
                <label>
                    <input type="checkbox" name="admin_only[{{ $index }}]" value="1" {{ old('admin_only.'.$index, isset($page['admin_only']) && $page['admin_only'] ? 'checked' : '') }}>
                    {{ __('Admin Only - Restrict to admin users only') }}
                </label>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-6">
            <button type="button" class="btn btn-danger remove-navbar-link" data-index="{{ $index }}">
                <i class="glyphicon glyphicon-trash"></i> {{ __('Remove') }}
            </button>
        </div>
    </div>
    
    <hr>
</div> 