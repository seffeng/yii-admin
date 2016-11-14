<?php

use yii\db\Migration;
use zxf\models\entities\Admin;
use zxf\models\services\AdminService;

class m161103_071812_insertTable_admin extends Migration
{
    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $model = Admin::find()->byUsername('10000')->byIsDel()->limit(1)->one();
        if (!$model) {
            $this->insert(Admin::tableName(), [
                'ad_id' => NULL,
                'ad_username' => '10000',
                'ad_password' => AdminService::encryptPassword('123456'),
                'adg_id'      => 1,
                'pv_ids'      => '',
                'pvg_ids'     => '',
                'ad_status'   => Admin::STATUS_ON,
                'ad_isdel'    => Admin::DEL_NOT,
                'ad_addtime'  => THIS_TIME,
                'ad_addip'    => ip2long('127.0.0.1'),
                'ad_lasttime' => THIS_TIME,
                'ad_lastip'   => ip2long('127.0.0.1'),
            ]);
        }
    }

    /**
     * 
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $model = Admin::find()->byUsername('10000')->byIsDel()->limit(1)->one();
        if ($model) {
            $model->ad_isdel = Admin::DEL_YET;
            $model->save();
        }
    }
}
