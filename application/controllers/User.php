<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->model('User_model');
        $this->load->model('Dashboard_model');
        if (!$this->session->userdata('logged_in')) {
            redirect(base_url('login'));
        }
    }

    public function viewProfile() {
        if (!isset($this->session->userdata['user']) || empty($this->session->userdata['user'])) {
            redirect(base_url());
        }
        $template['shop_data'] = '';
        //$shopper_data=$this->session->userdata['shopper_data'];
        if ($this->session->userdata('user_type') == 2 && isset($this->session->userdata['shopper_data']) && !empty($this->session->userdata['shopper_data'])) {
            $this->load->model('Stores_model');
            $shopper_data = $this->session->userdata['shopper_data'];
            //print_r($shopper_data);exit;
            if (!empty($shopper_data->shopper_id)) {
                $template['shop_data'] = $this->Stores_model->getStores($shopper_data->shopper_id);
            }
        }
        $template['page'] = 'User/viewProfile';
        $template['menu'] = 'User';
        $template['smenu'] = 'View Profile';
        $template['pTitle'] = "User Profile";
        $template['pDescription'] = "Edit or View Profile";
        $this->load->view('template', $template);
    }

    public function editProfile() {
        $this->load->model('Stores_model');
        $user_id = $this->session->userdata('id');
        $user_type = $this->session->userdata('user_type');
        $template['page'] = 'User/editProfile';
        $template['menu'] = "Profile";
        $template['smenu'] = "Edit Profile";
        $template['pTitle'] = "Edit Profile";
        $template['pDescription'] = "Edit User Profile";
        $shopper_id = ($this->session->userdata['user_type'] == 2) ? $this->session->userdata['id'] : '';
        $template['shop_data'] = $this->Stores_model->getStores($shopper_id);
        //print_r($template1);exit;
        $template['user_data'] = $this->User_model->getUserData();
        if (empty($template['user_data'])) {
            redirect(base_url());
        }
        $this->load->view('template', $template);
    }

    public function updateUser() {

        $user_id = $this->session->userdata('id');
        $user_type = $this->session->userdata('user_type');
        $flashMsg = array('message' => 'Something went wrong, please try again..!', 'class' => 'error');
        if (empty($user_id)) {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('User/editProfile'));
        }
        if (isset($_FILES['profile_image']) && !empty($_FILES['profile_image'])) {
            $config = set_upload_service("assets/uploads/services");
            $this->load->library('upload');
            $new_name = time() . "_" . $_FILES['profile_image']['name'];
            $config['file_name'] = $new_name;
            $this->upload->initialize($config);
            if ($this->upload->do_upload('profile_image')) {
                $upload_data = $this->upload->data();
                $_POST['profile_image'] = $config['upload_path'] . "/" . $upload_data['file_name'];
            }
        }

        if ((isset($_POST['password']) || isset($_POST['cPassword'])) &&
                (!empty($_POST['password']) || !empty($_POST['cPassword']))) {
            if ($_POST['password'] != $_POST['cPassword']) {
                $flashMsg = array('message' => 'Re-enter Password..!', 'class' => 'error');
                $this->session->set_flashdata('message', $flashMsg);
                redirect(base_url('User/editProfile'));
            }
            $password = $_POST['password'];
            unset($_POST['password']);
            unset($_POST['cPassword']);
            $_POST['password'] = md5($password);
        } else {
            unset($_POST['password']);
            unset($_POST['cPassword']);
        }
        if (!isset($_POST['display_name']) || empty($_POST['display_name'])) {
            $flashMsg = array('message' => 'Provide a valid Display Name..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('User/editProfile'));
        } else if (!isset($_POST['username']) || empty($_POST['username'])) {
            $flashMsg = array('message' => 'Provide a valid Username..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('User/editProfile'));
        }
        if ($user_type == 2) {
            if (!isset($_POST['display_name']) || empty($_POST['display_name'])) {
                $flashMsg = array('message' => 'Provide a display Name..!', 'class' => 'error');
                $this->session->set_flashdata('message', $flashMsg);
                redirect(base_url('User/editProfile'));
            } else if (!isset($_POST['phone']) || empty($_POST['phone'])) {
                $flashMsg = array('message' => 'Provide a valid Phone Number..!', 'class' => 'error');
                $this->session->set_flashdata('message', $flashMsg);
                redirect(base_url('User/editProfile'));
            } else if (!isset($_POST['email_id']) || empty($_POST['email_id'])) {
                $flashMsg = array('message' => 'Provide a valid Email ID..!', 'class' => 'error');
                $this->session->set_flashdata('message', $flashMsg);
                redirect(base_url('User/editProfile'));
            }
        }
        $status = $this->User_model->updateUser($user_id, $user_type, $_POST);
        if ($status == 1) {
            $flashMsg = array('message' => 'Successfully Updated User Details..!', 'class' => 'success');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('User/viewProfile'));
        } else if ($status == 2) {
            $flashMsg = array('message' => 'Email ID alrady exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('User/editProfile'));
        } else if ($status == 3) {
            $flashMsg = array('message' => 'Phone Number alrady exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('User/editProfile'));
        } else if ($status == 4) {
            $flashMsg = array('message' => 'User Name alrady exist..!', 'class' => 'error');
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('User/editProfile'));
        } else {
            $this->session->set_flashdata('message', $flashMsg);
            redirect(base_url('User/editProfile'));
        }
    }

}

?>