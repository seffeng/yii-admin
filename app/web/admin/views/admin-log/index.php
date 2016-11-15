<?php
/**
 * 日志列表
*/

use yii\helpers\Url;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use zxf\models\services\AdminLogService;
use zxf\models\entities\AdminLog;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-body">
    <?php $form = ActiveForm::begin([
        'id'    => 'search-form',
        'options' => ['class' => 'form-inline'],
    ]); ?>
        <div class="box-body">
            <?php
                echo $form->field($formModel, 'ad_id')->label('管理员ID');
                echo $form->field($formModel, 'username', ['labelOptions' => ['class' => 'margin-left-20']]);
                echo $form->field($formModel, 'name', ['labelOptions' => ['class' => 'margin-left-20']]);
                echo $form->field($formModel, 'result', ['labelOptions' => ['class' => 'margin-left-20']])->dropDownList($resultText, ['class' => 'form-control']);
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
                ['attribute' => 'al_id'],
                ['attribute' => 'ad_id', 'value' => function($model) {
                    return ArrayHelper::getValue($model, 'admin.ad_username', '-').'('.'ID: '. $model->ad_id .'; '. ArrayHelper::getValue($model, 'admin.adminInfo.ai_name', '-') .')';
                }],
                ['attribute' => 'al_result', 'format' => 'raw', 'value' => function($model) {
                    if ($model->al_result == AdminLog::RESULT_OK) {
                        return '<span class="btn btn-success btn-sm" disabled="disabled">'. AdminLogService::getResultText($model->al_result) .'</span>';
                    } elseif ($model->al_result == AdminLog::RESULT_FAILD) {
                        return '<span class="btn btn-warning  btn-sm" disabled="disabled">'. AdminLogService::getResultText($model->al_result) .'</span>';
                    }
                    return '-';
                }],
                ['attribute' => 'al_content'],
                ['attribute' => 'al_addtime', 'value' => function($model) {
                     return date('Y-m-d H:i', $model->al_addtime);
                }],
                ['attribute' => 'al_addip', 'value' => function($model) {
                     return long2ip($model->al_addip);
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
    CLS_FORM.init({url: "<?php echo Url::to(['admin-log/index']); ?>"});

    /* 时间控件 */
    $.datetimepicker.setLocale('zh');
    $('#adminlog-add_start_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                maxDate: $('#adminlog-add_end_date').val() ? $('#adminlog-add_end_date').val() : false
            })
       },
    });
    /* 时间控件 */
    $('#adminlog-add_end_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                minDate: $('#adminlog-add_start_date').val() ? $('#adminlog-add_start_date').val() : false
            })
       },
    });

    /**
     * ajax 翻页
     * @date   2016-11-4
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
        var _ad_id    = $('#adminlog-ad_id').val();
        var _username = $('#adminlog-username').val();
        var _name     = $('#adminlog-name').val();
        var _result   = $('#adminlog-result option:checked').val();
        var _add_start_date = $('#adminlog-add_start_date').val();
        var _add_end_date   = $('#adminlog-add_end_date').val();
        var _data = {'AdminLog[ad_id]': _ad_id, 'AdminLog[username]': _username, 'AdminLog[name]': _name, 'AdminLog[result]': _result, 'AdminLog[add_start_date]': _add_start_date, 'AdminLog[add_end_date]': _add_end_date};
        CLS_MENU.reset().set_data(_data).to_url(CLS_FORM._url)
    });
});
</script>