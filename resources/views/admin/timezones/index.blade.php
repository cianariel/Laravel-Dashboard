{{-- Stored in /resources/views/admin/timezones/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Timezones')
@section('page-class', 'admin-timezones-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Timezones') }}</span> <a class="font-lg-size" href="{{ route('admin.timezones.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Timezones') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($timezones) && count($timezones) !== 0)
                        @component('components.elements.table_data', ['headers' => ['zone', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $timezones, 'query' => $query?? []])
                            @foreach($timezones as $timezone)
                                <tr data-redirect-url="{{ route('admin.timezones.show', $timezone) }}">
                                    <td>{{ ucfirst($timezone->zone) }}</td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($timezone->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.timezones.restore', ['id' => $timezone]), 'confirmed' => true, 'dialog_message' => 'Continue to restore timezone?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.timezones.delete.trashed', ['id' => $timezone]), 'confirmed' => true, 'dialog_message' => 'Continue to delete timezone forever?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.timezones.destroy', $timezone), 'confirmed' => true, 'dialog_message' => 'Continue to delete timezone?'])
                                                <a class="fg-blue" href="{{ route('admin.timezones.edit', $timezone) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No timezones found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection