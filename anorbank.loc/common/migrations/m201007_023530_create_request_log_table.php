<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%request}}`.
 */
class m201007_023530_create_request_log_table extends Migration
{
    public const TABLE_NAME = '{{%request_log}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE_NAME, [
            'id'         => $this->primaryKey(),
            'session_id' => $this->string(),
            'pair_id'    => $this->string(),
            'service'    => $this->string(),
            'date'       => $this->dateTime(),
            'type'       => $this->string(),
            'body'       => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }
}
