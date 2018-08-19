<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Production extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addProductionView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增生產數據'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addProductionView');
        $this->load->view('footer');
    }

    public function addProduction()
    {
        $this->load->model('ordermodel');
        $this->load->model('productionmodel');
        $this->load->model('operatormodel');

        $productionData['order'] = $this->input->post('order');
        $productionData['operator'] = $this->input->post('operator');
        $productionData['batchNumber'] = $this->input->post('batchNumber');
        $productionData['producingDate'] = $this->input->post('producingDate');
        $productionData['producedPackageNumber'] = $this->input->post('producedPackageNumber');

        $orderData = $this->ordermodel->queryOrderForProductionByID($productionData['order']);

        $productionData['producedWeight'] = $productionData['producedPackageNumber'] * $orderData['unitWeight'];

        $result = $this->productionmodel->insertProductionData($productionData);

        // Minus prodcued product
        $this->ordermodel->updateOrderWeightPackageData($productionData);

        // Prepare the data for UI display
        $operatorData = $this->operatormodel->queryOperatorByID($productionData['operator']);
        $productionData['operator'] = $operatorData['operatorName'];

        $productionData['product'] = $orderData['productName'];

        if (true == $result) {
            echo json_encode($productionData);
        }
    }

    public function queryProductionView()
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
        $this->load->view('queryProductionView');
        $this->load->view('footer');
    }

    public function queryProduction()
    {
        $this->load->model('productionmodel');
        $query = $this->productionmodel->queryProductionData();

        echo json_encode($query->result_array());
    }

    public function queryProductionStatus()
    {
        $this->load->model('productiontargetmodel');
        $this->load->model('productionmodel');

        $productionStatus = array();
        $targetData = $this->productiontargetmodel->queryProductionTargetByYear();
        $yearlyData['yearlyTotalWeight'] = $targetData['yearlyTotalWeight'];
        $yearlyProduction = $this->productionmodel->queryProductionProducedWeightSum(0);
        if (isset($yearlyProduction['producedWeight'])) {
            $yearlyData['yearlyProducedWeight'] = $yearlyProduction['producedWeight'];
            $yearlyData['achievedRate'] = $yearlyData['yearlyProducedWeight'] / $yearlyData['yearlyTotalWeight'];
        }
        else {
            $yearlyData['yearlyProducedWeight'] = 0;
            $yearlyData['achievedRate'] = 0;
        }
        $productionStatus[] = $yearlyData;

        $q1Data['quarterOneTotalWeight'] = $targetData['quarterOneTotalWeight'];
        $q1Production = $this->productionmodel->queryProductionProducedWeightSum(1);
        if (isset($q1Production['producedWeight'])) {
            $q1Data['q1ProducedWeight'] = $q1Production['producedWeight'];
            $q1Data['achievedRate'] = $q1Data['q1ProducedWeight'] / $q1Data['quarterOneTotalWeight'];
        }
        else {
            $q1Data['q1ProducedWeight'] = 0;
            $q1Data['achievedRate'] = 0;
        }
        $productionStatus[] = $q1Data;

        $q2Data['quarterTwoTotalWeight'] = $targetData['quarterTwoTotalWeight'];
        $q2Production = $this->productionmodel->queryProductionProducedWeightSum(2);
        if (isset($q2Production['producedWeight'])) {
            $q2Data['q2ProducedWeight'] = $q2Production['producedWeight'];
            $q2Data['achievedRate'] = $q2Data['q2ProducedWeight'] / $q2Data['quarterTwoTotalWeight'];
        }
        else {
            $q2Data['q2ProducedWeight'] = 0;
            $q2Data['achievedRate'] = 0;
        }
        $productionStatus[] = $q2Data;

        $q3Data['quarterThreeTotalWeight'] = $targetData['quarterThreeTotalWeight'];
        $q3Production = $this->productionmodel->queryProductionProducedWeightSum(3);
        if (isset($q3Production['producedWeight'])) {
            $q3Data['q3ProducedWeight'] = $q3Production['producedWeight'];
            $q3Data['achievedRate'] = $q3Data['q3ProducedWeight'] / $q3Data['quarterThreeTotalWeight'];
        }
        else {
            $q3Data['q3ProducedWeight'] = 0;
            $q3Data['achievedRate'] = 0;
        }
        $productionStatus[] = $q3Data;

        $q4Data['quarterFourTotalWeight'] = $targetData['quarterFourTotalWeight'];
        $q4Production = $this->productionmodel->queryProductionProducedWeightSum(4);
        if (isset($q4Production['producedWeight'])) {
            $q4Data['q4ProducedWeight'] = $q4Production['producedWeight'];
            $q4Data['achievedRate'] = $q4Data['q4ProducedWeight'] / $q4Data['quarterFourTotalWeight'];
        }
        else {
            $q4Data['q4ProducedWeight'] = 0;
            $q4Data['achievedRate'] = 0;
        }
        $productionStatus[] = $q4Data;

        echo json_encode($productionStatus);
    }

    public function downloadProductionExcel($filterByDate = 0)
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
        $fileName = 'ProductionSN';
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
        $fileName = 'ProductionSN';
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

    public function deleteProduction($productionID)
    {
        $this->load->model('productionmodel');

        $productionData['productionID'] = $productionID;
        $result = $this->productionmodel->deleteProductionData($productionData);
    }
}
