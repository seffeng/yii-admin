<?php

namespace zxf\web\admin\components;

use Yii;
use zxf\components\WebController;
use zxf\models\services\AdminService;
use zxf\models\services\PurviewService;
use yii\helpers\Json;
use yii\web\HttpException;

class Controller extends WebController {

    /**
     * @var boolean whether to enable CSRF validation for the actions in this controller.
     * CSRF validation is enabled only when both this property and [[\yii\web\Request::enableCsrfValidation]] are true.
     */
    public $enableCsrfValidation = FALSE;

    /**
     * 
     * {@inheritDoc}
     * @see \zxf\components\WebController::beforeAction()
     */
    public function beforeAction($action) {
        if (parent::beforeAction($action)) {
            $actionId = $action->id;
            $controllerId  = $action->controller->id;
            $controllerAction = $controllerId .'/'. $actionId;
            $ignorePurview = ['site/login', 'site/logout', 'site/error', 'menu-nav/get-menu-nav', 'admin/update-self']; /* 忽略权限 */
            $ignoreLogin   = ['site/login'];    /* 忽略登录 */
            $notOnlyAjax   = ['login', 'logout', 'error', 'index'];
            if (!in_array($controllerAction, $ignoreLogin)) {
                if (!AdminService::isLogin()) {
                    if (Yii::$app->request->isAjax) {
                        die(Json::encode(['r' => 0, 'm' => '身份验证失败，请重新登录！']));
                    }
                    $this->redirect(Yii::$app->getUser()->loginUrl);
                    return FALSE;
                }
                if (!Yii::$app->request->isAjax && !in_array($actionId, $notOnlyAjax)) {
                    $this->goHome();
                    return FALSE;
                }
                if (!in_array($controllerAction, $ignorePurview)) {
                    if (!PurviewService::checkPurview($controllerId, $actionId)) {
                        if (Yii::$app->request->isAjax) {
                            die(Json::encode(['r' => 0, 'm' => '无此操作权限！']));
                        } else {
                            throw new HttpException(401, '无此操作权限！', 401);
                        }
                    }
                }
            }
            return TRUE;
        }
        return FALSE;
    }
}