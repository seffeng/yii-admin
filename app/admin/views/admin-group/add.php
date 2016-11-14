<?php

use yii\helpers\Url;
use yii\helpers\Html;
use appdir\admin\models\AdminGroup;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-primary">
    <div class="box-header"></div>
    <div id="add-form" class="form-horizontal box-body">
        <div class="form-group field-adg_name">
            <label class="col-lg-2 control-label" for="adg_name">组名：</label>
            <div class="col-lg-4"><input type="text" id="adg_name" class="form-control" name="AdminGroup[adg_name]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-adg_status">
            <label class="col-lg-2 control-label" for="adg_status">状态：</label>
            <div class="col-lg-4">
            <?php echo Html::checkbox('AdminGroup[adg_status]', TRUE, ['id' => 'adg_status']); ?></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-pv_id">
            <label class="col-lg-2 control-label">权限：</label>
            <div class="col-lg-8" id="pv_ids">
                <?php
                    if ($purview) :
                    foreach ($purview as $key => $val) :
                ?>
                <label><input type="checkbox" value="<?php echo $key; ?>" name="AdminGroup[pv_ids]" ><?php echo $val; ?></label>&nbsp;
                <?php endforeach; endif; ?>
            </div>
            <div class="col-lg-2"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-pvg_id">
            <label class="col-lg-2 control-label">权限组：</label>
            <div class="col-lg-8" id="pvg_ids">
                <?php
                    if ($purviewGroup) :
                    foreach ($purviewGroup as $key => $val) :
                ?>
                <label><input type="checkbox" value="<?php echo $key; ?>" name="AdminGroup[pvg_ids]" ><?php echo $val; ?></label>&nbsp;
                <?php endforeach; endif; ?>
            </div>
            <div class="col-lg-2"><div class="help-block"></div></div>
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
    CLS_FORM.init({url: "<?php echo Url::to(['admin-group/index']); ?>", url_add: "<?php echo Url::to(['admin-group/add']); ?>", url_edit: "<?php echo Url::to(['admin-group/edit']); ?>", url_del: "<?php echo Url::to(['admin-group/del']); ?>"});

    /* 状态 */
    $('input[name="AdminGroup[adg_status]"]').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 添加管理员组
     */
    $('button[adm="submit"]').on('click', function(){
        var _adg_name = $('#adg_name').val();
        var _adg_status   = $('#adg_status:checked').val() == '1' ? "<?php echo AdminGroup::STATUS_NORMAL; ?>" : "<?php echo AdminGroup::STATUS_STOP; ?>";
        var _pv_ids      = '';
        var _pvg_ids     = '';
        $('input[name="AdminGroup[pv_ids]"]:checked').each(function(){
            _pv_ids += $(this).val() + ',';
        });
        $('input[name="AdminGroup[pvg_ids]"]:checked').each(function(){
            _pvg_ids += $(this).val() + ',';
        });
        if (!checkForm()) {
            return false;
        }
        var _data = {'AdminGroup[adg_name]': _adg_name, 'AdminGroup[adg_status]': _adg_status, 'AdminGroup[pv_ids]': _pv_ids, 'AdminGroup[pvg_ids]': _pvg_ids};
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
    var _adg_name    = $('#adg_name').val();
    if (_adg_name == '') {
        $('.field-adg_name').removeClass('has-success').addClass('has-error').find('.help-block').text('请输入组名');
        return false;
    }
    $('.field-adg_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>