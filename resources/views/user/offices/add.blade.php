{{-- Stored in /resources/views/user/account/edit.blade.php --}}
@extends('user.layouts.master')
@section('html-title', 'Add Offices')
@section('page-class', 'user-offices-add')
@section('page_script')
    @component('components.elements.script', ['src' => mix('js/selectize.js')])@endcomponent
@endsection

{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => false
    ])
        <div class="bg-white">
            <div class="row ">
                <div class="col-md-6">
                    <div class="text-center p-2">
                        <button class="btn btn-primary">{{__('Registered Offices')}}</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center p-2">
                        <button class="btn btn-secondary">{{__('Non-Registered Offices')}}</button>
                    </div>
                </div>
            </div>
            <div class="row no-gutters">
                <div class="col-12">
                    @component('components.elements.search', [
                        'description' => __('Add an office to your list'),
                        'placeholder' => __('Enter office name, address or provider')
                    ])
                    @endcomponent
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 ">
                    @foreach($offices as $office)
                        <div class="search-result-holder pl-3 pr-3 pt-4 pb-2">
                            <h5>{{ $office->label }}</h5>
                            @php
                                $location = $office->getMetaField('location', '');
                            @endphp
                            @if($location)
                                <div>{{$location['address']. ", ". $location['city']. ", ". $location['state']. " " . $location['zipcode']}}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endcomponent

    <style>
        .search-result-holder{
            cursor: pointer;
            border: 1px solid #fff;
        }

        .search-result-holder:hover{
            background: #034ea2;
            color: #fff;
            
        }
    </style>

@endsection