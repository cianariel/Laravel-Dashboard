{{-- Stored in /resources/views/components/elements/accordion.blade.php --}}
<div class="gb-accordion accordion" id="gb-accordion-{{ $id }}">
    @if(isset($parent) && isset($children))
        <div class="card">
            <div class="card-header">{{ $parent->label?? ''}}</div>
            <div class="card-body">
            </div>
        </div>
    @endif
    {!! $slot !!}
</div>