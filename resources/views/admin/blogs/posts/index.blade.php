{{-- Stored in /resources/views/admin/blogs/posts/index.blade.php --}}
@extends('admin.layouts.master')
@section('html-title', 'Blog Posts')
@section('page-class', 'admin-blog-posts-index')
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
                <span class="font-lg-size font-weight-bold">{{ __('Posts') }}</span> <a class="font-lg-size" href="{{ route('admin.posts.create', $blog) }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            <div class="card">
                <div class="card-header border-0 bg-white"><span class="font-weight-bold">{{ __('List of Posts') }}</span> <span class="pull-right">{{ __('Show:') }} <a class="fg-grey" href="{{ url()->current() }}"><i class="fas fa-eye"></i></a> | <a class="fg-grey" href="{{ url()->current() . '?with_trashed=true' }}"><i class="fas fa-trash-alt"></i></a></span></div>
                <div class="card-body p-0 pt-3 pb-3">
                    @if(isset($posts) && count($posts) !== 0)
                        @component('components.elements.table_data', ['headers' => ['title', 'status', 'visible' , 'actions'], 'classes' => ['table-striped', 'table-hover'], 'paged' => $posts, 'query' => $query?? []])
                            @foreach($posts as $post)
                                <tr data-redirect-url="{{ route('admin.posts.show', ['post' => $post, 'blog' => $blog]) }}">
                                    <td>{{ $post->title }}</td>
                                    <td>
                                        @if($post->status == App\Models\System\Post::ACTIVE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Post::ACTIVE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Post::INACTIVE)) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($post->visible == App\Models\System\Post::VISIBLE)
                                            <span class="badge badge-primary">{{ __(ucfirst(App\Models\System\Post::VISIBLE)) }}</span>
                                        @else
                                            <span class="badge badge-secondary">{{ __(ucfirst(App\Models\System\Post::HIDDEN)) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        @if($withTrashed === true)
                                            @if(filled($post->deleted_at))
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'PUT', 'action' => route('admin.posts.restore', ['post' => $post, 'blog' => $blog]), 'confirmed' => true, 'dialog_message' => 'Continue to restore post?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Restore') }}"><i class="fas fa-trash-restore"></i></button>
                                                @endcomponent
                                                @component('components.forms.form', ['classes' => ['d-inline'], 'method' => 'DELETE', 'action' => route('admin.posts.delete.trashed', ['post' => $post, 'blog' => $blog]), 'confirmed' => true, 'dialog_message' => 'Continue to delete post forever?'])
                                                    <button class="btn fg-blue btn-unstyled" type="submit" title="{{ __('Delete Forever') }}"><i class="fas fa-trash-alt"></i></button>
                                                @endcomponent
                                            @endif
                                        @else
                                            @component('components.forms.form', ['method' => 'DELETE', 'action' => route('admin.posts.destroy', ['post' => $post, 'blog' => $blog]), 'confirmed' => true, 'dialog_message' => 'Continue to delete post?'])
                                                <a class="fg-blue" target="_blank" href="{{ site_url('blog/' . $blog->slug . '/' . $post->slug) }}"><i class="fas fa-eye"></i></a>
                                                <a class="fg-blue" href="{{ route('admin.posts.edit',['post' => $post, 'blog' => $blog]) }}"><i class="fas fa-edit"></i></a>
                                                <button class="btn fg-blue btn-unstyled" type="submit"><i class="fas fa-trash-alt"></i></button>
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endcomponent
                    @else
                        <p class="card-text text-center">{{ __('No posts found.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection