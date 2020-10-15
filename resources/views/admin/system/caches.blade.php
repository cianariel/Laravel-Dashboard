{{-- Stored in /resources/views/admin/system/usage.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'System Caches')
@section('page-class', 'admin-system-caches-index')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('System Caches') }}</span>
                <span class="pull-right">
                    @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.system.cache.flush'), 'confirmed' => true, 'dialog_message' => 'Continue to flush system cache?'])
                        <button class="fg-dark-blue btn-unstyled" type="submit">{{ __('Flush Cache') }} <i class="fas fa-trash-alt"></i></button>
                    @endcomponent
                </span>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Caches') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($caches) && count($caches) !== 0)
                        @component('components.elements.table_data', ['headers' => ['key', 'expiration'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $caches, 'query' => $query?? []])
                            @foreach($caches as $cache)
                                <tr>
                                    <td>{{ $cache->key}}</td>
                                    <td>{{ $cache->expiration }}</td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No cache data found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection