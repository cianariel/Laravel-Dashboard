{{-- Stored in resources/frontend/layouts/master.blade.php --}}
@extends('layouts.master')
{{--[head]--}}
@section('head')
    @include('admin.partials.head')
@endsection
{{--[/head]--}}
{{--[header]--}}
@section('header')
    @include('admin.partials.notify')
@endsection
{{--[/header]--}}
{{--[content]--}}
@section('content')
    <section id="admin-section" class="section w-100 h-100">
        {{--[card-group]--}}
        <div class="card-group card-main-group d-flex w-100 h-100">
            {{--[sidebar]--}}@include('admin.partials.sidebar'){{--[/sidebar]--}}
            <!--[content]-->
            <div class="card card-main-content m-0 border-0 bg-light-grey card-main-content-sidebar-open">
                @include('admin.partials.header')
                <div id="admin-content-body" class="card-body">
                    {{--[breadcrumbs]--}} @include('partials.breadcrumbs') {{--[/breadcrumbs]--}}
                    @yield('content-body')
                </div>
                @include('admin.partials.footer')
            </div>
            <!--[/content]-->
        </div>
        {{--[/card-group]--}}
    </section>
@endsection
{{--[/content]--}}