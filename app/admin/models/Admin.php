<?php
/**
 * 管理员 models
 */

namespace appdir\admin\models;

use Yii;
use appdir\admin\components\Model;
use appdir\admin\models\AdminQuery;
use appdir\admin\models\AdminLog;

class Admin extends Model implements \yii\web\IdentityInterface {

    /* ad_status */
    const STATUS_NORMAL = 1;  /* 启用 */
    const STATUS_STOP   = 2;  /* 停用 */
    /* ad_isdel */
    const DEL_NOT       = 0;  /* 未删除 */
    const DEL_YET       = 1;  /* 已删除 */

    const SUPER_ADMIN_GROUP = 1;    /* 超级管理员组ID */

    /**
     * 状态说明
     * @var array
     */
    public static $statusText = [
        self::STATUS_NORMAL => '启用',
        self::STATUS_STOP   => '停用',
    ];

    /**
     * 表名
     * @author ZhangXueFeng
     * @date   2015年12月4日
     * @return string
     */
    public static function tableName() {
        return '{{%admin}}';
    }

    /**
     * 重载 find
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @return mixed
     */
    public static function find() {
        return new AdminQuery(get_called_class());
    }

    /**
     * 字段规则
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @return array
     */
    public function rules() {
        return [
            [['ad_password'], 'required', 'message' => '请填写{attribute}', 'on' => 'insert'],
            [['ad_username', 'ad_status'], 'required', 'message' => '请填写{attribute}'],
            [['ad_username', 'ad_password', 'pv_ids', 'pvg_ids'], 'string'],
            [['ad_username'], 'check_username'],
            [['ad_status', 'adg_id'], 'integer'],
        ];
    }

    /**
     * 字段说明
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @return [array]
     */
    public function attributeLabels() {
        return [
            'ad_id'    => 'ID',
            'ad_username'  => '用户名',
            'ad_password'  => '用户密码',
            'adg_ids'    => '管理员组',
            'ad_status'  => '状态',
            'ad_addtime' => '添加时间',
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->ad_addtime = THIS_TIME;
            $this->ad_addip   = THIS_IP_LONG;
            $this->ad_password = self::enpassword($this->ad_password);
        } else {
            $this->ad_lasttime = THIS_TIME;
            $this->ad_lastip   = THIS_IP_LONG;
            if ($this->ad_password == '') {
                unset($this->ad_password);
            } else {
                $this->ad_password = self::enpassword($this->ad_password);
            }
        }
        if ($this->pv_ids) $this->pv_ids = ','. trim($this->pv_ids, ',') . ',';
        if ($this->pvg_ids) $this->pvg_ids = ','. trim($this->pvg_ids, ',') . ',';
        return parent::beforeSave($insert);
    }

    /**
     * 检测是否登录
     * @author ZhangXueFeng
     * @date   2015年12月4日
     * @return boolean
     */
    public static function isLogin() {
        if(Yii::$app->user->isGuest) return FALSE;
        return TRUE;
    }

    /**
     * 登录
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @return array
     */
    public static function login() {
        $request = Yii::$app->request;
        $username = $request->post('username', '');
        $userpass = $request->post('userpass', '');
        $return = ['r' => 0, 'm' => 'error', 'u' => ''];
        $admin = self::find()->byUsername($username)->byPassword(self::enpassword($userpass))->is_del()->one();
        if (isset($admin->ad_id)) {
            if ($admin->ad_status == self::STATUS_STOP) {
                $return['m'] = '该帐号已停用';
            } else {
                Yii::$app->user->login($admin);
                $return['r'] = 1;
                $return['m'] = '登录成功';
                $return['u'] = Yii::$app->params['admin_home'];
            }
        } else {
            $admin = self::find()->byUsername($username)->one();
            $return['m'] = '用户名或密码错误';
        }
        if (isset($admin->ad_id)) {
            $log_data = ['content' => '登录:'.$return['m'], 'ad_id' => $admin->ad_id, 'result' => $return['r'] == 1 ? AdminLog::RESULT_OK : AdminLog::RESULT_FAILD];
            AdminLog::addLog($log_data);
        }
        return $return;
    }

    /**
     * 密码加密
     * @date   2015-12-08
     * @author ZhangXueFeng
     * @param  [string]     $pass [密码]
     * @return [string]
     */
    public static function enpassword($pass) {
        $md5_pass = md5($pass);
        return md5(substr($md5_pass, hexdec($md5_pass[0]), hexdec($md5_pass[1])).$pass);
    }

    /**
     * 返回状态说明
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @param  integer     $status [状态]
     * @return string
     */
    public static function getStatusText($status) {
        if (isset(static::$statusText[$status])) {
            return static::$statusText[$status];
        }
        return '-';
    }

    /**
     * 检测用户名是否存在
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @param  string $attribute 字段
     * @return boolean
     */
    public function check_username($attribute) {
        if($this->ad_isdel == self::DEL_YET) return FALSE;
        $this->ad_username = trim($this->ad_username);
        $model = self::find()->byUsername($this->ad_username)->is_del()->one();
        if ($model && $model->ad_id != $this->ad_id) {
            $this->addError($attribute, '该用户名已经存在');
        }
    }

    /**
     * 关联管理员组
     * @author ZhangXueFeng
     * @date   2016年1月5日
     * @return ActiveQuery
     */
    public function getAdminGroup() {
        return $this->hasOne(AdminGroup::className(), ['adg_id' => 'adg_id']);
    }

    /**
     * 查询管理员权限
     * @author ZhangXueFeng
     * @date   2016年1月5日
     * @return array
     */
    public static function getAdminPurview() {
        $purview = [];
        if (self::isLogin()) {
            $user = Yii::$app->user->identity;
            $userGroup = $user->adminGroup;
            $pvg_ids = $user->pvg_ids;
            isset($userGroup->pvg_ids) && $pvg_ids .= $userGroup->pvg_ids;
            $pvg_ids = array_unique(array_filter(explode(',', trim($pvg_ids))));
            $pv_ids = $user->pv_ids;
            isset($userGroup->pv_ids) && $pv_ids .= $userGroup->pv_ids;
            $purviewGroup = [];
            $pvg_ids && $purviewGroup = PurviewGroup::find()->select('pv_ids')->where(['pvg_id' => $pvg_ids, 'pvg_isdel' => PurviewGroup::DEL_NOT])->column();
            if (is_foreach($purviewGroup)) foreach ($purviewGroup as $val) {
                $pv_ids .= $val;
            }
            $pv_ids = array_unique(array_filter(explode(',', trim($pv_ids))));
            $result = Purview::find()->where(['pv_id' => $pv_ids, 'pv_isdel' => Purview::DEL_NOT])->all();
            if (is_foreach($result)) foreach ($result as $val) {
                $purview['id'][] = $val->pv_id;
                $purview['key'][] = $val->pv_key;
            }
        }
        return $purview;
    }

    /**
     * 关联管理员资料
     * @author ZhangXueFeng
     * @date   2016年1月6日
     * @return ActiveQuery
     */
    public function getAdminInfo() {
        return $this->hasOne(AdminInfo::className(), ['ad_id' => 'ad_id']);
    }

    public static function findIdentity($id) {
        return self::findOne(['ad_id' => $id]);
    }

    public function getId() {
        return $this->ad_id;
    }

    public function getAuthKey() {
        return '';
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return NULL;
    }

    public function validateAuthKey($authKey) {
        return TRUE;
    }
}