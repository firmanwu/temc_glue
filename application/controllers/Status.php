<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Status extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome');
            return;
        }*/
    }

    public function displayStatusView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '狀態顯示'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('displayStatusView');
        $this->load->view('footer');
    }

    public function addProduct()
    {
        $this->load->model('productmodel');

        $productData['productName'] = $this->input->post('productName');

        $result = $this->productmodel->insertProductData($productData);
        if (true == $result) {
            echo json_encode($productData);
        }
    }

    public function queryProductView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'd',
            'title' => '查詢產品'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryProductView');
        $this->load->view('footer');
    }

    public function queryProduct()
    {
        $this->load->model('productmodel');

        $query = $this->productmodel->queryProductData();
        echo json_encode($query->result_array());
    }

    public function queryMaterialNameWithID()
    {
        $this->load->model('materialmodel');

        $queryData = 'SELECT materialID, materialName FROM material';
        $query = $this->materialmodel->queryMaterialSpecificColumn($queryData, false);
        echo json_encode($query->result_array());
    }

    public function deleteMaterial($materialID)
    {
        $this->load->model('materialmodel');

        $materialData['materialID'] = $materialID;
        $result = $this->materialmodel->deleteMaterialData($materialData);
    }
}
