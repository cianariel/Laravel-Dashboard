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
                            <div class="col-12 col-md-4">
                                {{ __('User') }}
                            </div>
                            <div class="col-12 col-md-8">
                                @if($_user = user($subscription->user_id, ['id', 'email']))
                                    @component('components.elements.link', [
                                        'href'  => route('admin.users.show', $_user)
                                    ])
                                        {{ $_user->email }}
                                    @endcomponent
                                @endif
                            </div>
                        </div>
                        @component('components.forms.input', [
                            'id'        => 'stripe_id',
                            'name'      => 'stripe_id',
                            'label'     => 'Stripe ID',
                            'value'     => $subscription->stripe_id,
                            'readonly'  => true
                        ])
                            @error('stripe_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        @endcomponent
                        @component('components.forms.select', [
                            'id'        => 'status',
                            'name'      => 'status',
                            'label'     => __('Status'),
                            'value'     => old('status')?? $subscription->status
                            'options'   => \App\Models\System\Subscription::STATUS_TYPES
                        ])
                            @error('status')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        @endcomponent
                        <div class="row">
                            <div class="col-12 offset-md-4">
                                @component('components.forms.button', [
                                    'id'        => 'submit-btn'
                                    'type'      => 'submit',
                                    'name'      => 'submit-btn',
                                    'classes'   => ['btn-primary'],
                                    'label'     => __('Update')
                                ])
                                    @component('components.elements.link', [
                                        'href'      => route('admin.subscriptions.index'),
                                        'classes'   => [
                                            'btn',
                                            'btn-secondary'
                                        ]
                                    ])
                                        {{ __('Cancel') }}
                                    @endcomponent
                                @endcomponent
                            </div>
                        </div>
                    @endcomponent
                </div>
            @endcomponent
        </div>
    </div>
@endsection