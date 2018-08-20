<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productionmodel extends CI_Model {

    public function insertProductionData($productionData)
    {
        $result = $this->db->insert('production', $productionData);

        return $result;
    }

    public function queryProductionData()
    {
        $this->db->select('
            production.order,
            product.productName,
            operator.operatorName,
            production.batchNumber,
            production.producingDate,
            production.producedPackageNumber,
            order.remainingPackageNumber,
            production.producedWeight,
            order.remainingWeight');
        $this->db->from('production');
        $this->db->join('order', 'production.order = order.orderID');
        $this->db->join('product', 'order.product = product.productID');
        $this->db->join('operator', 'production.operator = operator.operatorID');
        $this->db->where('order.complete =', 0);
        $result = $this->db->get();

        return $result;
    }

    public function queryProductionProducedWeightSumByProductIDData($productID)
    {
        $this->db->select_sum('production.producedWeight');
        $this->db->from('production');
        $this->db->join('order', 'production.order = order.orderID');
        $this->db->where('order.product', $productID);
        $this->db->where('production.producingDate', gmdate("Y-m-d", (time() + (28800))));
        $result = $this->db->get();

        return $result->row_array();
    }

    public function queryProductionProducedWeightSum($quarter)
    {
        $year = date("Y");
        switch($quarter) {
            case 0:
                $yearStart = $year . "-01-01";
                $yearEnd = $year . "-12-31";
                break;
            case 1:
                $yearStart = $year . "-01-01";
                $yearEnd = $year . "-03-31";
                break;
            case 2:
                $yearStart = $year . "-04-01";
                $yearEnd = $year . "-06-31";
                break;
            case 3:
                $yearStart = $year . "-07-01";
                $yearEnd = $year . "-09-31";
                break;
            case 4:
                $yearStart = $year . "-10-01";
                $yearEnd = $year . "-12-31";
                break;
        }
        $this->db->select_sum('producedWeight');
        $this->db->from('production');
        $this->db->where('producingDate >=', $yearStart);
        $this->db->where('producingDate <=', $yearEnd);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function deleteProductionData($productionData)
    {
        $this->db->where('productionID', $productionData['productionID']);
        $result = $this->db->delete('production');

        return $result;
    }
}
