<div id="{{ $id ?? '' }}" class="input-group search-container bt-1 bb-1 pt-1 pb-1">
    @if(!empty($description)) 
        <div class="text-center p-2 w-100 title">{{ $description }}</div>
    @endif
    <div class="search-holder">
        <input type="text" class="form-control" placeholder="{{ $placeholder }}">
        <span class="input-group-addon">
            <i class="fa fa-search search-icon"></i>
        </span>
    </div>
    {!! $slot !!}
</div>

