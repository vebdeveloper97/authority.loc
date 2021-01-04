<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_planning_thread}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_ne}}`
 * - `{{%toquv_thread}}`
 * - `{{%model_orders}}`
 * - `{{%model_orders_planning}}`
 * - `{{%model_orders_items}}`
 */
class m200726_131623_create_model_orders_planning_thread_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_planning_thread}}', [
            'id' => $this->primaryKey(),
            'toquv_ne_id' => $this->smallInteger(6),
            'toquv_thread_id' => $this->smallInteger(6),
            'xom_mato' => $this->decimal(10,2),
            'quantity' => $this->decimal(10,2),
            'load_date' => $this->date(),
            'model_orders_id' => $this->integer(),
            'model_orders_planning_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'reg_date' => $this->date(),
            'status' => $this->integer(3),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `toquv_ne_id`
        $this->createIndex(
            '{{%idx-model_orders_planning_thread-toquv_ne_id}}',
            '{{%model_orders_planning_thread}}',
            'toquv_ne_id'
        );

        // add foreign key for table `{{%toquv_ne}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning_thread-toquv_ne_id}}',
            '{{%model_orders_planning_thread}}',
            'toquv_ne_id',
            '{{%toquv_ne}}',
            'id',
            'CASCADE'
        );

        // creates index for column `toquv_thread_id`
        $this->createIndex(
            '{{%idx-model_orders_planning_thread-toquv_thread_id}}',
            '{{%model_orders_planning_thread}}',
            'toquv_thread_id'
        );

        // add foreign key for table `{{%toquv_thread}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning_thread-toquv_thread_id}}',
            '{{%model_orders_planning_thread}}',
            'toquv_thread_id',
            '{{%toquv_thread}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_planning_thread-model_orders_id}}',
            '{{%model_orders_planning_thread}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning_thread-model_orders_id}}',
            '{{%model_orders_planning_thread}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_orders_planning_id`
        $this->createIndex(
            '{{%idx-model_orders_planning_thread-model_orders_planning_id}}',
            '{{%model_orders_planning_thread}}',
            'model_orders_planning_id'
        );

        // add foreign key for table `{{%model_orders_planning}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning_thread-model_orders_planning_id}}',
            '{{%model_orders_planning_thread}}',
            'model_orders_planning_id',
            '{{%model_orders_planning}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_planning_thread-model_orders_items_id}}',
            '{{%model_orders_planning_thread}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning_thread-model_orders_items_id}}',
            '{{%model_orders_planning_thread}}',
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
        // drops foreign key for table `{{%toquv_ne}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning_thread-toquv_ne_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops index for column `toquv_ne_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning_thread-toquv_ne_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops foreign key for table `{{%toquv_thread}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning_thread-toquv_thread_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops index for column `toquv_thread_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning_thread-toquv_thread_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning_thread-model_orders_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning_thread-model_orders_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops foreign key for table `{{%model_orders_planning}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning_thread-model_orders_planning_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops index for column `model_orders_planning_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning_thread-model_orders_planning_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning_thread-model_orders_items_id}}',
            '{{%model_orders_planning_thread}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning_thread-model_orders_items_id}}',
            '{{%model_orders_planning_thread}}'
        );

        $this->dropTable('{{%model_orders_planning_thread}}');
    }
}
