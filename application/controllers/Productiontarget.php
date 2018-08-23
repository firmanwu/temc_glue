<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productiontarget extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addProductionTargetView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增年度季度生產目標'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addProductionTargetView');
        $this->load->view('footer');
    }

    public function addProductionTarget()
    {
        $this->load->model('productiontargetmodel');

        $productionTargetData['targetYear'] = $this->input->post('targetYear');
        $productionTargetData['quarterOneTotalWeight'] = $this->input->post('quarterOneTotalWeight');
        $productionTargetData['quarterTwoTotalWeight'] = $this->input->post('quarterTwoTotalWeight');
        $productionTargetData['quarterThreeTotalWeight'] = $this->input->post('quarterThreeTotalWeight');
        $productionTargetData['quarterFourTotalWeight'] = $this->input->post('quarterFourTotalWeight');
        $productionTargetData['yearlyTotalWeight'] = $productionTargetData['quarterOneTotalWeight'] + $productionTargetData['quarterTwoTotalWeight'] + $productionTargetData['quarterThreeTotalWeight'] + $productionTargetData['quarterFourTotalWeight'];

        $queryTarget = $this->productiontargetmodel->queryProductionTargetByInputYear($productionTargetData['targetYear']);
        if (isset($queryTarget['targetYear'])) {
            $result = $this->productiontargetmodel->updateProductionTargetData($productionTargetData);
        }
        else {
            $result = $this->productiontargetmodel->insertProductionTargetData($productionTargetData);
        }

        if (true == $result) {
            echo json_encode($productionTargetData);
        }
    }

    public function queryProductionTargetView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢生產數據'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryProductionTargetView');
        $this->load->view('footer');
    }

    public function queryProductionTarget()
    {
        $this->load->model('productiontargetmodel');
        $query = $this->productiontargetmodel->queryProductionTargetData();

        echo json_encode($query->result_array());
    }

    public function downloadProductionTargetExcel($filterByDate = 0)
    {
        $obj = $this->input->post('excelBuildData');
        $db_data_test = self::getDBInfo($obj['model'],$obj['queryfunction']);
        $header = $obj['header'];
        $this->load->helper('print_helper');
        $response = only_print_excel($db_data_test, $header);
        die(json_encode($response));
    }

    public function getDBInfo($model, $queryFunction){
        $model_local = $model;
        $query_function = $queryFunction;

        $this->load->model($model_local);

        $query = $this->$model_local->$query_function();
        return $query->result_array();
    }

    public function getSerialNumber()
    {
        $this->load->helper('file');
        $this->load->helper('date');
        $this->load->helper('string');

        $dateString = '%Y%m%d';
        $time = time();
        $currentDate = mdate($dateString, $time);
        $fileName = 'ProductionTargetSN';
        if (TRUE == file_exists($fileName)) {
            $currentSerialNumber = read_file($fileName);
            if (FALSE == strstr($currentSerialNumber, $currentDate)) {
                $newSerialNumber = $currentDate . "001";
                write_file($fileName, $newSerialNumber);

                echo $newSerialNumber;
            }
            else {
                echo $currentSerialNumber;
            }
        }
        else {
            $newSerialNumber = $currentDate . "001";
            write_file($fileName, $newSerialNumber);

            echo $newSerialNumber;
        }
    }

    public function increaseSerialNumber()
    {
        $this->load->helper('file');
        $this->load->helper('date');

        $dateString = '%Y%m%d';
        $time = time();
        $currentDate = mdate($dateString, $time);
        $fileName = 'ProductionTargetSN';
        if (TRUE == file_exists($fileName)) {
            $currentSerialNumber = read_file($fileName);
            if (FALSE == strstr($currentSerialNumber, $currentDate)) {
                $newSerialNumber = $currentDate . "001";
            }
            else {
                $number = str_replace($currentDate, '', $currentSerialNumber);
                $number = (int)$number + 1;
                $length = strlen($number);
                if (3 >= $length) {
                    for($i = 0; $i < (3 - $length); $i++)
                    {
                        $number = '0' . $number;
                    }
                }
                else {
                    $number = '0' . $number;
                }
                $newSerialNumber = $currentDate . $number;
            }
            write_file($fileName, $newSerialNumber);
        }
        else {
            $newSerialNumber = $currentDate . "001";
            write_file($fileName, $newSerialNumber);
        }
    }

    public function deleteProductionTarget($productionTargetID)
    {
        $this->load->model('productiontargetmodel');

        $productionTargetData['productionTargetID'] = $productionTargetID;
        $result = $this->productiontargetmodel->deleteProductionTargetData($productionTargetData);
    }
}
