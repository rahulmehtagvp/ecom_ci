<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Cms Data |Add Cms</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">

</head>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $pTitle ?><small><?= $pDescription ?></small></h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>"><i class="fa fa-star-o" aria-hidden="true"></i>Home</a></li>
            <li><?= $menu ?></li>
            <li class="active"><?= $smenu ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <?php
                $url = (!isset($cms_id) || empty($cms_id)) ? 'cmsdata/createcms' : 'cmsdata/updatecms/' . $cms_id;
                if ($this->session->flashdata('message')) {
                    $flashdata = $this->session->flashdata('message');
                    ?>
                    <div class="alert alert-<?= $flashdata['class'] ?>">
                        <button class="close" data-dismiss="alert" type="button">×</button>
                        <?= $flashdata['message'] ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-body">
                        <form role="form" action="<?= base_url($url) ?>" method="post" class="validate" data-parsley-validate="" enctype="multipart/form-data" autocomplete="off">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Identifier</label>
                                    <input type="text" class="form-control required" data-parsley-trigger="change"  
                                           data-parsley-minlength="2"
                                           required="" name="identifier" 
                                           value="<?= (isset($notificationData) && isset($notificationData->identifier)) ? $notificationData->identifier : '' ?>">
                                    <span class="glyphicon  form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <label>Data</label>
                                    <textarea id="rich_editor_3" type="text" class="ip_reg_form_input form-control reset-form-custom" name="data" style="height:108px;" data-parsley-trigger="change">
                                        <?= (isset($notificationData) && isset($notificationData->data)) ? $notificationData->data : '' ?></textarea>
                                    <span class="glyphicon  form-control-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-12">      
                                <div class="box-footer textCenterAlign">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a href="<?= base_url('cmsdata') ?>" class="btn btn-primary">Cancel</a>
                                </div>        
                            </div>        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>