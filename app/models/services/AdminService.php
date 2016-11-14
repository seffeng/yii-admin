<?php

namespace zxf\models\services;

use Yii;
use zxf\models\entities\Admin;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use zxf\models\entities\AdminLoginLog;

/**
 * 管理员
 * @author ZhangXueFeng
 * @date   2016年11月2日
 */
class AdminService {

    /**
     * 是否登录
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return boolean
     */
    public static function isLogin() {
        return Yii::$app->user->isGuest ? FALSE : TRUE;
    }

    /**
     * 登录
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @return array
     */
    public static function login() {
        $post  = Yii::$app->request->post();
        $form = new Admin();
        $form->setScenario(ConstService::SCENARIO_LOGIN);
        $return = ['r' => 0, 'd' => NULL, 'm' => '帐号不存在或密码错误！', 'u' => ''];
        if ($form->load($post, '') &&  $form->validate()) {
            $model = self::getByUsername($form->username);
            if ($model) {
                if (password_verify($form->userpass, $model->ad_password)) {
                    if ($model->ad_status == Admin::STATUS_OFF) {
                        $return['m'] = '帐号已停用！';
                    } else {
                        Yii::$app->user->login($model);
                        $return['r'] = 1;
                        $return['m'] = '登录成功！';
                        $return['u'] = Yii::$app->homeUrl;
                    }
                }
                $logData = [
                    'ad_id'   => $model->ad_id,
                    'type'    => AdminLoginLog::TYPE_LOGIN,
                    'result'  => $return['r'] == 1 ? AdminLoginLog::RESULT_OK : AdminLoginLog::RESULT_FAILD,
                    'content' => '登录:'.$return['m'].'[username='.$form->username.']'
                ];
                AdminLoginLogService::addLog($logData);
            }
            return $return;
        }
        $return['m'] = FunctionService::getErrorsForString($form);
        return $return;
    }

    /**
     * 根据用户名查询管理员
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param string $username 用户名
     * @return \yii\db\ActiveRecord|NULL
     */
    public static function getByUsername($username) {
        return Admin::find()->byUsername($username)->byIsDel()->limit(1)->one();
    }

    /**
     * 密码加密
     * @author ZhangXueFeng
     * @date   2016年11月3日
     * @param  string $password 密码
     * @return string
     */
    public static function encryptPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 管理员列表
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @param  mixed $form
     * @param  integer $page      当前页码
     * @param  integer $pageSize  每页显示数量
     * @return \zxf\models\services\ActiveDataProvider
     */
    public static function getList($form=NULL, $page=1, $pageSize=10) {
        $query = Admin::find();
        if (isset($form->ad_id) && $form->ad_id > 0) {
            $query->byId($form->ad_id);
        }
        if (isset($form->username) && $form->username != '') {
            $query->byUsername($form->username);
        }
        if (isset($form->name) && $form->name != '') {
            $query->byName($form->name);
        }
        if (isset($form->status) && $form->status > 0) {
            $query->byStatus($form->status);
        }
        if (isset($form->add_start_date) && $form->add_start_date != '') {
            $query->andWhere(['>=', 'ad_addtime', strtotime($form->add_start_date)]);
        }
        if (isset($form->add_end_date) && $form->add_end_date != '') {
            $query->andWhere(['<=', 'ad_addtime', strtotime($form->add_end_date) + 86400]);
        }
        $query->byIsDel();
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page'     => $page - 1,
                'pageSize' => $pageSize,
            ],
            'sort' => [
                'attributes' => ['ad_id', 'ad_lasttime'],
                'defaultOrder' => ['ad_id' => SORT_DESC]
            ]
        ]);
    }

    /**
     * 状态说明
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @param  integer $status
     * @return string
     */
    public static function getStatusText($status) {
        return ArrayHelper::getValue(Admin::STATUS_TEXT, $status, '-');
    }

    /**
     * 根据ID查询
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @param  integer $id
     * @return \zxf\models\entities\Admin|\zxf\models\entities\NULL
     */
    public static function getById($id) {
        return Admin::find()->byId($id)->byIsDel()->limit(1)->one();
    }

    /**
     * 状态是否启用
     * @author ZhangXueFeng
     * @date   2016年11月7日
     * @param  integer $status
     * @return boolean
     */
    public static function statusIsOn($status) {
        return $status == Admin::STATUS_ON ? TRUE : FALSE;
    }
}