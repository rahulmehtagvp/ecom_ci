<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Store | Add Store</title>
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
                $redirectUrl = (isset($store_id) && !empty($store_id)) ? 'stores/updatestore/' . $store_id : 'stores/createstore';

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
                        <h3 class="box-title">Store Details</h3>
                    </div>
                    <div class="box-body">
                        <form id="createStoreForm" role="form" action="<?= base_url($redirectUrl) ?>" method="post" class="validate" data-parsley-validate="" enctype="multipart/form-data">
                            <div class="col-md-12">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label>Store Name</label>
                                        <input type="text" class="form-control required" data-parsley-trigger="change" data-parsley-minlength="2" name="store_name" required="" 
                                               placeholder="Enter Store Name" value="<?= (isset($store_data->store_name)) ? $store_data->store_name : '' ?>">
                                        <span class="glyphicon form-control-feedback"></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Store Description</label>
                                        <textarea id="rich_editor" type="text" class="ip_reg_form_input form-control reset-form-custom" placeholder="Store Description" name="description" 
                                                  style="height:108px;" data-parsley-trigger="change" data-parsley-minlength="2"><?= (isset($store_data->description)) ? $store_data->description : '' ?></textarea>
                                    </div> 
                                </div>

                                <div class="col-md-6">
                                    <?php if (!empty($city_data)) { ?>
                                        <div class="form-group">
                                            <label>Cities</label>
                                            <select name="city_id" class="form-control" placeholder="Select City">
                                                <option selected value="0">Choose a City</option>
                                                <?php
                                                foreach ($city_data as $city) {
                                                    $select = (isset($user_data->city_id) && $user_data->city_id == $city->city_id) ? 'selected' : '';
                                                    echo '<option ' . $select . ' value="' . $city->city_id . '">' .
                                                    $city->location .
                                                    '</option>';
                                                }
                                                ?>
                                            </select> 
                                        </div>
                                    <?php } ?> 



                                    <div class="form-group">
                                        <label>Store Picture</label>
                                        <div class="col-md-12">
                                            <div class="col-md-3">
                                                <img id="profile_image" src="<?= (isset($store_data) && isset($store_data->store_image)) ? base_url($store_data->store_image) : '' ?>" onerror="this.src='<?= base_url("assets/images/user_avatar.jpg") ?>'" height="75" width="75" />
                                            </div>
                                            <div class="col-md-9" style="padding-top: 25px;">
                                                <input name="store_image" type="file" accept="image/*" 
                                                       class="<?= (isset($store_id) && !empty($store_id)) ? '' : 'required' ?>" 
                                                       onchange="setImg(this, 'store_image')" />
                                            </div>
                                        </div>
                                    </div><br>
                                    <label>Store Opening Time</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    <input type="time" id="appt" name="starting_time" required>
                                    <br>
                                    <label>Store Closing Time</label>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                                    <input type="time" id="appt" name="starting_end" required>
                                    <div class="col-md-12">          
                                        <div class="box-footer">
                                            <div style="text-align: center;">
                                                <button id="createStoreSubmit" type="submit" class="btn btn-primary">Submit</button>
                                                <a href="<?= base_url('stores/liststores') ?>" class="btn btn-primary">Cancel</a>
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            </form></div></div></div></div>
</section></div>