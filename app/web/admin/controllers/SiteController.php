<?php

namespace zxf\web\admin\controllers;

use Yii;
use zxf\web\admin\components\Controller;
use zxf\models\services\AdminService;
use zxf\models\entities\AdminLoginLog;
use zxf\models\services\AdminLoginLogService;

class SiteController extends Controller {

    /**
     * 首页
     * @author ZhangXueFeng
     * @date   2016年11月2日
     * @return string
     */
    public function actionIndex() {
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index'), 'breadcrumb' => ['首页', '后台管理', '首页']], 'm' => ''];
            return $return;
        }
        return $this->render('index');
    }

    /**
     * 登录
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return string
     */
    public function actionLogin() {
        if (AdminService::isLogin()) {
            return $this->goHome();
        }
        if (Yii::$app->request->isAjax) {
            return AdminService::login();
        }
        return $this->render('login');
    }

    /**
     * 登出
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return \yii\web\Response
     */
    public function actionLogout() {
        if (AdminService::isLogin()) {
            $user = Yii::$app->getUser()->getIdentity();
            $logData = [
                'ad_id'   => $user->ad_id,
                'type'    => AdminLoginLog::TYPE_LOGOUT,
                'result'  => AdminLoginLog::RESULT_OK,
                'content' => '登出:[username='.$user->ad_username.']'
            ];
            AdminLoginLogService::addLog($logData);
            Yii::$app->user->logout();
        }
        return $this->redirect(Yii::$app->user->loginUrl);
    }

    /**
     * 错误提示
     * @author ZhangXueFeng
     * @date   2016年11月9日
     * @return string
     */
    public function actionError() {
        $exception = Yii::$app->getErrorHandler()->exception;
        if ($exception) {
            $status    = $exception->getCode() ?: 404;
            $message   = $status == 404 ? '页面不存在！' : $exception->getMessage();
        } else {
            $status    = 404;
            $message   = '页面不存在！';
        }
        if (Yii::$app->request->isAjax) {
            return ['r' => 0, 'd' => $status, 'm' => $message];
        }
        return $this->render('error', ['status' => $status, 'message' => $message]);
    }
}