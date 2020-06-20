<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>category | category_list</title>
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
        $url = (!isset($category_id) || empty($category_id))?'category/createcategory':'category/updatecategory/'.$category_id;
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
            <form role="form" action="<?= base_url($url) ?>" method="post" 
                  class="validate" data-parsley-validate="" enctype="multipart/form-data" autocomplete="off">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Category Name</label>
                  <input type="text" class="form-control required" data-parsley-trigger="change"
                  data-parsley-minlength="2"
                  name="category_name" required="" placeholder="Enter Category Name" value="<?= (isset($category_data->category_name))?$category_data->category_name:'' ?>">
                  <span class="glyphicon form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <label>Picture</label>
                    <div class="col-md-12">
                      <div class="col-md-3">
                        <img id="profile_image" src="<?= (isset($category_data) && isset($category_data->category_image))?base_url($category_data->category_image):'' ?>" onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>'" height="75" width="75" />
                      </div>
                      <div class="col-md-9" style="padding-top: 25px;">
                        <input name="category_image" type="file" accept="image/*" 
                        class="<?= (isset($category_id) && !empty($category_id))?'':'required' ?>" 
                        onchange="setImg(this,'category_image')" />
                      </div>
                    </div>
                   
              <div class="col-md-12">      
                <div class="box-footer textCenterAlign">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="<?= base_url('category/listcategory') ?>" class="btn btn-primary">Cancel</a>
                </div>        
              </div>        
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>