<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">
</head>
<div class="content-wrapper">
    <section class="content-header">
        <h1> <?= $page_title ?>
            <small><?= $page_desc ?></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="javascript:void(0);" ><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <?php
    if ($this->session->flashdata('message')) {
        $flashdata = $this->session->flashdata('message');
        ?>
        <br><div class="alert alert-<?= $flashdata['class'] ?>">
            <button class="close" data-dismiss="alert" type="button">×</button>
            <?= $flashdata['message'] ?>
        </div>
    <?php } ?>  
    <section class="content">
        <div class="row">
            <?php if (isset($customerCount) && !empty($customerCount)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h4>Users</h4>
                            <p><?php echo 'Total : ' . $customerCount ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-people"></i>
                        </div>
                        <a href="<?= base_url('customer/listcustomerusers') ?>" class="small-box-footer ">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } if (isset($totalorder) && !empty($totalorder)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h4>Total orders</h4> 
                            <p>
                                <?php echo 'Total : ' . $totalorder; ?>
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="<?= base_url('orders/listorders') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } if (isset($completeorderCount) && !empty($completeorderCount)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h4>Completed orders</h4> 
                            <p>
                                <?php echo 'Total : ' . $completeorderCount; ?>
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="<?= base_url('orders/listorders') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } if (isset($pendingorderCount) && !empty($pendingorderCount)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h4>Pending orders</h4> 
                            <p>
                                <?php echo 'Total : ' . $pendingorderCount; ?>
                            </p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="<?= base_url('orders/listorders') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } if (isset($categoryCount) && !empty($categoryCount)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h4>Categories</h4>
                            <p><?php echo 'Total : ' . $categoryCount ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-android-list"></i>
                        </div>
                        <a href="<?= base_url('category/listcategory') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } if (isset($subcategoryCount) && !empty($subcategoryCount)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h4>Subcategories</h4>
                            <p><?php echo 'Total : ' . $subcategoryCount ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="<?= base_url('sub_category/listsubcat') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } if (isset($shopCount) && !empty($shopCount)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h4>Mechanic Shops</h4>
                            <p><?php echo 'Total : ' . $shopCount ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="<?= base_url('Shop/viewShops') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } if (isset($storeCount) && !empty($storeCount)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h4>Total Stores</h4>
                            <p><?php echo 'Total : ' . $storeCount ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="<?= base_url('stores/liststores') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } if (isset($productCount) && !empty($productCount)) { ?>
                <div class="col-lg-3 col-xs-6">
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h4>Total Products</h4>
                            <p><?php echo 'Total : ' . $productCount ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="<?= base_url('product/viewproducts') ?>" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            <?php } ?>
        </div> 
    </section>
</div>
