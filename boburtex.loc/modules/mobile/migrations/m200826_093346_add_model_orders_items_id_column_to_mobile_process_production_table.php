<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process_production}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 */
class m200826_093346_add_model_orders_items_id_column_to_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process_production}}', 'model_orders_items_id', $this->integer());

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-mobile_process_production-model_orders_items_id}}',
            '{{%mobile_process_production}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-mobile_process_production-model_orders_items_id}}',
            '{{%mobile_process_production}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-mobile_process_production-model_orders_items_id}}',
            '{{%mobile_process_production}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-mobile_process_production-model_orders_items_id}}',
            '{{%mobile_process_production}}'
        );

        $this->dropColumn('{{%mobile_process_production}}', 'model_orders_items_id');
    }
}
