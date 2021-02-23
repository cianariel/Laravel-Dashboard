<div id="{{ $id ?? '' }}" class="input-group search-container @if(!empty($classes) && is_array($classes)) {{ implode(' ', $classes) }}@endif">
    @if(!empty($description)) 
        <div class="text-center p-2 w-100 title">{{ $description }}</div>
    @endif
    <div class="search-holder">
        <input @if(!empty($search_id)) id="{{$search_id}}" @endif type="text" class="form-control" placeholder="{{ $placeholder }}">
        <span class="input-group-addon">
            <i class="fa fa-search search-icon"></i>
        </span>
    </div>
</div>

