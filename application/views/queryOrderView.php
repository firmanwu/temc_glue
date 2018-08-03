<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function deleteOrder(deleteURL) {
    $.ajax({
        url: deleteURL,
        success: function(result) {
            queryOrder();
        }
    });
}

function queryOrder() {
    $.ajax({
        url: "/order/queryOrderAll",
        success: function(result) {
            $('#queryOrderTable').remove();
            var row = JSON.parse(result);
            var header = ["工單編號", "訂單編號", "交期", "內/外銷", "產品", "客戶", "包裝", "生產數量", "生產重量", "尚欠數量", "尚欠重量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryOrderTable');
            table.appendTo($('#queryOrderList'));
            var tr = $(document.createElement('tr'));
            tr.appendTo(table);
            for(var i in header)
            {
                var th = $(document.createElement('th'));
                th.attr('class', 'sortable');
                th.attr('style', 'cursor:pointer');
                th.text(header[i]);
                th.appendTo(tr);
            }

            for(var j in row)
            {
                tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var k in row[j])
                {
                    if ("orderID" == k) {
                        var orderID = row[j][k];
                    }

                    var td = $(document.createElement('td'));
                    td.text(row[j][k]);
                    td.appendTo(tr);
                }

/*
                var deleteButton = $(document.createElement('button'));
                var onclickFunction = "deleteOrder(\"/order/deleteOrder/" + orderID + "\")";
                deleteButton.attr({"class":"selfButtonR", "onclick":onclickFunction});
                deleteButton.text("刪除");

                td = $(document.createElement('td'));
                deleteButton.appendTo(td);
                td.appendTo(tr);
*/
            }
            sortable_headers();
        }
    });
}

$(document).ready(function(){

    var postData = 
                {
                    "model":"ordermodel",
                    "queryfunction":"queryOrderAllData",
                    "header":["訂單編號", "交期", "內/外銷", "產品", "包裝", "客戶", "生產重量", "生產數量", "尚欠重量", "尚欠數量"]
                } 

    $('.download-order-excel').click( function(e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url:'downloadOrderExcel',
            dataType: 'json',
            data: {excelBuildData:postData},
            success: function (data, textstatus) {
                          if( !('error' in data) ) {
                            var $a = $("#excel-order-download");
                            var today = new Date();
                            var day = today.getDate();
                            var month_index = today.getMonth();
                            var year = today.getFullYear();
                            $a.attr("href",data.file);
                            $a.attr("download","生產工單報表"+"-"+(month_index+1)+"-"+day+"-"+year+".xlsx");
                            $a[0].click();
                          }
                          else {
                              console.log(data.error);
                          }
                    }
        });
        return false; 
    });
});
function sortable_headers (){
        $('th').click(function(){
                var table = $(this).parents('table').eq(0)
                var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))
                this.asc = !this.asc
                if (!this.asc){rows = rows.reverse()}
                for (var i = 0; i < rows.length; i++){table.append(rows[i])}
        });
    }

        function comparer(index) {
                return function(a, b) {
                        var valA = getCellValue(a, index), valB = getCellValue(b, index)
                        return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
                }
        }
        function getCellValue(row, index){ return $(row).children('td').eq(index).text() }
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('order/addOrderView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('order/queryOrderView');?>" data-role="button" data-icon="flat-bubble" data-theme="d">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="queryOrder()">生產工單查詢</button>
</div>

<div class="ui-block-b"><a id = "excel-order-download" style="display:none;" href="" data-role="button" data-icon="flat-bubble" data-theme="c">Excel Download FGE</a></div>
<div class="ui-block-b download-order-excel"><a href="" data-role="button" data-icon="flat-bubble" data-theme="d">下載生產工單 Excel</a></div>

<br><br>
<div></div>
<br><br>
<div id="queryOrderList"></div>
