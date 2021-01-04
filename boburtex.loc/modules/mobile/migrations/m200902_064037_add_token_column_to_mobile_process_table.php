<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process}}`.
 */
class m200902_064037_add_token_column_to_mobile_process_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process}}', 'token', $this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mobile_process}}', 'token');
    }
}
