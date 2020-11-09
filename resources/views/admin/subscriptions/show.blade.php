{{-- Stored in /resources/views/admin/subscriptions/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Show Subscription')
@section('page-class', 'admin-subscriptions-show')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', [
            'list' => $breadcrumbs
        ])@endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('Showing Subscription') }}</span>
            </div>
            @component('components.bootstrap.card', [
                'id'        => 'admin-subscriptions-show-card'
            ])
                <div class="card-body">
                </div>
            @endcomponent
        </div>
    </div>
@endsection