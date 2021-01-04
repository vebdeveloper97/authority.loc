<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_items_materail}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders}}`
 * - `{{%model_orders_items}}`
 * - `{{%toquv_raw_materials}}`
 */
class m200708_124300_create_model_orders_items_material_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_items_material}}', [
            'id' => $this->primaryKey(),
            'model_orders_id' => $this->integer(),
            'model_orders_items_id' => $this->integer(),
            'mato_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `model_orders_id`
        $this->createIndex(
            '{{%idx-model_orders_items_material-model_orders_id}}',
            '{{%model_orders_items_material}}',
            'model_orders_id'
        );

        // add foreign key for table `{{%model_orders}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_material-model_orders_id}}',
            '{{%model_orders_items_material}}',
            'model_orders_id',
            '{{%model_orders}}',
            'id'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_items_material-model_orders_items_id}}',
            '{{%model_orders_items_material}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_material-model_orders_items_id}}',
            '{{%model_orders_items_material}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id'
        );

        // creates index for column `mato_id`
        $this->createIndex(
            '{{%idx-model_orders_items_material-mato_id}}',
            '{{%model_orders_items_material}}',
            'mato_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-model_orders_items_material-mato_id}}',
            '{{%model_orders_items_material}}',
            'mato_id',
            '{{%toquv_raw_materials}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%model_orders}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_material-model_orders_id}}',
            '{{%model_orders_items_material}}'
        );

        // drops index for column `model_orders_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_material-model_orders_id}}',
            '{{%model_orders_items_material}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_material-model_orders_items_id}}',
            '{{%model_orders_items_material}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_material-model_orders_items_id}}',
            '{{%model_orders_items_material}}'
        );

        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_items_material-mato_id}}',
            '{{%model_orders_items_material}}'
        );

        // drops index for column `mato_id`
        $this->dropIndex(
            '{{%idx-model_orders_items_material-mato_id}}',
            '{{%model_orders_items_material}}'
        );

        $this->dropTable('{{%model_orders_items_material}}');
    }
}
