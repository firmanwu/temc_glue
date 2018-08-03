<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productmodel extends CI_Model {

    public function insertProductData($productData)
    {
        $result = $this->db->insert('product', $productData);

        return $result;
    }

    public function queryProductData()
    {
        $result = $this->db->get('product');

        return $result;
    }

    public function queryProductByID($productID)
    {
        $this->db->where('productID', $productID);
        $result = $this->db->get('product');

        return $result->row_array();
    }
}
