{{-- Stored in /resources/views/office/layouts/master.blade.php --}}
@extends('layouts.master')
{{--[head]--}}
@section('head')
    @include('office.partials.head')
@endsection
{{--[/head]--}}
{{--[header]--}}
@section('header')
    @include('office.partials.notify')
@endsection
{{--[/header]--}}
{{--[content]--}}
@section('content')
    <section id="office-section" class="section w-100 h-100">
        {{--[card-group]--}}
        <div class="card-group card-main-group d-flex w-100 h-100">
            {{--[sidebar]--}}@include('office.partials.sidebar'){{--[/sidebar]--}}
            <!--[content]-->
            <div class="card card-main-content m-0 border-0 bg-light-grey card-main-content-sidebar-open">
                @include('office.partials.header')
                <div id="admin-content-body" class="card-body">
                    {{--[breadcrumbs]--}} @include('partials.breadcrumbs') {{--[/breadcrumbs]--}}
                    @yield('content-body')
                </div>
            </div>
            <!--[/content]-->
            @include('office.partials.footer')
        </div>
        {{--[/card-group]--}}
    </section>
@endsection
{{--[/content]--}}