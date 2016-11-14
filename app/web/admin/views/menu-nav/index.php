<?php
/**
 * 导航列表
*/

use yii\helpers\Url;
use yii\grid\GridView;
use zxf\models\entities\MenuNav;
use zxf\models\services\MenuNavService;
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
                echo $form->field($formModel, 'mn_name');
                echo $form->field($formModel, 'mn_type', ['labelOptions' => ['class' => 'margin-left-20']])->dropDownList($typeText, ['class' => 'form-control']);
                echo $form->field($formModel, 'mn_status', ['labelOptions' => ['class' => 'margin-left-20']])->dropDownList($statusText, ['class' => 'form-control']);
                echo $form->field($formModel, 'add_start_date', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '修改时间'], 'labelOptions' => ['class' => 'margin-left-20']]);
                echo $form->field($formModel, 'add_end_date', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '修改时间']])->label(' - ');
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
                            return $editPurview ? (($delPurview ? '' : '<span class="margin10"></span>').'<a class="text-navy" href="javascript:;" title="编辑"><span class="glyphicon glyphicon-pencil" data="'. $model->mn_id .'" adm="edit"></span></a>') : FALSE;
                        },
                        'del' => function($url, $model) use ($delPurview) {
                            return $delPurview ? '<a class="text-navy" href="javascript:;" title="删除"><span class="glyphicon glyphicon-trash" data="'. $model->mn_id .'" adm="del"></span></a>' : FALSE;
                        },
                    ],
                ],
                ['attribute' => 'mn_name', 'label' => '名称'],
                ['attribute' => 'mn_url', 'label' => '菜单地址'],
                ['attribute' => 'mn_icon', 'label' => '菜单图标'],
                ['attribute' => 'mn_type', 'label' => '菜单类别', 'value' => function($model) { return MenuNavService::getTypeText($model->mn_type); }],
                ['attribute' => 'mn_pid', 'label' => '父导航ID'],
                ['attribute' => 'mn_status', 'label' => '状态', 'format' => 'raw', 'value' => function($model) {
                    $status = MenuNavService::getStatusText($model->mn_status);
                    if ($model->mn_status == MenuNav::STATUS_ON) {
                        return '<span class="btn btn-success btn-sm" disabled="disabled">'. $status .'</span>';
                    } elseif ($model->mn_status == MenuNav::STATUS_OFF) {
                        return '<span class="btn btn-warning btn-sm" disabled="disabled">'. $status .'</span>';
                    } else {
                        return $status;
                    }
                }],
                ['attribute' => 'mn_lasttime', 'label' => '修改时间', 'value' => function($model) {
                     return date('Y-m-d H:i', $model->mn_lasttime);
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
    CLS_FORM.init({url: "<?php echo Url::to(['menu-nav/index']); ?>", url_add: "<?php echo Url::to(['menu-nav/add']); ?>", url_edit: "<?php echo Url::to(['menu-nav/edit']); ?>", url_del: "<?php echo Url::to(['menu-nav/del']); ?>"});

    /* 时间控件 */
    $.datetimepicker.setLocale('zh');
    $('#menunav-add_start_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                maxDate: $('#menunav-add_end_date').val() ? $('#menunav-add_end_date').val() : false
            })
       },
    });
    /* 时间控件 */
    $('#menunav-add_end_date').datetimepicker({
        format: 'Y-m-d',
        timepicker:false,
        todayButton: true,
        onShow:function(ct){
            this.setOptions({
                minDate: $('#menunav-add_start_date').val() ? $('#menunav-add_start_date').val() : false
            })
       },
    });

    /**
     * ajax 翻页
     * @date   2016-11-3
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
        var _name     = $('#menunav-mn_name').val();
        var _status   = $('#menunav-mn_status option:checked').val();
        var _type     = $('#menunav-mn_type option:checked').val();
        var _add_start_date = $('#menunav-add_start_date').val();
        var _add_end_date   = $('#menunav-add_end_date').val();
        var _data = {'MenuNav[mn_name]': _name, 'MenuNav[mn_status]': _status, 'MenuNav[mn_type]': _type, 'MenuNav[add_start_date]': _add_start_date, 'MenuNav[add_end_date]': _add_end_date};
        CLS_MENU.reset().set_data(_data).to_url(CLS_FORM._url)
    });
});
</script>