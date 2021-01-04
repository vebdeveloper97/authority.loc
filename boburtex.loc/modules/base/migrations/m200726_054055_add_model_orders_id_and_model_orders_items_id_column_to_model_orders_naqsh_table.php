<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_naqsh}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 * - `{{%model_orders_items}}`
 */
class m200726_054055_add_model_orders_id_and_model_orders_items_id_column_to_model_orders_naqsh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_naqsh}}', 'model_orders_id', $this->integer());
        $this->addColumn('{{%model_orders_naqsh}}', 'model_orders_items_id', $this->integer());

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_naqsh-model_orders_id}}',
            '{{%model_orders_naqsh}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_naqsh-model_orders_id}}',
            '{{%model_orders_naqsh}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_naqsh-model_orders_items_id}}',
            '{{%model_orders_naqsh}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_naqsh-model_orders_items_id}}',
            '{{%model_orders_naqsh}}',
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
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_naqsh-model_orders_id}}',
            '{{%model_orders_naqsh}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_naqsh-model_orders_id}}',
            '{{%model_orders_naqsh}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_naqsh-model_orders_items_id}}',
            '{{%model_orders_naqsh}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_naqsh-model_orders_items_id}}',
            '{{%model_orders_naqsh}}'
        );

        $this->dropColumn('{{%model_orders_naqsh}}', 'model_orders_id');
        $this->dropColumn('{{%model_orders_naqsh}}', 'model_orders_items_id');
    }
}
