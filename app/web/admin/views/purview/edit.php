<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zxf\models\entities\Purview;
use zxf\models\services\PurviewService;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-primary">
    <div class="box-header"></div>
        <?php $form = ActiveForm::begin([
            'id'    => 'add-form',
            'options' => ['class' => 'form-horizontal box-body'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-4\">{input}</div>\n<div class=\"col-lg-6\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ],
        ]); ?>
        <?php
        echo $form->field($model, 'pv_name');
        echo $form->field($model, 'pv_key');
        ?>
        <div class="form-group field-purview-pv_status">
            <label class="col-lg-2 control-label" for="purview-pv_status">状态</label>
            <div class="col-lg-4">
            <?php echo Html::checkbox('Purview[pv_status]', PurviewService::statusIsOn($model->pv_status), ['id' => 'purview-pv_status']); ?></div>
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
    var _pv_id = "<?php echo $model->pv_id; ?>";
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['purview/index']); ?>", url_add: "<?php echo Url::to(['purview/add']); ?>", url_edit: "<?php echo Url::to(['purview/edit']); ?>", url_del: "<?php echo Url::to(['purview/del']); ?>"});

    /* 状态 */
    $('#purview-pv_status').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 添加
     * @date   2016-11-8
     */
    $('button[adm="submit"]').on('click', function(){
        var _pv_name   = $('#purview-pv_name').val();
        var _pv_key    = $('#purview-pv_key').val();
        var _pv_status = $('#purview-pv_status:checked').val() == '1' ? "<?php echo Purview::STATUS_ON; ?>" : "<?php echo Purview::STATUS_OFF; ?>";
        if (!checkForm()) {
            return false;
        }
        var _data = {'id': _pv_id, 'Purview[pv_name]': _pv_name, 'Purview[pv_key]': _pv_key, 'Purview[pv_status]': _pv_status};
        CLS_FORM.submit(CLS_FORM._url_edit, _data);
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
    var _pv_name = $('#purview-pv_name').val();
    var _pv_key  = $('#purview-pv_key').val();
    if (_pv_name == '') {
        $('.field-purview-pv_name').removeClass('has-success').addClass('has-error').find('.help-block').text('名称 不能为空！');
        return false;
    }
    $('.field-purview-pv_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_pv_key == '') {
        $('.field-purview-pv_key').removeClass('has-success').addClass('has-error').find('.help-block').text('KEY 不能为空！');
        return false;
    }
    $('.field-purview-pv_key').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>