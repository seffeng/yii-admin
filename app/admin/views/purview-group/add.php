<?php

use yii\helpers\Url;
use yii\helpers\Html;
use appdir\admin\models\PurviewGroup;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-primary">
    <div class="box-header"></div>
    <div id="add-form" class="form-horizontal box-body">
        <div class="form-group field-pvg_name">
            <label class="col-lg-2 control-label" for="pvg_name">权限名：</label>
            <div class="col-lg-4"><input type="text" id="pvg_name" class="form-control" name="PurviewGroup[pvg_name]" value=""></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-pvg_status">
            <label class="col-lg-2 control-label" for="pvg_status">状态：</label>
            <div class="col-lg-4">
            <?php echo Html::checkbox('PurviewGroup[pvg_status]', TRUE, ['id' => 'pvg_status']); ?></div>
            <div class="col-lg-6"><div class="help-block"></div></div>
        </div>
        <div class="form-group field-pv_id">
            <label class="col-lg-2 control-label">权限：</label>
            <div class="col-lg-8" id="pv_ids">
                <?php
                    if ($purview) :
                    foreach ($purview as $key => $val) :
                ?>
                <label><input type="checkbox" value="<?php echo $key; ?>" name="PurviewGroup[pv_ids]" ><?php echo $val; ?></label>&nbsp;
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
    CLS_FORM.init({url: "<?php echo Url::to(['purview-group/index']); ?>", url_add: "<?php echo Url::to(['purview-group/add']); ?>", url_edit: "<?php echo Url::to(['purview-group/edit']); ?>", url_del: "<?php echo Url::to(['purview-group/del']); ?>"});

    /* 状态 */
    $('input[name="PurviewGroup[pvg_status]"]').bootstrapSwitch({onText: '启用', offText: '停用', onColor: 'success', offColor: 'warning'});

    /**
     * 添加
     */
    $('button[adm="submit"]').on('click', function(){
        var _pvg_name = $('#pvg_name').val();
        var _pvg_status   = $('#pvg_status:checked').val() == '1' ? "<?php echo PurviewGroup::STATUS_NORMAL; ?>" : "<?php echo PurviewGroup::STATUS_STOP; ?>";
        var _pv_ids      = '';
        $('input[name="PurviewGroup[pv_ids]"]:checked').each(function(){
            _pv_ids += $(this).val() + ',';
        });
        if (!checkForm()) {
            return false;
        }
        var _data = {'PurviewGroup[pvg_name]': _pvg_name, 'PurviewGroup[pvg_status]': _pvg_status, 'PurviewGroup[pv_ids]': _pv_ids};
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
    var _pvg_name    = $('#pvg_name').val();
    if (_pvg_name == '') {
        $('.field-pvg_name').removeClass('has-success').addClass('has-error').find('.help-block').text('请输入组名');
        return false;
    }
    $('.field-pvg_name').removeClass('has-error').addClass('has-success').find('.help-block').text('');
    return true;
}
</script>