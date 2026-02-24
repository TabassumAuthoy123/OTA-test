@extends('master')

@section('header_css')
    <style>
        .box {
            border: 1px #999;
            border-radius: 4px;
            padding: 15px;
            border-style: solid;
            opacity: 0.7;
            transition: opacity 0.3s ease;
        }

        .box:hover {
            opacity: 1;
        }

        .box .restore_btn {
            display: inline-block;
            background: #28a745;
            padding: 5px 14px;
            border-radius: 4px;
            color: white;
            font-size: 14px;
            cursor: pointer;
            border: none;
        }

        .box .restore_btn:hover {
            background: #218838;
        }

        .archived-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #dc3545;
            color: #fff;
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 3px;
            font-weight: bold;
        }

        .gds_logo {
            display: block;
            width: 100%;
            position: relative;
            height: 50px;
        }

        .gds_logo img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 100%;
            height: auto;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #888;
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
            color: #ccc;
        }
    </style>
@endsection


@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="alert alert-warning mb-0" role="alert">
                        <h5 class="alert-heading mb-0"><i class="fas fa-archive"></i> Archived GDS</h5>
                    </div>
                </div>
                <div class="card-body">
                    @if($archivedGds->count() > 0)
                        <div class="row">
                            @foreach ($archivedGds as $item)
                                <div class="col-lg-6 mb-3">
                                    <div class="box" style="position: relative;">
                                        <span class="archived-badge"><i class="fas fa-archive"></i> Archived</span>
                                        <div class="row">
                                            <div class="col-lg-2" style="padding-right: 0px;">
                                                <div class="gds_logo">
                                                    <img src="{{url($item->logo)}}">
                                                </div>
                                            </div>
                                            <div class="col-lg-10">
                                                <h5 style="margin-bottom: 5px">{{$item->name}}</h5>
                                                <p class="mb-0" style="font-size: 12px">{{$item->description}}</p>
                                            </div>
                                        </div>
                                        <hr style="background: #999;">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <button class="restore_btn" onclick="restoreGds('{{ $item->code }}')"
                                                    title="Restore this GDS">
                                                    <i class="fas fa-undo"></i> Restore
                                                </button>
                                            </div>
                                            <div class="col-lg-6 text-end">
                                                <span style="color: #999; font-size: 13px;"><i class="fas fa-ban"></i>
                                                    Inactive</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h5>No Archived GDS</h5>
                            <p>All GDS providers are currently active. Archived GDS will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function restoreGds(gds_code) {
            $.ajax({
                url: "{{ url('gds/restore') }}/" + gds_code,
                type: "POST",
                success: function (data) {
                    toastr.success("GDS Restored Successfully");
                    setTimeout(function () { location.reload(); }, 800);
                },
                error: function (data) {
                    toastr.error("Something went wrong! Please try again.");
                }
            });
        }
    </script>
@endsection