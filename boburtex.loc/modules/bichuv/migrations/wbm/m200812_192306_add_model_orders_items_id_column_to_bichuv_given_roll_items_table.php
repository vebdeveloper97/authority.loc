<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_roll_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 */
class m200812_192306_add_model_orders_items_id_column_to_bichuv_given_roll_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_given_roll_items}}', 'model_orders_items_id', $this->integer());

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-bichuv_given_roll_items-model_orders_items_id}}',
            '{{%bichuv_given_roll_items}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-bichuv_given_roll_items-model_orders_items_id}}',
            '{{%bichuv_given_roll_items}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_given_roll_items-model_orders_items_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-bichuv_given_roll_items-model_orders_items_id}}',
            '{{%bichuv_given_roll_items}}'
        );

        $this->dropColumn('{{%bichuv_given_roll_items}}', 'model_orders_items_id');
    }
}
