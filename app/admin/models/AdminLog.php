<?php
/**
 * 管理员日志
 */

namespace appdir\admin\models;

use appdir\admin\components\Model;

class AdminLog extends Model {

    /* al_result */
    const RESULT_OK     = 1;    /* 成功 */
    const RESULT_FAILD  = 2;    /* 失败 */

    /* al_isdel */
    const DEL_NOT   = 0;    /* 未删除 */
    const DEL_YET   = 1;    /* 已删除 */

    /**
     * 结果说明
     * @var array
     */
    public static $resultText = [
        self::RESULT_OK    => '成功',
        self::RESULT_FAILD => '失败',
    ];

    /**
     * 字段说明
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @return [array]
     */
    public function attributeLabels() {
        return [
            'al_id'         => 'ID',
            'ad_id'         => '管理员ID',
            'al_result'     => '结果',
            'al_content'    => '内容',
            'al_isdel'      => '是否删除',
            'al_lasttime'   => '时间',
            'al_lastip'     => 'IP',
        ];
    }

    /**
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave($insert)
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->al_isdel     = self::DEL_NOT;
            $this->al_lasttime  = THIS_TIME;
            $this->al_lastip    = THIS_IP_LONG;
        }
        return parent::beforeSave($insert);
    }

    /**
     * 返回结果说明
     * @date   2015-12-28
     * @author ZhangXueFeng
     * @param  integer     $result [结果]
     * @return string
     */
    public static function getResultText($result) {
        if (isset(static::$resultText[$result])) {
            return static::$resultText[$result];
        }
        return '-';
    }

    /**
     * 添加日志
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @param  array $data  ['ad_id' => '', 'al_content' => 'content', 'al_result' => 'result']
     * @return boolean
     */
    public static function addLog($data) {
        if (!isset($data['ad_id']) || $data['ad_id'] < 1) return FALSE;
        if (!isset($data['content']) || $data['content'] == '') return FALSE;
        if (!isset($data['result']) || !in_array($data['result'], [self::RESULT_OK, self::RESULT_FAILD])) return FALSE;
        $model = new self();
        $model->ad_id       = $data['ad_id'];
        $model->al_content  = $data['content'];
        $model->al_result   = $data['result'];
        return $model->save();
    }

    /**
     * 管理员信息
     * @author ZhangXueFeng
     * @date   2015年12月28日
     * @return ActiveQuery
     */
    public function getAdmin() {
        return $this->hasOne(Admin::className(), ['ad_id' => 'ad_id']);
    }
}