 <?php if(!empty(get_static_option('preloader_status'))): ?>
    <?php echo $__env->make('frontend.partials.preloader.preloader-default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?><?php /**PATH E:\xampp\htdocs\fundorex-v1.4.0\@core\resources\views/frontend/partials/preloader.blade.php ENDPATH**/ ?>