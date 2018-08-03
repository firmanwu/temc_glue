<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function queryStatus() {
    $.ajax({
        url: "/order/queryOrderStatus",
        success: function(result) {
            $('#queryStatusTable').remove();
            var row = JSON.parse(result);
            var header = ["產品型號", "訂單所需總重量", "已生產重量", "尚欠重量"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryStatusTable');
            table.appendTo($('#queryStatusList'));
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
                    var td = $(document.createElement('td'));
                    td.text(row[j][k]);
                    td.appendTo(tr);
                }
            }
            sortable_headers();
        }
    });
    event.preventDefault();
}
queryStatus();

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

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="queryStatus()">更新生產狀態</button>
</div>

<br><br>
<div id="queryStatusList"></div>
