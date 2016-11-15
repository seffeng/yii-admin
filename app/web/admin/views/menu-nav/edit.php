<?php

use yii\helpers\Url;
use yii\helpers\Html;
use zxf\models\entities\MenuNav;
use yii\widgets\ActiveForm;
use zxf\models\services\MenuNavService;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-primary">
    <div class="box-header"></div>
        <?php $form = ActiveForm::begin([
            'id'    => 'edit-form',
            'options' => ['class' => 'form-horizontal box-body'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
        ]); ?>
        <?php
        echo $form->field($model, 'mn_name');
        echo $form->field($model, 'mn_url');
        echo $form->field($model, 'mn_icon');
        echo $form->field($model, 'mn_type')->dropDownList($mn_types);
        ?>
        <div class="form-group field-menunav-mn_pid">
            <label class="col-lg-2 control-label" for="menunav-mn_pid">父导航</label>
            <div class="col-lg-4">
                <select class="form-control" name="MenuNav[mn_pid]" id="menunav-mn_pid">
                    <option value="0">无</option>
                    <?php echo $menu_option; ?>
                </select>
            </div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-menunav-mn_status">
            <label class="col-lg-2 control-label" for="menunav-mn_status">状态</label>
            <div class="col-lg-4">
            <?php echo Html::checkbox('MenuNav[mn_status]', MenuNavService::statusIsOn($model->mn_status), ['id' => 'menunav-mn_status']); ?></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-4">
                <?php echo Html::button('确&nbsp;&nbsp;定', ['adm' => 'submit', 'class' => 'btn btn-primary', 'data-loading-text' => 'Loading...']); ?>
            </div>
        </div>
        <?php $form->end(); ?>
    <div class="box-footer"></div>
</div>
<script>
$(document).ready(function(){
    var _mn_id = "<?php echo $model->mn_id; ?>";
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['menu-nav/index']); ?>", url_add: "<?php echo Url::to(['menu-nav/add']); ?>", url_edit: "<?php echo Url::to(['menu-nav/edit']); ?>", url_del: "<?php echo Url::to(['menu-nav/del']); ?>"});

    /* 状态 */
    $('input[name="MenuNav[mn_status]"]').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 添加导航
     * @date   2015-12-12
     */
    $('button[adm="submit"]').on('click', function(){
        var _mn_name    = $('#menunav-mn_name').val();
        var _mn_url     = $('#menunav-mn_url').val();
        var _mn_icon    = $('#menunav-mn_icon').val();
        var _mn_type    = $('#menunav-mn_type > option:selected').val();
        var _mn_pid     = $('#menunav-mn_pid > option:selected').val();
        var _mn_status  = $('#menunav-mn_status:checked').val() == '1' ? "<?php echo MenuNav::STATUS_ON; ?>" : "<?php echo MenuNav::STATUS_OFF; ?>";
        if (!checkForm()) {
            return false;
        }
        var _data = {'id': _mn_id, 'MenuNav[mn_name]': _mn_name, 'MenuNav[mn_url]': _mn_url, 'MenuNav[mn_icon]': _mn_icon, 'MenuNav[mn_type]': _mn_type, 'MenuNav[mn_pid]': _mn_pid, 'MenuNav[mn_status]': _mn_status};
        CLS_FORM.submit(CLS_FORM._url_edit, _data);
    });

    /* input失去焦点检测 */
    $('#edit-form input').on('blur', function(){
        checkForm();
    });

    /* 选择一级导航时，父导航为0 */
    $('#menunav-mn_type').on('change', function() {
        var _type = $(this).val();
        if (_type == "<?php echo MenuNav::TYPE_TOP; ?>") {
            $('#menunav-mn_pid option[value="0"]').attr('selected', 'selected');
        }
    });

    /* 父导航为0时，导航类型为一级导航 */
    $('#menunav-mn_pid').on('change', function() {
        var _pid = $(this).val();
        if (_pid == '0') {
            $('#menunav-mn_type option[value="<?php echo MenuNav::TYPE_TOP; ?>"]').attr('selected', 'selected');
        }
    });
});

/**
 * 输入数据检查
 */
function checkForm() {
    var _mn_name = $('#menunav-mn_name').val();
    var _mn_url  = $('#menunav-mn_url').val();
    if (_mn_name == '') {
        $('.field-menunav-mn_name').removeClass('has-success').addClass('has-error').find('.help-block').text('菜单名称 不能为空！');
        return false;
    }
    $('.field-menunav-mn_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_mn_url == '') {
        $('.field-menunav-mn_url').removeClass('has-success').addClass('has-error').find('.help-block').text('菜单地址 不能为空！');
        return false;
    }
    $('.field-menunav-mn_url').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>