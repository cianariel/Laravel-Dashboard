{{-- Stored in /resources/views/office/reps/partials/user_sidebar.blade.php --}}
@component('components.bootstrap.card', [
    'id'        => 'office-reps-user-sidebar-card',
    'classes'   => [
        'border-0'
    ]
])
    <div class="card-body border-0">
        <h6 class="text-uppercase">{{ __('Contact The Rep') }}</h6>
        <ul class="list-unstyled">
            <li class="d-block mb-2">
                <i class="fas fa-phone-alt"></i>
                @if(filled($repUser->phone))
                    <span class="d-inline-block pl-2">{{ format_phone($repUser->phone) }}</span>
                @endif
            </li>
            <li class="d-block mb-2">
                <i class="far fa-envelope"></i>
                <span class="d-inline-block pl-2">
                    <a href="mailto:{{$repUser->email}}">{{ $repUser->email }}</a>
                </span>
            </li>
        </ul>
        @if($drugs = $repUser->getMetaField('drugs', null))
        <h6 class="text-uppercase mt-4">{{ __('Products Represented') }}</h6>
        <ul class="list-unstyled">
            @if(filled($drugs) && is_array($drugs))
                @foreach($drugs as $drug)
                    <li class="d-block mb-2">{{ $drug }}</li>
                @endforeach
            @endif
        </ul>
        @endif
    </div>
@endcomponent