{{-- Stored in /resources/views/admin/pages/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Pages')
@section('page-class', 'admin-pages-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Pages') }}</span> <a class="font-lg-size" href="{{ route('admin.pages.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Pages') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($pages) && count($pages) !== 0)
                         @component('components.elements.table_data', ['headers' => ['title', 'author' , 'visible', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $pages, 'query' => $query?? []])
                            @foreach($pages as $page)
                                <tr data-redirect-url="{{ route('admin.pages.show', $page) }}">
                                    <td>{{ $page->title }}</td>
                                    @if($user = user($page->user_id, ['first_name', 'last_name']))
                                        <td><span class="badge badge-secondary">{{ ucwords($user->first_name . ' ' . $user->last_name ) }}</span></td>
                                    @endif
                                    <td>
                                        @if($page->status == App\Models\System\Page::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Page::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Page::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($page->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.pages.restore', ['id' => $page]), 'confirmed' => true, 'dialog_message' => 'Continue to restore page?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.pages.delete.trashed', ['id' => $page]), 'confirmed' => true, 'dialog_message' => 'Continue to delete page forever?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.pages.destroy', $page), 'confirmed' => true, 'dialog_message' => 'Continue to delete page?'])
                                                <a class="fg-blue" target="_blank" href="{{ site_url('page/' . $page->slug) }}"><i class="fas fa-eye"></i></a>
                                                <a class="fg-blue" href="{{ route('admin.pages.edit', $page) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                         @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No pages found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection