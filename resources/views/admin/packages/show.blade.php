{{-- Stored in /resources/views/admin/packages/show.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Show Package')
@section('page-class', 'admin-packages-show')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('Show Package') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Package Information') }}</span></div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-sm-10">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection