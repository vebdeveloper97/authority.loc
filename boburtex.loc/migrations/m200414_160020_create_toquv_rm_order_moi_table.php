<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_rm_order_moi}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_rm_order}}`
 * - `{{%model_orders_items}}`
 */
class m200414_160020_create_toquv_rm_order_moi_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_rm_order_moi}}', [
            'id' => $this->primaryKey(),
            'toquv_rm_order_id' => $this->integer(),
            'model_orders_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'moi_rel_dept_id' => $this->integer(),
            'start_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'status' => $this->smallInteger(6)->defaultValue(1),
        ]);

        // creates index for column `toquv_rm_order_id`
        $this->createIndex(
            '{{%idx-toquv_rm_order_moi-toquv_rm_order_id}}',
            '{{%toquv_rm_order_moi}}',
            'toquv_rm_order_id'
        );

        // add foreign key for table `{{%toquv_rm_order}}`
        $this->addForeignKey(
            '{{%fk-toquv_rm_order_moi-toquv_rm_order_id}}',
            '{{%toquv_rm_order_moi}}',
            'toquv_rm_order_id',
            '{{%toquv_rm_order}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-toquv_rm_order_moi-model_orders_items_id}}',
            '{{%toquv_rm_order_moi}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-toquv_rm_order_moi-model_orders_items_id}}',
            '{{%toquv_rm_order_moi}}',
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
        // drops foreign key for table `{{%toquv_rm_order}}`
        $this->dropForeignKey(
            '{{%fk-toquv_rm_order_moi-toquv_rm_order_id}}',
            '{{%toquv_rm_order_moi}}'
        );

        // drops index for column `toquv_rm_order_id`
        $this->dropIndex(
            '{{%idx-toquv_rm_order_moi-toquv_rm_order_id}}',
            '{{%toquv_rm_order_moi}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-toquv_rm_order_moi-model_orders_items_id}}',
            '{{%toquv_rm_order_moi}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-toquv_rm_order_moi-model_orders_items_id}}',
            '{{%toquv_rm_order_moi}}'
        );

        $this->dropTable('{{%toquv_rm_order_moi}}');
    }
}
