<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    div#orderInProduction-button {
        display: none;
    }

    div#operatorInProduction-button {
        display: none;
    }

    span.select2-selection.select2-selection--single{
        width:150px;
    }
</style>
<script>
$(document).ready(function() {

    function autoFillOrder() {
        // Auto-fill in order ID
        $.ajax({
            url: "/order/queryOrderID",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        selectOption.attr('value', row[i][j]);
                        selectOption.text(row[i][j]);
                    }
                    selectOption.appendTo($('#orderInProduction'));
                }
            }
        });
    }
    autoFillOrder();

    function autoFillOperator() {
        // Auto-fill in packaging ID and display packaging name
        $.ajax({
            url: "/operator/queryOperator",
            success: function(result) {
                var row = JSON.parse(result);

                for(var i in row)
                {
                    selectOption = $(document.createElement('option'));
                    for(var j in row[i])
                    {
                        if ("operatorID" == j) {
                            var packagingID = row[i][j];
                            selectOption.attr('value', row[i][j]);
                        }
                        if ("operatorName" == j) {
                            selectOption.text(row[i][j]);
                        }
                    }
                    selectOption.appendTo($('#operatorInProduction'));
                }
            }
        });
    }
    autoFillOperator();

    // Display order information when order ID selected
    $('#orderInProductionSelection').on("change", '#orderInProduction', function() {
        var orderID = $('select#orderInProduction').find("option:selected").val();

        if ("請選擇" != orderID) {
            $.ajax({
                url: "/order/queryOrderByID/" + orderID,
                success: function(result) {
                    $('#queryOrderTable').remove();
                    var row = JSON.parse(result);
                    var header = ["工單編號", "客戶訂單編號", "交期", "內/外銷", "產品", "客戶", "包裝", "生產數量", "生產重量", "尚欠數量", "尚欠重量"];
                    var table = $(document.createElement('table'));
                    table.attr('id', 'queryOrderTable');
                    table.appendTo($('#addedOrderList'));
                    var tr = $(document.createElement('tr'));
                    tr.appendTo(table);
                    for(var i in header)
                    {
                        var th = $(document.createElement('th'));
                        th.text(header[i]);
                        th.appendTo(tr);
                    }

                    for(var j in row)
                    {
                        tr = $(document.createElement('tr'));
                        tr.appendTo(table);
                        for(var k in row[j])
                        {
                            var td = $(document.createElement('td'));
                            td.text(row[j][k]);
                            td.appendTo(tr);
                        }
                    }
                }
            });
        }
    });

    $('#addProductionForm').submit(function(event) {
        var formData = $('#addProductionForm').serialize();

        $.ajax({
            url: "/production/addProduction",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addProductionTable').remove();
                var row = JSON.parse(result);
                var header = ["訂單編號", "生產人員", "批號", "生產日期", "本次生產數量", "本次生產重量", "產品"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addProductionTable');
                table.appendTo($('#addProductionList'));
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
                    td = $(document.createElement('td'));
                    td.text(row[j]);
                    td.appendTo(tr);
                }
            }
        });
        event.preventDefault();
    });

    // When click reset button
    $('input[type="reset"]').click(function() {
        // Remove options of product then create again
        $('select#orderInProduction option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillOrder();

        // Remove options of packaging then create again
        $('select#operatorInProduction option').each( function() {
            if ("請選擇" != $(this).text()) {
                $(this).remove();
            }
        });
        autoFillOperator();

        // Remove added order information table
        $('#queryOrderTable').remove();
        // Remove added production information table
        $('#addProductionTable').remove();
    });
    $('.js-example-basic-single').select2();
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('production/addProductionView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('production/queryProductionView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addProductionForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        工單編號
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="orderInProductionSelection">
        <select id="orderInProduction" class="js-example-basic-single" name="order">
        <option>請選擇</option>
        </select>
    </div>
    <div id="addedOrderList"></div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        生產人員
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d" id="operatorInProductionSelection">
        <select id="operatorInProduction" class="js-example-basic-single" name="operator">
        <option>請選擇</option>
        </select>
    </div>
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        批號
        <input type="text" name="batchNumber" size=20 maxlength=16>
        生產日期
        <input type="date" name="producingDate" min="2017-01-01">
        本次生產數量
        <input type="text" name="producedPackageNumber">
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="addProductionList"></div>
