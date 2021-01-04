<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_mato_order_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%bichuv_mato_orders}}`
 */
class m200425_134947_create_bichuv_mato_order_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_mato_order_items}}', [
            'id' => $this->primaryKey(),
            'bichuv_mato_orders_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'name' => $this->string(),
            'quantity' => $this->decimal(20,3),
            'roll_count' => $this->smallInteger(),
            'count' => $this->smallInteger(),
            'moi_id' => $this->integer()->comment('model_orders_items_id'),
            'mop_id' => $this->integer()->comment('model_orders_planning_id'),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `bichuv_mato_orders_id`
        $this->createIndex(
            '{{%idx-bichuv_mato_order_items-bichuv_mato_orders_id}}',
            '{{%bichuv_mato_order_items}}',
            'bichuv_mato_orders_id'
        );

        // add foreign key for table `{{%bichuv_mato_orders}}`
        $this->addForeignKey(
            '{{%fk-bichuv_mato_order_items-bichuv_mato_orders_id}}',
            '{{%bichuv_mato_order_items}}',
            'bichuv_mato_orders_id',
            '{{%bichuv_mato_orders}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%bichuv_mato_orders}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_mato_order_items-bichuv_mato_orders_id}}',
            '{{%bichuv_mato_order_items}}'
        );

        // drops index for column `bichuv_mato_orders_id`
        $this->dropIndex(
            '{{%idx-bichuv_mato_order_items-bichuv_mato_orders_id}}',
            '{{%bichuv_mato_order_items}}'
        );

        $this->dropTable('{{%bichuv_mato_order_items}}');
    }
}
