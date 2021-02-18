{{-- Stored in /resources/views/user/account/edit.blade.php --}}
@extends('user.layouts.master')
@section('html-title', 'Offices')
@section('page-class', 'user-offices-index')
@section('page_script')
    @component('components.elements.script', ['src' => mix('js/selectize.js')])@endcomponent
@endsection

{{--[content]--}}
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => true
    ])
        <div class="bg-white">
            <div class="row no-gutters offices-container">
                <div class="col-md-3 br-1">
                    <h5 class="text-center pt-2">MY OFFICES</h5>
                    <a id="add-office-btn" href="{{ route('user.offices.add') }}" >
                        <i class="fa fa-plus"></i>
                    </a>
                    @component('components.elements.search', [
                        'placeholder' => __('Enter office name, address or provider')
                    ])
                    @endcomponent
                    <div class="p-3">
                        <i>No offices found</i>
                    </div>
                </div>
                <div class="col-md-9">
                    <h3 class="text-center mt-5">{{__("You currently have no offices!")}} Click <a href="{{ route('user.offices.add') }}">here</a> to add one!</h3>
                </div>
            </div>
        </div>
    @endcomponent
    
    <style>
        #add-office-btn{
            position: absolute;
            top: 7px;
            right: 15px;
            width: 27px;
            height: 27px;
            color: #000;
            border: 1px solid #000;
            text-align: center;
            border-radius: 50%; 
        }

        .offices-container{
            height: calc(100vh - 145px);
        }
    </style>
@endsection