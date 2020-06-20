<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Advertisement | list Advertisement</title>
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
                <?php if ($this->session->flashdata('message')) {
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
                        <div class="col-md-6"><h3 class="box-title">Advertisement List</h3></div>
                        <div class="col-md-6" align="right">

                            <a class="btn btn-sm btn-primary" href="<?= base_url('advertisement/addadvertisement') ?>">Add New Advertisement</a>
                            <a class="btn btn-sm btn-primary" href="<?= base_url() ?>">Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="driverTable" class="table table-bordered table-striped datatable ">
                            <thead>
                                <tr>
                                    <th class="hidden">Add id</th>
                                    <th class="13%">Add Name</th>
                                    <th width="13%;">image</th> 
                                    <th width="13%;">Starting Time</th> 
                                    <th width="10%;">Ending Time</th>
                                    <th width="5%;">Status</th>
                                    <th width="33%;">Action</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                if (!empty($notificationData)) {
                                    foreach ($notificationData as $Data) {
                                        ?>
                                        <tr>
                                            <th class="hidden"><?= $Data->add_id ?></th>
                                            <th class="center"><?= $Data->add_name ?></th>
                                            <th class="center"><img src="<?= base_url($Data->image) ?>" class="cpoint" onclick="viewImageModal('Advertisement Image', '<?= base_url($Data->image) ?>');"
                                                                    onerror="this.src='<?= base_url("assets/images/user_avatar.jpg") ?>';" height="100" width="100" /></th> 
                                            <td class="center"><?= $Data->starting_time ?></th>
                                            <td class="center"><?= $Data->ending_time ?></th> 
                                            <td class="center"><?= ($Data->status == '1') ? 'Active' : 'Inactive' ?></td>
                                            <td class="center">	 

                                                <a class="btn btn-sm btn-danger" 
                                                   href="<?= base_url('advertisement/editadvertisement/' . encode_param($Data->add_id)) ?>">
                                                    <i class="fa fa-fw fa-edit"></i>Edit
                                                </a> 
                                                <a class="btn btn-sm btn-danger" 
                                                   href="<?= base_url("advertisement/changestatus/" . encode_param($Data->add_id)) . "/2" ?>" 
                                                   onClick="return doconfirm()">
                                                    <i class="fa fa-fw fa-trash"></i>Delete
                                                </a>    
        <?php if ($Data->status == 1) { ?>
                                                    <a class="btn btn-sm btn-success" style="background-color:#ac2925" href="<?= base_url("advertisement/changestatus/" . encode_param($Data->add_id)) . "/0" ?>">
                                                        <i class="fa fa-cog"></i> De-activate
                                                    </a>
        <?php } else { ?>
                                                    <a class="btn btn-sm btn-success" href="<?= base_url("advertisement/changestatus/" . encode_param($Data->add_id)) . "/1" ?>">
                                                        <i class="fa fa-cog"></i> Activate
                                                    </a>
        <?php } ?>
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