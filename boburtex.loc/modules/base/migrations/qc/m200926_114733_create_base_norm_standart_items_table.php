<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_norm_standart_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_norm_standart}}`
 * - `{{%base_error_list}}`
 */
class m200926_114733_create_base_norm_standart_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_norm_standart_items}}', [
            'id' => $this->primaryKey(),
            'norm_standart_id' => $this->integer(),
            'error_list_id' => $this->integer(),
            'quantity' => $this->integer(),
            'info' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `norm_standart_id`
        $this->createIndex(
            '{{%idx-base_norm_standart_items-norm_standart_id}}',
            '{{%base_norm_standart_items}}',
            'norm_standart_id'
        );

        // add foreign key for table `{{%base_norm_standart}}`
        $this->addForeignKey(
            '{{%fk-base_norm_standart_items-norm_standart_id}}',
            '{{%base_norm_standart_items}}',
            'norm_standart_id',
            '{{%base_norm_standart}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `error_list_id`
        $this->createIndex(
            '{{%idx-base_norm_standart_items-error_list_id}}',
            '{{%base_norm_standart_items}}',
            'error_list_id'
        );

        // add foreign key for table `{{%base_error_list}}`
        $this->addForeignKey(
            '{{%fk-base_norm_standart_items-error_list_id}}',
            '{{%base_norm_standart_items}}',
            'error_list_id',
            '{{%base_error_list}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_norm_standart}}`
        $this->dropForeignKey(
            '{{%fk-base_norm_standart_items-norm_standart_id}}',
            '{{%base_norm_standart_items}}'
        );

        // drops index for column `norm_standart_id`
        $this->dropIndex(
            '{{%idx-base_norm_standart_items-norm_standart_id}}',
            '{{%base_norm_standart_items}}'
        );

        // drops foreign key for table `{{%base_error_list}}`
        $this->dropForeignKey(
            '{{%fk-base_norm_standart_items-error_list_id}}',
            '{{%base_norm_standart_items}}'
        );

        // drops index for column `error_list_id`
        $this->dropIndex(
            '{{%idx-base_norm_standart_items-error_list_id}}',
            '{{%base_norm_standart_items}}'
        );

        $this->dropTable('{{%base_norm_standart_items}}');
    }
}
