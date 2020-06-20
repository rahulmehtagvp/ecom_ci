<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Cms Data| Cms List </title> 
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">
</head>

<div class="content-wrapper" >
    <section class="content-header">
        <h1>
            <?= $pTitle ?>
            <small><?= $pDescription ?></small>
        </h1>
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
                if ($this->session->flashdata('message')) {
                    $flashdata = $this->session->flashdata('message');
                    ?>
                    <div class="alert alert-<?= $flashdata['class'] ?>">
                        <button class="close" data-dismiss="alert" type="button">×</button>
                        <?= $flashdata['message'] ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-xs-12">
                <div class="box box-warning"> 
                    <div class="box-header with-border">
                        <div class="col-md-6"><h3 class="box-title">CMS List</h3></div>
                        <div class="col-md-6" align="right">
                            <a class="btn btn-sm btn-primary" href="<?= base_url('cmsdata/addcms') ?>">Add New Cms Data</a>
                            <a class="btn btn-sm btn-primary" href="<?= base_url() ?>">Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="driverTable" class="table table-bordered table-striped datatable ">
                            <thead>
                                <tr>
                                    <th width="13%;">Identifier</th>
                                    <th width="14%;">Data</th> 
                                    <th width="33%;">Action</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                if (!empty($notificationData)) {
                                    foreach ($notificationData as $notification) {
                                        ?>
                                        <tr>
                                            <th width="20%" class="center"><?= $notification->identifier ?></th>
                                            <td width="60%" class="center"><?= $notification->data ?></th>
                                            <td> <a class="btn btn-sm btn-danger" 
                                                    href="<?= base_url('cmsdata/editcms/' . encode_param($notification->cms_id)) ?>">
                                                    <i class="fa fa-fw fa-edit"></i>Edit
                                                </a>
                                                <a class="btn btn-sm btn-danger" 
                                                   href="<?= base_url("cmsdata/changestatus/" . encode_param($notification->cms_id)) . "/0" ?>" 
                                                   onClick="return doconfirm()">
                                                    <i class="fa fa-fw fa-trash"></i>Delete
                                                </a>   
                                            </td>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                </section>
            </div>