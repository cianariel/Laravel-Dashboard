{{-- Stored in /resources/views/office/setup/subscription/thankyou.blade.php --}}
@extends('frontend.layouts.master')
@section('html-title', __('Thank You.'))
@section('page-class', 'thank-you')
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => false,
    ])
        <div class="row pt-3 mt-3 bg-white">
            <div class="col-12">
                <h1 class="text-center">{{ __('Thank You!') }}</h1>
                <span class="text-center d-block w-100"><i class="fg-red fas fa-heart fa-3x"></i></span>
                <div class="d-block text-center mt-3 mb-3">
                    @component('components.elements.link', [
                        'href'      => route('user.dashboard'),
                        'classes'   => [
                            'btn',
                            'btn-primary'
                        ]
                    ])
                        {{ __('Go to dashboard') }}
                    @endcomponent
                </div>
            </div>
        </div>
    @endcomponent
@endsection