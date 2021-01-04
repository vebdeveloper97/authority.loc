<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items_toquv_acs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%toquv_raw_materials}}`
 */
class m200406_061149_create_model_orders_items_toquv_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items_toquv_acs}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'toquv_raw_materials_id' => $this->integer(),
            'quantity' => $this->decimal(20,3),
            'count' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_items_toquv_acs-model_orders_items_id}}',
            '{{%model_orders_items_toquv_acs}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_toquv_acs-model_orders_items_id}}',
            '{{%model_orders_items_toquv_acs}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // creates index for column `toquv_raw_materials_id`
        $this->createIndex(
            '{{%idx-model_orders_items_toquv_acs-toquv_raw_materials_id}}',
            '{{%model_orders_items_toquv_acs}}',
            'toquv_raw_materials_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_toquv_acs-toquv_raw_materials_id}}',
            '{{%model_orders_items_toquv_acs}}',
            'toquv_raw_materials_id',
            '{{%toquv_raw_materials}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_toquv_acs-model_orders_items_id}}',
            '{{%model_orders_items_toquv_acs}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_toquv_acs-model_orders_items_id}}',
            '{{%model_orders_items_toquv_acs}}'
        );

        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_toquv_acs-toquv_raw_materials_id}}',
            '{{%model_orders_items_toquv_acs}}'
        );

        // drops index for column `toquv_raw_materials_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_toquv_acs-toquv_raw_materials_id}}',
            '{{%model_orders_items_toquv_acs}}'
        );

        $this->dropTable('{{%model_orders_items_toquv_acs}}');
    }
}
