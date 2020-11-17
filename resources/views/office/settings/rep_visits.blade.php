@extends('office.layouts.master')
@section('html-title', 'Edit Rep Visits Settings')
@section('page-class', 'office-settings-rep-visits-edit')
{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid'     => true,
        'classes'   => [
            'mt-3'
        ]
    ])
        <div class="row justify-content-center">
            <div class="col-12 col-md-12">
                @component('components.bootstrap.card', [
                    'layout'    => 'card-group'
                ])
                    @include('office.settings.partials.sidebar')
                    @component('components.bootstrap.card', [
                        'id' => 'office-settings-visits-edit-card'
                    ])
                    @endcomponent
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection