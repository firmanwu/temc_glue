<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productiontargetmodel extends CI_Model {

    public function insertProductionTargetData($productionTargetData)
    {
        $result = $this->db->insert('productiontarget', $productionTargetData);

        return $result;
    }

    public function updateProductionTargetData($productionTargetData)
    {
        $this->db->where('targetYear', $productionTargetData['targetYear']);
        $result = $this->db->update('productiontarget', $productionTargetData);

        return $result;
    }

    public function queryProductionTargetByYear()
    {
        $year = date("Y");
        $this->db->where('targetYear =', $year);
        $result = $this->db->get('productiontarget');

        return $result->row_array();
    }

    public function queryProductionTargetByInputYear($year)
    {
        $this->db->where('targetYear =', $year);
        $result = $this->db->get('productiontarget');

        return $result->row_array();
    }

    public function queryProductionTargetProducedWeightSumByProductIDData($productID)
    {
        $this->db->select_sum('productiontarget.producedWeight');
        $this->db->from('productiontarget');
        $this->db->join('order', 'productiontarget.order = order.orderID');
        $this->db->where('order.product', $productID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function deleteProductionTargetData($productionTargetData)
    {
        $this->db->where('productionTargetID', $productionTargetData['productionTargetID']);
        $result = $this->db->delete('productionTarget');

        return $result;
    }
}
