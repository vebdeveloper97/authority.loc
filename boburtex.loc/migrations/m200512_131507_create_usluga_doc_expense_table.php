<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%usluga_doc_expense}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%usluga_doc}}`
 * - `{{%pul_birligi}}`
 */
class m200512_131507_create_usluga_doc_expense_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%usluga_doc_expense}}', [
            'id' => $this->primaryKey(),
            'usluga_doc_id' => $this->integer(),
            'price' => $this->decimal(20,2),
            'pb_id' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(1),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `usluga_doc_id`
        $this->createIndex(
            '{{%idx-usluga_doc_expense-usluga_doc_id}}',
            '{{%usluga_doc_expense}}',
            'usluga_doc_id'
        );

        // add foreign key for table `{{%usluga_doc}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_expense-usluga_doc_id}}',
            '{{%usluga_doc_expense}}',
            'usluga_doc_id',
            '{{%usluga_doc}}',
            'id',
            'CASCADE'
        );

        // creates index for column `pb_id`
        $this->createIndex(
            '{{%idx-usluga_doc_expense-pb_id}}',
            '{{%usluga_doc_expense}}',
            'pb_id'
        );

        // add foreign key for table `{{%pul_birligi}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_expense-pb_id}}',
            '{{%usluga_doc_expense}}',
            'pb_id',
            '{{%pul_birligi}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%usluga_doc}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_expense-usluga_doc_id}}',
            '{{%usluga_doc_expense}}'
        );

        // drops index for column `usluga_doc_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_expense-usluga_doc_id}}',
            '{{%usluga_doc_expense}}'
        );

        // drops foreign key for table `{{%pul_birligi}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_expense-pb_id}}',
            '{{%usluga_doc_expense}}'
        );

        // drops index for column `pb_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_expense-pb_id}}',
            '{{%usluga_doc_expense}}'
        );

        $this->dropTable('{{%usluga_doc_expense}}');
    }
}
