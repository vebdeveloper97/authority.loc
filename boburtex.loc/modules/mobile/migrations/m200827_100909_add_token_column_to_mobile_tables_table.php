<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_tables}}`.
 */
class m200827_100909_add_token_column_to_mobile_tables_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_tables}}', 'token', $this->string(50)->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mobile_tables}}', 'token');
    }
}
