<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operator extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome');
            return;
        }*/
    }

    public function addOperatorView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '新增生產人員'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addOperatorView');
        $this->load->view('footer');
    }

    public function addOperator()
    {
        $this->load->model('operatormodel');

        $operatorData['operatorName'] = $this->input->post('operatorName');

        $result = $this->operatormodel->insertOperatorData($operatorData);
        if (true == $result) {
            echo json_encode($operatorData);
        }
    }

    public function queryOperatorView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '查詢生產人員'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryOperatorView');
        $this->load->view('footer');
    }

    public function queryOperator()
    {
        $this->load->model('operatormodel');

        $query = $this->operatormodel->queryOperatorData();
        echo json_encode($query->result_array());
    }
}
