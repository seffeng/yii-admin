<?php
/**
 *  @file:   error.php
 *  @brief:  错误文件
**/
use yii\helpers\Url;
$this->params['breadcrumb'] = ['页面错误', '后台管理', '页面错误'];
?>
<section class="content">
    <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i></h3>
            <p><a class="text-error text-red" href="<?php echo Url::to(['default/index']); ?>">页面错误》》》</a></p>
        </div>
    </div>
</section>