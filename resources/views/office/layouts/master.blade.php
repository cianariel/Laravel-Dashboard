{{-- Stored in resources/frontend/layouts/master.blade.php --}}
@extends('layouts.master')
{{--[head]--}}
@section('head')
    @include('office.partials.head')
@endsection
{{--[/head]--}}
{{--[header]--}}
@section('header')
    @include('office.partials.notify')
    @include('office.partials.header')
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
    @component('components.bootstrap.container', [
        'fluid'     => true,
        'classes'   => [
            'mt-3'
        ]
    ])
        {{--[breadcrumbs]--}} @include('partials.breadcrumbs') {{--[/breadcrumbs]--}}
    @endcomponent
    @yield('content-body');
@endsection
{{--[/content]--}}
{{--[footer]--}}
@section('footer')
    @include('office.partials.footer')
@endsection
{{--[/footer]--}}