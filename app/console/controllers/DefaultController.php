<?php
/**
 *  @file:   DefaultController.php
 *  @brief:  默认控制器
**/

namespace appdir\console\controllers;

use yii\console\Controller;

class DefaultController extends Controller {

    /**
     *  @name:   actionIndex
     *  @brief:  默认action
    **/
    public function actionIndex() {
        echo 'test console';
        return 0;
    }
}