<?php

use yii\db\Migration;

/**
 * Class m191128_103713_add_category_id_to_toquv_departments_table
 */
class m191128_103713_add_category_id_to_toquv_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_departments}}','company_categories_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_departments}}','company_categories_id');
    }
}
