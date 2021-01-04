<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%top_accepted}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tikuv_outcome_products}}`
 */
class m191014_171512_create_top_accepted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%top_accepted}}', [
            'id' => $this->primaryKey(),
            'top_id' => $this->integer(),
            'accepted' => $this->decimal(20,3),
            'type' => $this->smallInteger(2),
            'reg_date' => $this->dateTime(),
            'status' => $this->smallInteger()->defaultValue(1),
        ]);

        // creates index for column `top_id`
        $this->createIndex(
            '{{%idx-top_accepted-top_id}}',
            '{{%top_accepted}}',
            'top_id'
        );

        // add foreign key for table `{{%tikuv_outcome_products}}`
        $this->addForeignKey(
            '{{%fk-top_accepted-top_id}}',
            '{{%top_accepted}}',
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
        // drops foreign key for table `{{%tikuv_outcome_products}}`
        $this->dropForeignKey(
            '{{%fk-top_accepted-top_id}}',
            '{{%top_accepted}}'
        );

        // drops index for column `top_id`
        $this->dropIndex(
            '{{%idx-top_accepted-top_id}}',
            '{{%top_accepted}}'
        );

        $this->dropTable('{{%top_accepted}}');
    }
}
