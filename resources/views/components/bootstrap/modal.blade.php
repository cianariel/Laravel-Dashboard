{{-- Stored in /resources/views/components/bootstrap/modal.blade.php --}}
<div id="{{ $id?? uniqid('md-modal-btn', false) }}" class="md-modal modal {{ $modal_effect?? 'fade' }} @if(isset($classes) && is_array($classes) && filled($classes)) {{ implode(' ', $classes) }} @endif" tabindex="-1" role="dialog">
    <div class="modal-dialog {{ $size?? '' }} {{ $alignment?? 'modal-dialog-centered' }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                @if(isset($title) && filled($title))
                    <h5 class="modal-title">
                        @if( s($title)->contains('<') && s($title)->contains('>'))
                            {!! $title !!}
                        @else
                            {{ $title }}
                        @endif
                    </h5>
                @endif
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <i class="close-icon fas fa-times" aria-hidden></i>
                </button>
            </div>
            {!! $slot !!}
            <div class="modal-footer">
                @if(!isset($buttons))
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ $button_label?? __('Close') }}</button>
                @else
                    @if(!is_array($buttons) && s($buttons)->contains('<') && s($buttons)->contains('>'))
                        {!! $buttons !!}
                    @elseif(is_array($buttons) && filled($buttons))
                        @foreach($buttons as $btn)
                            @if($loop->first)
                                @component('components.forms.button', [
                                    'type'      => 'button',
                                    'name'      => ($btn['name']?? $id?? ''),
                                    'value'     => ($btn['value']?? ''),
                                    'label'     => ($btn['label']?? ''),
                                    'classes'   => (is_array($btn['classes'])? $btn['classes'] : ['btn', 'btn-primary'])
                                ])@endcomponent
                            @else
                                @component('components.forms.button', [
                                    'type'      => 'button',
                                    'name'      => ($btn['name']?? $id?? ''),
                                    'value'     => ($btn['value']?? ''),
                                    'label'     => ($btn['label']?? ''),
                                    'classes'   => (is_array($btn['classes'])? $btn['classes'] : ['btn', 'btn-secondary'])
                                ])@endcomponent
                            @endif
                        @endforeach
                    @else
                        @component('components.forms.button', [
                            'type'      => 'button',
                            'name'      => uniqid('md-modal-btn', false),
                            'value'     => $value?? '',
                            'label'     => $label?? __('Close'),
                            'classes'   => (is_array($button_classes)? $button_classes : ['btn', 'btn-secondary'])
                        ])@endcomponent
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@component('components.elements.script')
    jQuery(document).ready(function($){
        let wvModal = $('#{{ $id }}');

        @if(isset($options))
            @if(is_array($options) && filled($options))
                wvModal.modal(@json($options));
            @endif
        @else
            wvModal.modal({
                backdrop: true,
                keyboard: true,
                focus: true,
                show: true
            });
        @endif
    });
@endcomponent