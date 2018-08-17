<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#salesInOrder-button {
        display: none;
    }

    div#productInOrder-button {
        display: none;
    }

    div#packagingInOrder-button {
        display: none;
    }

    div#customerInOrder-button {
        display: none;
    }

    span.select2-selection.select2-selection--single{
        width:150px;
    }
</style>
<script>
function calculateWeight() {
    // Calculate the weight according to the expecting package number
    var packagingID = $('select#packagingInOrder').find("option:selected").val();
    var packageNumber = $('input[name="expectingPackageNumber"]').val();
    if (("請選擇" != packagingID) && ('' != packageNumber)) {
        $.ajax({
            url: "/packaging/queryUnitWeightByID/" + packagingID,
            type: "POST",
            success: function(result) {
                $('#orderWeightTable').remove();
                var row = JSON.parse(result);
                var header = ["生產重量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'orderWeightTable');
                table.appendTo($('#orderWeightList'));
                var tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var i in header)
                {
                    var th = $(document.createElement('th'));
                    th.text(header[i]);
                    th.appendTo(tr);
                }

                tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var j in row)
                {
                    if ("unitWeight" == j) {
                        var expectingWeight = row[j];
                    }

                    td = $(document.createElement('td'));
                    td.text((expectingWeight * packageNumber));
                    td.appendTo(tr);
                }
            }
        });
    }
    event.preventDefault();
}

$(document).ready(function() {
    function autoGenerateOrderID() {
        $.ajax({
            url: "/order/getSerialNumber",
            success: function(serialNumber) {
                $("input[name = 'orderID']").attr({"value":"CFX" + serialNumber, "readonly":true});
            }
        });
    }
    autoGenerateOrderID();

    function autoFillProduct() {
        // Auto-fill in product ID and display product name
        $.ajax({
            url: "/product/queryProduct",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        if ("productID" == j) {
                            var productID = row[i][j];
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("productName" == j) {
                            selectOption.text(row[i][j]);
                        }
                    }
                    selectOption.appendTo($('#productInOrder'));
                }
            }
        });
    }
    autoFillProduct();

    function autoFillPackaging() {
        // Auto-fill in packaging ID and display packaging name
        $.ajax({
            url: "/packaging/queryPackaging",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        if ("packagingID" == j) {
                            packagingID = row[i][j];
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("packaging" == j) {
                            selectOption.text(row[i][j]);
                        }
                    }
                    selectOption.appendTo($('#packagingInOrder'));
                }
            }
        });
    }
    autoFillPackaging();

    function autoFillCustomer() {
        // Auto-fill in customer ID and display customer name
        $.ajax({
            url: "/customer/queryCustomer",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        if ("customerID" == j) {
                            var customerID = row[i][j];
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("customerName" == j) {
                            selectOption.text(row[i][j]);
                        }
                    }
                    selectOption.appendTo($('#customerInOrder'));
                }
            }
        });
    }
    autoFillCustomer();

    $('#addOrderForm').submit(function(event) {
        var formData = $('#addOrderForm').serialize();

        $.ajax({
            url: "/order/addOrder",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addOrderTable').remove();
                var row = JSON.parse(result);
                var header = ["工單編號", "客戶訂單編號", "交期", "內/外銷", "產品", "客戶", "包裝", "生產數量", "生產重量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addOrderTable');
                table.appendTo($('#addOrderList'));
                var tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var i in header)
                {
                    var th = $(document.createElement('th'));
                    th.text(header[i]);
                    th.appendTo(tr);
                }

                tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var j in row)
                {
                    if (("remainingPackageNumber" == j) || ("remainingWeight" == j)) {
                        break;
                    }
                    td = $(document.createElement('td'));
                    td.text(row[j]);
                    td.appendTo(tr);
                }

                $.ajax({
                    url: "/order/increaseSerialNumber"
                });
            }
        });
        event.preventDefault();
    });

    // When click reset button
    $('input[type="reset"]').click(function() {
        autoGenerateOrderID();

        // Remove options of product then create again
        $('select#productInOrder option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillProduct();

        // Remove options of packaging then create again
        $('select#packagingInOrder option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillPackaging();

        // Remove options of customer then create again
        $('select#customerInOrder option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillCustomer();

        // Remove expecting weight information table
        $('#orderWeightTable').remove();
        // Remove added order information table
        $('#addOrderTable').remove();
    });
    $('.js-example-basic-single').select2();
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('order/addOrderView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('order/queryOrderView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addOrderForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        工單編號
        <input type="text" name="orderID" size=20 maxlength=16>
        客戶訂單編號
        <input type="text" name="customerOrderID" size=20 maxlength=16>
        交期
        <input type="date" name="deadline" min="2017-01-01">
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        內/外銷
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="salesInOrderSelection">
        <select id="salesInOrder" class="js-example-basic-single" name="sales">
        <option>內銷</option>
        <option>外銷</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        產品
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="productInOrderSelection">
        <select id="productInOrder" class="js-example-basic-single" name="product">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        包裝
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="packagingInOrderSelection">
        <select id="packagingInOrder" class="js-example-basic-single" name="packaging">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        客戶
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="customerInOrderSelection">
        <select id="customerInOrder" class="js-example-basic-single" name="customer">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        生產數量
        <input type="text" name="expectingPackageNumber">
        <div id="orderWeightList"></div>
        <div class="selfButtonB" onclick="calculateWeight()">計算重量</button>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        <br>
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="addOrderList"></div>
