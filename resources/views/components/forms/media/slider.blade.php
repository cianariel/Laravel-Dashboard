{{-- Stored in /resources/components/forms/media/slider.blade.php --}}
@if(isset($images) && count($images) !== 0)
<div class="form-group row">
     @if(isset($label))<label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">{{ __($label?? '') }}</label>@endif
     <div class="{{ isset($label)? 'col-sm-8' : 'col-sm-12' }}">
        <div id="{{ $id }}" class="md-carousel carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                @if(isset($images) && count($images) !== 0)
                    @foreach($images as $index => $image)
                        <div class="carousel-item @if($loop->first) active @endif">
                            <img class="d-block w-100" src="{{ $image->getFullUrl('large') }}">
                            <div class="overlay carousel-caption d-flex h-100 w-100 justify-content-center  text-center">
                                @if(filled($route_params))
                                    @php
                                        $route_params['image'] = $index;
                                    @endphp
                                @endif
                                @component('components.elements.link', ['href' => route($route, $route_params), 'classes' => ['fg-white', 'align-self-center'], 'confirmed' => true, 'dialog_title' => 'Confirm', 'dialog_message' => 'Are you sure you want to delete image?'])
                                    {{ __('Delete') }} <i class="fas fa-trash-alt"></i>
                                @endcomponent
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <a class="carousel-control-prev" href="#{{ $id }}" role="button" data-slide="prev">
                <i class="fg-blue font-xl-size fas fa-chevron-left"></i>
                <span class="sr-only">{{ __('Previous') }}</span>
            </a>
            <a class="carousel-control-next" href="#{{ $id }}" role="button" data-slide="next">
                <i class="fg-blue font-xl-size fas fa-chevron-right"></i>
                <span class="sr-only">{{ __('Next') }}</span>
            </a>
        </div>
        {!! $slot !!}
     </div>
</div>
@endif