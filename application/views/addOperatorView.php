<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
$(document).ready(function() {
    $('#addOperatorForm').submit(function(event) {
        var formData = $('#addOperatorForm').serialize();

        $.ajax({
            url: "/operator/addOperator",
            type: "POST",
            data: formData,
            success: function(result) {
                $('#addOperatorTable').remove();
                var row = JSON.parse(result);
                var header = ["生產人員"];
                var table = $(document.createElement('table'));
                table.attr('id', 'addOperatorTable');
                table.appendTo($('#operatorList'));
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
    <div class="ui-block-a"><a href="<?php echo base_url('operator/addOperatorView');?>" data-role="button" data-icon="flat-plus" data-theme="f">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('operator/queryOperatorView');?>" data-role="button" data-icon="flat-bubble" data-theme="c">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<form id="addOperatorForm">
    <div data-role="controlgroup" data-type="horizontal" data-theme="d">
        生產人員
        <input type="text" name="operatorName" size=20 maxlength=16>
        <input type="submit" value="新增" data-role="button">
    </div>
</form>

<br><br>
<div id="operatorList"></div>
