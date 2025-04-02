@extends('layouts.app')

@section('title', $title)

@section('content')
@if(!$hideHeader)
<div class="section-heading {{ $hideTitle ? 'hide-header' : '' }}">
    @if(!$hideTitle)
    {{ $title }}
    @endif
</div>
@endif

<div class="container-fluid embedded-container {{ $seamless ? 'seamless' : '' }} {{ $hideTitle ? 'hide-title' : '' }}">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default embedded-panel">
                @if($isCustomHTML)
                <div id="custom-html-container">
                    {!! str_replace('data-csp-nonce="true"', \Helper::cspNonceAttr(), $customHTML) !!}
                </div>
                @else
                <iframe 
                    id="embedded-iframe"
                    src="{{ $url }}" 
                    frameborder="0"
                    allowfullscreen
                    sandbox="allow-forms allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox">
                </iframe>
                @endif
            </div>
        </div>
    </div>
</div>

<style {!! \Helper::cspNonceAttr() !!}>
    .embedded-container {
        padding-left: 0;
        padding-right: 0;
        margin-top: 0;
    }
    
    .seamless {
        margin-top: -20px;
    }
    
    .hide-title {
        margin-top: -10px;
    }
    
    .embedded-panel {
        border: none;
        margin-bottom: 0;
        box-shadow: none;
        background: transparent;
    }
    
    #embedded-iframe, #custom-html-container {
        width: 100%;
        height: calc(100vh - 100px);
        border: none;
        background-color: white;
        display: block;
    }
    
    #custom-html-container {
        overflow: auto;
        padding: 15px;
    }
    
    .container-fluid.embedded-container .panel-default {
        padding: 0;
    }
    
    /* Remove unnecessary padding */
    .container-fluid.embedded-container .panel-body {
        padding: 0;
    }
    
    /* Hide any panel overflow */
    .embedded-panel {
        overflow: hidden;
    }
    
    /* Custom scrollbar for the iframe */
    #embedded-iframe::-webkit-scrollbar,
    #custom-html-container::-webkit-scrollbar {
        width: 10px;
    }
    
    #embedded-iframe::-webkit-scrollbar-track,
    #custom-html-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    #embedded-iframe::-webkit-scrollbar-thumb,
    #custom-html-container::-webkit-scrollbar-thumb {
        background: #888;
    }
    
    #embedded-iframe::-webkit-scrollbar-thumb:hover,
    #custom-html-container::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .hide-header {
        display: none;
    }
    /* Adjust height for hidden header */
    {{ $hideHeader ? '#embedded-iframe, #custom-html-container { height: calc(100vh - 60px); }' : '' }}
    
    /* Custom CSS if provided */
    {!! $customCSS !!}
</style>

<script {!! \Helper::cspNonceAttr() !!}>
    document.addEventListener('DOMContentLoaded', function() {
        // Adjust iframe/container height on window resize
        function adjustHeight() {
            var element = document.getElementById('{{ $isCustomHTML ? "custom-html-container" : "embedded-iframe" }}');
            if (element) {
                var headerOffset = {{ $hideHeader ? 60 : 100 }};
                element.style.height = (window.innerHeight - headerOffset) + 'px';
            }
        }
        
        // Initial adjustment
        adjustHeight();
        
        // Adjust on resize
        window.addEventListener('resize', adjustHeight);
        
        @if(!$isCustomHTML)
        // Try to hide FreeScout header in the iframe if same origin
        var iframe = document.getElementById('embedded-iframe');
        iframe.onload = function() {
            try {
                // This will only work if it's the same origin due to CORS
                var iframeDoc = iframe.contentWindow.document;
                var iframeHead = iframeDoc.querySelector('head');
                if (iframeHead) {
                    var style = iframeDoc.createElement('style');
                    style.textContent = '.navbar, footer, header { display: none !important; }' + 
                                        'body { padding-top: 0 !important; }';
                    iframeHead.appendChild(style);
                    
                    // Adjust padding of body to hide headers
                    var iframeBody = iframeDoc.querySelector('body');
                    if (iframeBody) {
                        iframeBody.style.paddingTop = '0';
                    }
                }
            } catch (e) {
                // CORS will prevent this - it's expected to fail for cross-origin sites
                console.log('Note: Could not modify iframe content due to same-origin policy.');
            }
            
            // Dispatch a custom event for any additional scripting
            var event = new CustomEvent('iframeLoaded', { detail: iframe });
            document.dispatchEvent(event);
        };
        @endif
        
        // Custom JavaScript if provided
        {!! $customJS !!}
    });
</script>
@endsection 