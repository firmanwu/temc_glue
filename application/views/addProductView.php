<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    $('#addProductForm').submit(function(event) {
        var formData = $('#addProductForm').serialize();

        $.ajax({
            url: "/product/addProduct",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addProductTable').remove();
                var row = JSON.parse(result);
                var header = ["產品型號"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addProductTable');
                table.appendTo($('#productList'));
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
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('product/addProductView');?>" data-role="button" data-icon="flat-plus" data-theme="f">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('product/queryProductView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addProductForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        產品型號
        <input type="text" name="productName" size=20 maxlength=16>
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="productList"></div>
