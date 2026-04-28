@extends('master')
@section('header_css')
<style>
.brc-page{display:flex;gap:24px;align-items:flex-start;}
.brc-sidebar{min-width:200px;background:#fff;border-radius:8px;box-shadow:0 2px 8px rgba(0,0,0,.08);padding:16px 20px;}
.brc-sidebar h6{font-size:15px;font-weight:700;color:#1a3a5c;margin-bottom:6px;}
.brc-sidebar small{font-size:12px;color:#888;}
.brc-main{flex:1;}
.route-card{background:#fff;border:1px solid #e8ecf0;border-radius:8px;padding:20px 24px;margin-bottom:14px;box-shadow:0 1px 4px rgba(0,0,0,.06);}
.route-card-hdr{font-size:14px;font-weight:700;color:#1a3a5c;margin-bottom:16px;}
.rc-grid-3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px;}
.rc-grid-3b{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px;}
.rc-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:0;}
.rc-fld label{font-size:12px;font-weight:700;color:#555;margin-bottom:5px;display:block;}
.rc-fld label span{color:#dc3545;}
.rc-fld select{font-size:13px;padding:6px 10px;border:1px solid #ced4da;border-radius:5px;width:100%;height:36px;}
/* toggle buttons */
.tgl-btn{display:inline-flex;border:1.5px solid #1a3a5c;border-radius:6px;overflow:hidden;}
.tgl-btn input[type=radio]{display:none;}
.tgl-btn label{padding:5px 18px;font-size:13px;font-weight:600;cursor:pointer;margin:0;color:#1a3a5c;background:#fff;transition:background .15s,color .15s;}
.tgl-btn input[type=radio]:checked + label{background:#1a3a5c;color:#fff;}
.add-more-link{color:#1a3a5c;font-size:13px;font-weight:600;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:4px;}
.add-more-link:hover{color:#f0a500;}
.btn-create{background:#f0a500;color:#fff;border:none;padding:11px 28px;border-radius:6px;font-size:14px;font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;}
.btn-create:hover{background:#d4911a;}
.btn-remove-card{background:#dc3545;color:#fff;border:none;padding:3px 10px;border-radius:5px;font-size:11px;cursor:pointer;float:right;}
</style>
@endsection
@section('content')
<div class="row"><div class="col-lg-12">

  <div class="brc-page">

    {{-- Left sidebar label --}}
    <div class="brc-sidebar">
      <h6>Add new block routes</h6>
      <small>Dashboard &rsaquo; Configuration &rsaquo; Block-route &rsaquo; Add</small>
    </div>

    {{-- Form --}}
    <div class="brc-main">
      <form method="POST" action="{{ url('configuration/block-routes') }}" id="createForm">
        @csrf

        <div id="routeCards">
          @php $airportOpts = ''; foreach($airports as $ap) { $airportOpts .= '<option value="'.e($ap->airport_code).'">'.e($ap->airport_code).' – '.e($ap->city_name).'</option>'; } @endphp
          @php $airlineOpts = '<option value="">Any Airline</option>'; foreach($airlines as $al) { $airlineOpts .= '<option value="'.e($al->iata).'">'.e($al->name).' ('.e($al->iata).')</option>'; } @endphp

          {{-- First card --}}
          <div class="route-card" id="card-1">
            <div class="route-card-hdr">SL - 1</div>
            <div class="rc-grid-3">
              <div class="rc-fld">
                <label>Departure <span>*</span></label>
                <select name="routes[0][departure]" required>
                  <option value="">Select Airport or Route eg: DXB</option>
                  {!! $airportOpts !!}
                </select>
              </div>
              <div class="rc-fld">
                <label>Arrival <span>*</span></label>
                <select name="routes[0][arrival]" required>
                  <option value="">Select Airport or Route eg: DXB</option>
                  {!! $airportOpts !!}
                </select>
              </div>
              <div class="rc-fld">
                <label>Select Airline</label>
                <select name="routes[0][airline_code]">
                  {!! $airlineOpts !!}
                </select>
              </div>
            </div>
            <div class="rc-grid-3b">
              <div class="rc-fld">
                <label>One Way <span>*</span></label>
                <div class="tgl-btn">
                  <input type="radio" name="routes[0][one_way]" id="c0_ow_t" value="true"><label for="c0_ow_t">True</label>
                  <input type="radio" name="routes[0][one_way]" id="c0_ow_f" value="false" checked><label for="c0_ow_f">False</label>
                </div>
              </div>
              <div class="rc-fld">
                <label>Round Way <span>*</span></label>
                <div class="tgl-btn">
                  <input type="radio" name="routes[0][round_trip]" id="c0_rt_t" value="true"><label for="c0_rt_t">True</label>
                  <input type="radio" name="routes[0][round_trip]" id="c0_rt_f" value="false" checked><label for="c0_rt_f">False</label>
                </div>
              </div>
              <div class="rc-fld">
                <label>Booking Block <span>*</span></label>
                <div class="tgl-btn">
                  <input type="radio" name="routes[0][booking_block]" id="c0_bb_t" value="true"><label for="c0_bb_t">True</label>
                  <input type="radio" name="routes[0][booking_block]" id="c0_bb_f" value="false" checked><label for="c0_bb_f">False</label>
                </div>
              </div>
            </div>
            <div class="rc-grid-2">
              <div class="rc-fld">
                <label>Full Block <span>*</span></label>
                <div class="tgl-btn">
                  <input type="radio" name="routes[0][full_block]" id="c0_fb_t" value="true"><label for="c0_fb_t">True</label>
                  <input type="radio" name="routes[0][full_block]" id="c0_fb_f" value="false" checked><label for="c0_fb_f">False</label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div style="margin-bottom:16px;">
          <a class="add-more-link" onclick="addRouteCard()">+ Add More Route</a>
        </div>

        <button type="submit" class="btn-create">&#9658; Create Block Route</button>
      </form>
    </div>
  </div>

</div></div>

<script>
let cardCount = 1;

const AIRPORT_OPTS = `<option value="">Select Airport or Route eg: DXB</option>{!! addslashes($airportOpts) !!}`;
const AIRLINE_OPTS = `{!! addslashes($airlineOpts) !!}`;

function addRouteCard() {
  const idx = cardCount;
  const sl  = cardCount + 1;
  const tpl = `
  <div class="route-card" id="card-${sl}">
    <div class="route-card-hdr">
      SL - ${sl}
      <button type="button" class="btn-remove-card" onclick="removeCard(${sl})">✕ Remove</button>
    </div>
    <div class="rc-grid-3">
      <div class="rc-fld">
        <label>Departure <span style="color:#dc3545;">*</span></label>
        <select name="routes[${idx}][departure]" required>
          ${AIRPORT_OPTS}
        </select>
      </div>
      <div class="rc-fld">
        <label>Arrival <span style="color:#dc3545;">*</span></label>
        <select name="routes[${idx}][arrival]" required>
          ${AIRPORT_OPTS}
        </select>
      </div>
      <div class="rc-fld">
        <label>Select Airline</label>
        <select name="routes[${idx}][airline_code]">
          ${AIRLINE_OPTS}
        </select>
      </div>
    </div>
    <div class="rc-grid-3b">
      <div class="rc-fld">
        <label>One Way <span style="color:#dc3545;">*</span></label>
        <div class="tgl-btn">
          <input type="radio" name="routes[${idx}][one_way]" id="c${idx}_ow_t" value="true"><label for="c${idx}_ow_t">True</label>
          <input type="radio" name="routes[${idx}][one_way]" id="c${idx}_ow_f" value="false" checked><label for="c${idx}_ow_f">False</label>
        </div>
      </div>
      <div class="rc-fld">
        <label>Round Way <span style="color:#dc3545;">*</span></label>
        <div class="tgl-btn">
          <input type="radio" name="routes[${idx}][round_trip]" id="c${idx}_rt_t" value="true"><label for="c${idx}_rt_t">True</label>
          <input type="radio" name="routes[${idx}][round_trip]" id="c${idx}_rt_f" value="false" checked><label for="c${idx}_rt_f">False</label>
        </div>
      </div>
      <div class="rc-fld">
        <label>Booking Block <span style="color:#dc3545;">*</span></label>
        <div class="tgl-btn">
          <input type="radio" name="routes[${idx}][booking_block]" id="c${idx}_bb_t" value="true"><label for="c${idx}_bb_t">True</label>
          <input type="radio" name="routes[${idx}][booking_block]" id="c${idx}_bb_f" value="false" checked><label for="c${idx}_bb_f">False</label>
        </div>
      </div>
    </div>
    <div class="rc-grid-2">
      <div class="rc-fld">
        <label>Full Block <span style="color:#dc3545;">*</span></label>
        <div class="tgl-btn">
          <input type="radio" name="routes[${idx}][full_block]" id="c${idx}_fb_t" value="true"><label for="c${idx}_fb_t">True</label>
          <input type="radio" name="routes[${idx}][full_block]" id="c${idx}_fb_f" value="false" checked><label for="c${idx}_fb_f">False</label>
        </div>
      </div>
    </div>
  </div>`;
  document.getElementById('routeCards').insertAdjacentHTML('beforeend', tpl);
  cardCount++;
}

function removeCard(sl) {
  const card = document.getElementById('card-' + sl);
  if (card) card.remove();
}
</script>
@endsection
