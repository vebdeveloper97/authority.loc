<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_method_size_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%base_method}}`
 * - `{{%size}}`
 */
class m200923_053840_create_base_method_size_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_method_size_items}}', [
            'id' => $this->primaryKey(),
            'base_method_id' => $this->integer(),
            'size_id' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `base_method_id`
        $this->createIndex(
            '{{%idx-base_method_size_items-base_method_id}}',
            '{{%base_method_size_items}}',
            'base_method_id'
        );

        // add foreign key for table `{{%base_method}}`
        $this->addForeignKey(
            '{{%fk-base_method_size_items-base_method_id}}',
            '{{%base_method_size_items}}',
            'base_method_id',
            '{{%base_method}}',
            'id',
            'CASCADE'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-base_method_size_items-size_id}}',
            '{{%base_method_size_items}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-base_method_size_items-size_id}}',
            '{{%base_method_size_items}}',
            'size_id',
            '{{%size}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%base_method}}`
        $this->dropForeignKey(
            '{{%fk-base_method_size_items-base_method_id}}',
            '{{%base_method_size_items}}'
        );

        // drops index for column `base_method_id`
        $this->dropIndex(
            '{{%idx-base_method_size_items-base_method_id}}',
            '{{%base_method_size_items}}'
        );

        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-base_method_size_items-size_id}}',
            '{{%base_method_size_items}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-base_method_size_items-size_id}}',
            '{{%base_method_size_items}}'
        );

        $this->dropTable('{{%base_method_size_items}}');
    }
}
