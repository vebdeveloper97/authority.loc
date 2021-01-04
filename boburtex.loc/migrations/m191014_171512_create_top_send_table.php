<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_top_send}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tikuv_outcome_products}}`
 */
class m191014_171512_create_top_send_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_top_send}}', [
            'id' => $this->primaryKey(),
            'top_id' => $this->integer(),
            'doc_number' => $this->string(),
            'add_info' => $this->text(),
            'sent' => $this->decimal(20,3),
            'type' => $this->smallInteger(2),
            'reg_date' => $this->dateTime(),
            'status' => $this->smallInteger()->defaultValue(1),
        ]);

        // creates index for column `top_id`
        $this->createIndex(
            '{{%idx-tikuv_top_send-top_id}}',
            '{{%tikuv_top_send}}',
            'top_id'
        );

        // add foreign key for table `{{%tikuv_top_send}}`
        $this->addForeignKey(
            '{{%fk-tikuv_top_send-top_id}}',
            '{{%tikuv_top_send}}',
            'top_id',
            '{{%tikuv_outcome_products}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tikuv_top_send}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_top_send-top_id}}',
            '{{%tikuv_top_send}}'
        );

        // drops index for column `top_id`
        $this->dropIndex(
            '{{%idx-tikuv_top_send-top_id}}',
            '{{%tikuv_top_send}}'
        );

        $this->dropTable('{{%tikuv_top_send}}');
    }
}
