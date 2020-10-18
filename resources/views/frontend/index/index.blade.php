{{-- Stored in /resources/views/frontend/index/index.blade.php --}}
@extends('frontend.layouts.master')
@section('content-body')
    @component('components.bootstrap.card', [
        'id'        => 'card-frontend-index',
        'classes'   => ['bg-blue', 'fg-white', 'p-0']
    ])
        <div class="card-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-md-3 mt-5">
                        <h1 class="font-weight-light">{{ __('Right Rep. Right Time. Right Information. Zero Hassle. Zero Cost.') }}</h1>
                        <p class="card-text fg-white mt-5">
                            {{ __('We seamslessly connect your practice to the precise life science experts and resources you need, exactly when and where you need them.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endcomponent
@endsection