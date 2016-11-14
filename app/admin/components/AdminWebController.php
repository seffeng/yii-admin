<?php
/**
 *  @file:   AdminController.php
 *  @brief:  默认控制器
**/

namespace appdir\admin\components;

use Yii;
use yii\web\Controller;
use appdir\admin\models\Admin;
use appdir\admin\models\Purview;

class AdminWebController extends Controller {

    public $enableCsrfValidation = false;

    public function beforeAction($actions) {
        $action = $actions->id;
        $controller = $actions->controller->id;
        $ignore = ['default/login', 'default/logout', 'default/update-info', 'default/index', 'menu/get-menu']; /* 忽略权限 */
        if (in_array($controller .'/'. $action, $ignore)) {
            return parent::beforeAction($actions);
        } else {
            if (!Admin::isLogin()) {
                return $this->redirect(Yii::$app->user->loginUrl);
            }
            if (!Purview::check_purview($controller, $action)) {
                die(json_encode(['r' => 0, 'm' => '无此操作权限']));
            }
            return parent::beforeAction($actions);
        }
    }
}