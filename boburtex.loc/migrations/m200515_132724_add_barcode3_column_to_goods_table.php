<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 */
class m200515_132724_add_barcode3_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%goods_barcode}}', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer(),
            'brand_id' => $this->integer(),
            'number' => $this->integer(2)->defaultValue(1),
            'barcode' => $this->string(40)
        ]);

        // creates index for column `goods_id`
        $this->createIndex(
            '{{%idx-goods_barcode-goods_id}}',
            '{{%goods_barcode}}',
            'goods_id'
        );

        // add foreign key for table `{{%goods}}`
        $this->addForeignKey(
            '{{%fk-goods_barcode-goods_id}}',
            '{{%goods_barcode}}',
            'goods_id',
            '{{%goods}}',
            'id',
            'CASCADE'
        );

        // creates index for column `brand_id`
        $this->createIndex(
            '{{%idx-goods_barcode-brand_id}}',
            '{{%goods_barcode}}',
            'brand_id'
        );

        // add foreign key for table `{{%brend}}`
        $this->addForeignKey(
            '{{%fk-goods_barcode-brand_id}}',
            '{{%goods_barcode}}',
            'brand_id',
            '{{%brend}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%goods}}`
        $this->dropForeignKey(
            '{{%fk-goods_barcode-goods_id}}',
            '{{%goods_barcode}}'
        );

        // drops index for column `goods_id`
        $this->dropIndex(
            '{{%idx-goods_barcode-goods_id}}',
            '{{%goods_barcode}}'
        );

        // drops foreign key for table `{{%brend}}`
        $this->dropForeignKey(
            '{{%fk-goods_barcode-brand_id}}',
            '{{%goods_barcode}}'
        );

        // drops index for column `brand_id`
        $this->dropIndex(
            '{{%idx-goods_barcode-brand_id}}',
            '{{%goods_barcode}}'
        );

        $this->dropTable('{{%goods_barcode}}');
    }
}
