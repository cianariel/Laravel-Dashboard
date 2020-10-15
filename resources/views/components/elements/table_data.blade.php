{{-- Stored in /resources/views/compontents/elements/table_data.blade.php --}}
@if(isset($paged) && filled($paged))
<div class="row">
    <div class="col-6">
        <div class="gb-pagination-show-select pl-4">
            <span>{{ __('Show') }}</span>
            <form class="form form-auto-submit d-inline" action="{{ url()->current() }}" method="GET">
                @csrf
                <select name="per_page">
                    <option value="10" @if(isset($query) && array_key_exists('per_page', $query) && safe_integer($query['per_page']) == 10 ) selected @endif>{{ __('10') }}</option>
                    <option value="25" @if(isset($query) && array_key_exists('per_page', $query) && safe_integer($query['per_page']) == 25 ) selected @endif>{{ __('25') }}</option>
                    <option value="50" @if(isset($query) && array_key_exists('per_page', $query) && safe_integer($query['per_page']) == 50 ) selected @endif>{{ __('50') }}</option>
                    <option value="100" @if(isset($query) && array_key_exists('per_page', $query) && safe_integer($query['per_page']) == 100 ) selected @endif>{{ __('100') }}</option>
                </select>
                @if(isset($withTrashed) && filled($withTrashed))
                    <input type="hidden" name="with_trashed" value="{{ $withTrashed }}">
                @endif
                <button class="hidden" type="submit">{{ __('Change') }}</button>
            </form>
            <span>{{ __('entries') }}</span>
        </div>
    </div>
    <div class="col-6">
        <span class="d-block text-right pr-4 pt-1 pb-3">{{ __('Showing')}} {{ $paged->currentPage() }} {{ __('to') }} {{ $paged->perPage() }} {{ __('of') }} {{ $paged->total() }} {{ __('entries') }}</span>
    </div>
</div>
@endif
<div class="gb-table table-responsive">
    <table id="{{ $id?? uniqid('gb-table-', false) }}" class="table @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }}@endif" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif>
        @if(isset($headers) && filled($headers))
            <thead>
                <tr>
                    @foreach($headers as $th)
                        <th scope="col">{{ filled($th)? __(ucfirst($th)) : '' }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            {!! $slot !!}
        </tbody>
    </table>
</div>
@if(isset($paged) && filled($paged))
<div class="gb-pagination text-right pr-4">
    @if(isset($query) && filled($query))
        {{ $paged->appends($query)->links() }}
    @else
        {{ $paged->links() }}
    @endif
</div>
@endif