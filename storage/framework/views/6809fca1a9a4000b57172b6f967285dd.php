<?php $__env->startSection('header_css'); ?>
    <link href="<?php echo e(url('dataTable')); ?>/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo e(url('dataTable')); ?>/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0px;
            border-radius: 4px;
        }

        table.dataTable tbody td:nth-child(1) {
            font-weight: 600;
        }

        table.dataTable tbody {
            text-align: center !important;
        }

        tfoot {
            display: table-header-group !important;
        }

        tfoot th {
            text-align: center;
        }

        table#DataTables_Table_0 img {
            transition: all .2s linear;
        }

        img.gridProductImage:hover {
            scale: 2;
            cursor: pointer;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0" style="font-size: 18px">View B2B Account Deductions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <label id="customFilter">
                                <a href="<?php echo e(url('submit/b2b/account/deduction')); ?>" style="margin-left: 5px" class="btn btn-success btn-sm"><b><i class="fas fa-coins"></i> New Deduction</b></a>
                            </label>

                            <div class="table-responsive">

                                <table class="table table-bordered mb-0 data-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SL</th>
                                            <th class="text-center">B2B User Name</th>
                                            <th class="text-center">B2B User Company</th>
                                            <th class="text-center">Amount</th>
                                            <th class="text-center">Reason</th>
                                            <th class="text-center">Deducted At</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>



<?php $__env->startSection('footer_js'); ?>
    
    <script src="<?php echo e(url('dataTable')); ?>/js/jquery.validate.js"></script>
    <script src="<?php echo e(url('dataTable')); ?>/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo e(url('dataTable')); ?>/js/dataTables.bootstrap4.min.js"></script>

    <script type="text/javascript">
        var table = $(".data-table").DataTable({

            processing: true,
            serverSide: true,
            stateSave: true,
            pageLength: 10,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],

            ajax: "<?php echo e(url('view/account/deductions')); ?>",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: '',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'user_name',
                    name: 'user_name'
                },
                {
                    data: 'company_name',
                    name: 'company_name'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'details',
                    name: 'details'
                },
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });
        $(".dataTables_filter").append($("#customFilter"));
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '.deleteBtn', function () {
            var slug = $(this).data("id");
            if(confirm("Are You sure want to delete history !")){
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(url('delete/b2b/account/deduction')); ?>"+'/'+slug,
                    success: function (data) {
                        table.draw(false);
                        toastr.error("Account Deduction History Deleted");
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/b2b_account_deductions/view.blade.php ENDPATH**/ ?>