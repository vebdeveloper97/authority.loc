<?php

use yii\db\Migration;

/**
 * Class m200805_230327_add_boyoq_xom_ombor_to_toquv_departments_table
 */
class m200805_230327_add_boyoq_xom_ombor_to_toquv_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%toquv_departments}}', ['id'=>3, 'parent' => null, 'name' => "Bo'yoqxona xom mato ombori", 'status' => 1, 'token' => 'BOYOQ_XOM_OMBOR', 'company_categories_id' => 1, 'type' => 2],true);
//        $this->upsert('{{%toquv_departments}}', ['parent' => null, 'name' => "To'quv mato skladi", 'token' => 'TOQUV_MATO_SKLAD', 'company_categories_id' => 1, 'type' => 2],true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200805_230327_add_boyoq_xom_ombor_to_toquv_departments_table cannot be reverted.\n";

        return true;
    }
}
