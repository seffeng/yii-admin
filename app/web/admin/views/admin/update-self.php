<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use zxf\models\entities\Admin;
use zxf\models\services\AdminService;

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
        echo $form->field($model, 'ad_username');
        echo $form->field($model, 'ad_password')->passwordInput(['value' => '']);
        echo $form->field($model, 'adg_id')->dropDownList($adminGroup);
        ?>
        <div class="form-group field-admin-ad_status">
            <label class="col-lg-2 control-label" for="admin-ad_status">状态</label>
            <div class="col-lg-4">
            <?php echo Html::checkbox('Admin[ad_status]', AdminService::statusIsOn($model->ad_status), ['id' => 'admin-ad_status']); ?></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <hr />
        <div class="form-group field-admin-pv_id">
            <label class="col-lg-2 control-label">权限：</label>
            <div class="col-lg-8">
                <?php
                    if ($purview) :
                    $pv_ids = explode(',', trim($model->pv_ids, ','));
                    foreach ($purview as $key => $val) :
                ?>
                <label><input type="checkbox" value="<?php echo $key; ?>" name="Admin[pv_ids]" <?php echo in_array($key, $pv_ids) ? ' checked' : ''; ?>><?php echo $val; ?></label>&nbsp;
                <?php endforeach; endif; ?>
            </div>
            <div class="col-lg-2"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-admin-pvg_id">
            <label class="col-lg-2 control-label">权限组：</label>
            <div class="col-lg-8">
                <?php
                    if ($purviewGroup) :
                    $pvg_ids = explode(',', trim($model->pvg_ids, ','));
                    foreach ($purviewGroup as $key => $val) :
                ?>
                <label><input type="checkbox" value="<?php echo $key; ?>" name="Admin[pvg_ids]" <?php echo in_array($key, $pvg_ids) ? ' checked' : ''; ?>><?php echo $val; ?></label>&nbsp;
                <?php endforeach; endif; ?>
            </div>
            <div class="col-lg-2"><div class="help-block"></div></div>
        </div>
        <hr />
        <?php
        echo $form->field($adminInfo, 'ai_name');
        echo $form->field($adminInfo, 'ai_phone', ['inputOptions' => ['class' => 'form-control', 'value' => $adminInfo->ai_phone ?: '']]);
        echo $form->field($adminInfo, 'ai_email');
        ?>
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
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['admin/update-self']); ?>", url_edit: "<?php echo Url::to(['admin/update-self']); ?>"});

    /* 状态 */
    $('input[name="Admin[ad_status]"]').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 修改
     * @date   2016-11-7
     */
    $('button[adm="submit"]').on('click', function(){
        var _ad_username = $('#admin-ad_username').val();
        var _ad_password = $('#admin-ad_password').val();
        var _adg_id      = $('#admin-adg_id > option:selected').val();
        var _ad_status   = $('#admin-ad_status:checked').val() == '1' ? "<?php echo Admin::STATUS_ON; ?>" : "<?php echo Admin::STATUS_OFF; ?>";
        var _pv_ids   = '';
        var _pvg_ids  = '';
        $('input[name="Admin[pv_ids]"]:checked').each(function(){
            _pv_ids += $(this).val() + ',';
        });
        $('input[name="Admin[pvg_ids]"]:checked').each(function(){
            _pvg_ids += $(this).val() + ',';
        });
        var _ai_name  = $('#admininfo-ai_name').val();
        var _ai_phone = $('#admininfo-ai_phone').val();
        var _ai_email = $('#admininfo-ai_email').val();
        if (!checkForm()) {
            return false;
        }
        var _data = {'Admin[ad_username]': _ad_username, 'Admin[ad_password]': _ad_password, 'Admin[ad_status]': _ad_status, 'Admin[adg_id]': _adg_id, 'Admin[pv_ids]': _pv_ids, 'Admin[pvg_ids]': _pvg_ids, 'AdminInfo[ai_name]': _ai_name, 'AdminInfo[ai_phone]': _ai_phone, 'AdminInfo[ai_email]': _ai_email};
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
    var _ad_username = $('#admin-ad_username').val();
    var _ai_name = $('#admininfo-ai_name').val();
    if (_ad_username == '') {
        $('.field-admin-ad_username').removeClass('has-success').addClass('has-error').find('.help-block').text('用户名 不能为空！');
        return false;
    }
    $('.field-admin-ad_username').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_ai_name == '') {
        $('.field-admininfo-ai_name').removeClass('has-success').addClass('has-error').find('.help-block').text('姓名 不能为空！');
        return false;
    }
    $('.field-admininfo-ai_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>