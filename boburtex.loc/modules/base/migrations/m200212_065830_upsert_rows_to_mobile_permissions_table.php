<?php

use yii\db\Migration;

/**
 * Class m200212_065830_upsert_rows_to_mobile_permissions_table
 */
class m200212_065830_upsert_rows_to_mobile_permissions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('mobile_permissions',['name' => "bichuv_main_process", 'type' => 2, 'desc' => 'Bichuv kesim'],false);
        $this->upsert('mobile_permissions',['name' => "bichuv_meto_process", 'type' => 2, 'desc' => 'Bichuv Meto prosesi'],false);
        $this->upsert('mobile_permissions',['name' => "bichuv_tasnif_process", 'type' => 2, 'desc' => 'Bichuv Tasnif prosesi'],false);
        $this->upsert('users_mobile_permissions',['users_id' => 3, 'mobile_permission_id' => 1],false);
        $this->upsert('users_mobile_permissions',['users_id' => 3, 'mobile_permission_id' => 2],false);
        $this->upsert('users_mobile_permissions',['users_id' => 3, 'mobile_permission_id' => 3],false);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
