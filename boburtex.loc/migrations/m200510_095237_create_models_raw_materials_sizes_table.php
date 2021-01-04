<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_raw_materials_sizes}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_raw_materials}}`
 * - `{{%size}}`
 */
class m200510_095237_create_models_raw_materials_sizes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models_raw_materials_sizes}}', [
            'id' => $this->primaryKey(),
            'models_raw_materials_id' => $this->integer(),
            'size_id' => $this->integer(),
        ]);

        // creates index for column `models_raw_materials_id`
        $this->createIndex(
            '{{%idx-models_raw_materials_sizes-models_raw_materials_id}}',
            '{{%models_raw_materials_sizes}}',
            'models_raw_materials_id'
        );

        // add foreign key for table `{{%models_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-models_raw_materials_sizes-models_raw_materials_id}}',
            '{{%models_raw_materials_sizes}}',
            'models_raw_materials_id',
            '{{%models_raw_materials}}',
            'id',
            'CASCADE'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-models_raw_materials_sizes-size_id}}',
            '{{%models_raw_materials_sizes}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-models_raw_materials_sizes-size_id}}',
            '{{%models_raw_materials_sizes}}',
            'size_id',
            '{{%size}}',
            'id',
            'CASCADE'
        );
        $this->addColumn('{{%models_raw_materials}}','thread_length', $this->string(30));
        $this->addColumn('{{%models_raw_materials}}','finish_en', $this->string(30));
        $this->addColumn('{{%models_raw_materials}}','finish_gramaj', $this->string(30));
        $this->addColumn('{{%models_raw_materials}}','for_all_sizes', $this->boolean()->defaultValue(1));

        $this->addColumn('{{%model_orders_items_mato}}','thread_length', $this->string(30));
        $this->addColumn('{{%model_orders_items_mato}}','finish_en', $this->string(30));
        $this->addColumn('{{%model_orders_items_mato}}','finish_gramaj', $this->string(30));
        $this->addColumn('{{%model_orders_items_mato}}','for_all_sizes', $this->boolean()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-models_raw_materials_sizes-models_raw_materials_id}}',
            '{{%models_raw_materials_sizes}}'
        );

        // drops index for column `models_raw_materials_id`
        $this->dropIndex(
            '{{%idx-models_raw_materials_sizes-models_raw_materials_id}}',
            '{{%models_raw_materials_sizes}}'
        );

        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-models_raw_materials_sizes-size_id}}',
            '{{%models_raw_materials_sizes}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-models_raw_materials_sizes-size_id}}',
            '{{%models_raw_materials_sizes}}'
        );

        $this->dropTable('{{%models_raw_materials_sizes}}');

        $this->dropColumn('{{%models_raw_materials}}','thread_length');
        $this->dropColumn('{{%models_raw_materials}}','finish_en');
        $this->dropColumn('{{%models_raw_materials}}','finish_gramaj');
        $this->dropColumn('{{%models_raw_materials}}','for_all_sizes');

        $this->dropColumn('{{%model_orders_items_mato}}','thread_length');
        $this->dropColumn('{{%model_orders_items_mato}}','finish_en');
        $this->dropColumn('{{%model_orders_items_mato}}','finish_gramaj');
        $this->dropColumn('{{%model_orders_items_mato}}','for_all_sizes');
    }
}
