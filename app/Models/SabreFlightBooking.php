<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SabreGdsConfig;
use Illuminate\Support\Facades\Auth;

class SabreFlightBooking extends Model
{
    use HasFactory;
    public static function flightBooking($revlidatedData, $travellerContact, $travellerEmail, $firstNames, $lastNames, $passengerTitles, $dob, $passengerTypes, $ages, $documentIssueCountry, $nationality, $documentNo, $documentExpireDate)
    {

        // making flight segment start
        $segmentArray = [];
        $legsArray = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['legs'];
        foreach ($legsArray as $leg) {
            $legRef = $leg['ref'] - 1;
            $legDescription = $revlidatedData['groupedItineraryResponse']['legDescs'][$legRef];
            $schedulesArray = $legDescription['schedules'];

            foreach ($schedulesArray as $schedule) {
                $scheduleRef = $schedule['ref'] - 1;
                $segment = $revlidatedData['groupedItineraryResponse']['scheduleDescs'][$scheduleRef];
                if (isset($schedule['departureDateAdjustment'])) {
                    $segment['bothDateAdjustment'] = $schedule['departureDateAdjustment'];
                }
                $segmentArray[] = $segment;
            }
        }

        $flightSegment = array();
        $departureDate = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][0]['departureDate'];
        $returnDepartureDate = isset($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][1]['departureDate']) ? $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][1]['departureDate'] : null;
        $isReturnFlight = false;


        // before your loop
        $itinerary = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0];
        $legs = $itinerary['legs'];
        $firstLegRefIdx = $legs[0]['ref'] - 1;
        $outboundCount = count($revlidatedData['groupedItineraryResponse']['legDescs'][$firstLegRefIdx]['schedules']);


        foreach ($segmentArray as $segmentIndex => $segmentData) {

            // Check if this is a return flight segment
            if ($isReturnFlight == false && $returnDepartureDate && $segmentData['departure']['airport'] == $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['groupDescription']['legDescriptions'][1]['departureLocation']) {
                $isReturnFlight = true;
            }

            if ($isReturnFlight == false) {
                $departureDateTime = new DateTime($departureDate . ' ' . $segmentData['departure']['time']);
            } else {
                $departureDateTime = new DateTime($returnDepartureDate . ' ' . $segmentData['departure']['time']);
            }

            if (isset($segmentData['bothDateAdjustment']) && $segmentData['bothDateAdjustment'] >= 1) {
                $departureDateTime->modify('+' . $segmentData['bothDateAdjustment'] . ' day');
            } else {
                // Adjust the departure date if there's a date adjustment only for departure
                if (isset($segmentData['departure']['dateAdjustment']) && $segmentData['departure']['dateAdjustment'] > 0) {
                    $departureDateTime->modify('+' . $segmentData['departure']['dateAdjustment'] . ' day');
                }
            }


            if ($segmentIndex < $outboundCount) {
                // outbound — use the same absolute index
                $bookingCode = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][0]['fare']['passengerInfoList'][0]['passengerInfo']['fareComponents'][0]['segments'][$segmentIndex]['segment']['bookingCode'] ?? "L";

                $marriageGrp = "O";
                if (isset($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][0]['fare']['passengerInfoList'][0]['passengerInfo']['fareComponents'][0]['segments'][$segmentIndex]['segment']['availabilityBreak'])) {
                    $marriageGrp = "I";
                }
            } else {
                // return — subtract the outbound count to get a 0-based local index
                $localReturnIdx = $segmentIndex - $outboundCount;
                $bookingCode = $revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][0]['fare']['passengerInfoList'][0]['passengerInfo']['fareComponents'][1]['segments'][$localReturnIdx]['segment']['bookingCode'] ?? "L";

                $marriageGrp = "O";
                if (isset($revlidatedData['groupedItineraryResponse']['itineraryGroups'][0]['itineraries'][0]['pricingInformation'][0]['fare']['passengerInfoList'][0]['passengerInfo']['fareComponents'][1]['segments'][$localReturnIdx]['segment']['availabilityBreak'])) {
                    $marriageGrp = "I";
                }
            }

            // mixed airlines er jonno booking er issue ta besi hoi sekhetre FlightNumber e always marketing flight number ta use korai valo
            $flightSegment[] = array(
                "DepartureDateTime" => $departureDateTime->format('Y-m-d') . "T" . $departureDateTime->format('H:i:s'),
                "FlightNumber" => (string) $segmentData['carrier']['marketingFlightNumber'], //(string) $segmentData['carrier']['operatingFlightNumber'],
                "NumberInParty" => (string) (session('adult') + session('child') + session('infant')),
                "ResBookDesigCode" => (string) $bookingCode,
                "Status" => "NN",
                "OriginLocation" => array(
                    "LocationCode" => $segmentData['departure']['airport']
                ),
                "DestinationLocation" => array(
                    "LocationCode" => $segmentData['arrival']['airport']
                ),
                "MarketingAirline" => array(
                    "Code" => $segmentData['carrier']['marketing'],
                    "FlightNumber" => (string) $segmentData['carrier']['marketingFlightNumber']
                ),
                "MarriageGrp" => $marriageGrp
            );
        }
        // making flight segment end


        $personName = [];
        $pricingQualifiersPassengerTypes = [];
        $advancePassengers = [];
        $secureFlights = [];

        $specialServices = [];
        $specialServices[] = [
            "SSR_Code" => "CTCM",
            "Text" => (string) preg_replace('/[^a-zA-Z0-9]/', '', $travellerContact),
            "PersonName" => [
                "NameNumber" => "1.1"
            ],
            "SegmentNumber" => "A"
        ];
        $specialServices[] = [
            "SSR_Code" => "CTCE",
            "Text" => strtoupper(str_replace("@", "//", $travellerEmail)),
            "PersonName" => [
                "NameNumber" => "1.1"
            ],
            "SegmentNumber" => "A"
        ];

        foreach ($firstNames as $passengerIndex => $firstName) {

            $nameReference = "";
            if ($passengerTypes[$passengerIndex] != "ADT") {
                if ($passengerTypes[$passengerIndex] == 'INF') {
                    $nameReference = 'I' . str_pad($ages[$passengerIndex], 2, "0", STR_PAD_LEFT);
                } else {
                    $nameReference = 'C' . str_pad($ages[$passengerIndex], 2, "0", STR_PAD_LEFT);
                }
            }

            $passengerTypeForPersonName = "ADT";
            if ($passengerTypes[$passengerIndex] != "ADT") {
                if ($passengerTypes[$passengerIndex] == 'INF') {
                    $passengerTypeForPersonName = "INF";

                    $specialServices[] = [
                        "SSR_Code" => "INFT",

                        "Text" => str_replace(" ", "/", str_replace(".", "", $passengerTitles[$passengerIndex]) . "/" . trim($firstName)) . "/" . str_replace(" ", "/", trim($lastNames[$passengerIndex])) . " /" . date("dMy", strtotime($dob[$passengerIndex])),
                        // "Text" => "Sultana/Abeda /11Jan23",

                        "PersonName" => [
                            "NameNumber" => (string) 1.1 //(string) $passengerIndex+1 .".1" Infant have to attached with Adult
                        ],
                        "SegmentNumber" => "A"
                    ];

                } else {
                    $passengerTypeForPersonName = 'C' . str_pad($ages[$passengerIndex], 2, "0", STR_PAD_LEFT);

                    $specialServices[] = [
                        "SSR_Code" => "CHLD",
                        "Text" => (string) date("dMy", strtotime($dob[$passengerIndex])),
                        "PersonName" => [
                            "NameNumber" => (string) $passengerIndex + 1 . ".1"
                        ],
                        "SegmentNumber" => "A"
                    ];
                }
            }

            $personName[] = [
                "GivenName" => $firstName . " " . str_replace(".", "", $passengerTitles[$passengerIndex]),
                "Surname" => $lastNames[$passengerIndex],
                "NameNumber" => (string) $passengerIndex + 1 . ".1", //Infant have to attached with Adult but not here
                "Infant" => $passengerTypes[$passengerIndex] == 'INF' ? true : false,
                "NameReference" => $nameReference,
                "PassengerType" => $passengerTypeForPersonName,
            ];

            $advancePassengers[] = [
                "Document" => [
                    'IssueCountry' => $documentIssueCountry[$passengerIndex],
                    'NationalityCountry' => $nationality[$passengerIndex],
                    'ExpirationDate' => (string) $documentExpireDate[$passengerIndex],
                    'Number' => (string) $documentNo[$passengerIndex],
                    'Type' => "P",
                ],
                "PersonName" => [
                    'Gender' => ($passengerTitles[$passengerIndex] == 'Mr.' || $passengerTitles[$passengerIndex] == 'Mstr.') ? "M" : ($passengerTypes[$passengerIndex] != 'INF' ? "F" : "FI"),
                    // 'GivenName' => str_replace(".","",$passengerTitles[$passengerIndex])." ".$firstName,
                    'GivenName' => $firstName,
                    'Surname' => $lastNames[$passengerIndex],
                    'DateOfBirth' => (string) $dob[$passengerIndex],
                    'NameNumber' => $passengerTypes[$passengerIndex] != 'INF' ? (string) $passengerIndex + 1 . ".1" : (string) 1.1, //Infant have to attached with Adult
                ],
                "SegmentNumber" => "A"
            ];

            $secureFlights[] = [
                "PersonName" => [
                    'Gender' => ($passengerTitles[$passengerIndex] == 'Mr.' || $passengerTitles[$passengerIndex] == 'Mstr.') ? "M" : ($passengerTypes[$passengerIndex] != 'INF' ? "F" : "FI"),
                    // 'GivenName' => str_replace(".","",$passengerTitles[$passengerIndex])." ".$firstName,
                    'GivenName' => $firstName,
                    'Surname' => $lastNames[$passengerIndex],
                    'DateOfBirth' => (string) $dob[$passengerIndex],
                    'NameNumber' => $passengerTypes[$passengerIndex] != 'INF' ? (string) $passengerIndex + 1 . ".1" : (string) 1.1, //Infant have to attached with Adult
                ],
                "SegmentNumber" => "A",
                "VendorPrefs" => [
                    "Airline" => [
                        'Hosted' => false
                    ]
                ]
            ];

            $found = false;
            foreach ($pricingQualifiersPassengerTypes as $pricingQualifiersPassengerIndex => $pricingQualifiersPassengerType) {
                if ($pricingQualifiersPassengerType['Code'] == $passengerTypeForPersonName) {
                    $pricingQualifiersPassengerTypes[$pricingQualifiersPassengerIndex] = [
                        "Code" => $passengerTypeForPersonName,
                        "Quantity" => (string) 2
                    ];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $pricingQualifiersPassengerTypes[] = [
                    "Code" => $passengerTypeForPersonName,
                    "Quantity" => (string) 1
                ];
            }
        }

        $sabreGdsInfo = SabreGdsConfig::where('id', 1)->first();
        if ($sabreGdsInfo->is_production == 0) {
            $apiEndPoint = 'https://api.cert.platform.sabre.com/v2.5.0/passenger/records?mode=create';
        } else {
            $apiEndPoint = 'https://api.platform.sabre.com/v2.5.0/passenger/records?mode=create';
        }

        // agency info to set in booking details start
        $agencyUserInfo = User::where('id', Auth::user()->id)->first();
        $agencyCompanyInfo = CompanyProfile::where('user_id', $agencyUserInfo->id)->first();
        $receivedFrom = "";
        $agencyContact = "";
        $agencyEmail = "";
        if ($agencyCompanyInfo) {
            $receivedFrom = $agencyCompanyInfo->name . " " . preg_replace('/[^a-zA-Z0-9]/', '', $agencyCompanyInfo->phone);
            $agencyContact = preg_replace('/[^a-zA-Z0-9]/', '', $agencyCompanyInfo->phone);
            $agencyEmail = $agencyCompanyInfo->email;
        }

        if ($receivedFrom == "") {
            $receivedFrom = $agencyUserInfo->name . " " . preg_replace('/[^a-zA-Z0-9]/', '', $agencyUserInfo->phone);
        }
        if ($agencyContact == "") {
            $agencyContact = preg_replace('/[^a-zA-Z0-9]/', '', $agencyUserInfo->phone);
        }
        if ($agencyEmail == "") {
            $agencyEmail = $agencyUserInfo->email;
        }
        // agency info to set in booking details end


        $request_body = array(
            "CreatePassengerNameRecordRQ" => array(
                "version" => "2.5.0",
                "targetCity" => (string) $sabreGdsInfo->pcc,
                "haltOnAirPriceError" => true,
                "TravelItineraryAddInfo" => array(
                    "AgencyInfo" => array(
                        "Address" => array(
                            "AddressLine" => "Faith Travels & Tours Ltd",
                            "CityName" => "Dhaka",
                            "CountryCode" => "BD",
                            "PostalCode" => "1213",
                            "StateCountyProv" => array(
                                "StateCode" => "BD"
                            ),
                            "StreetNmbr" => "DHAKA"
                        ),
                        "Ticketing" => array(
                            "TicketType" => "7TAW"
                        )
                    ),
                    "CustomerInfo" => array(
                        "ContactNumbers" => array(
                            "ContactNumber" => array(
                                array(
                                    "LocationCode" => "DAC",
                                    "NameNumber" => "1.1",
                                    "PhoneUseType" => "M",
                                    "Phone" => $agencyContact //$travellerContact
                                )
                            )
                        ),
                        "Email" => array(
                            array(
                                "Address" => $agencyEmail, //$travellerEmail,
                                "Type" => "CC"
                            )
                        ),
                        "PersonName" => $personName,
                    )
                ),
                "AirBook" => array(
                    "HaltOnStatus" => array(
                        array("Code" => "HL"),
                        array("Code" => "KK"),
                        array("Code" => "LL"),
                        array("Code" => "NN"),
                        array("Code" => "NO"),
                        array("Code" => "UC"),
                        array("Code" => "US"),
                        array("Code" => "UN"),
                        array("Code" => "HX"),
                        array("Code" => "WL")
                    ),
                    "OriginDestinationInformation" => array(
                        "FlightSegment" => $flightSegment
                    ),
                    "RedisplayReservation" => array(
                        "NumAttempts" => 3,
                        "WaitInterval" => 3000
                    )
                ),
                "AirPrice" => array(
                    array(
                        "PriceRequestInformation" => array(
                            "Retain" => true,
                            "OptionalQualifiers" => array(
                                "FOP_Qualifiers" => array(
                                    "BasicFOP" => array(
                                        "Type" => "CASH"
                                    )
                                ),
                                "PricingQualifiers" => array(
                                    "PassengerType" => $pricingQualifiersPassengerTypes
                                )
                            )
                        )
                    )
                ),
                "SpecialReqDetails" => array(
                    "SpecialService" => array(
                        "SpecialServiceInfo" => array(
                            "AdvancePassenger" => $advancePassengers,
                            "SecureFlight" => $secureFlights,
                            "Service" => $specialServices
                        )
                    ),
                    "AddRemark" => array(
                        "RemarkInfo" => array(
                            "Remark" => array(
                                array(
                                    "Type" => "General",
                                    "Text" => "Booking Created from Portal"
                                ),
                            )
                        )
                    )
                ),
                "PostProcessing" => array(
                    "EndTransaction" => array(
                        "Source" => array(
                            "ReceivedFrom" => $receivedFrom
                        ),
                        "Email" => array(
                            "Ind" => true,
                        )
                    ),
                    "RedisplayReservation" => array("waitInterval" => 8000),
                )
            )
        );


        // Convert the request body array to JSON format
        $request_json = json_encode($request_body);
        session(["booking_request" => $request_json]); //lated saved in database

        // return $request_json;
        // exit();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiEndPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $request_json,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Conversation-ID: 2021.01.DevStudio',
                'Authorization: Bearer  ' . session('access_token'),
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
        // return $request_json;
        // return $flightSegment;

    }

    public static function cancelBooking($booking_no)
    {
        if (session('access_token') && session('access_token') != '' && session('expires_in') != '') {

            $seconds = session('expires_in');
            $date = new DateTime();
            $date->setTimestamp(time() + $seconds);
            $tokenExpireDate = $date->format('Y-m-d');
            $currentDate = date("Y-m-d");

            if ($currentDate >= $tokenExpireDate) {
                SabreFlightSearch::generateAccessToken();
            }

        } else {
            SabreFlightSearch::generateAccessToken();
        }

        $flightBookingInfo = FlightBooking::where('booking_no', $booking_no)->first();
        $data = array(
            "confirmationId" => $flightBookingInfo->pnr_id,
            "retrieveBooking" => true,
            "cancelAll" => true,
            "errorHandlingPolicy" => "ALLOW_PARTIAL_CANCEL"
        );
        $payload = json_encode($data);

        $sabreGdsInfo = SabreGdsConfig::where('id', 1)->first();
        if ($sabreGdsInfo->is_production == 0) {
            $apiEndPoint = 'https://api.cert.platform.sabre.com/v1/trip/orders/cancelBooking';
        } else {
            $apiEndPoint = 'https://api.platform.sabre.com/v1/trip/orders/cancelBooking';
        }

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiEndPoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Conversation-ID: 2021.01.DevStudio',
                'Authorization: Bearer ' . session('access_token'),
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

}
