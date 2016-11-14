<?php
/**
 * 管理员列表
*/

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use zxf\models\services\AdminService;
use zxf\models\entities\Admin;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-body">
    <?php $form = ActiveForm::begin([
        'id'    => 'search-form',
        'options' => ['class' => 'form-inline'],
    ]); ?>
        <div class="box-body">
            <?php
                echo $form->field($formModel, 'username');
                echo $form->field($formModel, 'name', ['labelOptions' => ['class' => 'margin-left-20']]);
                echo $form->field($formModel, 'status', ['labelOptions' => ['class' => 'margin-left-20']])->dropDownList($statusText, ['class' => 'form-control']);
                echo $form->field($formModel, 'add_start_date', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '添加时间'], 'labelOptions' => ['class' => 'margin-left-20']]);
                echo $form->field($formModel, 'add_end_date', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '添加时间']])->label(' - ');
            ?>
            <div class="form-group field-admin-add_end_date">
                <?php
                    echo Html::button('查询', ['class'=> 'btn btn-info', 'adm' => 'submit']);
                ?>
                <div class="help-block"></div>
            </div>
        </div>
    <?php ActiveForm::end(); ?>
    <hr />
    <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'options'   => ['class' => 'grid-view table-responsive'],
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'summary'   => '共 {totalCount} 条记录，每页 {count} 条。',
            'layout'    => "{summary}\n{items}\n{pager}",
            'emptyText' => '暂无数据',
            'columns'   => [
                ['header'   => '操作', 'visible' => $delPurview || $editPurview, 'class' => 'yii\grid\ActionColumn', 'options' => ['class' => 'th-sm'], 'template' => '{edit}<span class="margin10"></span>{del}',
                    'buttons'  => [
                        'edit' => function($url, $model) use ($editPurview, $delPurview) {
                            return $editPurview ? (($delPurview ? '' : '<span class="margin10"></span>').'<a class="text-navy" href="javascript:;" title="编辑"><span class="glyphicon glyphicon-pencil" data="'. $model->ad_id .'" adm="edit"></span></a>') : FALSE;
                        },
                        'del' => function($url, $model) use ($delPurview) {
                            return $delPurview ? '<a class="text-navy" href="javascript:;" title="删除"><span class="glyphicon glyphicon-trash" data="'. $model->ad_id .'" adm="del"></span></a>' : FALSE;
                        },
                    ],
                ],
                ['attribute' => 'ad_id'],
                ['attribute' => 'ad_username'],
                ['label' => '姓名', 'value' => function ($model) {
                    return ArrayHelper::getValue($model, 'adminInfo.ai_name', '-');
                }],
                ['attribute' => 'adg_id', 'value' => function ($model) {
                    return ArrayHelper::getValue($model, 'adminGroup.adg_name', '-');
                }],
                ['attribute' => 'pv_ids'],
                ['attribute' => 'pvg_ids'],
                ['attribute' => 'ad_status', 'label' => '状态', 'format' => 'raw', 'value' => function($model) {
                    $status = AdminService::getStatusText($model->ad_status);
                    if ($model->ad_status == Admin::STATUS_ON) {
                        return '<span class="btn btn-success btn-sm" disabled="disabled">'. $status .'</span>';
                    } elseif ($model->ad_status == Admin::STATUS_OFF) {
                        return '<span class="btn btn-warning btn-sm" disabled="disabled">'. $status .'</span>';
                    } else {
                        return $status;
                    }
                }],
                ['attribute' => 'ad_lasttime', 'label' => '修改时间', 'value' => function($model) {
                     return date('Y-m-d H:i', $model->ad_lasttime);
                }],
            ],
            'pager' => [
                'firstPageLabel' => '第 1 页',
                'lastPageLabel'  => '第 '. ceil($dataProvider->totalCount / $dataProvider->pagination->pageSize).' 页',
            ],
        ]);
    ?>
</div>
<script>
$(document).ready(function(){
    /* 初始化 */
    CLS_FORM.init({url: "<?php echo Url::to(['admin/index']); ?>", url_add: "<?php echo Url::to(['admin/add']); ?>", url_edit: "<?php echo Url::to(['admin/edit']); ?>", url_del: "<?php echo Url::to(['admin/del']); ?>"});

    /* 时间控件 */
    $.datetimepicker.setLocale('zh');
    $('#admin-add_start_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                maxDate: $('#admin-add_end_date').val() ? $('#admin-add_end_date').val() : false
            })
       },
    });
    /* 时间控件 */
    $('#admin-add_end_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                minDate: $('#admin-add_start_date').val() ? $('#admin-add_start_date').val() : false
            })
       },
    });

    /**
     * ajax 翻页
     * @date   2016-11-7
     */
    $('ul.pagination li a').on('click', function(){
        var _page = parseInt($(this).attr('data-page')) + 1;
        var _url = "<?php $url = Yii::$app->request->getUrl(); $url = parse_url($url); if(isset($url['path'])) { echo $url['path']; } ?>";
        var _query = "<?php if(isset($url['query'])) { echo $url['query']; } ?>";
        var _query_arr = _query.split('&');
        var _data = {page: _page};
        for(var i in _query_arr) {
            var _tmp = _query_arr[i].split('=');
            if (typeof(_tmp[0]) != 'undefined' && typeof(_tmp[1]) != 'undefined' && _tmp[0] != 'page') _data[decodeURIComponent(_tmp[0])] = _tmp[1];
        }
        CLS_MENU.set_data(_data).to_url(_url);
        return false;
    });

    /**
     * 查询
     */
    $('button[adm="submit"]').on('click', function(){
        var _username = $('#admin-username').val();
        var _name     = $('#admin-name').val();
        var _status   = $('#admin-status option:checked').val();
        var _add_start_date = $('#admin-add_start_date').val();
        var _add_end_date   = $('#admin-add_end_date').val();
        var _data = {'Admin[username]': _username, 'Admin[name]': _name, 'Admin[status]': _status, 'Admin[add_start_date]': _add_start_date, 'Admin[add_end_date]': _add_end_date};
        CLS_MENU.reset().set_data(_data).to_url(CLS_FORM._url)
    });
});
</script>