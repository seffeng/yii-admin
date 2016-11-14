<?php

use yii\helpers\Url;
use yii\helpers\Html;
use appdir\admin\models\Admin;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-primary">
    <div class="box-header"></div>
    <div id="add-form" class="form-horizontal box-body">
        <div class="form-group field-admin-ad_username">
            <label class="col-lg-2 control-label" for="admin-ad_username">用户名：</label>
            <div class="col-lg-4"><input type="text" id="admin-ad_username" class="form-control" name="Admin[ad_username]" value="<?php echo $model->ad_username; ?>" readonly ></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-admin-ad_password">
            <label class="col-lg-2 control-label" for="admin-ad_password">密码：</label>
            <div class="col-lg-4"><input type="text" id="admin-ad_password" class="form-control" name="Admin[ad_password]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-ai_name">
            <label class="col-lg-2 control-label" for="ai_name">姓名：</label>
            <div class="col-lg-4"><input type="text" id="ai_name" class="form-control" name="AdminInfo[ai_name]" value="<?php echo isset($admin_info->ai_name) ? $admin_info->ai_name : ''; ?>"></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-ai_nickname">
            <label class="col-lg-2 control-label" for="ai_nickname">昵称：</label>
            <div class="col-lg-4"><input type="text" id="ai_nickname" class="form-control" name="AdminInfo[ai_nickname]" value="<?php echo isset($admin_info->ai_nickname) ? $admin_info->ai_nickname : ''; ?>"></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-ai_phone">
            <label class="col-lg-2 control-label" for="ai_phone">手机：</label>
            <div class="col-lg-4"><input type="text" id="ai_phone" class="form-control" name="AdminInfo[ai_phone]" value="<?php echo isset($admin_info->ai_phone) ? $admin_info->ai_phone : ''; ?>"></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-ai_email">
            <label class="col-lg-2 control-label" for="ai_email">邮箱：</label>
            <div class="col-lg-4"><input type="text" id="ai_email" class="form-control" name="AdminInfo[ai_email]" value="<?php echo isset($admin_info->ai_email) ? $admin_info->ai_email : ''; ?>"></div>
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
    CLS_FORM.init({url: "<?php echo Url::to(['default/update-info']); ?>", url_edit: "<?php echo Url::to(['default/update-info']); ?>"});

    /**
     * 编辑
     */
    $('button[adm="submit"]').on('click', function(){
        var _ad_password = $('#admin-ad_password').val();
        var _ai_name     = $('#ai_name').val();
        var _ai_nickname = $('#ai_nickname').val();
        var _ai_phone    = $('#ai_phone').val();
        var _ai_email    = $('#ai_email').val();
        if (!checkForm()) {
            return false;
        }
        var _data = {'Admin[ad_password]': _ad_password, 'AdminInfo[ai_name]': _ai_name, 'AdminInfo[ai_nickname]': _ai_nickname, 'AdminInfo[ai_phone]': _ai_phone, 'AdminInfo[ai_email]': _ai_email};
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
    var _ai_phone    = $('#ai_phone').val();
    var _ai_email    = $('#ai_email').val();
    if (_ai_phone != '' && !CLS_GLOBAL.check_data(_ai_phone, 'mobile')) {
        $('.field-ai_phone').removeClass('has-success').addClass('has-error').find('.help-block').text('手机号格式错误');
        return false;
    }
    $('.field-ai_phone').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_ai_email != '' && !CLS_GLOBAL.check_data(_ai_email, 'email')) {
        $('.field-ai_email').removeClass('has-success').addClass('has-error').find('.help-block').text('邮箱格式错误');
        return false;
    }
    $('.field-ai_email').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>