<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items_mato}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%toquv_raw_materials}}`
 * - `{{%models_list}}`
 */
class m200428_120914_create_model_orders_items_mato_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items_mato}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'toquv_raw_materials_id' => $this->integer(),
            'models_list_id' => $this->integer(),
            'model_var_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1)
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_items_mato-model_orders_items_id}}',
            '{{%model_orders_items_mato}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_mato-model_orders_items_id}}',
            '{{%model_orders_items_mato}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `toquv_raw_materials_id`
        $this->createIndex(
            '{{%idx-model_orders_items_mato-toquv_raw_materials_id}}',
            '{{%model_orders_items_mato}}',
            'toquv_raw_materials_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_mato-toquv_raw_materials_id}}',
            '{{%model_orders_items_mato}}',
            'toquv_raw_materials_id',
            '{{%toquv_raw_materials}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-model_orders_items_mato-models_list_id}}',
            '{{%model_orders_items_mato}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_mato-models_list_id}}',
            '{{%model_orders_items_mato}}',
            'models_list_id',
            '{{%models_list}}',
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
            '{{%fk-model_orders_items_mato-model_orders_items_id}}',
            '{{%model_orders_items_mato}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_mato-model_orders_items_id}}',
            '{{%model_orders_items_mato}}'
        );

        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_mato-toquv_raw_materials_id}}',
            '{{%model_orders_items_mato}}'
        );

        // drops index for column `toquv_raw_materials_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_mato-toquv_raw_materials_id}}',
            '{{%model_orders_items_mato}}'
        );

        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_mato-models_list_id}}',
            '{{%model_orders_items_mato}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_mato-models_list_id}}',
            '{{%model_orders_items_mato}}'
        );

        $this->dropTable('{{%model_orders_items_mato}}');
    }
}
