<?php
/**
 * 权限列表
 */

use yii\grid\GridView;
use yii\helpers\Url;
use appdir\admin\models\Purview;

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
                    return '<a class="text-navy" href="javascript:;" title="编辑"><span class="glyphicon glyphicon-pencil pull-left" data="'. $model->pv_id .'" adm="edit">&nbsp;</span></a><a class="text-navy" href="javascript:;" title="删除"><span class="glyphicon glyphicon-trash pull-left" data="'. $model->pv_id .'" adm="del"></span></a>';
                }],
                ['attribute' => 'pv_name'],
                ['attribute' => 'pv_key'],
                ['attribute' => 'pv_status', 'format' => 'raw', 'value' => function($model) {
                    $status = Purview::getStatusText($model->pv_status);
                    if ($model->pv_status == Purview::STATUS_NORMAL) {
                        return '<span class="btn btn-success btn-sm" disabled="disabled">'. $status .'</span>';
                    } elseif ($model->pv_status == Purview::STATUS_STOP) {
                        return '<span class="btn btn-warning btn-sm" disabled="disabled">'. $status .'</span>';
                    } else {
                        return $status;
                    }
                }],
                ['attribute' => 'pv_lasttime', 'value' => function($model) {
                     return date('Y-m-d H:i', $model->pv_lasttime);
                }],
                ['attribute' => 'pv_lastip', 'value' => function($model) {
                     return long_ip($model->pv_lastip);
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
    CLS_FORM.init({url: "<?php echo Url::to(['purview/index']); ?>", url_add: "<?php echo Url::to(['purview/add']); ?>", url_edit: "<?php echo Url::to(['purview/edit']); ?>", url_del: "<?php echo Url::to(['purview/del']); ?>"});

    /**
     * ajax 翻页
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