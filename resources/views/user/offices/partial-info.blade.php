<div id="office-info-container">
    <div class="p-4 mt-2">
        <h5>{{ $office->name }}</h5>
        @php
            $location = $office->getMetaField('location', '');
        @endphp
        @if($location)
            <div>{{$location['address']. ", ". $location['city']. ", ". $location['state']. " " . $location['zipcode']}}</div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <button class="btn btn-primary" data-id="{{$office->uuid}}">{{__('RESERVE APPOINTMENT')}}</button>
        </div>
    </div>
</div>