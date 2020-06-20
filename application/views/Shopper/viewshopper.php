<div class="content-wrapper" >
  <section class="content-header">
    <h1><?= $pTitle ?><small><?= $pDescription ?></small>
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
        <?php if($this->session->flashdata('message')) { 
          $flashdata = $this->session->flashdata('message'); ?>
          <div class="alert alert-<?= $flashdata['class'] ?>">
             <button class="close" data-dismiss="alert" type="button">×</button>
             <?= $flashdata['message'] ?>
          </div>
        <?php } ?>
      </div>
      <div class="col-xs-12">
        <div class="box box-warning"> 
          <div class="box-header with-border">
            <div class="col-md-6"><h3 class="box-title">Shopper List</h3></div>
            <div class="col-md-6" align="right">

              <a class="btn btn-sm btn-primary" href="<?= base_url('shopper/addshopper')?>">Add New Shopper</a>
              <a class="btn btn-sm btn-primary" href="<?= base_url() ?>">Back</a>
            </div>
          </div>
          <div class="box-body">
            <table id="mechanicUsers" class="table table-bordered table-striped datatable ">
              <thead>
                <tr>
                  <th class="hidden">ID</th>
                  <th width="150px;">Shopper Name</th>
                  <th width="150px;">Shopper email</th>
                  <th width="150px;">Shopper image</th>
                  <th width="150px;">Store name</th>
                  <th width="100px;">Status</th>
                  <th width="500px;">Action</th>
               </tr>
              </thead> 
              <tbody>
                <?php
                if(!empty($user_data)){
                  foreach($user_data as $user) { //print_r($user);exit; ?>
                    <tr>
                      <th class="hidden"><?= $user->shopper_id ?></th>
                      <th class="center"><?= $user->display_name ?></th>
                      <th class="center"><?= $user->email ?></th>
                      <th class="center"> <img src="<?= base_url($user->shopper_image) ?>" class="cpoint" onclick="viewImageModal('Profile Image','<?= base_url($user->shopper_image) ?>');"
                  onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>';" height="100" width="100" /> </th>
                      <th class="center"><?= $user->store_name ?></th>
                      <th class="center"><?= ($user->admin_status == 1)?'Active':'De-activate' ?></th>
                      <td class="center">   
                      <a class="btn btn-sm btn-primary" id="viewShopperDetails" shopper_id="<?= encode_param($user->shopper_id) ?>">
                          <i class="fa fa-fw fa-eye"></i>View
                        </a>
                        <a class="btn btn-sm btn-danger" 
                            href="<?= base_url('shopper/editshopper/'.encode_param($user->shopper_id)) ?>">
                          <i class="fa fa-fw fa-edit"></i>Edit
                        </a> 
                        <a class="btn btn-sm btn-danger" 
                            href="<?= base_url("shopper/changestatus/".encode_param($user->shopper_id))."/2" ?>" 
                            onClick="return doconfirm()">
                          <i class="fa fa-fw fa-trash"></i>Delete
                        </a>    
                        <?php if($user->admin_status == 1){ ?>
                          <a class="btn btn-sm btn-success" style="background-color:#ac2925" href="<?= base_url("shopper/changestatus/".encode_param($user->shopper_id))."/0" ?>">
                            <i class="fa fa-cog"></i> De-activate
                          </a>
                        <?php } else { ?>
                          <a class="btn btn-sm btn-success" href="<?= base_url("shopper/changestatus/".encode_param($user->shopper_id))."/1" ?>">
                            <i class="fa fa-cog"></i> Activate
                          </a>
                        <?php } ?>
                      </td>
                    </tr>
                <?php } } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
