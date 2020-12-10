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
                                        'href'      => route('office.messages.create'),
                                        'classes'   => [
                                            'btn',
                                            'btn-primary'
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