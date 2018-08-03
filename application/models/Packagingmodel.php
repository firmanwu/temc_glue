<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Packagingmodel extends CI_Model {

    public function insertPackagingData($packagingData)
    {
        $result = $this->db->insert('packaging', $packagingData);

        return $result;
    }

    public function queryPackagingData()
    {
        $result = $this->db->get('packaging');

        return $result;
    }

    public function queryPackagingByID($packagingID)
    {
        $this->db->where('packagingID', $packagingID);
        $result = $this->db->get('packaging');

        return $result->row_array();
    }
}