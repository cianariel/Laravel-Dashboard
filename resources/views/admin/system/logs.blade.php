{{-- Stored in /resources/views/admin/system/logs.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'System Logs')
@section('page-class', 'admin-system-logs')
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
                <span class="font-lg-size font-weight-bold">{{ __('Logs') }}</span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('Details') }}</span></div>
                <div class="card-body p-0 pt-5 pb-5">
                </div>
            </div>
        </div>
    </div>
@endsection