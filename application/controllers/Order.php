<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller {

    public function index()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/
    }

    public function addOrderView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '新增生產工單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('addOrderView');
        $this->load->view('footer');
    }

    public function addOrder()
    {
        $this->load->model('ordermodel');
        $this->load->model('packagingmodel');
        $this->load->model('productmodel');
        $this->load->model('customermodel');

        $orderData['orderID'] = $this->input->post('orderID');
        $orderData['customerOrderID'] = $this->input->post('customerOrderID');
        $orderData['deadline'] = $this->input->post('deadline');
        $orderData['sales'] = $this->input->post('sales');
        $orderData['product'] = $this->input->post('product');
        $orderData['customer'] = $this->input->post('customer');
        $orderData['packaging'] = $this->input->post('packaging');
        $orderData['expectingPackageNumber'] = $this->input->post('expectingPackageNumber');

        $packagingData = $this->packagingmodel->queryPackagingByID($orderData['packaging']);

        $orderData['expectingWeight'] = $orderData['expectingPackageNumber'] * $packagingData['unitWeight'];

        $orderData['remainingPackageNumber'] = $orderData['expectingPackageNumber'];
        $orderData['remainingWeight'] = $orderData['expectingWeight'];

        $result = $this->ordermodel->insertOrderData($orderData);

        // Prepare the data for UI display
        // Get product name by product ID
        $productData = $this->productmodel->queryProductByID($orderData['product']);
        $orderData['product'] = $productData['productName'];

        // Replace packaging
        $orderData['packaging'] = $packagingData['packaging'];

        // Get customer name by customer ID
        $customerData = $this->customermodel->queryCustomerByID($orderData['customer']);
        $orderData['customer'] = $customerData['customerName'];

        if (true == $result) {
            echo json_encode($orderData);
        }
    }

    public function queryOrderView()
    {
        /*
        if (false == isset($_SESSION['userID'])) {
            redirect('welcome/iframeContent');
            return;
        }*/

        $data = array(
            'theme' => 'b',
            'title' => '查詢生產工單'
        );

        $this->load->view('header');
        $this->load->view('panel', $data);
        $this->load->view('queryOrderView');
        $this->load->view('footer');
    }

    public function downloadOrderExcel($filterByDate = 0)
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

    public function queryOrderAll()
    {
        $this->load->model('ordermodel');
        $query = $this->ordermodel->queryOrderAllData();

        echo json_encode($query->result_array());
    }

    public function queryOrderByID($orderID)
    {
        $this->load->model('ordermodel');
        $query = $this->ordermodel->queryOrderByIDData($orderID);

        echo json_encode($query->result_array());
    }

    public function queryOrderID()
    {
        $this->load->model('ordermodel');

        $query = $this->ordermodel->queryOrderIDData();
        echo json_encode($query->result_array());
    }

    public function queryOrderStatus()
    {
        $this->load->model('productmodel');
        $this->load->model('ordermodel');
        $this->load->model('productionmodel');

        $query = $this->productmodel->queryProductData();
        $productData = $query->result_array();

        $status = array();
        foreach($productData as $row)
        {
            $listData['productName'] = $row['productName'];

            $queryOrder = $this->ordermodel->queryOrderExpectingRemainingWeightSumByProductIDData($row['productID']);
            $queryProduction = $this->productionmodel->queryProductionProducedWeightSumByProductIDData($row['productID']);

            if (isset($queryOrder['expectingWeight'])) {
                $listData['expectingWeight'] = $queryOrder['expectingWeight'];
            }
            else {
                $listData['expectingWeight'] = 0;
            }

            if (isset($queryProduction['producedWeight'])) {
                $listData['producedWeight'] = $queryProduction['producedWeight'];
            }
            else {
                $listData['producedWeight'] = 0;
            }

            if (isset($queryOrder['remainingWeight'])) {
                $listData['remainingWeight'] = $queryOrder['remainingWeight'];
            }
            else {
                $listData['remainingWeight'] = 0;
            }

            // Add each array into one array
            $status[] = $listData;
        }
        echo json_encode($status);
    }

    public function queryUnitWeightByID($orderID)
    {
        $this->load->model('ordermodel');

        $query = $this->ordermodel->queryUnitWeightByIDData($orderID);
        echo json_encode($query);
    }

    public function getSerialNumber()
    {
        $this->load->helper('file');
        $this->load->helper('date');
        $this->load->helper('string');

        $dateString = '%Y%m%d';
        $time = time();
        $currentDate = mdate($dateString, $time);
        $fileName = 'OrderSN';
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
        $fileName = 'OrderSN';
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

    public function completeOrder($orderID)
    {
        $this->load->model('ordermodel');

        $result = $this->ordermodel->completeOrderData($orderID);
    }

    public function deleteOrder($orderID)
    {
        $this->load->model('ordermodel');

        $result = $this->ordermodel->deleteOrderData($orderID);
    }
}
