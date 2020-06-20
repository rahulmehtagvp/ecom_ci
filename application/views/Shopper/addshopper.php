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
                $url = (!isset($shopper_id) || empty($shopper_id)) ? 'shopper/createshopper' : 'shopper/updateshopper/' . $shopper_id;
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
                        <form role="form" action="<?= base_url($url) ?>" method="post" 
                              class="validate" data-parsley-validate="" enctype="multipart/form-data" autocomplete="off">
                            <div class="col-md-12">  
                                <div class="box-header with-border padUnset">
                                    <h3 class="box-title">Basic Details</h3>
                                </div><br>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Display Name</label>
                                    <input type="text" class="form-control required" data-parsley-trigger="change" data-parsley-minlength="2" name="display_name" required="" 
                                           placeholder="Enter Display Name" value="<?= (isset($user_data->display_name)) ? $user_data->display_name : '' ?>">
                                    <span class="glyphicon form-control-feedback"></span>
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control required" data-parsley-trigger="change"  
                                           data-parsley-minlength="2" required="" name="email_id" placeholder="Enter email ID"  value="<?= (isset($user_data->email)) ? $user_data->email : '' ?>">
                                    <span class="glyphicon form-control-feedback"></span>
                                </div> 
                                <div class="form-group">
                                    <label>User Name</label>
                                    <input type="text" class="form-control required" data-parsley-trigger="change"
                                           data-parsley-minlength="2" name="username" required="" value="<?= (isset($user_data->username)) ? $user_data->username : '' ?>"
                                           data-parsley-pattern="^[a-zA-Z0-9\ . _ @  \/]+$" placeholder="Enter User Name">
                                    <span class="glyphicon  form-control-feedback"></span>
                                </div>
                                <?php if (!isset($shopper_id)) { ?>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" class="form-control required" name="password" placeholder="Password" required="">
                                        <span class="glyphicon  form-control-feedback"></span>
                                    </div>  
                                <?php } ?>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone number</label>
                                    <input type="text" class="form-control required" data-parsley-trigger="change" 
                                           data-parsley-minlength="2"  data-parsley-pattern="^[0-9\ , - + \/]+$" required=""
                                           value="<?= (isset($user_data->phone_no)) ? $user_data->phone_no : '' ?>" name="phone" placeholder="Enter Phone Number" >
                                    <span class="glyphicon  form-control-feedback"></span>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Profile Picture</label>
                                        <div class="col-md-12" style="padding-bottom:10px;">
                                            <div class="col-md-3">
                                                <img id="image_id" src="<?= (isset($user_data->profile_image)) ? base_url($user_data->profile_image) : '' ?>" onerror="this.src='<?= base_url("assets/images/user_avatar.jpg") ?>';" height="75" width="75" />
                                            </div>
                                            <div class="col-md-9" style="padding-top: 25px;">
                                                <input name="profile_image" type="file" accept="image/*" onchange="setImg(this, 'image_id');" />
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <?php if (!empty($shop_data)) { ?>
                                    <div class="form-group">
                                        <label>Store</label>
                                        <select name="store_id" class="form-control" placeholder="Select Store" required>
                                            <option selected value="0">Choose a Store</option>
                                            <?php
                                            foreach ($shop_data as $store) {
                                                $select = (isset($user_data->store_id) && $user_data->store_id == $store->store_id) ? 'selected' : '';
                                                echo '<option ' . $select . ' value="' . $store->store_id . '">' .
                                                $store->store_name .
                                                '</option>';
                                            }
                                            ?>
                                        </select> 
                                    </div>
                                <?php } ?>

                                <div class="col-md-12">      
                                    <div class="box-footer textCenterAlign">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="<?= base_url('shopper/viewshopper') ?>" class="btn btn-primary">Cancel</a>
                                    </div>        
                                </div>        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>