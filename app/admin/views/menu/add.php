<?php

use yii\helpers\Url;
use yii\helpers\Html;
use appdir\admin\models\Menu;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-primary">
    <div class="box-header"></div>
    <div id="add-form" class="form-horizontal box-body">
        <div class="form-group field-menu-mn_name">
            <label class="col-lg-2 control-label" for="menu-mn_name">菜单名称：</label>
            <div class="col-lg-4"><input type="text" id="menu-mn_name" class="form-control" name="Menu[mn_name]"></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-menu-mn_url">
            <label class="col-lg-2 control-label" for="menu-mn_url">菜单地址：</label>
            <div class="col-lg-4"><input type="text" id="menu-mn_url" class="form-control" name="Menu[mn_url]"></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-menu-mn_icon">
            <label class="col-lg-2 control-label" for="menu-mn_icon">菜单图标：</label>
            <div class="col-lg-4"><input type="text" id="menu-mn_icon" class="form-control" name="Menu[mn_icon]"></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-menu-mn_type">
            <label class="col-lg-2 control-label" for="menu-mn_type">菜单类型：</label>
            <div class="col-lg-4">
            <?php echo Html::dropDownList('Menu[mn_type]', Menu::TYPE_TOP, $mn_types, ['class' => 'form-control', 'id' => 'menu-mn_type']); ?>
            </div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-menu-mn_pid">
            <label class="col-lg-2 control-label" for="menu-mn_pid">父导航：</label>
            <div class="col-lg-4">
                <select class="form-control" name="Menu[mn_pid]" id="menu-mn_pid">
                    <option value="0">无</option>
                    <?php echo $menu_option; ?>
                </select>
            </div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-menu-mn_status">
            <label class="col-lg-2 control-label" for="menu-mn_status">状态：</label>
            <div class="col-lg-4">
            <?php echo Html::checkbox('Menu[mn_status]', TRUE, ['id' => 'menu-mn_status']); ?></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-4">
                <button adm="submit" class="btn btn-primary" data-loading-text="Loading...">确&nbsp;&nbsp;定</button>
            </div>
        </div>
    </div>
    <div class="box-footer"></div>
</div>
<script>
$(document).ready(function(){
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['menu/index']); ?>", url_add: "<?php echo Url::to(['menu/add']); ?>", url_edit: "<?php echo Url::to(['menu/edit']); ?>", url_del: "<?php echo Url::to(['menu/del']); ?>"});

    /* 状态 */
    $('input[name="Menu[mn_status]"]').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});
    /**
     * 添加导航
     * @date   2015-12-12
     */
    $('button[adm="submit"]').on('click', function(){
        var _mn_name    = $('#menu-mn_name').val();
        var _mn_url     = $('#menu-mn_url').val();
        var _mn_icon    = $('#menu-mn_icon').val();
        var _mn_type    = $('#menu-mn_type').val();
        var _mn_pid     = $('#menu-mn_pid > option:selected').val();
        var _mn_status  = $('#menu-mn_status:checked').val() == '1' ? "<?php echo Menu::STATUS_NORMAL; ?>" : "<?php echo Menu::STATUS_STOP; ?>";
        if (!checkForm()) {
            return false;
        }
        var _data = {'Menu[mn_name]': _mn_name, 'Menu[mn_url]': _mn_url, 'Menu[mn_icon]': _mn_icon, 'Menu[mn_type]': _mn_type, 'Menu[mn_pid]': _mn_pid, 'Menu[mn_status]': _mn_status};
        CLS_FORM.submit(CLS_FORM._url_add, _data);
    });

    /* input失去焦点检测 */
    $('#add-form input').on('blur', function(){
        checkForm();
    });

    /* 选择一级导航时，父导航为0 */
    $('#menu-mn_type').on('change', function() {
        var _type = $(this).val();
        if (_type == "<?php echo Menu::TYPE_TOP; ?>") {
            $('#menu-mn_pid option[value="0"]').attr('selected', 'selected');
        }
    });

    /* 父导航为0时，导航类型为一级导航 */
    $('#menu-mn_pid').on('change', function() {
        var _pid = $(this).val();
        if (_pid == '0') {
            $('#menu-mn_type option[value="<?php echo Menu::TYPE_TOP; ?>"]').attr('selected', 'selected');
        }
    });
});

/**
 * 输入数据检查
 */
function checkForm() {
    var _mn_name    = $('#menu-mn_name').val();
    if (_mn_name == '') {
        $('.field-menu-mn_name').removeClass('has-success').addClass('has-error').find('.help-block').text('请输入菜单名称');
        return false;
    }
    $('.field-menu-mn_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>