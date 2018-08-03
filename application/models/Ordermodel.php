<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ordermodel extends CI_Model {

    public function insertOrderData($orderData)
    {
        $result = $this->db->insert('order', $orderData);

        return $result;
    }

    public function queryOrderAllData()
    {
        $this->db->select('
            order.orderID,
            order.customerOrderID,
            order.deadline,
            order.sales,
            product.productName,
            customer.customerName,
            packaging.packaging,
            order.expectingPackageNumber,
            order.expectingWeight,
            order.remainingPackageNumber,
            order.remainingWeight');
        $this->db->from('order');
        $this->db->join('product', 'order.product = product.productID');
        $this->db->join('customer', 'order.customer = customer.customerID');
        $this->db->join('packaging', 'order.packaging = packaging.packagingID');
        $result = $this->db->get();

        return $result;
    }

    public function queryOrderByIDData($orderID)
    {
        $this->db->select('
            order.orderID,
            order.customerOrderID,
            order.deadline,
            order.sales,
            product.productName,
            customer.customerName,
            packaging.packaging,
            order.expectingPackageNumber,
            order.expectingWeight,
            order.remainingPackageNumber,
            order.remainingWeight');
        $this->db->from('order');
        $this->db->join('product', 'order.product = product.productID');
        $this->db->join('customer', 'order.customer = customer.customerID');
        $this->db->join('packaging', 'order.packaging = packaging.packagingID');
        $this->db->where('order.orderID', $orderID);
        $result = $this->db->get();

        return $result;
    }

    public function queryOrderIDData()
    {
        $this->db->select('orderID');
        $this->db->from('order');
        $this->db->where('remainingWeight >', 0);
        $result = $this->db->get();

        return $result;
    }

    public function queryOrderForProductionByID($orderID)
    {
        $this->db->select('
            product.productName,
            packaging.unitWeight');
        $this->db->from('order');
        $this->db->join('product', 'order.product = product.productID');
        $this->db->join('packaging', 'order.packaging = packaging.packagingID');
        $this->db->where('order.orderID', $orderID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function queryOrderExpectingRemainingWeightSumByProductIDData($productID)
    {
        $this->db->select_sum('expectingWeight');
        $this->db->select_sum('remainingWeight');
        $this->db->from('order');
        $this->db->where('product', $productID);
        $result = $this->db->get();

        return $result->row_array();
    }

    public function deleteOrderData($orderData)
    {
        $this->db->where('orderID', $orderData['orderID']);
        $result = $this->db->delete('order');

        return $result;
    }

    public function updateOrderWeightPackageData($productionData)
    {
        $this->db->set('remainingWeight', 'remainingWeight + ' . (-$productionData['producedWeight']), FALSE);
        $this->db->set('remainingPackageNumber', 'remainingPackageNumber + ' . (-$productionData['producedPackageNumber']), FALSE);
        $this->db->where('orderID', $productionData['order']);
        $this->db->update('order');
    }
}
