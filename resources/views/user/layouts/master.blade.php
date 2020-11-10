{{-- Stored in /resources/views/user/layouts/master.blade.php --}}
@extends('layouts.master')
{{--[head]--}}
@section('head')
    @include('user.partials.head')
@endsection
{{--[/head]--}}
{{--[header]--}}
@section('header')
    @include('user.partials.notify')
@endsection
{{--[/header]--}}
@section('breadcrumbs')
    @if(isset($breadcrumbs))
        @section('breadcrumbs')
            @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])@endcomponent
        @endsection
    @endif
@endsection
{{--[content]--}}
@section('content')
    <section id="user-section" class="section w-100 h-100">
        {{--[card-group]--}}
        <div class="card-group card-main-group d-flex w-100 h-100">
            {{--[content]--}}
            <div class="card card-main-content m-0 border-0 bg-light-grey">
                {{--[header]--}}
                @include('user.partials.header')
                {{--[/header]--}}
                <div id="user-content-body" class="card-body">
                    {{--[breadcrumbs]--}}
                        @include('partials.breadcrumbs')
                    {{--[/breadcrumbs]--}}
                    @yield('content-body')
                </div>
            </div>
            {{--[/content]--}}
            {{--[footer]--}}
                @include('user.partials.footer')
            {{--[/footer]--}}
        </div>
        {{--[/card-group]--}}
    </section>
@endsection
{{--[/content]--}}