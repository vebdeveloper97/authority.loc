<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_orders_planning}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%toquv_raw_materials}}`
 */
class m191002_144951_create_model_orders_planning_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_orders_planning}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'toquv_raw_materials_id' => $this->integer(),
            'work_weight' => $this->float(),
            'finished_fabric' => $this->float(),
            'raw_fabric' => $this->float(),
            'thread_length' => $this->integer(),
            'finish_en' => $this->integer(),
            'finish_gramaj' => $this->integer(),
            'color_pantone_id' => $this->integer(),
            'add_info' => $this->text(),
            'model_orders_id' => $this->integer(),
        ]);

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_orders_planning-model_orders_items_id}}',
            '{{%model_orders_planning}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning-model_orders_items_id}}',
            '{{%model_orders_planning}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // creates index for column `toquv_raw_materials_id`
        $this->createIndex(
            '{{%idx-model_orders_planning-toquv_raw_materials_id}}',
            '{{%model_orders_planning}}',
            'toquv_raw_materials_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-model_orders_planning-toquv_raw_materials_id}}',
            '{{%model_orders_planning}}',
            'toquv_raw_materials_id',
            '{{%toquv_raw_materials}}',
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
            '{{%fk-model_orders_planning-model_orders_items_id}}',
            '{{%model_orders_planning}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning-model_orders_items_id}}',
            '{{%model_orders_planning}}'
        );

        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-model_orders_planning-toquv_raw_materials_id}}',
            '{{%model_orders_planning}}'
        );

        // drops index for column `toquv_raw_materials_id`
        $this->dropIndex(
            '{{%idx-model_orders_planning-toquv_raw_materials_id}}',
            '{{%model_orders_planning}}'
        );

        $this->dropTable('{{%model_orders_planning}}');
    }
}
