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
                    <h6 class="mb-0" style="font-size: 18px">View Saved Passengers</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="table-responsive">
                                <table class="table table-bordered mb-0 data-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">SL</th>
                                            <th class="text-center">Type</th>
                                            <th class="text-center">Full Name</th>
                                            <th class="text-center">Email</th>
                                            <th class="text-center">Contact</th>
                                            <th class="text-center">DOB</th>
                                            <th class="text-center">Document</th>
                                            <th class="text-center">Expired At</th>
                                            <th class="text-center">Issued By</th>
                                            <th class="text-center">Nationality</th>
                                            <th class="text-center">Frequent Flyer No</th>
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
            ajax: "<?php echo e(url('view/saved/passengers')); ?>",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'first_name',
                    name: 'first_name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'contact',
                    name: 'contact'
                },
                {
                    data: 'dob',
                    name: 'dob'
                },
                {
                    data: 'document_no',
                    name: 'document_no'
                },
                {
                    data: 'document_expire_date',
                    name: 'document_expire_date'
                },
                {
                    data: 'document_issue_country',
                    name: 'document_issue_country'
                },
                {
                    data: 'nationality',
                    name: 'nationality'
                },
                {
                    data: 'frequent_flyer_no',
                    name: 'frequent_flyer_no'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('body').on('click', '.deleteBtn', function () {
            var id = $(this).data("id");
            if (confirm("Are You sure want to delete !")) {
                $.ajax({
                    type: "GET",
                    url: "<?php echo e(url('delete/saved/passenger')); ?>" + '/' + id,
                    success: function (data) {
                        table.draw(false);
                        toastr.error("Saved Passenger Removed");
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/user/saved_passsangers.blade.php ENDPATH**/ ?>