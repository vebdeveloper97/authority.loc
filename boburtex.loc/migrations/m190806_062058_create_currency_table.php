git a<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%currency}}`.
 */
class m190806_062058_create_currency_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'usd' => $this->float()->notNull(),
            'start_date' => $this->date()->defaultValue(date("Y-m-d")),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%currency}}');
    }
}
