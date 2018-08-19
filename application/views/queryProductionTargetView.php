<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<script>
function queryProductionTarget() {
    $.ajax({
        url: "/production/queryProductionStatus",
        success: function(result) {
            $('#queryProductionTargetStatusTable').remove();
            var row = JSON.parse(result);
            //var headerList = ["Year", "Q1", "Q2", "Q3", "Q4"];
            var headerYear = ["年目標生產總重量", "年實際生產總重量", "年目標達成率"];
            var headerQ1 = ["第一季目標生產總重量", "第一季實際生產總重量", "年目標達成率"];
            var headerQ2 = ["第二季目標生產總重量", "第二季實際生產總重量", "年目標達成率"];
            var headerQ3 = ["第三季目標生產總重量", "第三季實際生產總重量", "年目標達成率"];
            var headerQ4 = ["第四季目標生產總重量", "第四季實際生產總重量", "年目標達成率"];
            var table = $(document.createElement('table'));
            table.attr('id', 'queryProductionTargetStatusTable');
            table.appendTo($('#queryProductionTargetStatusList'));
            for(t = 0; t < 5; t++) {
                var tr = $(document.createElement('tr'));
                tr.appendTo(table);
                switch(t) {
                    case 0:
                        header = headerYear;
                        break;
                    case 1:
                        header = headerQ1;
                        break;
                    case 2:
                        header = headerQ2;
                        break;
                    case 3:
                        header = headerQ3;
                        break;
                    case 4:
                        header = headerQ4;
                        break;
                }

                for(var i in header)
                {
                    var th = $(document.createElement('th'));
                    th.text(header[i]);
                    th.appendTo(tr);
                }

                tr = $(document.createElement('tr'));
                tr.appendTo(table);
                for(var j in row[t])
                {
                    td = $(document.createElement('td'));
                    if ("achievedRate" == j) {
                        td.text(row[t][j].toFixed(2));
                    }
                    else{
                        td.text(row[t][j]);
                    }
                    td.appendTo(tr);
                }
            }
        }
    });
}
queryProductionTarget();
</script>

<div data-role="content" role="main">
<fieldset class="ui-grid-a">
    <div class="ui-block-a"><a href="<?php echo base_url('productiontarget/addProductionTargetView');?>" data-role="button" data-icon="flat-plus" data-theme="c">新增</a></div>
    <div class="ui-block-b"><a href="<?php echo base_url('productiontarget/queryProductionTargetView');?>" data-role="button" data-icon="flat-bubble" data-theme="d">查詢</a></div>
</fieldset>
<hr size="5" noshade>

<div data-role="controlgroup" data-type="horizontal">
<button data-icon="flat-man" data-theme="d" onclick="queryProductionTarget()">更新年度季度生產目標</button>
</div>

<br><br>
<div id="queryProductionTargetStatusList"></div>
