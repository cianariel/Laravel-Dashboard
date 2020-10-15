{{-- Stored in /resources/views/admin/redirects/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Redirects')
@section('page-class', 'admin-redirects-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Redirects') }}</span> <a class="font-lg-size" href="{{ route('admin.redirects.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Redirects') }}</span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($redirects) && count($redirects) !== 0)
                        @component('components.elements.table_data', ['headers' => ['name', 'path' , 'redirect', 'code', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $redirects, 'query' => $query?? []])
                            @foreach($redirects as $redirect)
                                <tr data-redirect-url="{{ route('admin.redirects.show', $redirect) }}">
                                    <td>{{ $redirect->name }}</td>
                                    <td>{{ $redirect->path }}</td>
                                    <td>{{ $redirect->redirect_path }}</td>
                                    <td>{{ $redirect->code }}</td>
                                    <td class="text-right">
                                        @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.redirects.destroy', $redirect), 'confirmed' => true, 'dialog_message' => 'Continue to delete redirect?'])
                                            <a class="fg-blue" href="{{ route('admin.redirects.edit', $redirect) }}"><i class="fas fa-edit"></i></a>
                                            <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No redirects found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection