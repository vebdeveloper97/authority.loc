<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_order_items_stone}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%model_var_stone}}`
 */
class m200310_175431_create_model_order_items_stone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_order_items_stone}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'model_var_stone_id' => $this->integer(),
            'add_info' => $this->string(),
        ]);

        $this->execute("ALTER TABLE `model_var_stone` ADD `height` DOUBLE(8,2) NULL DEFAULT '0' AFTER `name`;");
        $this->execute("ALTER TABLE `model_var_stone` ADD `width` DOUBLE(8,2) NULL DEFAULT '0' AFTER `name`;");
        $this->addColumn('{{%model_var_stone}}', 'image', $this->string());
        $this->addColumn('{{%model_var_stone}}', 'desen_no', $this->string(30));
        $this->addColumn('{{%model_var_prints}}', 'model_view_id', $this->integer());
        $this->addColumn('{{%model_var_prints}}', 'model_types_id', $this->integer());
        $this->addColumn('{{%model_var_stone}}', 'model_view_id', $this->integer());
        $this->addColumn('{{%model_var_stone}}', 'model_types_id', $this->integer());
        $this->addColumn('{{%model_var_stone}}', 'brend_id', $this->integer());
        $this->addColumn('{{%model_var_stone}}', 'musteri_id', $this->bigInteger());

        $this->alterColumn('{{%model_orders_planning}}', 'thread_length', $this->string(30));
        $this->alterColumn('{{%model_orders_planning}}', 'finish_en', $this->string(30));
        $this->alterColumn('{{%model_orders_planning}}', 'finish_gramaj', $this->string(30));

        // creates index for column `brend_id`
        $this->createIndex(
            '{{%idx-model_var_stone-brend_id}}',
            '{{%model_var_stone}}',
            'brend_id'
        );

        // add foreign key for table `{{%brend}}`
        $this->addForeignKey(
            '{{%fk-model_var_stone-brend_id}}',
            '{{%model_var_stone}}',
            'brend_id',
            '{{%brend}}',
            'id'
        );

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-model_var_stone-musteri_id}}',
            '{{%model_var_stone}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-model_var_stone-musteri_id}}',
            '{{%model_var_stone}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_order_items_stone-model_orders_items_id}}',
            '{{%model_order_items_stone}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_order_items_stone-model_orders_items_id}}',
            '{{%model_order_items_stone}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_var_stone_id`
        $this->createIndex(
            '{{%idx-model_order_items_stone-model_var_stone_id}}',
            '{{%model_order_items_stone}}',
            'model_var_stone_id'
        );

        // add foreign key for table `{{%model_var_stone}}`
        $this->addForeignKey(
            '{{%fk-model_order_items_stone-model_var_stone_id}}',
            '{{%model_order_items_stone}}',
            'model_var_stone_id',
            '{{%model_var_stone}}',
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
            '{{%fk-model_order_items_stone-model_orders_items_id}}',
            '{{%model_order_items_stone}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_order_items_stone-model_orders_items_id}}',
            '{{%model_order_items_stone}}'
        );

        // drops foreign key for table `{{%model_var_stone}}`
        $this->dropForeignKey(
            '{{%fk-model_order_items_stone-model_var_stone_id}}',
            '{{%model_order_items_stone}}'
        );

        // drops index for column `model_var_stone_id`
        $this->dropIndex(
            '{{%idx-model_order_items_stone-model_var_stone_id}}',
            '{{%model_order_items_stone}}'
        );

        $this->dropTable('{{%model_order_items_stone}}');

        // drops foreign key for table `{{%brend}}`
        $this->dropForeignKey(
            '{{%fk-model_var_stone-brend_id}}',
            '{{%model_var_stone}}'
        );

        // drops index for column `brend_id`
        $this->dropIndex(
            '{{%idx-model_var_stone-brend_id}}',
            '{{%model_var_stone}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-model_var_stone-musteri_id}}',
            '{{%model_var_stone}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-model_var_stone-musteri_id}}',
            '{{%model_var_stone}}'
        );

        $this->dropColumn('{{%model_var_stone}}', 'brend_id');
        $this->dropColumn('{{%model_var_stone}}', 'musteri_id');
        $this->dropColumn('{{%model_var_stone}}', 'width');
        $this->dropColumn('{{%model_var_stone}}', 'height');
        $this->dropColumn('{{%model_var_stone}}', 'image');
        $this->dropColumn('{{%model_var_prints}}', 'model_view_id');
        $this->dropColumn('{{%model_var_prints}}', 'model_types_id');
        $this->dropColumn('{{%model_var_stone}}', 'model_view_id');
        $this->dropColumn('{{%model_var_stone}}', 'model_types_id');
        $this->dropColumn('{{%model_var_stone}}', 'desen_no');
    }
}