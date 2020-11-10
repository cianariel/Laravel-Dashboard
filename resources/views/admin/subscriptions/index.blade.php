{{-- Stored in /resources/views/admin/subscriptions/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Subscriptions')
@section('page-class', 'admin-subscriptions-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Subscriptions') }}</span>
            </div>
            @component('components.bootstrap.card', [
                'id'    => 'admin-subscriptions-card',
            ])
                <div class="card-body">
                    @if($subscriptions->count() !== 0)
                        @component('components.elements.table_data',[
                            'headers' => [
                                'email',
                                'status',
                                ''
                            ],
                            'classes' => ['table-striped', 'table-hover']
                        ])
                            @foreach($subscriptions as $subscription)
                                <tr data-redirect-url="{{ route('admin.subscriptions.show', $subscription) }}">
                                    @if($_user = user($subscription->user_id, ['id', 'email']))
                                        <td>
                                            @component('components.elements.link',[
                                                'href' => route('admin.users.edit', $_user)
                                            ])
                                                {{ $_user->email }}
                                            @endcomponent
                                        </td>
                                    @else
                                        <td>-</td>
                                    @endif
                                    <td><span class="badge badge-{{ $subscription->stripe_status }}">{{ $subscription->stripe_status }}</span></td>
                                    <td class="text-right">
                                        @component('components.elements.link', [
                                            'href'  => route('admin.subscriptions.show', $subscription)
                                        ])
                                            <i class="fas fa-eye fg-blue"></i>
                                        @endcomponent
                                        @component('components.elements.link', [
                                            'href'  => route('admin.subscriptions.edit', $subscription)
                                        ])
                                            <i class="fas fa-edit fg-blue"></i>
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="text-center">{{ __('No user subscriptions.') }}</p>
                    @endif
                </div>
            @endcomponent
        </div>
    </div>
@endsection