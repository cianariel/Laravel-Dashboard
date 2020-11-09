{{-- Stored in /resources/views/admin/subscriptions/edit.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Edit Subscription')
@section('page-class', 'admin-subscriptions-edit')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', [
            'list' => $breadcrumbs
        ])@endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('Edit Subscription') }}</span>
            </div>
            @component('components.bootstrap.card', [
                'id'        => 'admin-subscriptions-edit-card'
            ])
                <div class="card-body">
                    @component('components.forms.form', [
                        'id'        => 'admin-subscriptions-edit-form',
                        'method'    => 'PUT',
                        'action'    => route('admin.subscriptions.update', $subscription)
                        'confirmed' => true
                    ])
                        <div class="row">
                            <div class="col-12 offset-md-4">

                            </div>
                        </div>
                    @endcomponent
                </div>
            @endcomponent
        </div>
    </div>
@endsection