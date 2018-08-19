<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div data-role="page" data-title="<?php echo $title; ?>" id="first">
    <div data-role="panel" id="panel" data-position="left" data-display="push">
        <div data-role="collapsible" data-collapsed-icon="flat-plus" data-expanded-icon="flat-cross" data-collapsed="true" data-theme="b">
            <h3>生產資料</h3>
            <a href="<?php echo base_url('order/addOrderView');?>" data-role="button" data-icon="flat-new" data-theme="b">訂單管理</a>
            <a href="<?php echo base_url('production/addProductionView');?>" data-role="button" data-icon="flat-new" data-theme="b">生產管理</a>
            <a href="<?php echo base_url('productiontarget/addProductionTargetView');?>" data-role="button" data-icon="flat-new" data-theme="b">年度季度目標管理</a>
            <a href="<?php echo base_url('status/displayStatusView');?>" data-role="button" data-icon="flat-new" data-theme="b">生產狀態顯示</a>
        </div>

        <div data-role="collapsible" data-collapsed-icon="flat-plus" data-expanded-icon="flat-cross" data-theme="d">
            <h3>基本資料</h3>
            <a href="<?php echo base_url('product/addProductView');?>" data-role="button" data-icon="flat-new" data-theme="d">產品管理</a>
            <a href="<?php echo base_url('packaging/addPackagingView');?>" data-role="button" data-icon="flat-new" data-theme="d">包裝管理</a>
            <a href="<?php echo base_url('customer/addCustomerView');?>" data-role="button" data-icon="flat-new" data-theme="d">客戶管理</a>
            <a href="<?php echo base_url('operator/addOperatorView');?>" data-role="button" data-icon="flat-new" data-theme="d">生產人員管理</a>
        </div>

        <!--div data-role="collapsible" data-collapsed-icon="flat-plus" data-expanded-icon="flat-cross" data-theme="f">
            <h3>使用者</h3>
            <a href="<?php echo base_url('login');?>" data-role="button" data-icon="flat-new" data-theme="f">登入</a>
            <a href="<?php echo base_url('user/addUserView');?>" data-role="button" data-icon="flat-new" data-theme="f">帳號管理</a>
        </div-->
    </div>
    <div data-role="header" data-theme="<?php echo $theme; ?>">
        <a data-iconpos="notext" href="#panel" data-role="button" data-icon="flat-menu"></a>
        <h1><?php echo $title; ?></h1>
        <a data-iconpos="notext" href="<?php echo base_url('welcome');?>" data-role="button" data-icon="home"></a>
    </div>
