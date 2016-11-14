<?php

use yii\db\Migration;
use zxf\models\entities\AdminGroup;
use zxf\models\services\FunctionService;

class m161110_141142_insertTable_admin_group extends Migration
{
    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeUp()
     */
    public function safeUp() {
        $model = AdminGroup::find()->byId(AdminGroup::SUPER_ADMIN_GROUP)->limit(1)->one();
        if ($model) {
            if ($model->adg_isdel == AdminGroup::DEL_YET) {
                $model->adg_isdel = AdminGroup::DEL_NOT;
                if (!$model->save()) {
                    echo FunctionService::getErrorsForString($model, FALSE);
                    return FALSE;
                }
            }
        } else {
            $this->insert(AdminGroup::tableName(), [
                'adg_id' => AdminGroup::SUPER_ADMIN_GROUP,
                'adg_name' => '超级管理员',
                'adg_status'   => AdminGroup::STATUS_ON,
                'adg_isdel'    => AdminGroup::DEL_NOT,
                'adg_addtime'  => THIS_TIME,
                'adg_addip'    => ip2long('127.0.0.1'),
                'adg_lasttime' => THIS_TIME,
                'adg_lastip'   => ip2long('127.0.0.1'),
            ]);
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \yii\db\Migration::safeDown()
     */
    public function safeDown() {
        $model = AdminGroup::find()->byId(AdminGroup::SUPER_ADMIN_GROUP)->byIsDel()->limit(1)->one();
        if ($model && $model->adg_name == '超级管理员') {
            $model->adg_isdel = AdminGroup::DEL_YET;
            if (!$model->save()) {
                echo FunctionService::getErrorsForString($model, FALSE);
                return FALSE;
            }
        }
    }
}
