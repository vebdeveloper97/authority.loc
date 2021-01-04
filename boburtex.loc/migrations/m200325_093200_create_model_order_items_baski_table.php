<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%model_order_items_baski}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%model_orders_items}}`
 * - `{{%model_var_baski}}`
 */
class m200325_093200_create_model_order_items_baski_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%model_order_items_baski}}', [
            'id' => $this->primaryKey(),
            'model_orders_items_id' => $this->integer(),
            'model_var_baski_id' => $this->integer(),
            'add_info' => $this->string(),
        ]);

        $this->execute("ALTER TABLE `model_var_baski` ADD `height` DOUBLE(8,2) NULL DEFAULT '0' AFTER `name`;");
        $this->execute("ALTER TABLE `model_var_baski` ADD `width` DOUBLE(8,2) NULL DEFAULT '0' AFTER `name`;");
        $this->addColumn('{{%model_var_baski}}', 'image', $this->string());
        $this->addColumn('{{%model_var_baski}}', 'model_view_id', $this->integer());
        $this->addColumn('{{%model_var_baski}}', 'model_types_id', $this->integer());
        $this->addColumn('{{%model_var_baski}}', 'brend_id', $this->integer());
        $this->addColumn('{{%model_var_baski}}', 'musteri_id', $this->bigInteger());

        // creates index for column `brend_id`
        $this->createIndex(
            '{{%idx-model_var_baski-brend_id}}',
            '{{%model_var_baski}}',
            'brend_id'
        );

        // add foreign key for table `{{%brend}}`
        $this->addForeignKey(
            '{{%fk-model_var_baski-brend_id}}',
            '{{%model_var_baski}}',
            'brend_id',
            '{{%brend}}',
            'id'
        );

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-model_var_baski-musteri_id}}',
            '{{%model_var_baski}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-model_var_baski-musteri_id}}',
            '{{%model_var_baski}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );

        // creates index for column `model_orders_items_id`
        $this->createIndex(
            '{{%idx-model_order_items_baski-model_orders_items_id}}',
            '{{%model_order_items_baski}}',
            'model_orders_items_id'
        );

        // add foreign key for table `{{%model_orders_items}}`
        $this->addForeignKey(
            '{{%fk-model_order_items_baski-model_orders_items_id}}',
            '{{%model_order_items_baski}}',
            'model_orders_items_id',
            '{{%model_orders_items}}',
            'id',
            'CASCADE'
        );

        // creates index for column `model_var_baski_id`
        $this->createIndex(
            '{{%idx-model_order_items_baski-model_var_baski_id}}',
            '{{%model_order_items_baski}}',
            'model_var_baski_id'
        );

        // add foreign key for table `{{%model_var_baski}}`
        $this->addForeignKey(
            '{{%fk-model_order_items_baski-model_var_baski_id}}',
            '{{%model_order_items_baski}}',
            'model_var_baski_id',
            '{{%model_var_baski}}',
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
            '{{%fk-model_order_items_baski-model_orders_items_id}}',
            '{{%model_order_items_baski}}'
        );

        // drops index for column `model_orders_items_id`
        $this->dropIndex(
            '{{%idx-model_order_items_baski-model_orders_items_id}}',
            '{{%model_order_items_baski}}'
        );

        // drops foreign key for table `{{%model_var_baski}}`
        $this->dropForeignKey(
            '{{%fk-model_order_items_baski-model_var_baski_id}}',
            '{{%model_order_items_baski}}'
        );

        // drops index for column `model_var_baski_id`
        $this->dropIndex(
            '{{%idx-model_order_items_baski-model_var_baski_id}}',
            '{{%model_order_items_baski}}'
        );

        $this->dropTable('{{%model_order_items_baski}}');

        // drops foreign key for table `{{%brend}}`
        $this->dropForeignKey(
            '{{%fk-model_var_baski-brend_id}}',
            '{{%model_var_baski}}'
        );

        // drops index for column `brend_id`
        $this->dropIndex(
            '{{%idx-model_var_baski-brend_id}}',
            '{{%model_var_baski}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-model_var_baski-musteri_id}}',
            '{{%model_var_baski}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-model_var_baski-musteri_id}}',
            '{{%model_var_baski}}'
        );

        $this->dropColumn('{{%model_var_baski}}', 'brend_id');
        $this->dropColumn('{{%model_var_baski}}', 'musteri_id');
        $this->dropColumn('{{%model_var_baski}}', 'width');
        $this->dropColumn('{{%model_var_baski}}', 'height');
        $this->dropColumn('{{%model_var_baski}}', 'image');
        $this->dropColumn('{{%model_var_baski}}', 'model_view_id');
        $this->dropColumn('{{%model_var_baski}}', 'model_types_id');
    }
}
