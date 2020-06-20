<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> Product | Add Product</title>
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
          $redirectUrl = (isset($product_id) && !empty($product_id))
                            ?'Product/updateproduct/'.$product_id
                            :'Product/createproduct';
        
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
            <h3 class="box-title">Product Details</h3>
          </div>
          <div class="box-body">
              <form id="createProductForm" role="form" action="<?= base_url($redirectUrl) ?>" method="post" class="validate" data-parsley-validate="" enctype="multipart/form-data" autocomplete="off">
              <div class="col-md-12">
                <div class="col-md-6">
                  <div class="form-group has-feedback">
                  <label>Product Name</label>
                  <input type="text" class="form-control required" data-parsley-trigger="change" data-parsley-minlength="2" name="product_name" required="" 
                  placeholder="Enter Product Name" value="<?= (isset($product_data->product_name))?$product_data->product_name:'' ?>">
                  <span class="glyphicon form-control-feedback"></span>
                </div>

                  <div class="form-group">
                  <label>Product Price</label>
                  <input type="text" class="form-control required" data-parsley-trigger="change" data-parsley-minlength="2" name="product_price" required="" 
                  placeholder="Enter Product Price" value="<?= (isset($product_data->product_price))?$product_data->product_price:'' ?>">
                  <span class="glyphicon form-control-feedback"></span>
                </div>
              </div>

                <div class="col-md-6">
                <?php if(!empty($store_data)){ ?>
                  <div class="form-group">
                    <label>Store</label>
                    <select name="store_id" class="form-control" placeholder="Select Store">
                      <option selected value="0">Choose a Store</option>
                      <?php 
                        foreach ($store_data as $store) {
                          $select = (isset($user_data->city_id) && $user_data->store_id == $store->store_id)?'selected':'';
                          echo '<option '.$select.' value="'.$store->store_id.'">'.
                                  $store->store_name.
                               '</option>';
                        }
                      ?>
                    </select> 
                  </div>
                <?php } ?> 
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
                

                
                <div class="form-group">
                    <label>Product Picture</label>
                    <div class="col-md-12">
                      <div class="col-md-3">
                        <img id="profile_image" src="<?= (isset($product_data) && isset($product_data->product_image))?base_url($product_data->product_image):'' ?>" onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>'" height="75" width="75" />
                      </div>
                      <div class="col-md-9" style="padding-top: 25px;">
                        <input name="product_image" type="file" accept="image/*" 
                        class="<?= (isset($product_id) && !empty($product_id))?'':'required' ?>" 
                        onchange="setImg(this,'product_image')" />
                      </div>

                      <div class="col-md-12">          
              
                  <button id="createProductSubmit" type="submit" class="btn btn-primary">Submit</button>
                  <a href="<?=base_url('product/viewproducts')?>" class="btn btn-primary">Cancel</a>
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