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
@section('breadcrumbs')
    @if(isset($breadcrumbs))
        @section('breadcrumbs')
            @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])@endcomponent
        @endsection
    @endif
@endsection
{{--[content]--}}
@section('content')
    <section id="office-section" class="section w-100 h-100">
        {{--[card-group]--}}
        <div class="card-group card-main-group d-flex w-100 h-100">
            {{--[content]--}}
            <div class="card card-main-content m-0 border-0 bg-light-grey">
                {{--[header]--}}
                @include('office.partials.header')
                {{--[/header]--}}
                <div id="office-content-body" class="card-body">
                    {{--[breadcrumbs]--}}
                        @include('partials.breadcrumbs')
                    {{--[/breadcrumbs]--}}
                    @yield('content-body')
                </div>
            </div>
            {{--[/content]--}}
            {{--[footer]--}}
            @include('office.partials.footer')
            {{--[/footer]--}}
        </div>
        {{--[/card-group]--}}
    </section>
@endsection
{{--[/content]--}}