$(document).on('click','#update',function () {
    $(this).addClass("disabled")
    $(this).html('<i class="fas fa-spinner fa-spin mr-1"></i> <?php echo e(__("Updating")); ?>');
});<?php /**PATH /var/www/html/fundorex-v1.4.0/@core/resources/views/components/btn/update.blade.php ENDPATH**/ ?>