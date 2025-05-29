<form class="form-horizontal" method="POST" action="{{ route('pageembedder.settings.save.navbar') }}" id="navbar-links-form">
    {{ csrf_field() }}
    
    <div class="alert alert-info">
        <p><strong>{{ __('Navbar Links:') }}</strong> {{ __('Create standalone links that appear in the navbar and can open in new tabs.') }}</p>
    </div>
    
    <div id="navbar-links-container">
        @if (!empty($embeddedPages))
            @php $hasNavbarLinks = false; @endphp
            @foreach ($embeddedPages as $index => $page)
                @if(isset($page['is_navbar_link']) && $page['is_navbar_link'])
                    @php $hasNavbarLinks = true; @endphp
                    @include('pageembedder::partials.navbar-link-item', ['page' => $page, 'index' => $index])
                @endif
            @endforeach
            
            @if(!$hasNavbarLinks)
                <div class="alert alert-warning" id="no-navbar-links-warning">
                    <p>{{ __('No navbar links defined yet. Click "Add Navbar Link" to create one.') }}</p>
                </div>
            @endif
        @else
            <div class="alert alert-warning" id="no-navbar-links-warning">
                <p>{{ __('No navbar links defined yet. Click "Add Navbar Link" to create one.') }}</p>
            </div>
        @endif
    </div>
    
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-2">
            <button type="button" class="btn btn-success" id="add-navbar-link">
                <i class="glyphicon glyphicon-plus"></i> {{ __('Add Navbar Link') }}
            </button>
            <button type="submit" class="btn btn-primary" id="save-navbar-links-btn">
                {{ __('Save Navbar Links') }}
            </button>
        </div>
    </div>
</form>

<script type="text/javascript" {!! \Helper::cspNonceAttr() !!}>
    // Prevent double form submission
    document.getElementById('navbar-links-form').addEventListener('submit', function(e) {
        var submitBtn = document.getElementById('save-navbar-links-btn');
        if (submitBtn.disabled) {
            e.preventDefault();
            return false;
        }
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="glyphicon glyphicon-refresh spinning"></i> {{ __('Saving...') }}';
    });
</script>

<!-- Delete Confirmation Modal for Navbar Links -->
<div class="modal fade" id="deleteNavbarLinkModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ __('Confirm Delete') }}</h4>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this navbar link?') }}</p>
                <p class="text-danger"><strong>{{ __('This action cannot be undone.') }}</strong></p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('pageembedder.settings.delete.navbar') }}" id="delete-navbar-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" name="link_index" id="delete-navbar-index" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div> 