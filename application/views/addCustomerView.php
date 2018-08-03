<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    $('#addCustomerForm').submit(function(event) {
        var formData = $('#addCustomerForm').serialize();

        $.ajax({
            url: "/customer/addCustomer",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addCustomerTable').remove();
                var row = JSON.parse(result);
                var header = ["客戶名稱"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addCustomerTable');
                table.appendTo($('#customerList'));
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
    <div class="ui-block-a"><a href="<?php echo base_url('customer/addCustomerView');?>" data-role="button" data-icon="flat-plus" data-theme="f">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('customer/queryCustomerView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addCustomerForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        客戶名稱
        <input type="text" name="customerName" size=20 maxlength=16>
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="customerList"></div>
