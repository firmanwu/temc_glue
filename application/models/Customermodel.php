<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customermodel extends CI_Model {

    public function insertCustomerData($customerData)
    {
        $result = $this->db->insert('customer', $customerData);

        return $result;
    }

    public function queryCustomerData()
    {
        $result = $this->db->get('customer');

        return $result;
    }

    public function queryCustomerByID($customerID)
    {
        $this->db->where('customerID', $customerID);
        $result = $this->db->get('customer');

        return $result->row_array();
    }
}
