{{-- Stored in /resources/views/components/elements/breadcrumb.blade.php --}}
<div id="{{ $id?? $id = uniqid('md-fullcalendar-', false) }}" class="md-fullcalendar @if(isset($classes) && filled($classes) && is_array($classes)) {{ implode(' ', $classes) }} @endif" @if(isset($attrs) && filled($attrs)) @foreach($attrs as $attr => $attr_value) {{ $attr }}="{{ $attr_value }}" @endforeach @endif></div>
    <script>
<!--
    jQuery(document).ready(function($){
        let mdOfficeFullCalendarBlock = $('#{{ $id }}');
        let mdFullCalendar = new FullCalendar.Calendar(mdOfficeFullCalendarBlock[0], @if(isset($options)) @php echo json_encode($options??['themeSystem' => 'bootstrap','initialView'=>'dayGridMonth'], JSON_FORCE_OBJECT) @endphp @else {} @endif);
        mdFullCalendar.render();
    });
//-->
</script>