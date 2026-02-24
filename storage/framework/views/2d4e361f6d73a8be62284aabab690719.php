<?php $__env->startSection('header_css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link href="<?php echo e(url('assets')); ?>/admin-assets/css/homepage.css" rel="stylesheet" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="search_box_container">
        <img class="search_bg" src="<?php echo e(url('assets')); ?>/img/bg_search.jpg" alt="" />
        <div data-airport-url="#">
            <div class="mx-auto text-center top_part">
                <h2 class="top_heading">
                    <strong>Start your journey</strong> By one click
                    <span class="text-warning">Explore beautiful world!</span>
                </h2>
            </div>
            <div class="search-box container p-2">
                <div class="tab-content position-relative">
                    <div class="search-tabs d-flex flex-wrap">

                        <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                            <input type="radio" name="flight_type" value="1" onclick="showOnewayDate()" checked> One-Way
                        </label>
                        <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                            <input type="radio" name="flight_type" value="2" onclick="showRoundTripDate()"> Round-Trip
                        </label>
                        <label class="checkbox-label d-inline-block font-weight-500 me-2 border rounded fs-14 bg-white">
                            <input type="radio" name="flight_type" value="3" onclick="showMultiCityDate()"> Multi-City
                        </label>

                        <div class="search-content d-block w-100 pt-3" id="search-content2">
                            <form class="modify-search">
                                <input type="hidden" id="flight_type" value="1">
                                <div class="search-row row no-gutters position-relative mx-0 mb-4">
                                    <div class="col-lg-5 px-0">
                                        <div class="input-group rounded">
                                            <div class="form-floating flight-form">
                                                <label for="flight_from">From</label>
                                                <select class="form-control border-bottom-0 border-right flight_from"
                                                    id="flight_from"></select>
                                            </div>
                                            <span class="input-group-text">
                                                <img src="<?php echo e(url('assets')); ?>/admin-assets/img/arrow-symbol.png"
                                                    id="oneway-swap">
                                            </span>
                                            <div class="form-floating flight-to">
                                                <label for="flight_to">To</label>
                                                <select class="form-control border-bottom-0 border-right flight_to"
                                                    id="flight_to"></select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 px-0 position-static" id="departureDateCol">
                                        <div data-t-start data-t-end
                                            class="oneWay-datepicker t-datepicker t-datepicker-modal-oneway d-flex w-100 border-0 h-100 d-block"
                                            id="oneWayDatePicker">
                                            <div class="t-check-in"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 px-0" id="returnDateCol">
                                        <!-- Return date placeholder for one-way mode -->
                                        <div class="return-date-placeholder h-100 d-flex flex-column justify-content-center px-3"
                                            id="returnDatePlaceholder" onclick="switchToRoundTrip()"
                                            style="cursor:pointer;">
                                            <span class="fw-bold text-uppercase"
                                                style="font-size:12px; color:#1a1a6c;">Return Date</span>
                                            <span style="font-size:13px; color:#888;">Save more on return flight</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 px-0 position-static d-none" id="roundDateCol">
                                        <div data-t-start data-t-end
                                            class="oneWay-datepicker t-datepicker t-datepicker-modal-round d-flex w-100 border-0 h-100 d-block"
                                            id="roundDatePicker">
                                            <div class="t-check-in w-100"></div>
                                            <div class="t-check-out w-100"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 px-0">
                                        <div class="dropdown travellers-dropdown" id="dropdown-oneway">
                                            <div class="form-floating" id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                aria-haspopup="true">
                                                <input type="text" class="form-control dropdown-toggle"
                                                    id="passengers-oneway" value="1 Travelers, Economy" readonly />
                                                <label for="passengers">Traveler(s) cabin</label>
                                            </div>
                                            <div class="dropdown-menu pax-dropdown-menu"
                                                aria-labelledby="dropdownMenuButton">
                                                <div class="pax-dropdown-body">
                                                    
                                                    <div class="pax-row">
                                                        <div class="pax-info">
                                                            <span class="pax-label">Adults</span>
                                                            <span class="pax-desc">12 years and above</span>
                                                        </div>
                                                        <div class="pax-controls">
                                                            <button type="button" class="pax-btn pax-minus"
                                                                id="oneway-adult-minus">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="text" id="oneway-adult-input" class="pax-count"
                                                                readonly value="1" />
                                                            <button type="button" class="pax-btn pax-plus"
                                                                id="oneway-adult-plus">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <input hidden name="adult_members" id="adult_input_one" value="1" />
                                                    </div>
                                                    
                                                    <div class="pax-row">
                                                        <div class="pax-info">
                                                            <span class="pax-label">Children</span>
                                                            <span class="pax-desc">2–11 years</span>
                                                        </div>
                                                        <div class="pax-controls">
                                                            <button type="button" class="pax-btn pax-minus"
                                                                id="oneway-child-minus" onclick="oneWayChildDec()">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="text" id="oneway-child-input" class="pax-count"
                                                                readonly value="0" />
                                                            <button type="button" class="pax-btn pax-plus"
                                                                id="oneway-child-plus" onclick="oneWayChildInc()">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <input hidden name="child_members" id="child_input_one" value="0" />
                                                    </div>
                                                    
                                                    <div data-child-total="0" class="_child_age_" id="_child_age_"></div>
                                                    
                                                    <div class="pax-row">
                                                        <div class="pax-info">
                                                            <span class="pax-label">Infant</span>
                                                            <span class="pax-desc">Below 2 years</span>
                                                        </div>
                                                        <div class="pax-controls">
                                                            <button type="button" class="pax-btn pax-minus"
                                                                id="oneway-infant-minus">
                                                                <i class="fas fa-minus"></i>
                                                            </button>
                                                            <input type="text" id="oneway-infant-input" class="pax-count"
                                                                readonly value="0" />
                                                            <button type="button" class="pax-btn pax-plus"
                                                                id="oneway-infant-plus">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </div>
                                                        <input hidden name="infant_members" id="infant_input_one"
                                                            value="0" />
                                                    </div>
                                                    
                                                    <div class="pax-class-row">
                                                        <span class="pax-class-label">Class</span>
                                                        <div class="pax-class-options">
                                                            <label class="pax-class-option">
                                                                <input type="radio" id="economy1" name="cabin_class_oneway"
                                                                    value="economy" class="cabin_class_oneway" checked />
                                                                <span>Economy</span>
                                                            </label>
                                                            <label class="pax-class-option">
                                                                <input type="radio" id="business1" name="cabin_class_oneway"
                                                                    value="business" class="cabin_class_oneway" />
                                                                <span>Business</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <input hidden name="classType" id="class_type_one" value="Y" />
                                                    
                                                    <div class="pax-done-row">
                                                        <button type="button" class="pax-done-btn"
                                                            onclick="oneWayTotalPassenger()">Done</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-lg-12 text-end">
                                        <button type="button" id="add_another_city"
                                            class="btn btn-primary multicity-btn d-none">
                                            <i class="far fa-plus-square"></i> Add Another City
                                        </button>
                                    </div>
                                </div>

                                <div id="btn-hub-oneway">
                                    <button type="button" style="padding: 0.8rem 2rem;" onclick="searchForFlights()"
                                        id="btn-search-oneway" class="btn btn-primary btn-search">
                                        Search flights
                                        <i class="fas fa-plane-departure"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <?php if(count($banners) > 0): ?>
            <?php echo $__env->make('promotional_banners', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php endif; ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_js'); ?>
    <script src="<?php echo e(url('assets')); ?>/module-assets/js/booking/search_box.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="<?php echo e(url('assets')); ?>/plugins/swiper/swiper-bundle.min.js"></script>

    <script>

        // by default load with oneway
        document.addEventListener("DOMContentLoaded", function () {
            // force One-Way mode on page load
            document.querySelector('input[name="flight_type"][value="1"]').checked = true;
            showOnewayDate();
        });

        var swiper = new Swiper(".services-slider", {
            loop: true,
            slidesPerView: 2,
            spaceBetween: 16,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            // Responsive breakpoints
            breakpoints: {
                300: {
                    slidesPerView: 1,
                },
                576: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 1,
                },
                992: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 2,
                }
            }
        });

        $('.flight_from').select2({
            placeholder: 'Departure City/Airport',
            minimumInputLength: 2,
            ajax: {
                url: '/live/city/airport/search',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.search_result,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('.flight_to').select2({
            placeholder: 'Destination City/Airport',
            minimumInputLength: 2,
            ajax: {
                url: '/live/city/airport/search',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.search_result,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $('.preferred_airlines').select2({
            placeholder: 'Preferred Airlines',
            minimumInputLength: 2,
            ajax: {
                url: '/live/airline/search',
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.search_result,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function initDatepickerForRow(row) {
            const $dp = $(row).find(".t-datepicker");

            // remove any old generated markup if cloned
            $dp.find(".t-input").remove();          // plugin-generated (if present)
            $dp.find(".t-table").remove();          // plugin-generated (if present)

            // ensure expected structure exists
            if ($dp.find(".t-check-in").length === 0) {
                $dp.html('<div class="t-check-in"></div>');
            }

            // init plugin for this row
            $dp.tDatePicker({
                autoClose: true,
                durationArrowTop: 200,
                formatDate: "dd-mm-yyyy",
                dateCheckIn: new Date(),
                dateCheckOut: new Date(),
                iconDate: "",
                titleCheckIn: $("[data-departure]").data("departure"),
                titleCheckOut: $("[data-return]").data("return"),
                limitDateRanges: 360,
                limitNextMonth: 12,
            });
        }

        let multiCityRowIndex = 1;
        function createRemovableRow(fromDiv) {
            const clone = fromDiv.cloneNode(true);
            multiCityRowIndex++;

            // Add remove button (safe place)
            const toCol = clone.querySelector(".flight-to")?.closest(".col-lg-5");
            if (toCol && !toCol.querySelector(".search-row-remove")) {
                const removeBtn = document.createElement("button");
                removeBtn.innerHTML = "<i class='fas fa-times'></i>";
                removeBtn.className = "search-row-remove btn";
                removeBtn.type = "button";
                removeBtn.style.marginLeft = "10px";
                removeBtn.addEventListener("click", function () {
                    clone.remove();
                });
                toCol.appendChild(removeBtn);
            }

            // Remove Travellers + Preferred Airlines
            clone.querySelector("#dropdown-oneway")?.closest(".col-lg-2")?.remove();
            clone.querySelector(".preferred_airlines")?.closest(".col-lg-2")?.remove();

            // ✅ Remove any select2 containers inside the clone
            $(clone).find(".select2-container").remove();

            // ✅ Make IDs unique (important!)
            const fromSelect = clone.querySelector(".flight_from");
            const toSelect = clone.querySelector(".flight_to");

            if (fromSelect) {
                fromSelect.id = "flight_from_" + multiCityRowIndex;
                clone.querySelector('label[for="flight_from"]')?.setAttribute("for", fromSelect.id);
            }
            if (toSelect) {
                toSelect.id = "flight_to_" + multiCityRowIndex;
                clone.querySelector('label[for="flight_to"]')?.setAttribute("for", toSelect.id);
            }

            // ✅ Strip select2 internal state from cloned selects (super important)
            $(clone).find(".flight_from, .flight_to").each(function () {
                $(this)
                    .removeClass("select2-hidden-accessible")
                    .removeAttr("data-select2-id tabindex aria-hidden")
                    .removeData("select2");

                // also remove select2 ids from options (sometimes needed)
                $(this).find("option").removeAttr("data-select2-id");
                this.selectedIndex = -1;
            });

            // ✅ Re-init select2 ONLY on the new row's selects
            $(clone).find(".flight_from").select2({
                placeholder: "Departure City/Airport",
                minimumInputLength: 2,
                ajax: {
                    url: "/live/city/airport/search",
                    dataType: "json",
                    delay: 250,
                    processResults: function (data) {
                        return { results: $.map(data, item => ({ text: item.search_result, id: item.id })) };
                    },
                    cache: true
                }
            });

            $(clone).find(".flight_to").select2({
                placeholder: "Destination City/Airport",
                minimumInputLength: 2,
                ajax: {
                    url: "/live/city/airport/search",
                    dataType: "json",
                    delay: 250,
                    processResults: function (data) {
                        return { results: $.map(data, item => ({ text: item.search_result, id: item.id })) };
                    },
                    cache: true
                }
            });

            return clone;
        }

        document.getElementById("add_another_city").addEventListener("click", function () {
            const original = document.querySelector(".search-row"); // the first one
            const newRow = createRemovableRow(original);
            const allRows = document.querySelectorAll(".search-row");
            const lastRow = allRows[allRows.length - 1];
            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);
            // ✅ init datepicker for the new row
            initDatepickerForRow(newRow);
        });

        function showOnewayDate() {
            $("#flight_type").val(1);

            // removing extra row of multicity search
            const allRows = document.querySelectorAll(".search-row");
            for (let i = 1; i < allRows.length; i++) {
                allRows[i].remove();
            }

            // multicity add city button
            var multicityBtn = document.querySelector('.multicity-btn');
            multicityBtn.classList.remove('d-inline-block');
            multicityBtn.classList.add('d-none');

            // show departure + return placeholder columns
            document.getElementById('departureDateCol').classList.remove('d-none');
            document.getElementById('returnDateCol').classList.remove('d-none');
            // hide round-trip combined column
            document.getElementById('roundDateCol').classList.add('d-none');
        }

        function switchToRoundTrip() {
            document.querySelector('input[name="flight_type"][value="2"]').click();
        }

        function showRoundTripDate() {
            $("#flight_type").val(2);

            // removing extra row of multicity search
            const allRows = document.querySelectorAll(".search-row");
            for (let i = 1; i < allRows.length; i++) {
                allRows[i].remove();
            }

            // multicity add city button
            var multicityBtn = document.querySelector('.multicity-btn');
            multicityBtn.classList.remove('d-inline-block');
            multicityBtn.classList.add('d-none');

            // hide departure + return placeholder columns
            document.getElementById('departureDateCol').classList.add('d-none');
            document.getElementById('returnDateCol').classList.add('d-none');
            // show round-trip combined column
            document.getElementById('roundDateCol').classList.remove('d-none');
        }

        function showMultiCityDate() {
            $("#flight_type").val(3);

            // show departure + return placeholder columns (multi-city uses one-way dates)
            document.getElementById('departureDateCol').classList.remove('d-none');
            document.getElementById('returnDateCol').classList.remove('d-none');
            // hide round-trip combined column
            document.getElementById('roundDateCol').classList.add('d-none');

            // adding row for multicity search
            const original = document.querySelector(".search-row"); // the first one
            const newRow = createRemovableRow(original);
            const allRows = document.querySelectorAll(".search-row");
            const lastRow = allRows[allRows.length - 1];
            lastRow.parentNode.insertBefore(newRow, lastRow.nextSibling);
            // ✅ init datepicker for the new row
            initDatepickerForRow(newRow);

            // multicity add city button
            var multicityBtn = document.querySelector('.multicity-btn');
            multicityBtn.classList.remove('d-none');
            multicityBtn.classList.add('d-inline-block');
        }

        function searchForFlights() {

            var flightType = $("#flight_type").val(); // 1=>Oneway; 2=>Return
            let returnDate = '';

            if (flightType == 3) {
                searchMultiCityFlights();
                return false;
            }

            var departureLocationId = $("#flight_from").val();
            var destinationLocationId = $("#flight_to").val();
            var preferred_airlines = $("#preferred_airlines").val();
            var adult = Number($("#oneway-adult-input").val());
            var child = Number($("#oneway-child-input").val());
            var infant = Number($("#oneway-infant-input").val());
            var cabinClass = $('input.cabin_class_oneway:checked').val();

            if (flightType == 1) {
                var departureDate = document.querySelector('#oneWayDatePicker .t-check-in input[name="t-start"]').value;
            } else {
                var departureDate = document.querySelector('#roundDatePicker .t-check-in input[name="t-start"]').value;
                returnDate = document.querySelector('#roundDatePicker .t-check-out input[name="t-end"]').value;
            }

            if (!departureLocationId) {
                toastr.error("Departure Location is missing");
                return false;
            }
            if (!destinationLocationId) {
                toastr.error("Destination Location is missing");
                return false;
            }
            if (departureDate == '') {
                toastr.error("Departure Date is missing");
                return false;
            }
            if (flightType == 2 && returnDate == '') {
                toastr.error("Return Date is mendatory for Round Trip");
                return false;
            }
            if ((adult + child + infant) <= 0) {
                toastr.error("Please Provide Passenger Information");
                return false;
            }

            if (departureLocationId == destinationLocationId) {
                toastr.error("Departure & Destination Cannot be Same");
                return false;
            }


            $(".page-loader-wrapper").show();

            var formData = new FormData();
            formData.append("flight_type", flightType);
            formData.append("departure_location_id", departureLocationId);
            formData.append("destination_location_id", destinationLocationId);
            formData.append("departure_date", departureDate);
            formData.append("return_date", returnDate);
            formData.append("adult", adult);
            formData.append("child", child);
            formData.append("infant", infant);
            formData.append("preferred_airlines", preferred_airlines);
            formData.append("cabin_class", cabinClass);

            $.ajax({
                data: formData,
                url: "<?php echo e(url('search/flights')); ?>",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".page-loader-wrapper").hide();
                    window.location.href = "/flight/search-results";
                },
                error: function (data) {
                    // console.log('Error:', data);
                    $(".page-loader-wrapper").hide();
                    toastr.error("Someting Went Wrong! Please Try Again");
                }
            });

        }

        function searchMultiCityFlights() {

            const segments = [];
            const rows = document.querySelectorAll(".search-row");

            rows.forEach((row, idx) => {
                const from = $(row).find(".flight_from").val();
                const to = $(row).find(".flight_to").val();

                // tDatePicker generates this input under .t-check-in
                const date = $(row).find('.t-check-in input[name="t-start"]').val();

                // skip completely empty rows
                if (!from && !to && !date) return;

                // basic validation (optional)
                if (!from || !to || !date) {
                    toastr.error(`Segment ${idx + 1} is missing From/To/Date`);
                    return false;
                }
                if (from === to) {
                    toastr.error(`Segment ${idx + 1}: From and To cannot be same`);
                    return false;
                }
                segments.push({ from, to, date });
            });

            var flightType = $("#flight_type").val();
            var preferred_airlines = $("#preferred_airlines").val();
            var adult = Number($("#oneway-adult-input").val());
            var child = Number($("#oneway-child-input").val());
            var infant = Number($("#oneway-infant-input").val());
            var cabinClass = $('input.cabin_class_oneway:checked').val();

            if ((adult + child + infant) <= 0) {
                toastr.error("Please Provide Passenger Information");
                return false;
            }

            $(".page-loader-wrapper").show();

            var formData = new FormData();
            formData.append("flight_type", flightType);
            segments.forEach((seg, i) => {
                formData.append(`segments[${i}][from]`, seg.from);
                formData.append(`segments[${i}][to]`, seg.to);
                formData.append(`segments[${i}][date]`, seg.date);
            });
            formData.append("adult", adult);
            formData.append("child", child);
            formData.append("infant", infant);
            formData.append("preferred_airlines", preferred_airlines);
            formData.append("cabin_class", cabinClass);

            $.ajax({
                data: formData,
                url: "<?php echo e(url('search/multi-city/flights')); ?>",
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $(".page-loader-wrapper").hide();
                    window.location.href = "/flight/search-results";
                },
                error: function (data) {
                    // console.log('Error:', data);
                    $(".page-loader-wrapper").hide();
                    toastr.error("Someting Went Wrong! Please Try Again");
                }
            });

        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/home.blade.php ENDPATH**/ ?>