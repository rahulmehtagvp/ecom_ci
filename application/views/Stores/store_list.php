 <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Store | Store List</title>
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
          <div class="col-md-6"><h3 class="box-title">Stores List</h3></div>
          <div class="col-md-6" align="right">
            <a class="btn btn-sm btn-primary" href="<?= base_url('Stores/addNewStore')?>">Add New Store</a>
            <a class="btn btn-sm btn-primary" href="<?= base_url() ?>">Back</a>
          </div>
        </div>
        <?php if($this->session->userdata['user_type'] == 1 && !empty($storedata)){ ?>
            <div class="box-body">
              <form id="chooseMechForm" role="form" action="<?=base_url('stores/liststores')?>" 
                method="post" class="validate" data-parsley-validate="" enctype="multipart/form-data">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Choose a store</label>
                    <select name="store_id" class="form-control required" data-parsley-trigger="change" onchange="changeMechanic()" dmClick="0" required>
                    <?php if($this->session->userdata['user_type'] == 1){?>
                      <option>View All</option>
                      <?php }else{ ?>
                        <option selected disabled>Select store</option>
                      <?php }
                        if(!empty($storedata)){
                          foreach ($storedata as $shopp) {
                            $chkFlg = ($store_id == $shopp->store_id)?'selected':'';
                            echo '<option value="'.encode_param($shopp->store_id).'" '.$chkFlg.'>
                                  '.$shopp->store_name.
                                '</option>';
                          } 
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </form>
            </div>
          <?php } ?>
          </div>
          </div>
          <?php 
      if($this->session->userdata['user_type'] != 1 || ($this->session->userdata['user_type'] == 1)){ ?>
      <div class="box-body">
        <table id="driverTable" class="table table-bordered table-striped datatable ">
          <thead>
            <tr>
              <th class="hidden">ID</th>
              <th width="13%;">Store Name</th>
              <th width="13%;">Store image</th>  
              <th width="20%;">Description</th> 
              <th width="5%;">Status</th>
              <th width="33%;">Action</th>
            </tr>
          </thead> 
          <tbody>
            <?php
            if(!empty($store_data)){
              foreach($store_data as $store) {
               ?>
               <tr>
                 <th class="hidden"><?= $store->store_id ?></th>
                 <td class="center"><?= $store->store_name ?></th> 
                 <td class="center"><img src="<?= base_url($store->store_image) ?>" class="cpoint" onclick="viewImageModal('Profile Image','<?= base_url($store->store_image) ?>');"
                  onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>';" height="100" width="100" /></th> 
                 <td class="center"><?= $store->description ?></th> 
                 <td class="center"><?= ($store->status == '1')?'Active':'Inactive'?></td>
                 <td class="center">	 
                    <a class="btn btn-sm btn-primary" id="viewStoreDetails" store_id="<?= encode_param($store->store_id) ?>">
                      <i class="fa fa-fw fa-eye"></i>View
                    </a>
                    <a class="btn btn-sm btn-danger" 
                      href="<?= base_url('stores/editstore/'.encode_param($store->store_id)) ?>">
                      <i class="fa fa-fw fa-edit"></i>Edit
                    </a> 
                    <a class="btn btn-sm btn-danger" 
                      href="<?= base_url("stores/changestatus/".encode_param($store->store_id))."/0" ?>" 
                      onClick="return doconfirm()">
                      <i class="fa fa-fw fa-trash"></i>Delete
                    </a>    
                    <?php if($store->status == 1){ ?>
                      <a class="btn btn-sm btn-success" style="background-color:#ac2925" href="<?= base_url("stores/changestatus/".encode_param($store->store_id))."/2" ?>">
                        <i class="fa fa-cog"></i> De-activate
                      </a>
                    <?php } else { ?>
                      <a class="btn btn-sm btn-success" href="<?= base_url("stores/changestatus/".encode_param($store->store_id))."/1" ?>">
                        <i class="fa fa-cog"></i> Activate
                      </a>
                    <?php } ?>
                  </td>
                </tr>
            <?php 
              } 
            }?>
          </tbody>
        </table>
      </div>
    </div>
    <?php } ?>
  </section>
</div>