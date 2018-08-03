<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome');
            return;
        }*/
    }

    public function addCustomerView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '新增客戶'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addCustomerView');
        $this->load->view('footer');
    }

    public function addCustomer()
    {
        $this->load->model('customermodel');

        $customerData['customerName'] = $this->input->post('customerName');

        $result = $this->customermodel->insertCustomerData($customerData);
        if (true == $result) {
            echo json_encode($customerData);
        }
    }

    public function queryCustomerView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '查詢客戶'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryCustomerView');
        $this->load->view('footer');
    }

    public function queryCustomer()
    {
        $this->load->model('customermodel');

        $query = $this->customermodel->queryCustomerData();
        echo json_encode($query->result_array());
    }
}
