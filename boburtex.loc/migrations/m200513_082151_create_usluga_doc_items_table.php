<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%usluga_doc_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%usluga_doc}}`
 * - `{{%pul_birligi}}`
 * - `{{%sort_name}}`
 * - `{{%unit}}`
 * - `{{%size}}`
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 * - `{{%model_orders_items}}`
 */
class m200513_082151_create_usluga_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%usluga_doc_items}}', [
            'id' => $this->primaryKey(),
            'usluga_doc_id' => $this->integer(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger(6),
            'quantity' => $this->decimal(20,3),
            'document_qty' => $this->decimal(20,3),
            'price' => $this->decimal(20,2),
            'pb_id' => $this->integer(),
            'sort_name_id' => $this->integer(),
            'unit_id' => $this->integer(),
            'size_id' => $this->integer(),
            'nastel_party' => $this->string(30),
            'party_no' => $this->string(50),
            'musteri_party_no' => $this->string(50),
            'work_weight' => $this->double(),
            'models_list_id' => $this->integer(),
            'model_var_id' => $this->integer(),
            'moi_id' => $this->integer(),
            'type' => $this->smallInteger()->defaultValue(1),
            'is_accessory' => $this->tinyInteger(),
            'bsib_id' => $this->integer(),
            'add_info' => $this->text(),
            'status' => $this->smallInteger(6)->defaultValue(1),
        ]);

        // creates index for column `usluga_doc_id`
        $this->createIndex(
            '{{%idx-usluga_doc_items-usluga_doc_id}}',
            '{{%usluga_doc_items}}',
            'usluga_doc_id'
        );

        // add foreign key for table `{{%usluga_doc}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_items-usluga_doc_id}}',
            '{{%usluga_doc_items}}',
            'usluga_doc_id',
            '{{%usluga_doc}}',
            'id',
            'CASCADE'
        );

        // creates index for column `pb_id`
        $this->createIndex(
            '{{%idx-usluga_doc_items-pb_id}}',
            '{{%usluga_doc_items}}',
            'pb_id'
        );

        // add foreign key for table `{{%pul_birligi}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_items-pb_id}}',
            '{{%usluga_doc_items}}',
            'pb_id',
            '{{%pul_birligi}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `sort_name_id`
        $this->createIndex(
            '{{%idx-usluga_doc_items-sort_name_id}}',
            '{{%usluga_doc_items}}',
            'sort_name_id'
        );

        // add foreign key for table `{{%sort_name}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_items-sort_name_id}}',
            '{{%usluga_doc_items}}',
            'sort_name_id',
            '{{%sort_name}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `unit_id`
        $this->createIndex(
            '{{%idx-usluga_doc_items-unit_id}}',
            '{{%usluga_doc_items}}',
            'unit_id'
        );

        // add foreign key for table `{{%unit}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_items-unit_id}}',
            '{{%usluga_doc_items}}',
            'unit_id',
            '{{%unit}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `size_id`
        $this->createIndex(
            '{{%idx-usluga_doc_items-size_id}}',
            '{{%usluga_doc_items}}',
            'size_id'
        );

        // add foreign key for table `{{%size}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_items-size_id}}',
            '{{%usluga_doc_items}}',
            'size_id',
            '{{%size}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-usluga_doc_items-models_list_id}}',
            '{{%usluga_doc_items}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_items-models_list_id}}',
            '{{%usluga_doc_items}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `model_var_id`
        $this->createIndex(
            '{{%idx-usluga_doc_items-model_var_id}}',
            '{{%usluga_doc_items}}',
            'model_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_items-model_var_id}}',
            '{{%usluga_doc_items}}',
            'model_var_id',
            '{{%models_variations}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `moi_id`
        $this->createIndex(
            '{{%idx-usluga_doc_items-moi_id}}',
            '{{%usluga_doc_items}}',
            'moi_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-usluga_doc_items-moi_id}}',
            '{{%usluga_doc_items}}',
            'moi_id',
            '{{%model_orders_items}}',
            'id',
            'RESTRICT'
        );
        $this->addColumn('{{%usluga_doc}}','model_orders_id',$this->integer());
        $this->addColumn('{{%usluga_doc}}','model_orders_items_id',$this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%usluga_doc}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_items-usluga_doc_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops index for column `usluga_doc_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_items-usluga_doc_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops foreign key for table `{{%pul_birligi}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_items-pb_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops index for column `pb_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_items-pb_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops foreign key for table `{{%sort_name}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_items-sort_name_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops index for column `sort_name_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_items-sort_name_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops foreign key for table `{{%unit}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_items-unit_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops index for column `unit_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_items-unit_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_items-size_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops index for column `size_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_items-size_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_items-models_list_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_items-models_list_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_items-model_var_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops index for column `model_var_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_items-model_var_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops foreign key for table `{{%model_orders_items}}`
        $this->dropForeignKey(
            '{{%fk-usluga_doc_items-moi_id}}',
            '{{%usluga_doc_items}}'
        );

        // drops index for column `moi_id`
        $this->dropIndex(
            '{{%idx-usluga_doc_items-moi_id}}',
            '{{%usluga_doc_items}}'
        );

        $this->dropTable('{{%usluga_doc_items}}');
        $this->dropColumn('{{%usluga_doc}}','model_orders_id');
        $this->dropColumn('{{%usluga_doc}}','model_orders_items_id');
    }
}
