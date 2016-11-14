<?php

use yii\helpers\Url;
use yii\helpers\Html;
use appdir\admin\models\Purview;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-primary">
    <div class="box-header"></div>
    <div id="add-form" class="form-horizontal box-body">
        <div class="form-group field-pv_name">
            <label class="col-lg-2 control-label" for="pv_name">权限名：</label>
            <div class="col-lg-4"><input type="text" id="pv_name" class="form-control" name="Purview[pv_name]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-pv_key">
            <label class="col-lg-2 control-label" for="pv_key">权限KEY：</label>
            <div class="col-lg-4"><input type="text" id="pv_key" class="form-control" name="Purview[pv_key]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-pv_status">
            <label class="col-lg-2 control-label" for="pv_status">状态：</label>
            <div class="col-lg-4">
            <?php echo Html::checkbox('Purview[pv_status]', TRUE, ['id' => 'pv_status']); ?></div>
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
    CLS_FORM.init({url: "<?php echo Url::to(['purview/index']); ?>", url_add: "<?php echo Url::to(['purview/add']); ?>", url_edit: "<?php echo Url::to(['purview/edit']); ?>", url_del: "<?php echo Url::to(['purview/del']); ?>"});

    /* 状态 */
    $('input[name="Purview[pv_status]"]').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 添加权限
     */
    $('button[adm="submit"]').on('click', function(){
        var _pv_name = $('#pv_name').val();
        var _pv_key = $('#pv_key').val();
        var _pv_status   = $('#pv_status:checked').val() == '1' ? "<?php echo Purview::STATUS_NORMAL; ?>" : "<?php echo Purview::STATUS_STOP; ?>";
        if (!checkForm()) {
            return false;
        }
        var _data = {'Purview[pv_name]': _pv_name, 'Purview[pv_key]': _pv_key, 'Purview[pv_status]': _pv_status};
        CLS_FORM.submit(CLS_FORM._url_add, _data);
    });

    /* input失去焦点检测 */
    $('#add-form input').on('blur', function(){
        checkForm();
    });
});

/**
 * 输入数据检查
 */
function checkForm() {
    var _pv_name    = $('#pv_name').val();
    var _pv_key     = $('#pv_key').val();
    if (_pv_name == '') {
        $('.field-pv_name').removeClass('has-success').addClass('has-error').find('.help-block').text('请输入组名');
        return false;
    }
    $('.field-pv_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_pv_key == '') {
        $('.field-pv_key').removeClass('has-success').addClass('has-error').find('.help-block').text('请输入权限KEY');
        return false;
    }
    $('.field-pv_key').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>