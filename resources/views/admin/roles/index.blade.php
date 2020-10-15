{{-- Stored in /resources/views/admin/roles/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Roles')
@section('page-class', 'admin-roles-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Roles') }}</span> <a class="font-lg-size" href="{{ route('admin.roles.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Roles') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($roles) && count($roles) !== 0)
                        @component('components.elements.table_data', ['headers' => ['role' , 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $roles, 'query' => $query?? []])
                            @foreach($roles as $role)
                                <tr data-redirect-url="{{ route('admin.roles.show', $role) }}">
                                    <td>{{ $role->label }}</td>
                                    <td>
                                        @if($role->status == App\Models\System\Role::ACTIVE)
                                            <span class="badge badge-primary">{{ __('Active') }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __('Inactive') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if(in_array($role->name, App\Models\System\Role::ROLES))
                                            <a class="fg-blue" href="{{ route('admin.roles.edit', $role) }}"><i class="fas fa-edit"></i></a>
                                            <span class="fg-blue" title="Locked"><i class="fas fa-lock"></i></span>
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.roles.destroy', $role), 'confirmed' => true, 'dialog_message' => 'Continue to delete role?'])
                                                <a class="fg-blue" href="{{ route('admin.roles.edit', $role) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No roles found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection