<?php
//$settings = getSettings();
$userData = $this->session->userdata['user'];
?>
<aside class="main-sidebar">
    <section class="sidebar" >
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= isset($userData->profile_image) ? base_url($userData->profile_image) : base_url("assets/images/user_avatar.jpg") ?>" onerror="this.src='<?= base_url("assets/images/user_avatar.jpg") ?>'" class="user-image left-sid" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $userData->display_name; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <ul class="sidebar-menu">
            <li>
                <a  href="<?= base_url('dashboard') ?>">
                    <i class="fa fa-dashboard" aria-hidden="true"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <?php if ($this->session->userdata['user_type'] == 1) { ?>
                <li class="treeview">

                    <a href="#">
                        <i class="fa fa-user" aria-hidden="true"></i>
                        <span>Shopper Management</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= base_url('shopper/addshopper') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                Add Shopper
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('shopper/viewshopper') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                View Shopper
                            </a>
                        </li>

                    </ul>
                </li>    
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-users" aria-hidden="true"></i>
                        <span>Customer Management</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= base_url('customer/addcustomeruser') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                Add Customer
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('customer/listcustomerusers') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                View Customers
                            </a>
                        </li>

                    </ul>
                </li>
            <?php } ?>

            <li class="treeview">
                <a href="<?= base_url('orders/listorders') ?>">
                    <i class="fa fa-book" aria-hidden="true"></i>
                    <span>Order Management</span>
                </a>
            </li>

            <?php if ($this->session->userdata['user_type'] == 1) { ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-tasks" aria-hidden="true"></i>
                        <span>Category</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= base_url('category/addcategory') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                Add New Category
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('category/listcategory') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                View All Categories
                            </a>
                        </li>

                    </ul>
                </li>    
            <?php } ?>

            <?php if ($this->session->userdata['user_type'] == 1) { ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-indent" aria-hidden="true"></i>
                        <span>Sub Category</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= base_url('sub_category/addsubcategory') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                Add New Subcategory
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('sub_category/listsubcat') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                View All Subcategory
                            </a>
                        </li>

                    </ul>
                </li>    
            <?php } ?>


            <li class="treeview">
                <a href="#">
                    <i class="fa fa-laptop" aria-hidden="true"></i>
                    <span>Store Product</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= base_url('product/addproduct') ?>">
                            <i class="fa fa-circle-o text-aqua"></i>
                            Add New Product
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('store/listStores') ?>">
                            <i class="fa fa-circle-o text-aqua"></i>
                            View All Stores
                        </a>
                    </li>
                </ul>
            </li>
            <?php if ($this->session->userdata['user_type'] == 1) { ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-codepen" aria-hidden="true"></i>
                        <span>Promocode</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= base_url('promocode/addpromocode') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                Add New Promocode
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('promocode/listpromocode') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                View All Promocode
                            </a>
                        </li>

                    </ul>
                </li>
            <?php } ?>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-laptop" aria-hidden="true"></i>
                    <span>Product Management</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= base_url('product/addproduct') ?>">
                            <i class="fa fa-circle-o text-aqua"></i>
                            Add New Product
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('product/viewproducts') ?>">
                            <i class="fa fa-circle-o text-aqua"></i>
                            View All Product
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tags" aria-hidden="true"></i>
                    <span>Advertisements</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?= base_url('advertisement/addadvertisement') ?>">
                            <i class="fa fa-circle-o text-aqua"></i>
                            Add New Advertisement
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('advertisement') ?>">
                            <i class="fa fa-circle-o text-aqua"></i>
                            View All Advertisement
                        </a>
                    </li>
                </ul>
            </li>
            <?php if ($this->session->userdata['user_type'] == 1) { ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                        <span>Store Management</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= base_url('stores/addnewstore') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                Add New Store
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('stores/liststores') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                View All Stores
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-globe" aria-hidden="true"></i>
                        <span>City Management</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="<?= base_url('city/addnewcity') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                Add New City
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('city/listcities') ?>">
                                <i class="fa fa-circle-o text-aqua"></i>
                                View All City
                            </a>
                        </li>
                    </ul>   
                </li>   
            <?php } ?>

            <?php if ($this->session->userdata['user_type'] == 1) { ?>
                <li class="treeview">
                    <a href="<?= base_url('cmsdata') ?>">
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <span>CMS Data</span>
                    </a>
                </li>    
            <?php } ?>
        </ul>

    </section>
</aside>

