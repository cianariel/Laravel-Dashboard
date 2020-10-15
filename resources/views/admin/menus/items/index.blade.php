{{-- Stored in /resources/views/admin/menus/items/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Menus Items')
@section('page-class', 'admin-menus-items-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Menu Items') }}</span> <a class="font-lg-size" href="{{ route('admin.menus.menu_items.create', $menu) }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Menu Items') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($menu_items) && $menu_items->count() !== 0)
                        @component('components.elements.table_data', ['headers' => ['position', 'label', 'url' , 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $menu_items, 'query' => $query?? []])
                            @foreach($menu_items as $item)
                                <tr data-redirect-url="{{ route('admin.menus.menu_items.show', [$menu, $item]) }}">
                                    <td>{{ $item->position }}</td>
                                    <td>{{ ucwords($item->label) }}</td>
                                    <td>{{ Str::limit($item->url) }}</td>
                                    <td class="text-right">
                                        @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.menus.menu_items.destroy', [$menu, $item]), 'confirmed' => true, 'dialog_message' => 'Continue to delete menu item?'])
                                            <a class="fg-blue" href="{{ route('admin.menus.menu_items.edit', [$menu, $item]) }}"><i class="fas fa-edit"></i></a>
                                            <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No menu items found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection