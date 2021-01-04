<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_user_department}}`.
 */
class m200521_122021_add_type_column_to_toquv_user_department_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_user_department}}', 'type', $this->tinyInteger()->defaultValue("0"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_user_department}}', 'type');
    }
}
