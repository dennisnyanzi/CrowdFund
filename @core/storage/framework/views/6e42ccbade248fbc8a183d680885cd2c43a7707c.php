<script>
    $('.icp-dd').iconpicker();
    $('body').on('iconpickerSelected','.icp-dd', function (e) {
        var selectedIcon = e.iconpickerValue;
        $(this).parent().parent().children('input').val(selectedIcon);
        $('body .dropdown-menu.iconpicker-container').removeClass('show');
    });
</script><?php /**PATH /var/www/html/fundorex-v1.4.0/@core/resources/views/components/icon-picker-activate-js.blade.php ENDPATH**/ ?>