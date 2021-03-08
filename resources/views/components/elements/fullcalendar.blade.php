{{-- Stored in /resources/views/components/elements/breadcrumb.blade.php --}}
<div id="{{ $id?? $id = uniqid('md-fullcalendar-', false) }}" class="md-fullcalendar @if(isset($classes) && filled($classes) && is_array($classes)) {{ implode(' ', $classes) }} @endif" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif></div>
<script>
<!--
    jQuery(document).ready(function($){
        let mdOfficeFullCalendarBlock = $('#{{ $id }}');
        let mdFullCalendar = new FullCalendar.Calendar(mdOfficeFullCalendarBlock[0],
            @if(isset($events))
                {
                'themeSystem':'bootstrap',
                'initialView': 'dayGridMonth',
                'displayEventTime': true,
                @if($events->count() != 0)
                    events: [
                        @foreach($events as $event)
                        {
                                id:         @json($event['id']),
                                title:      @json($event['title']),
                                start:      @json($event['start']),
                                end:        @json($event['end']),
                                editable:   @json($event['editable'])
                        },
                        @endforeach
                    ]
                @endif
                }
            @else
            {
                'themeSystem':'bootstrap',
                'initialView': 'dayGridMonth',
                'displayEventTime': true,
            }
            @endif
        );
        mdFullCalendar.render();
    });
//-->
</script>