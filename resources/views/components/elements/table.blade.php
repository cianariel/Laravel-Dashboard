{{-- Stored in /resources/views/compontents/elements/table.blade.php --}}
<div id="{{ $id?? uniqid('md-table-', false) }}" class="md-table table-responsive">
    <table class="table @if(isset($classes) && filled($classes) && count($classes) !=0) {{ implode(' ', $classes) }}@endif" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif>
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
<div class="md-pagination text-right pr-4">
    @if(isset($query) && filled($query))
        {{ $paged->appends($query)->links() }}
    @else
        {{ $paged->links() }}
    @endif
</div>
@endif