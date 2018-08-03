<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Operatormodel extends CI_Model {

    public function insertOperatorData($operatorData)
    {
        $result = $this->db->insert('operator', $operatorData);

        return $result;
    }

    public function queryOperatorData()
    {
        $result = $this->db->get('operator');

        return $result;
    }

    public function queryOperatorByID($operatorID)
    {
        $this->db->where('operatorID', $operatorID);
        $result = $this->db->get('operator');

        return $result->row_array();
    }
}
