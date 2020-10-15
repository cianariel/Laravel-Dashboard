{{-- Stored in /resources/views/admin/settings/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Settings')
@section('page-class', 'admin-settings-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Settings') }}</span> <a class="font-lg-size" href="{{ route('admin.settings.create') }}"><i class="fas fa-plus-circle"></i></a>
                <a class="pull-right" href="{{ route('admin.settings.manage') }}">{{ __('Manage Settings') }}</a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('All Groups') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($groups) && count($groups) !== 0)
                        @component('components.elements.table_data', ['headers' => ['settings', 'actions'], 'classes' => ['table-striped', 'table-hover']])
                            @foreach($groups as $group)
                                <tr data-redirect-url="{{ route('admin.settings.group', ['group' => $group->name]) }}">
                                    <td>{{ $group->label }}</td>
                                    <td class="text-right">
                                        <a class="fg-blue" href="{{ route('admin.settings.group', ['group' => $group->name]) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No settings found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection