<?php
/**
 *  @file:   DefaultController.php
 *  @brief:  默认控制器
**/

namespace appdir\admin\controllers;

use Yii;
use appdir\admin\components\AdminWebController;
use appdir\admin\models\Admin;
use yii\helpers\ArrayHelper;
use appdir\admin\models\AdminInfo;

class DefaultController extends AdminWebController {

    /**
     *  @name:   actions
     *  @brief:  action组合
    **/
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     *  @name:   actionIndex
     *  @brief:  默认action
    **/
    public function actionIndex() {
        if (Yii::$app->request->isAjax) {
            $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('index'), 'breadcrumb' => ['首页', '后台管理', '首页']], 'm' => ''];
            die(json_encode($return));
        }
        return $this->render('index');
    }

    /**
     * 登录
     * @date   2015-12-08
     */
    public function actionLogin() {
        if (isset($_POST['username']) && isset($_POST['userpass'])) {
            die(json_encode(Admin::login()));
        } else {
            if (Admin::isLogin()) {
                return $this->redirect(Yii::$app->params['admin_home']);
            }
            return $this->render('login');
        }
    }

    /**
     * 登出
     * @date   2015-12-08
     */
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionUpdateInfo() {
        $user = Yii::$app->user->identity;
        $params = [
            'model' => $user,
            'admin_info' => AdminInfo::findOne(['ad_id' => $user->ad_id]),
            'breadcrumb' => ['修改资料', '后台管理', '修改资料'],
        ];
        if (Yii::$app->request->isAjax) {
            $post = Yii::$app->request->post();
            if (count($post) > 0) {
                $model = Admin::find()->where(['ad_id' => $user->ad_id, 'ad_isdel' => Admin::DEL_NOT])->one();
                if ($model && $model->load($post) && $model->save()) {
                    $admin_info = $params['admin_info'];
                    if (!$admin_info) {
                        $admin_info = new AdminInfo();
                    }
                    if ($admin_info->load($post)) {
                        $admin_info->ad_id = $model->ad_id;
                        $admin_info->save();
                    }
                    $return = ['r' => 1, 'm' => '修改资料成功'];
                } else {
                    if ($model) {
                        $errors = $model->getFirstErrors();
                        $return = ['r' => 0, 'd' => null, 'm' => ArrayHelper::getValue($errors, function($errors){
                            if (!$errors) return '';
                            foreach ($errors as $val) {
                                return $val;
                            }
                        })];
                    } else {
                        $return = ['r' => 0, 'd' => null, 'm' => '帐号资料错误'];
                    }
                }
            } else {
                $return = ['r' => 1, 'd' => ['content' => $this->renderPartial('update-info', $params), 'breadcrumb' => $params['breadcrumb']], 'm' => ''];
            }
            die(json_encode($return));
        }
        return $this->render('update-info', $params);
    }
}