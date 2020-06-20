<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sub category | category_list</title>
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
         $url = (!isset($subcat_id) || empty($subcat_id))?'Sub_category/createsubcategory':'Sub_category/updatesubcategory/'.$subcat_id;
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
              class="validate" data-parsley-validate="" enctype="multipart/form-data">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Sub Category Name</label>
                  <input type="text" class="form-control required" data-parsley-trigger="change"
                  data-parsley-minlength="2"
                  name="subcategory_name" required="" placeholder="Enter sub Category Name" value="<?= (isset($subcategory_data->subcategory_name))?$subcategory_data->subcategory_name:'' ?>">
                  <span class="glyphicon form-control-feedback"></span>
                </div>
                <div class="form-group">
                    <label>Picture</label>
                    <div class="col-md-12">
                      <div class="col-md-3">
                        <img id="profile_image" src="<?= (isset($subcategory_data) && isset($subcategory_data->image))?base_url($subcategory_data->image):'' ?>" onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>'" height="75" width="75" />
                      </div>
                      <div class="col-md-9" style="padding-top: 25px;">
                        <input name="image" type="file" accept="image/*" 
                        class="<?= (isset($subcat_id) && !empty($subcat_id))?'':'required' ?>" 
                        onchange="setImg(this,'image')" />
                      </div>
                    </div>
              
              <div class="col-md-6">
                <?php if(!empty($category_data)){ ?>
                  <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control" placeholder="Select Store">
                      <option selected value="0">Choose a category</option>
                      <?php 
                        foreach ($category_data as $category) {
                          $select = (isset($user_data->categry_id) && $user_data->category_id == $category->category_id)?'selected':'';
                          echo '<option '.$select.' value="'.$category->category_id.'">'.
                                  $category->category_name.
                               '</option>';
                        }
                      ?>
                    </select> 
                  </div>
                <?php } ?> 
              </div>
              <div class="col-md-12">      
                <div class="box-footer textCenterAlign">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="<?= base_url('Sub_category/listsubcat') ?>" class="btn btn-primary">Cancel</a>
                </div>        
              </div>        
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>