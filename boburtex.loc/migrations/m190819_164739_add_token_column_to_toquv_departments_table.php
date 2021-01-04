<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_departments}}`.
 */
class m190819_164739_add_token_column_to_toquv_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_departments}}', 'token', $this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%toquv_departments}}', 'token');
    }
}
