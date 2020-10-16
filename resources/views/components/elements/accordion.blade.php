{{-- Stored in /resources/views/components/elements/accordion.blade.php --}}
<div class="md-accordion accordion" id="md-accordion-{{ $id }}">
    @if(isset($parent) && isset($children))
        <div class="card">
            <div class="card-header">{{ $parent->label?? ''}}</div>
            <div class="card-body">
            </div>
        </div>
    @endif
    {!! $slot !!}
</div>