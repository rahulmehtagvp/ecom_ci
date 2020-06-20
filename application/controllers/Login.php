<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("Asia/Kolkata");
        $this->load->helper(array('form'));
        $this->load->model('login_model');
        $this->load->helper('security');
        if ($this->session->userdata('logged_in')) {
            redirect(base_url('dashboard'));
        }
    }

    public function index() {
        $template['page_title'] = "Login/Login";
        if (isset($_POST)) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_checkUsrLogin');
            if ($this->form_validation->run() == TRUE) {
                redirect(base_url());
            }
        }
        $this->load->view('Login/loginform');
    }

    function checkUsrLogin($password) {
        $username = $this->input->post('username');
        $result = $this->login_model->login($username, md5($password));
        if ($result && !empty($result)) {
            $this->session->set_userdata('id', $result->id);
            $this->session->set_userdata('user', $result);
            $this->session->set_userdata('logged_in', '1');
            $this->session->set_userdata('user_type', $result->user_type);
            $this->session->set_userdata('shopper_data', $result->shopper_data);
            $this->session->set_userdata('shop_data', $result->shop_data);
            return TRUE;
        } else {
            $this->form_validation->set_message('checkUsrLogin', 'Invalid username or password');
            return FALSE;
        }
    }

}

?>