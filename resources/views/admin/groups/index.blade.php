{{-- Stored in /resources/views/admin/groups/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Groups')
@section('page-class', 'admin-groups-index')
@if(isset($breadcrumbs))
    @section('breadcrumbs')
        @component('components.elements.breadcrumbs', ['list' => $breadcrumbs])
        @endcomponent
    @endsection
@endif
@section('content-body')
    <div class="row h-100 justify-content-center">
        <div class="col-12">
            <div class="header">
                <span class="font-lg-size font-weight-bold">{{ __('Groups') }}</span> <a class="font-lg-size" href="{{ route('admin.groups.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Groups') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($groups) && count($groups) !== 0)
                        @component('components.elements.table_data', ['headers' => ['folder' , 'visible', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $groups, 'query' => $query?? []])
                            @foreach($groups as $group)
                                <tr data-redirect-url="{{ route('admin.groups.show', $group) }}">
                                    <td>{{ $group->label }}</td>
                                    <td>
                                        @if($group->visible == App\Models\System\Group::VISIBLE)
                                            <span><i class="fas fa-eye"></i></span>
                                        @else
                                            <span><i class="fas fa-eye-slash"></i></span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($group->lock == App\Models\System\Group::LOCKED)
                                            <a class="fg-blue" href="{{ route('admin.groups.edit', $group) }}"><i class="fas fa-edit"></i></a>
                                            <span class="fg-blue" title="Locked"><i class="fas fa-lock"></i></span>
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE','action' => route('admin.groups.destroy', $group), 'confirmed' => true, 'dialog_message' => 'Continue to delete group?'])
                                                <a class="fg-blue" href="{{ route('admin.groups.edit', $group) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                         <p class="card-text text-center">{{ __('No groups found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection