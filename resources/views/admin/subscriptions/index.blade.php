{{-- Stored in /resources/views/admin/subscriptions/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Subscriptions')
@section('page-class', 'admin-subscriptions-index')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif