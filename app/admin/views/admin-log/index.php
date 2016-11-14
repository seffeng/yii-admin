<?php
/**
 * 日志列表
 */

use yii\grid\GridView;
use yii\helpers\Url;
use appdir\admin\models\AdminLog;

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
                ['label' => '管理员', 'value' => function($model) {
                    return isset($model->admin->ad_username) ? $model->admin->ad_username : '-';
                }],
                ['attribute' => 'al_result', 'format' => 'raw', 'value' => function($model) {
                    $status = AdminLog::getResultText($model->al_result);
                    if ($model->al_result == AdminLog::RESULT_OK) {
                        return '<span class="btn btn-success btn-sm" disabled="disabled">'. $status .'</span>';
                    } elseif ($model->al_result == AdminLog::RESULT_FAILD) {
                        return '<span class="btn btn-warning btn-sm" disabled="disabled">'. $status .'</span>';
                    } else {
                        return $status;
                    }
                }],
                ['attribute' => 'al_content'],
                ['attribute' => 'al_lasttime', 'value' => function($model) {
                     return date('Y-m-d H:i', $model->al_lasttime);
                }],
                ['attribute' => 'al_lastip', 'value' => function($model) {
                     return long_ip($model->al_lastip);
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
    /* 排序 */
    CLS_FORM.sort();

    /**
     * ajax 翻页
     * @date   2015-12-28
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