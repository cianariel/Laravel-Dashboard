{{-- Stored in /resources/views/admin/menus/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Menus')
@section('page-class', 'admin-menus-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Menus') }}</span> <a class="font-lg-size" href="{{ route('admin.menus.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Menus') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($menus) && count($menus) != 0)
                        @component('components.elements.table_data', ['headers' => ['title', 'type', 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $menus, 'query' => $query?? []])
                            @foreach($menus as $menu)
                                <tr data-redirect-url="{{ route('admin.menus.show', $menu) }}">
                                    <td>{{ ucwords(strip_tags($menu->label)) }}</td>
                                    <td>{{ ucwords($menu->location) }}</td>
                                    <td>
                                        @if($menu->status == App\Models\System\Menu::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Menu::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Menu::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.menus.destroy', $menu), 'confirmed' => true, 'dialog_message' => 'Continue to delete menu?'])
                                            <a class="fg-blue" href="{{ route('admin.menus.edit', $menu) }}"><i class="fas fa-edit"></i></a>
                                            <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No menus found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection