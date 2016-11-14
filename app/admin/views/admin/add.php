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
            <div class="col-lg-4"><input type="text" id="admin-ad_username" class="form-control" name="Admin[ad_username]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-admin-ad_password">
            <label class="col-lg-2 control-label" for="admin-ad_password">密码：</label>
            <div class="col-lg-4"><input type="text" id="admin-ad_password" class="form-control" name="Admin[ad_password]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-admin-adg_id">
            <label class="col-lg-2 control-label" for="admin-adg_id">管理员组：</label>
            <div class="col-lg-4">
                <?php echo Html::dropDownList('Admin[adg_id]', 0, $admin_group, ['class' => 'form-control', 'id' => 'admin-adg_id']); ?>
            </div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-admin-ad_status">
            <label class="col-lg-2 control-label" for="admin-ad_status">状态：</label>
            <div class="col-lg-4"><?php echo Html::checkbox('Admin[ad_status]', TRUE, ['id' => 'admin-ad_status']); ?></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-admin-pv_id">
            <label class="col-lg-2 control-label">权限：</label>
            <div class="col-lg-8">
                <?php
                    if ($purview) :
                    foreach ($purview as $key => $val) :
                ?>
                <label><input type="checkbox" value="<?php echo $key; ?>" name="Admin[pv_ids]" ><?php echo $val; ?></label>&nbsp;
                <?php endforeach; endif; ?>
            </div>
            <div class="col-lg-2"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-admin-pvg_id">
            <label class="col-lg-2 control-label">权限组：</label>
            <div class="col-lg-8">
                <?php
                    if ($purviewGroup) :
                    foreach ($purviewGroup as $key => $val) :
                ?>
                <label><input type="checkbox" value="<?php echo $key; ?>" name="Admin[pvg_ids]" ><?php echo $val; ?></label>&nbsp;
                <?php endforeach; endif; ?>
            </div>
            <div class="col-lg-2"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-ai_name">
            <label class="col-lg-2 control-label" for="ai_name">姓名：</label>
            <div class="col-lg-4"><input type="text" id="ai_name" class="form-control" name="AdminInfo[ai_name]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-ai_nickname">
            <label class="col-lg-2 control-label" for="ai_nickname">昵称：</label>
            <div class="col-lg-4"><input type="text" id="ai_nickname" class="form-control" name="AdminInfo[ai_nickname]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-ai_phone">
            <label class="col-lg-2 control-label" for="ai_phone">手机：</label>
            <div class="col-lg-4"><input type="text" id="ai_phone" class="form-control" name="AdminInfo[ai_phone]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-ai_email">
            <label class="col-lg-2 control-label" for="ai_email">邮箱：</label>
            <div class="col-lg-4"><input type="text" id="ai_email" class="form-control" name="AdminInfo[ai_email]" value=""></div>
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
    CLS_FORM.init({url: "<?php echo Url::to(['admin/index']); ?>", url_add: "<?php echo Url::to(['admin/add']); ?>", url_edit: "<?php echo Url::to(['admin/edit']); ?>", url_del: "<?php echo Url::to(['admin/del']); ?>"});

    /* 状态 */
    $('input[name="Admin[ad_status]"]').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 添加管理员
     */
    $('button[adm="submit"]').on('click', function(){
        var _ad_username = $('#admin-ad_username').val();
        var _ad_password = $('#admin-ad_password').val();
        var _adg_id      = $('#admin-adg_id option:selected').val();
        var _pv_ids      = '';
        var _pvg_ids     = '';
        var _ad_status   = $('#admin-ad_status:checked').val() == '1' ? "<?php echo Admin::STATUS_NORMAL; ?>" : "<?php echo Admin::STATUS_STOP; ?>";
        $('input[name="Admin[pv_ids]"]:checked').each(function(){
            _pv_ids += $(this).val() + ',';
        });
        $('input[name="Admin[pvg_ids]"]:checked').each(function(){
            _pvg_ids += $(this).val() + ',';
        });
        var _ai_name     = $('#ai_name').val();
        var _ai_nickname = $('#ai_nickname').val();
        var _ai_phone    = $('#ai_phone').val();
        var _ai_email    = $('#ai_email').val();
        if (!checkForm()) {
            return false;
        }
        var _data = {'Admin[ad_username]': _ad_username, 'Admin[ad_password]': _ad_password, 'Admin[ad_status]': _ad_status, 'Admin[adg_id]': _adg_id, 'Admin[pv_ids]': _pv_ids, 'Admin[pvg_ids]': _pvg_ids, 'AdminInfo[ai_name]': _ai_name, 'AdminInfo[ai_nickname]': _ai_nickname, 'AdminInfo[ai_phone]': _ai_phone, 'AdminInfo[ai_email]': _ai_email};
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
    var _ad_username = $('#admin-ad_username').val();
    var _ad_password = $('#admin-ad_password').val();
    var _ai_phone    = $('#ai_phone').val();
    var _ai_email    = $('#ai_email').val();
    if (_ad_username == '') {
        $('.field-admin-ad_username').removeClass('has-success').addClass('has-error').find('.help-block').text('请输入用户名');
        return false;
    }
    $('.field-admin-ad_username').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    if (_ad_password == '') {
        $('.field-admin-ad_password').removeClass('has-success').addClass('has-error').find('.help-block').text('请输入用户密码');
        return false;
    }
    $('.field-admin-ad_password').removeClass('has-error').addClass('has-success').find('.help-block').text('');
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