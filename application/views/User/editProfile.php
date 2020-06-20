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
          <div class="box-body">
            <form role="form" action="<?=base_url('User/updateUser')?>" method="post" class="validate" data-parsley-validate="" enctype="multipart/form-data">
              <div class="col-md-12">  
                <div class="box-header with-border padUnset">
                  <h3 class="box-title">Basic Details</h3>
                </div><br>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Display Name</label>
                  <input type="text" class="form-control required" data-parsley-trigger="change"
                  data-parsley-minlength="2" name="display_name" required=""
                  value="<?= $user_data->display_name ?>" placeholder="Enter Display Name">
                  <span class="glyphicon form-control-feedback"></span>
                </div>
                <div class="form-group">
                  <label>User Name</label>
                  <input type="text" class="form-control required" data-parsley-trigger="change"
                  data-parsley-minlength="2" name="username" required=""
                  data-parsley-pattern="^[a-zA-Z0-9\ . _ @  \/]+$" 
                  value="<?= $user_data->username ?>" placeholder="Enter User Name">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>  
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Profile Picture</label>
                  <div class="col-md-12" style="padding-bottom:10px;">
                    <div class="col-md-3">
                      <img id="image_id" src="<?= base_url($user_data->profile_image) ?>" onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>';" height="75" width="75" />
                    </div>
                    <div class="col-md-9" style="padding-top: 25px;">
                      <input name="profile_image" type="file" accept="image/*" onchange="setImg(this,'image_id');" />
                    </div>
                  </div>
                </div>
              </div>
              <?php 
                if(isset($user_data->shopper_data) && !empty($user_data->shopper_data)){
                  $shopper_data = $user_data->shopper_data;
              ?>
              <div class="col-md-12">  
                <div class="box-header with-border padUnset">
                  <h3 class="box-title">Personal Details</h3>
                </div><br>
              </div>
              <div class="col-md-6">
                 
                <div class="form-group">
                  <label>Email</label>
                  <input type="email" class="form-control required" data-parsley-trigger="change"  
                  data-parsley-minlength="2" required="" name="email_id" placeholder="Enter email ID" 
                  value="<?= $shopper_data->email?>" >
                  <span class="glyphicon form-control-feedback"></span>
                </div> 
                
                
                <?php if(!empty($shop_data)){ ?>
                  <div class="form-group">
                    <label>store</label>
                    <select name="store" class="form-control" placeholder="Select Workshop">
                      <option selected value="0">Choose a Store</option>
                      <?php 
                        foreach ($shop_data as $shop) {
                          $select = ($shopper_data->store_id == $shop->store_id)?'selected':'';
                          echo '<option '.$select.' value="'.$shop->store_id.'">'.
                                  $shop->store_name.
                               '</option>';
                        }
                      ?>
                    </select> 
                  </div>
                <?php } ?> 
              </div>
              <div class="col-md-6">
                
                <div class="form-group">
                  <label>Phone</label>
                  <input type="text" class="form-control required" data-parsley-trigger="change" 
                  data-parsley-minlength="2"  data-parsley-pattern="^[0-9\ , - + \/]+$" required="" name="phone" placeholder="Enter Phone Number" value="<?= $shopper_data->phone_no ?>" >
                  <span class="glyphicon  form-control-feedback"></span>
                </div>
                
              
            <?php } ?>
              <div class="col-md-12">  
                <div class="box-header with-border padUnset">
                  <h3 class="box-title">Change Password</h3>
                </div><br>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>New Password</label>
                  <input type="password" class="form-control" name="password" placeholder="New Password">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>  
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input type="password" class="form-control" name="cPassword" placeholder="Confirm Password">
                  <span class="glyphicon  form-control-feedback"></span>
                </div>
              </div>   
              <div class="col-md-12">      
                <div class="box-footer" style="padding-left:46%;">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="<?=base_url('User/viewProfile')?>" class="btn btn-primary">Cancel</a>
                </div>        
              </div>        
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>