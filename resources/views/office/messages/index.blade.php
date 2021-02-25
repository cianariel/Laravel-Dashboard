{{-- Stored in /resources/views/office/calendar/index.blade.php --}}
@extends('office.layouts.master')
@section('html-title', 'Messages')
@section('page-class', 'office-messages-index')
{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid'     => true,
        'classes'   => [
            'mt-3'
        ]
    ])
        <div class="row">
            <div class="col-12">
                @component('components.bootstrap.card', [
                    'layout'    => 'card-group'
                ])
                    @include('office.messages.partials.sidebar')
                    @component('components.bootstrap.card', [
                        'id'    => 'office-messges-card'
                    ])
                        <div class="card-body">
                            @if($messages->count() !== 0)
                            @else
                                <p class="card-text text-center">{{ __('No messages found.') }}</p>
                                <p class="card-text text-center">
                                    @component('components.elements.link', [
                                        'href'      => '#office-message-create-modal',
                                        'classes'   => [
                                            'btn',
                                            'btn-primary'
                                        ],
                                        'attrs'     => [
                                            'data-toggle'   => 'modal',
                                            'data-target'   => '#office-message-create-modal'
                                        ]
                                    ])
                                        {{ __('Click here to create a new message.') }}
                                    @endcomponent
                                </p>
                            @endif
                        </div>
                    @endcomponent
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection
@section('html_before_end')
    @include('office.messages.partials.new_message')
@endsection
@section('scripts_end')
<script type="text/javascript">
<!--
    jQuery(document).ready(function($){

        let retrieveMessage = function(id) {

            let url = '{{ route('office.ajax.message.retrieve') }}';

            if(id) {

                let ajaxRequest = MD.post(url, {id: id}, 'json', function(response){

                    let data = response.data;

                    if(data.status == 404) {
                         dialog('{{ __('Notice') }}', '{{ __('Invaild message reference.') }}');
                    }

                    if(data.status == 200) {
                        let message = data.message;
                    }

                }, function(error){

                    dialog('{{ __('Error') }}', '{{ __('Error occured, please try again.') }}');

                }, function() {
                    // finally

                }, 5000);

            } else {
                dialog('{{ __('Notice') }}', '{{ __('Invaild message reference.') }}');
            }
        }

    });
//-->
</script>
@endsection