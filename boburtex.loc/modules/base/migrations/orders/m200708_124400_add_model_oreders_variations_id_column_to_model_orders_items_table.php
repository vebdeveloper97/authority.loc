<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_variations}}`
 */
class m200708_124400_add_model_oreders_variations_id_column_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'model_orders_variations_id', $this->integer());

        // creates index for column `model_orders_variations_id`
        $this->createIndex(
            '{{%idx-model_orders_items-model_orders_variations_id}}',
            '{{%model_orders_items}}',
            'model_orders_variations_id'
        );

        // add foreign key for table `{{%model_orders_variations}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items-model_orders_variations_id}}',
            '{{%model_orders_items}}',
            'model_orders_variations_id',
            '{{%model_orders_variations}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_variations}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items-model_orders_variations_id}}',
            '{{%model_orders_items}}'
        );

        // drops index for column `model_orders_variations_id`
        $this->dropIndex(
            '{{%idx-model_orders_items-model_orders_variations_id}}',
            '{{%model_orders_items}}'
        );

        $this->dropColumn('{{%model_orders_items}}', 'model_orders_variations_id');
    }
}
