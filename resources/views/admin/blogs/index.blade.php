{{-- Stored in /resources/views/admin/blogs/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Blogs')
@section('page-class', 'admin-blogs-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Blogs') }}</span> <a class="font-lg-size" href="{{ route('admin.blogs.create') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Blogs') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($blogs) && count($blogs) !== 0)
                         @component('components.elements.table_data', ['headers' => ['title', 'status', 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $blogs, 'query' => $query?? []])
                            @foreach($blogs as $blog)
                                <tr data-redirect-url="{{ route('admin.posts.index', $blog) }}">
                                    <td>{{ $blog->title }}</td>
                                    <td>
                                        @if($blog->status == App\Models\System\Blog::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Blog::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Blog::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($blog->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.blogs.restore', ['id' => $blog]), 'confirmed' => true, 'dialog_message' => 'Continue to restore blog?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.blogs.delete.trashed', ['id' => $blog]), 'confirmed' => true, 'dialog_message' => 'Continue to delete blog forever?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.blogs.destroy', $blog), 'confirmed' => true, 'dialog_message' => 'Continue to blog delete?'])
                                                <a class="fg-blue" target="_blank" href="{{ site_url('blog/' . $blog->slug) }}" title="View Blog"><i class="fas fa-eye"></i></a>
                                                <a class="fg-blue" href="{{ route('admin.blogs.settings', ['id' => $blog]) }}" title="Edit Settings"><i class="fas fa-cog"></i></a>
                                                <a class="fg-blue" href="{{ route('admin.blogs.edit', $blog) }}" title="Edit"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Trash') }}"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                         @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No blogs found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection