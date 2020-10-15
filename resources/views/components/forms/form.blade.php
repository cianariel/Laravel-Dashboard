{{-- Stored in /resources/views/components/forms/form.blade.php --}}
<form @if(isset($id) && filled($id)) id="{{ $id }}" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif @endif class="form @if(isset($confirmed) && $confirmed === true){{ __('gb-confirm-action-form') }}@endif @if(isset($classes) && filled($classes) && is_array($classes)){{ implode(' ', $classes) }}@else{{ $classes?? '' }}@endif" @if(isset($confirmed) && $confirmed === true) data-type="confirmed" data-dialog-title="{{ $dialog_title?? 'Confirm' }}" data-dialog-message="{{ $dialog_message?? 'Continue and save changes?'}}" @endif method="{{ (strtoupper($method) == 'GET')? 'GET' : 'POST' }}" action="{{ $action?? ''}}" @if(isset($enctype) && filled($enctype)) enctype="{{ $enctype }}" @endif>
    @if(!isset($csrf))
        @csrf
        @if(isset($method) && filled($method) && $method != 'POST') @method($method) @endif
    @endif
    {!! $slot !!}
</form>
