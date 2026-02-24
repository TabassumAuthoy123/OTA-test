@extends('master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tags"></i> Pricing & Margins Configuration</h5>
                </div>
                <div class="card-body">

                    <form action="{{ url('update/pricing/config') }}" method="POST">
                        @csrf

                        {{-- B2C Section --}}
                        <div class="row mb-4">
                            <div class="col-lg-12">
                                <h6 class="text-primary font-weight-bold border-bottom pb-2 mb-3">
                                    <i class="fas fa-user"></i> B2C Customer Markup
                                </h6>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="b2c_markup_type">Markup Type</label>
                                    <select name="b2c_markup_type" id="b2c_markup_type" class="form-control">
                                        <option value="percentage" {{ optional($b2cConfig)->markup_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                        <option value="fixed" {{ optional($b2cConfig)->markup_type === 'fixed' ? 'selected' : '' }}>Fixed Amount (BDT)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="b2c_markup_value">Markup Value</label>
                                    <input type="number" step="0.01" min="0" name="b2c_markup_value" id="b2c_markup_value"
                                        value="{{ optional($b2cConfig)->markup_value ?? 5.00 }}" class="form-control"
                                        placeholder="e.g. 5.00">
                                    <small class="text-muted" id="b2c_hint">Applied as % of base GDS fare</small>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group pt-4">
                                    <label for="b2c_is_active" style="cursor: pointer;">
                                        <input type="checkbox" name="b2c_is_active" id="b2c_is_active" value="1"
                                            {{ optional($b2cConfig)->is_active !== false ? 'checked' : '' }}>
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- B2B Section --}}
                        <div class="row mb-4">
                            <div class="col-lg-12">
                                <h6 class="text-success font-weight-bold border-bottom pb-2 mb-3">
                                    <i class="fas fa-building"></i> B2B Agent Markup
                                </h6>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="b2b_markup_type">Markup Type</label>
                                    <select name="b2b_markup_type" id="b2b_markup_type" class="form-control">
                                        <option value="percentage" {{ optional($b2bConfig)->markup_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                                        <option value="fixed" {{ optional($b2bConfig)->markup_type === 'fixed' ? 'selected' : '' }}>Fixed Amount (BDT)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="b2b_markup_value">Markup Value</label>
                                    <input type="number" step="0.01" min="0" name="b2b_markup_value" id="b2b_markup_value"
                                        value="{{ optional($b2bConfig)->markup_value ?? 3.00 }}" class="form-control"
                                        placeholder="e.g. 3.00">
                                    <small class="text-muted" id="b2b_hint">Applied as % of base GDS fare</small>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group pt-4">
                                    <label for="b2b_is_active" style="cursor: pointer;">
                                        <input type="checkbox" name="b2b_is_active" id="b2b_is_active" value="1"
                                            {{ optional($b2bConfig)->is_active !== false ? 'checked' : '' }}>
                                        Active
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Info Banner --}}
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i>
                            <strong>How it works:</strong>
                            Admin sees the raw GDS base price. B2C/B2B customers see base price + markup applied here.
                            <br>
                            <small>Example: If GDS base fare = ৳10,000 and B2C markup = 5%, B2C customer sees ৳10,500.</small>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <button type="submit" class="btn btn-success rounded">
                                    <i class="fas fa-save"></i> Save Pricing Config
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('footer_js')
    <script>
        function updateHint(typeId, hintId) {
            var type = document.getElementById(typeId).value;
            document.getElementById(hintId).textContent = (type === 'percentage')
                ? 'Applied as % of base GDS fare'
                : 'Applied as fixed BDT amount on top of base fare';
        }
        document.getElementById('b2c_markup_type').addEventListener('change', function () {
            updateHint('b2c_markup_type', 'b2c_hint');
        });
        document.getElementById('b2b_markup_type').addEventListener('change', function () {
            updateHint('b2b_markup_type', 'b2b_hint');
        });
    </script>
@endsection
