<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%moi_rel_dept}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%toquv_departments}}`
 */
class m191011_164332_create_junction_table_for_model_orders_items_and_toquv_departments_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%moi_rel_dept}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'model_orders_items_id' => $this->integer(),
            'toquv_departments_id' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(1)
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-moi_rel_dept-model_orders_items_id}}',
            '{{%moi_rel_dept}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-moi_rel_dept-model_orders_items_id}}',
            '{{%moi_rel_dept}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `toquv_departments_id`
        $this->createIndex(
            '{{%idx-moi_rel_dept-toquv_departments_id}}',
            '{{%moi_rel_dept}}',
            'toquv_departments_id'
        );

        // add foreign key for table `{{%toquv_departments}}`
        $this->addForeignKey(
            '{{%fk-moi_rel_dept-toquv_departments_id}}',
            '{{%moi_rel_dept}}',
            'toquv_departments_id',
            '{{%toquv_departments}}',
            'id',
            'CASCADE',
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
            '{{%fk-moi_rel_dept-model_orders_items_id}}',
            '{{%moi_rel_dept}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-moi_rel_dept-model_orders_items_id}}',
            '{{%moi_rel_dept}}'
        );

        // drops foreign key for table `{{%toquv_departments}}`
        $this->dropForeignKey(
            '{{%fk-moi_rel_dept-toquv_departments_id}}',
            '{{%moi_rel_dept}}'
        );

        // drops index for column `toquv_departments_id`
        $this->dropIndex(
            '{{%idx-moi_rel_dept-toquv_departments_id}}',
            '{{%moi_rel_dept}}'
        );

        $this->dropTable('{{%moi_rel_dept}}');
    }
}
