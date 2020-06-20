
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>orders | order_list</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">

</head>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $pTitle ?>&nbsp &nbsp<small><?= $pDescription ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?= base_url() ?>"><i class="fa fa-star-o" aria-hidden="true"></i>Home</a></li>
            <li><?= $menu ?></li>
            <li class="active"><?= $smenu ?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-sm-12">
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
                        <div class="col-md-6"><h3 class="box-title">Order List</h3></div>
                        <div class="col-md-6" align="right">
                            <?php if (!empty($orderData)) { ?>

                            <?php } ?>&nbsp &nbsp
                            <a class="btn btn-sm btn-primary" href="<?= base_url() ?>">Back</a>
                        </div>
                    </div> 
                    <br>   
                    <div class="box-body">
                        <table id="mechanicUsers" class="table table-bordered table-striped datatable ">
                            <thead>
                                <tr>
                                    <th class="hidden">ID</th>
                                    <th width="12%;">Order ID</th>
                                    <th width="15%;">Customer</th>
                                    <th width="22%;">Product</th>
                                    <th width="9%;">Amount</th>
                                    <th width="17%;">Status</th>
                                    <th width="17%;">Action</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                if (!empty($orderData)) {
                                    foreach ($orderData as $odrData) {
                                        ?>
                                        <tr>
                                            <th class="hidden"><?= $odrData->order_id ?></th>
                                            <th class="center"><?= $odrData->order_id ?></th>
                                            <th class="center"><?= $odrData->fullname ?></th>
                                            <th class="center"><?= $odrData->product_name ?></th>
                                            <th class="center"><?= $odrData->total_amount ?></th>
                                            <th class="center" id="orderStatus_<?= $odrData->order_id ?>">
                                                <?php
                                                switch ($odrData->status) {
                                                    case 0: echo 'Ordered On<br>
                                              (Order placed on ' . $odrData->booking_date . ')';
                                                        break;
                                                    case 1: echo 'Order Delivered <br>
                                              (Delivered on ' . $odrData->scheduled_date . ')';
                                                        break;
                                                    case 2: echo 'Order Packed <br>
                                              (Deliver by ' . $odrData->scheduled_date . ')';
                                                        break;
                                                    case 3: echo 'Order shipped <br>
                                              (Deliver by ' . $odrData->scheduled_date . ')';
                                                        break;
                                                }
                                                ?>
                                            </th>
                                            <td class="center">
                                                <a class="btn btn-sm btn-primary" id="viewOrderDetails" 
                                                   order_id="<?= encode_param($odrData->order_id) ?>">
                                                    <i class="fa fa-fw fa-eye"></i>View
                                                </a>

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
            </div>
        </div>
    </section>
</div>