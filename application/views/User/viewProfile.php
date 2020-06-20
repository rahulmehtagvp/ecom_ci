<?php
  $user_data = $this->session->userdata['user'];
?>
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
          if($this->session->flashdata('message')) { 
          $flashdata = $this->session->flashdata('message'); ?>
          <div class="alert alert-<?= $flashdata['class'] ?>">
            <button class="close" data-dismiss="alert" type="button">×</button>
            <?= $flashdata['message'] ?>
          </div>
        <?php } ?>
      </div>
      <div class="col-md-12">
        <div class="box box-warning">
          <div class="box-header with-border">
            <div class="col-md-6"><h3 class="box-title">Admin Details</h3></div>
            <div class="col-md-6" align="right">
              <a class="btn btn-sm btn-primary" href="<?= base_url('User/editProfile') ?>">Edit</a>
              <a class="btn btn-sm btn-primary" href="<?= base_url() ?>">Back</a>
            </div>
          </div>
          <div class="box-body">
            <div class="col-md-12">
              <div class="col-md-2">
                <div class="form-group has-feedback">
                  <img src="<?= base_url($user_data->profile_image) ?>" class="cpoint" onclick="viewImageModal('Profile Image','<?= base_url($user_data->profile_image) ?>');"
                  onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>';" height="100" width="100" />
                </div>
              </div>
              <div class="col-md-5">
                <div class="row">
                  <div class="col-md-5"><span>Display Name </span></div>
                  <div class="col-md-7">
                    <span>:</span>
                    <label class="padLeft20">
                      <?= $user_data->display_name ?> 
                    </label>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5"><span>User Name </span></div>
                  <div class="col-md-7">
                    <span>:</span>
                    <label class="padLeft20">
                      <?= $user_data->username ?> 
                    </label>
                  </div>
                </div>
                <?php 
                  if($this->session->userdata('user_type') == 2 && 
                  isset($this->session->userdata['shopper_data']) && 
                  !empty($this->session->userdata['shopper_data'])){ 
                    //print_r($user_data);exit;
                  $shopper_data = $this->session->userdata['user']->shopper_data; ?>
                  
                  <div class="row">
                    <div class="col-md-5"><span>Phone Number </span></div>
                    <div class="col-md-7">
                      <span>:</span>
                      <label class="padLeft20">
                        <?= $shopper_data->phone_no ?> 
                      </label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-5"><span>Email ID </span></div>
                    <div class="col-md-7">
                      <span>:</span>
                      <label class="padLeft20">
                        <?= $shopper_data->email ?> 
                      </label>
                    </div>
                  </div>
                  <?php 
                    if(isset($this->session->userdata['shopper_data']) && 
                    !empty($this->session->userdata['shopper_data'])){ 
                        //print_r($user_data);exit;
                      $shop_data = $this->session->userdata['shopper_data'];
                      //print_r($shop_data);exit;
                    ?>
                    <div class="row">
                      <div class="col-md-5"><span>Store Name </span></div>
                      <div class="col-md-7">
                        <span>:</span>
                        <label class="padLeft20">
                          <?= $shop_data->store_name ?> 
                        </label>
                      </div>
                    </div>
                    <div class="row">
                    <div class="col-md-5"><span>Store image </span></div>
                      <div class="col-md-7">
                        <span>:</span>
                        <img src="<?= base_url($shop_data->store_image) ?>" class="cpoint" onclick="viewImageModal('Profile Image','<?= base_url($shop_data->store_image) ?>');"
                  onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>';" height="100" width="100" />
                
                      </div>
                    </div>
                    
                  <?php } ?>
                </div>
                <div class="col-md-5">
                  
                  
                 
                <?php } else { ?>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
</div>