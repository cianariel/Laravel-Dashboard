{{-- Stored in /resources/views/user/account/edit.blade.php --}}
@extends('user.layouts.master')
@section('html-title', 'Account')
@section('page-class', 'user-account')
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
        </div>
    @endcomponent

@endsection