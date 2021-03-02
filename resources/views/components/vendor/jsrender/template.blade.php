{{-- Stored in /resources/views/components/vendor/jsrender/template.blade.php --}}
<script id="{{ $id }}" class='gb-jsrender-tpl' type="text/x-jsrender" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif nonce="{{ csp_nonce() }}">
{!! $slot !!}
</script>