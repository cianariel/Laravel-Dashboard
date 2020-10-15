{{-- Stored in /resources/views/components/elements/breadcrumb.blade.php --}}
@if(isset($list) && filled($list))
    @foreach($list as $index => $item)
        <li class="breadcrumb-item {{ $item->active? 'active': '' }}">@if($item->active === false)<a class="inactive" href="{{ $item->path }}">{{ ucfirst($index) }}</a> @else <span class="active">{{ ucfirst($index) }}</span>@endif</li>
    @endforeach
@endif