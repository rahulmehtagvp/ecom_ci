<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Customer | add</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">
</head>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <?= $pTitle ?><small><?= $pDescription ?></small>
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
                $redirectUrl = (isset($customer_id) && !empty($customer_id)) ? 'customer/updatecustomer/' . $customer_id : 'customer/createcustomer';
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
                    <div class="box-header with-border">
                        <h3 class="box-title">Personal Details</h3>
                    </div>
                    <div class="box-body">
                        <form id="createCustomerForm" role="form" action="<?= base_url($redirectUrl) ?>" method="post" class="validate" data-parsley-validate="" enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label>Name</label>
                                        <input type="text" class="form-control required" data-parsley-trigger="change"  
                                               data-parsley-minlength="2" data-parsley-pattern="^[a-zA-Z\ . ! @ # $ % ^ & * () + = , \/]+$"
                                               required="" name="fullname"  placeholder="Enter Full Name" 
                                               value="<?= (isset($customer_data) && isset($customer_data->fullname)) ? $customer_data->fullname : '' ?>">
                                        <span class="glyphicon  form-control-feedback"></span>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label>Password</label>
                                        <input type="password" class="form-control required" data-parsley-trigger="change"  
                                               data-parsley-minlength="2" required="" name="password" placeholder="Enter password"
                                               value="<?= (isset($customer_data) && isset($customer_data->password)) ? $customer_data->password : '' ?>">
                                        <span class="glyphicon  form-control-feedback"></span>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label>Email</label>
                                        <input type="email" class="form-control required" data-parsley-trigger="change"  
                                               data-parsley-minlength="2" required="" name="email" placeholder="Enter Email"
                                               value="<?= (isset($customer_data) && isset($customer_data->email)) ? $customer_data->email : '' ?>">
                                        <span class="glyphicon  form-control-feedback"></span>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label>Phone Number</label>
                                        <input type="number" class="form-control required" data-parsley-trigger="change"  
                                               data-parsley-minlength="2" required="" name="phone_no" placeholder="Enter Phone"
                                               value="<?= (isset($customer_data) && isset($customer_data->phone_no)) ? $customer_data->phone_no : '' ?>">
                                        <span class="glyphicon  form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label>District</label>
                                        <input type="text" class="form-control required" data-parsley-trigger="change"  
                                               data-parsley-minlength="2"  data-parsley-pattern="^[a-zA-Z\ . ! @ # $ % ^ & * () + = , \/]+$" required="" name="district"  placeholder="Enter District"
                                               value="<?= (isset($customer_data) && isset($customer_data->district)) ? $customer_data->district : '' ?>">
                                        <span class="glyphicon  form-control-feedback"></span>
                                    </div>

                                    <?php if (!empty($city_data)) { ?>
                                        <div class="form-group">
                                            <label>City</label>
                                            <select name="city_id" class="form-control" placeholder="Select City">
                                                <option selected value="0">Choose a City</option>
                                                <?php
                                                foreach ($city_data as $city) {
                                                    $select = (isset($customer_data->city_id) && $customer_data->city_id == $city->city_id) ? 'selected' : '';
                                                    echo '<option ' . $select . ' value="' . $city->city_id . '">' .
                                                    $city->location .
                                                    '</option>';
                                                }
                                                ?>
                                            </select> 
                                        </div>
                                    <?php } ?> 

                                    <div class="form-group">
                                        <label>Profile Picture</label>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <img id="profile_image" src="<?= (isset($customer_data) && isset($customer_data->image)) ? base_url($customer_data->image) : '' ?>" onerror="this.src='<?= base_url("assets/images/user_avatar.jpg") ?>'" height="75" width="75" />
                                            </div>
                                            <div class="col-md-9" style="padding-top: 25px;">
                                                <input name="image" type="file" accept="image/*" 
                                                       class="<?= (isset($customer_id) && !empty($customer_id)) ? '' : 'required' ?>" 
                                                       onchange="setImg(this, 'image')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12">          
                                <div class="box-footer">
                                    <div style="text-align: center;">
                                        <button id="createCustomerSubmit" type="submit" class="btn btn-primary">Submit</button>
                                        <a href="<?= base_url('customer/listcustomerusers') ?>" class="btn btn-primary">Cancel</a>
                                    </div>
                                </div>        
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>