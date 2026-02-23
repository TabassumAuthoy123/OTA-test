<?php $__env->startSection('header_css'); ?>
    <style>
        @media print {
            .hidden-print{
                display: none !important;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header bg-success text-white" style="padding: 14px 22px;">
                    <h6 class="mb-0" style="font-size: 18px">Generate B2B Financial Report</h6>
                </div>
                <div class="card-body">
                    <form class="needs-validation row" id="sales_report_form">
                        <div class="form-group col">
                            <label for="start_date" class="d-block" style="margin-bottom: 4px; padding-left: 2px;">Search From</label>
                            <input type="date" class="form-control" id="start_date">
                        </div>
                        <div class="form-group col">
                            <label for="end_date" class="d-block" style="margin-bottom: 4px; padding-left: 2px;">Search To</label>
                            <input type="date" class="form-control" id="end_date">
                        </div>
                        <div class="form-group col">
                            <label for="user_id" class="d-block" style="margin-bottom: 4px; padding-left: 2px;">B2B Users</label>
                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">All User</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->phone); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="user_status" class="d-block" style="margin-bottom: 4px; padding-left: 2px;">User Status</label>
                            <select name="user_status" id="user_status" class="form-select">
                                <option value="">All</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label style="color: transparent; margin-bottom: 4px; padding-left: 2px;" class="d-block">Generate Report</label>
                            <button type="button" onclick="generateReport()" class="btn btn-success w-100" id="generate_flights_report_btn"><i class="typcn typcn-zoom-outline"></i> Generate Report</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-xl-12" id="report_view_section">

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function generateReport(){

            $("#generate_flights_report_btn").html("Generating...");

            var startDate = $("#start_date").val();
            var endDate = $("#end_date").val();
            var userId = $("#user_id").val();
            var userStatus = $("#user_status").val();

            $.ajax({
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    user_id: userId,
                    user_status: userStatus,
                },
                url: "<?php echo e(url('generate/b2b/financial/report')); ?>",
                type: "POST",
                dataType: 'json',
                success: function(data) {
                    toastr.success("Report Generated Successfully");
                    $("#report_view_section").html(data.report);
                    $("#generate_flights_report_btn").html("<i class='typcn typcn-zoom-outline'></i> Generate Report");
                },
                error: function(data) {
                    $("#generate_flights_report_btn").html("<i class='typcn typcn-zoom-outline'></i> Generate Report");
                    console.log('Error:', data);
                    toastr.error("Something Went Wrong", "Try Again");
                    return false;
                }
            });
        }

        function printPageArea(areaID){
            var printContent = document.getElementById(areaID).innerHTML;
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/report/b2b_financial.blade.php ENDPATH**/ ?>