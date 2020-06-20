<?php
  //$settings = getSettings();
  $userData = $this->session->userdata['user'];
?>

<header class="main-header" style="background-color:#3c8dbc;">
  <a href="<?= base_url() ?>" class="logo">
    <span class="logo-mini">
      <img id="fav_icon" src="<?= isset($userData->profile_image) ? base_url($userData->profile_image) : base_url("assets/images/no_image.png"); ?>" 
      onerror="this.src='<?= base_url("assets/images/no_image.png") ?>';" height="50" width="50" />
    </span>
    <span class="hidden-xs"></span>
  </a>
  <nav class="navbar navbar-static-top" role="navigation">
    <a href="#" class="sidebar-toggle" style="color:white;" data-toggle="offcanvas" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?= isset($userData->profile_image) ? base_url($userData->profile_image) : base_url("assets/images/user_avatar.jpg"); ?>" 
              onerror="this.src='<?= base_url("assets/images/user_avatar.jpg") ?>';" 
              class="user-image" alt="User Image">
            <span class="hidden-xs" style="color:white"><?= isset($userData->display_name) ? $userData->display_name : ""; ?></span>
          </a>
          <ul class="dropdown-menu">
            <li class="user-header">
              <img src="<?= isset($userData->profile_image) ? base_url($userData->profile_image) : base_url("assets/images/user_avatar.jpg"); ?>" 
               onerror="this.src='<?=base_url("assets/images/user_avatar.jpg")?>';" 
               class="img-circle" alt="User Image">
            </li>
            <li class="user-footer">
              <div class="pull-left">
                <a href="<?=base_url('User/viewProfile')?>" class="btn btn-default btn-flat">Profile</a>
               </div>
               <div class="pull-right">
                <a href="<?= base_url('logout') ?>" class="btn btn-default btn-flat">Sign out</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
<div id="errModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="modal_header_msg"></h4>
      </div>
      <div class="modal-body">
        <p id="modal_body_msg"></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
