@foreach($searchPassengers as $searchPassengerIndex => $searchPassenger)
    <li class="live_search_item">
        <a class="live_search_product_link" href="javascript:void(0)" onclick="autoFillUpForm({{$searchPassengerIndex}})">
            <h6 class="live_search_product_title">
                {{$searchPassenger->title}} {{$searchPassenger->first_name}} {{$searchPassenger->last_name}}
                ({{$searchPassenger->contact}})

                <input type="hidden" id="passenger_title_{{$searchPassengerIndex}}" value="{{$searchPassenger->title}}">
                <input type="hidden" id="passenger_first_name_{{$searchPassengerIndex}}"
                    value="{{$searchPassenger->first_name}}">
                <input type="hidden" id="passenger_last_name__{{$searchPassengerIndex}}"
                    value="{{$searchPassenger->last_name}}">
                <input type="hidden" id="passenger_email_{{$searchPassengerIndex}}" value="{{$searchPassenger->email}}">
                <input type="hidden" id="passenger_contact_{{$searchPassengerIndex}}" value="{{$searchPassenger->contact}}">
                <input type="hidden" id="passenger_type_{{$searchPassengerIndex}}" value="{{$searchPassenger->type}}">
                <input type="hidden" id="passenger_dob_{{$searchPassengerIndex}}" value="{{$searchPassenger->dob}}">
                <input type="hidden" id="passenger_document_type_{{$searchPassengerIndex}}"
                    value="{{$searchPassenger->document_type}}">
                <input type="hidden" id="passenger_document_no_{{$searchPassengerIndex}}"
                    value="{{$searchPassenger->document_no}}">
                <input type="hidden" id="passenger_document_expire_date_{{$searchPassengerIndex}}"
                    value="{{$searchPassenger->document_expire_date}}">
                <input type="hidden" id="passenger_document_issue_country_{{$searchPassengerIndex}}"
                    value="{{$searchPassenger->document_issue_country}}">
                <input type="hidden" id="passenger_nationality_{{$searchPassengerIndex}}"
                    value="{{$searchPassenger->nationality}}">
                <input type="hidden" id="passenger_frequent_flyer_no_{{$searchPassengerIndex}}"
                    value="{{$searchPassenger->frequent_flyer_no}}">
            </h6>
        </a>
    </li>
@endforeach