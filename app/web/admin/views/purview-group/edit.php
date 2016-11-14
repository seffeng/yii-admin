<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zxf\models\entities\Purview;
use zxf\models\entities\PurviewGroup;
use zxf\models\services\PurviewGroupService;

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
        echo $form->field($model, 'pvg_name');
        ?>
        <div class="form-group field-purviewgroup-pvg_status">
            <label class="col-lg-2 control-label" for="purviewgroup-pvg_status">状态</label>
            <div class="col-lg-4">
            <?php echo Html::checkbox('PurviewGroup[pvg_status]', PurviewGroupService::statusIsOn($model->pvg_status), ['id' => 'purviewgroup-pvg_status']); ?></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-pv_id">
            <label class="col-lg-2 control-label">权限：</label>
            <div class="col-lg-8">
                <?php
                    if ($purview) :
                    $pv_ids = explode(',', trim($model->pv_ids, ','));
                    foreach ($purview as $key => $val) :
                ?>
                <label><input type="checkbox" value="<?php echo $key; ?>" name="PurviewGroup[pv_ids]" <?php echo in_array($key, $pv_ids) ? ' checked' : ''; ?>><?php echo $val; ?></label>&nbsp;
                <?php endforeach; endif; ?>
            </div>
            <div class="col-lg-2"><div class="help-block"></div></div>
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
    var _pvg_id = "<?php echo $model->pvg_id; ?>";
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['purview-group/index']); ?>", url_add: "<?php echo Url::to(['purview-group/add']); ?>", url_edit: "<?php echo Url::to(['purview-group/edit']); ?>", url_del: "<?php echo Url::to(['purview-group/del']); ?>"});

    /* 状态 */
    $('#purviewgroup-pvg_status').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 添加
     * @date   2016-11-8
     */
    $('button[adm="submit"]').on('click', function(){
        var _pvg_name   = $('#purviewgroup-pvg_name').val();
        var _pvg_status = $('#purviewgroup-pvg_status:checked').val() == '1' ? "<?php echo PurviewGroup::STATUS_ON; ?>" : "<?php echo PurviewGroup::STATUS_OFF; ?>";
        var _pv_ids = '';
        $('input[name="PurviewGroup[pv_ids]"]:checked').each(function(){
            _pv_ids += $(this).val() + ',';
        });
        if (!checkForm()) {
            return false;
        }
        var _data = {'id': _pvg_id, 'PurviewGroup[pvg_name]': _pvg_name, 'PurviewGroup[pvg_status]': _pvg_status, 'PurviewGroup[pv_ids]': _pv_ids};
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
    var _pvg_name = $('#purviewgroup-pvg_name').val();
    if (_pvg_name == '') {
        $('.field-purviewgroup-pvg_name').removeClass('has-success').addClass('has-error').find('.help-block').text('名称 不能为空！');
        return false;
    }
    $('.field-purviewgroup-pvg_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>