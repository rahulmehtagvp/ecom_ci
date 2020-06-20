<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Store | store_add</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.min.css') ?>">
    
  </head>
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
        $url = (!isset($store_id) || empty($store_id))?'Store/createstore':'Store/updatestore/'.$store_id;
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
            <form role="form" action="<?= base_url('Store/mapstore') ?>" method="post" 
              class="validate" data-parsley-validate="" enctype="multipart/form-data">
              <input type="hidden" name="store_id" value="<?= decode_param($store_id)?>">
              <div class="col-md-6">
                <div class="form-group">
                <select name="product_id">
                  <?php
                      foreach($store_data as $row){
                        $select = (isset($user_data->product_id) && $user_data->product_id == $row->product_id)?'selected':'';
                          echo '<option '.$select.' value="'.$row->product_id.'">'.
                                  $row->product_name.
                               '</option>'; 
                      }
                  ?>
              </select>
                </div>
                
              <div class="col-md-12">      
                <div class="box-footer textCenterAlign">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="<?= base_url('Store/listStores') ?>" class="btn btn-primary">Cancel</a>
                </div>        
              </div>        
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>