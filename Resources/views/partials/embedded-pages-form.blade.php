<form class="form-horizontal" method="POST" action="{{ route('pageembedder.settings.save.embedded') }}">
    {{ csrf_field() }}
    
    <!-- Hidden input to track deleted pages -->
    <input type="hidden" name="deleted_pages" id="deleted-pages-embedded" value="">
    
    <div class="alert alert-info">
        <p><strong>{{ __('Embedded Pages:') }}</strong> {{ __('Create pages that are embedded within FreeScout. These can appear in the main menu or in the navbar.') }}</p>
    </div>
    
    <div id="embedded-pages-container">
        @if (!empty($embeddedPages))
            @php $hasEmbeddedPages = false; @endphp
            @foreach ($embeddedPages as $index => $page)
                @if(!isset($page['is_navbar_link']) || !$page['is_navbar_link'])
                    @php $hasEmbeddedPages = true; @endphp
                    @include('pageembedder::partials.embedded-page-item', ['page' => $page, 'index' => $index])
                @endif
            @endforeach
            
            @if(!$hasEmbeddedPages)
                <div class="alert alert-warning">
                    <p>{{ __('No embedded pages defined yet. Click "Add Page" to create one.') }}</p>
                </div>
            @endif
        @else
            <div class="alert alert-warning">
                <p>{{ __('No embedded pages defined yet. Click "Add Page" to create one.') }}</p>
            </div>
        @endif
    </div>
    
    <div class="form-group">
        <div class="col-sm-6 col-sm-offset-2">
            <button type="button" class="btn btn-success" id="add-embedded-page">
                <i class="glyphicon glyphicon-plus"></i> {{ __('Add Page') }}
            </button>
            <button type="submit" class="btn btn-primary">
                {{ __('Save Embedded Pages') }}
            </button>
        </div>
    </div>
</form>

<!-- Delete Confirmation Modal for Embedded Pages -->
<div class="modal fade" id="deleteEmbeddedPageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ __('Confirm Delete') }}</h4>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this embedded page?') }}</p>
                <p class="text-danger"><strong>{{ __('This action cannot be undone.') }}</strong></p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('pageembedder.settings.delete.embedded') }}" id="delete-embedded-form">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <input type="hidden" name="page_index" id="delete-embedded-index" value="">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div> 