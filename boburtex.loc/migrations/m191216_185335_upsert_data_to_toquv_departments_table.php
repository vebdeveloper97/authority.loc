<?php

use yii\db\Migration;

/**
 * Class m191216_185335_upsert_data_to_toquv_departments_table
 */
class m191216_185335_upsert_data_to_toquv_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%toquv_departments}}', ['parent' => null, 'name' => "To'quv aksessuar skladi", 'token' => 'TOQUV_ACS_SKLAD', 'company_categories_id' => 1, 'type' => 2],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%toquv_departments}}', ['parent' => null, 'name' => "To'quv aksessuar skladi", 'token' => 'TOQUV_ACS_SKLAD', 'company_categories_id' => 1, 'type' => 2]);
    }
}
