{{-- Stored in /resources/views/frontend/pages/default.blade.php --}}
@extends('frontend.layouts.master')
@if(filled($page->title))
    @section('html-title', $page->title)
@endif
@section('page-class', 'page-' . $page->slug)
@section('content-body')
    @component('components.bootstrap.container', [
        'fluid' => false,
    ])
        <div class="row mt-3">
            <div class="col-12">
                @component('components.bootstrap.card', [
                    'id'    => 'page-' . $page->slug . '-card'
                ])
                    @if($image = $page->getMedia('images')->first())
                        <img src="{{ $image->getFullUrl() }}" class="card-img-top">
                    @endif
                    <div class="card-body pt-3 border-0">
                        <h1 class="card-title">{{ $page->title }}</h1>
                        @if(filled($page->content))
                            {!! $page->content !!}
                        @endif
                    </div>
                    <div class="card-footer bg-white border-0">
                        <p class="card-text">
                            <small class="text-muted">Last updated {{ \Carbon\Carbon::parse($page->updated_at)->diffForHumans() }}</small>
                        </p>
                    </div>
                @endcomponent
            </div>
        </div>
    @endcomponent
@endsection