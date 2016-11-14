<?php
/**
 * 导航列表
 */

use yii\grid\GridView;
use yii\helpers\Url;
use appdir\admin\models\Menu;

$this->params['breadcrumb'] = isset($breadcrumb) ? $breadcrumb : [];
?>
<div class="box-body">
    <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'options'   => ['class' => 'grid-view table-responsive'],
            'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
            'layout'    => "{items}\n{pager}",
            'emptyText' => '暂无数据',
            'columns'   => [
                ['label' => '操作', 'format' => 'raw', 'headerOptions' => ['class' => 'th-sm'], 'value' => function($model) {
                    return '<a class="text-navy" href="javascript:;" title="编辑"><span class="glyphicon glyphicon-pencil pull-left" data="'. $model->mn_id .'" adm="edit">&nbsp;</span></a><a class="text-navy" href="javascript:;" title="删除"><span class="glyphicon glyphicon-trash pull-left" data="'. $model->mn_id .'" adm="del"></span></a>';
                }],
                ['attribute' => 'mn_name', 'label' => '名称'],
                ['attribute' => 'mn_url', 'label' => '菜单地址'],
                ['attribute' => 'mn_icon', 'label' => '菜单图标'],
                ['attribute' => 'mn_type', 'label' => '菜单类别', 'value' => function($model) { return Menu::getTypeText($model->mn_type); }],
                ['attribute' => 'mn_pid', 'label' => '父导航ID'],
                ['attribute' => 'mn_status', 'label' => '状态', 'format' => 'raw', 'value' => function($model) {
                    $status = Menu::getStatusText($model->mn_status);
                    if ($model->mn_status == Menu::STATUS_NORMAL) {
                        return '<span class="btn btn-success btn-sm" disabled="disabled">'. $status .'</span>';
                    } elseif ($model->mn_status == Menu::STATUS_STOP) {
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
    CLS_FORM.init({url: "<?php echo Url::to(['menu/index']); ?>", url_add: "<?php echo Url::to(['menu/add']); ?>", url_edit: "<?php echo Url::to(['menu/edit']); ?>", url_del: "<?php echo Url::to(['menu/del']); ?>"});

    /**
     * ajax 翻页
     * @date   2015-12-10
     */
    $('ul.pagination li a').on('click', function(){
        var _page = parseInt($(this).attr('data-page')) + 1;
        var _url = "<?php $url = Yii::$app->request->getUrl(); $url = parse_url($url); if(isset($url['path'])) { echo $url['path']; } ?>";
        var _query = "<?php if(isset($url['query'])) { echo $url['query']; } ?>";
        var _query_arr = _query.split('&');
        var _data = {page: _page};
        for(var i in _query_arr) {
            var _tmp = _query_arr[i].split('=');
            if (typeof(_tmp[0]) != 'undefined' && typeof(_tmp[1]) != 'undefined' && _tmp[0] != 'page') _data[_tmp[0]] = _tmp[1];
        }
        CLS_MENU.set_data(_data).to_url(_url);
        return false;
    });
});
</script>