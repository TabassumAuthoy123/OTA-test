

<?php $__env->startSection('header_css'); ?>
    <style>
        .settings-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .06);
        }

        .settings-card h5 {
            font-weight: 700;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .settings-card h5 i {
            font-size: 18px;
            color: #084277;
        }

        /* Fix form labels */
        .settings-card .form-label {
            position: static !important;
            transform: none !important;
            pointer-events: auto !important;
            padding: 0 0 4px 0 !important;
            height: auto !important;
            font-size: 13px !important;
            color: #212529 !important;
            white-space: normal !important;
            z-index: auto !important;
            border: none !important;
            opacity: 1 !important;
            font-weight: 600;
        }

        .settings-card .form-control,
        .settings-card .form-select {
            padding: 8px 12px !important;
            height: auto !important;
        }

        .cms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="cms-header">
        <div>
            <h4 class="mb-0 fw-bold">⚙️ Site Settings</h4>
            <small class="text-muted">Manage hero section, footer content, and social links for the B2C site</small>
        </div>
    </div>

    <form action="<?php echo e(url('cms/site-settings')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        
        <div class="settings-card">
            <h5><i class="fas fa-star"></i> Hero Section</h5>
            <div class="mb-3">
                <label class="form-label">Badge Text</label>
                <input type="text" name="hero_badge" class="form-control"
                    value="<?php echo e($settings['hero_badge'] ?? 'Trusted by 10,000+ travelers'); ?>"
                    placeholder="e.g. Trusted by 10,000+ travelers">
                <small class="text-muted">The small badge shown above the hero title</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Hero Title (supports HTML)</label>
                <textarea name="hero_title" class="form-control" rows="3"
                    placeholder="e.g. Find & Book <span>Best Flights</span><br>At Unbeatable Prices"><?php echo e($settings['hero_title'] ?? 'Find & Book <span>Best Flights</span><br>At Unbeatable Prices'); ?></textarea>
                <small class="text-muted">Use <code>&lt;span&gt;text&lt;/span&gt;</code> for gradient accent
                    and <code>&lt;br&gt;</code> for line breaks</small>
            </div>
        </div>

        
        <div class="settings-card">
            <h5><i class="fas fa-address-card"></i> Footer Contact Info</h5>
            <div class="mb-3">
                <label class="form-label">Site Description</label>
                <textarea name="footer_description" class="form-control"
                    rows="2"><?php echo e($settings['footer_description'] ?? ''); ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="text" name="footer_phone" class="form-control"
                        value="<?php echo e($settings['footer_phone'] ?? ''); ?>" placeholder="+880-XXXX-XXXXXX">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="footer_email" class="form-control"
                        value="<?php echo e($settings['footer_email'] ?? ''); ?>" placeholder="support@example.com">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" name="footer_address" class="form-control"
                        value="<?php echo e($settings['footer_address'] ?? ''); ?>" placeholder="Dhaka, Bangladesh">
                </div>
            </div>
        </div>

        
        <div class="settings-card">
            <h5><i class="fas fa-share-alt"></i> Social Media Links</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-facebook-f me-1"></i> Facebook URL</label>
                    <input type="url" name="social_facebook" class="form-control"
                        value="<?php echo e($settings['social_facebook'] ?? ''); ?>" placeholder="https://facebook.com/yourpage">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-instagram me-1"></i> Instagram URL</label>
                    <input type="url" name="social_instagram" class="form-control"
                        value="<?php echo e($settings['social_instagram'] ?? ''); ?>" placeholder="https://instagram.com/yourpage">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-twitter me-1"></i> Twitter URL</label>
                    <input type="url" name="social_twitter" class="form-control"
                        value="<?php echo e($settings['social_twitter'] ?? ''); ?>" placeholder="https://twitter.com/yourpage">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label"><i class="fab fa-linkedin-in me-1"></i> LinkedIn URL</label>
                    <input type="url" name="social_linkedin" class="form-control"
                        value="<?php echo e($settings['social_linkedin'] ?? ''); ?>"
                        placeholder="https://linkedin.com/company/yourpage">
                </div>
            </div>
        </div>

        <div class="text-end mb-4">
            <button type="submit" class="btn btn-primary px-4">
                <i class="fas fa-save me-1"></i> Save Settings
            </button>
        </div>
    </form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH I:\Softifybd Devs\OTA-Platform\resources\views/cms/site-settings.blade.php ENDPATH**/ ?>