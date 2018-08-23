<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>

$(document).ready(function() {

    function autoFillCurrentYear() {
        // Auto-fill current year into targetYear
        var dateObject = new Date();
        var year = dateObject.getFullYear();

        $("input[name = 'targetYear']").attr('value', year);
    }
    autoFillCurrentYear();

    $('#addProductionTargetForm').submit(function(event) {
        var formData = $('#addProductionTargetForm').serialize();

        $.ajax({
            url: "/productiontarget/addProductionTarget",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addProductionTargetTable').remove();
                var row = JSON.parse(result);
                var header = ["生產目標年份", "第一季目標總重量", "第二季目標總重量", "第三季目標總重量", "第四季目標總重量", "年目標總重量"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addProductionTargetTable');
                table.appendTo($('#addProductionTargetList'));
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

    $('input[type="reset"]').click(function() {
        $('#addProductionTargetTable').remove();
    });
});
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('productiontarget/addProductionTargetView');?>" data-role="button" data-icon="flat-plus" data-theme="d">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('productiontarget/queryProductionTargetView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addProductionTargetForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        生產目標年份
        <input type="number" name="targetYear" required>
        第一季目標總重量
        <input type="number" name="quarterOneTotalWeight" required>
        第二季目標總重量
        <input type="number" name="quarterTwoTotalWeight" required>
        第三季目標總重量
        <input type="number" name="quarterThreeTotalWeight" required>
        第四季目標總重量
        <input type="number" name="quarterFourTotalWeight" required>
        <input type="submit" value="確定" data-role="button">
        <input type="reset" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="addProductionTargetList"></div>
