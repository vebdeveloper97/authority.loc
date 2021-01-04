<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_size}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items_changes}}`
 */
class m191125_163649_add_column_to_model_orders_items_size_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_size}}', 'parent_id', $this->integer());
        $this->addColumn('{{%model_orders_items_size}}', 'model_orders_items_changes_id', $this->integer());
        $this->addColumn('{{%model_orders_planning}}', 'parent_id', $this->integer());
        $this->addColumn('{{%model_orders_planning}}', 'model_orders_items_changes_id', $this->integer());

        // creates index for column `model_orders_items_changes_id`
        $this->createIndex(
            '{{%idx-model_orders_items_size-model_orders_items_changes_id}}',
            '{{%model_orders_items_size}}',
            'model_orders_items_changes_id'
        );

        // add foreign key for table `{{%model_orders_items_changes}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_size-model_orders_items_changes_id}}',
            '{{%model_orders_items_size}}',
            'model_orders_items_changes_id',
            '{{%model_orders_items_changes}}',
            'id',
            'CASCADE'
        );
        // creates index for column `model_orders_items_changes_id`
        $this->createIndex(
            '{{%idx-model_orders_planning-model_orders_items_changes_id}}',
            '{{%model_orders_planning}}',
            'model_orders_items_changes_id'
        );

        // add foreign key for table `{{%model_orders_items_changes}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning-model_orders_items_changes_id}}',
            '{{%model_orders_planning}}',
            'model_orders_items_changes_id',
            '{{%model_orders_items_changes}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_items_changes}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_size-model_orders_items_changes_id}}',
            '{{%model_orders_items_size}}'
        );

        // drops index for column `model_orders_items_changes_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_size-model_orders_items_changes_id}}',
            '{{%model_orders_items_size}}'
        );

        $this->dropColumn('{{%model_orders_planning}}', 'parent_id');
        $this->dropColumn('{{%model_orders_planning}}', 'model_orders_items_changes_id');

        // drops foreign key for table `{{%model_orders_items_changes}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning-model_orders_items_changes_id}}',
            '{{%model_orders_planning}}'
        );

        // drops index for column `model_orders_items_changes_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning-model_orders_items_changes_id}}',
            '{{%model_orders_planning}}'
        );

        $this->dropColumn('{{%model_orders_planning}}', 'parent_id');
        $this->dropColumn('{{%model_orders_planning}}', 'model_orders_items_changes_id');
    }
}
