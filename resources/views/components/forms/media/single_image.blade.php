{{-- Stored in /resources/views/components/forms/media/single_image.blade.php --}}
<div id="image-{{ $id ?? '' }}" class="form-group row">
    <div class="col-sm-12">
        <div class="remove-image-block m-auto @if(isset($classes) && filled($classes) && is_array($classes)) {{ implode(' ', $classes) }} @endif">
            @if(isset($path) && filled($path)) <img src="{{ $path }}"> @endif
            <div class="overlay">
                <a class="fg-white" href="{{ $route?? '' }}">{{ __('Remove') }} <i class="fas fa-trash-alt"></i></a>
            </div>
        </div>
    </div>
</div>