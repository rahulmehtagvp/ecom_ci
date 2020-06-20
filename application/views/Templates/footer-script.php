<?php
 // $settings = getSettings();
 // $gKey = $settings['google_api_key'];
?>
<script>
    base_url = "<?= base_url() ?>";
</script>

<script src="<?= base_url('assets/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/pace.js') ?>"></script>
<script src="<?= base_url('assets/js/select2.full.min.js') ?>"></script>
<script src="<?= base_url('assets/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/js/dataTables.bootstrap.min.js') ?>"></script>
<script src="<?= base_url('assets/js/bootbox.min.js') ?>"></script>
<script src="<?= base_url('assets/js/app.min.js') ?>"></script>
<script src="<?= base_url('assets/js/custom-script.js') ?>"></script>
<script src="<?= base_url('assets/js/parsley.min.js') ?>"></script>
<script src="https://cdn.ckeditor.com/4.5.7/standard/ckeditor.js"></script>
<script src="<?= base_url('assets/js/bootstrap-datepicker.js') ?>"></script>
<script src="<?= base_url('assets/js/clockpicker.js') ?>" type="text/javascript"></script>
<script src="<?= base_url('assets/js/raphael.min.js') ?>"></script>
<script src="<?= base_url('assets/js/morris.min.js')?>"></script>
<script>
    jQuery('.clockpicker').clockpicker();

    jQuery( document ).ready(function() {
        if(jQuery('#rich_editor').length==1){ CKEDITOR.replace('rich_editor'); }
        if(jQuery('#rich_editor1').length==1){CKEDITOR.replace('rich_editor1'); }
        if(jQuery('#rich_editor_2').length==1){CKEDITOR.replace('rich_editor_2');}
        if(jQuery('#rich_editor_3').length==1){CKEDITOR.replace('rich_editor_3');}
        if(jQuery('#rich_editor_4').length==1){CKEDITOR.replace('rich_editor_4');}
        if(jQuery('#rich_editor_5').length==1){CKEDITOR.replace('rich_editor_5');}
        if(jQuery('#rich_editor_6').length==1){CKEDITOR.replace('rich_editor_6');}
    });

    function doconfirm(){
        action = confirm("Are you sure to delete permanently?");
        if(action != true) return false;
    }

    <?php
        $ci = & get_instance();
        $controllerName = $ci->uri->segment(1);
        $actionName = $ci->uri->segment(2);
        $page = $controllerName . '-' . $actionName;

        // switch ($page) {
            //case 'Ride-view_rides': ?>
                // jQuery(function () {
                //     jQuery('.datatable').DataTable({
                //         scrollY:        "300px",
                //         scrollX:        true,
                //         scrollCollapse: true,
                //         paging:         false,
                //         fixedColumns:   {
                //             heightMatch: 'none'
                //         }
                //     });
                // });
                <?php //break; 
            //default : ?>
                jQuery(function () {
                    jQuery('.datatable').DataTable({
                        "ordering" : jQuery(this).data("ordering"),
                        "order": [[ 0, "desc" ]]
                    });
                });
    <?php //} ?>
</script>