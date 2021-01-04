<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tikuv_products}}`.
 */
class m200302_073448_create_tikuv_products_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tikuv_products}}', [
            'id' => $this->primaryKey(),
            'goods_id' => $this->integer(),
            'barcode' => $this->integer(13),
            'barcode1' => $this->integer(13),
            'barcode2' => $this->integer(13),
            'is_inside' => $this->boolean()->defaultValue(1),
            'type' => $this->integer()->defaultValue(1),
            'model_no' => $this->string(30),
            'model_id' => $this->integer(),
            'size_type' => $this->integer(),
            'size' => $this->integer(),
            'color' => $this->integer(),
            'name' => $this->string(100),
            'old_name' => $this->string(100),
            'category' => $this->integer(),
            'sub_category' => $this->integer(),
            'model_type' => $this->integer(),
            'season' => $this->integer(),
            'status' => $this->smallInteger(6)->defaultValue(1),
            'desc1' => $this->string(255),
            'desc2' => $this->string(255),
            'desc3' => $this->string(255),
            'size_collection' => $this->string(255),
            'color_collection' => $this->string(255),
            'boyoqhona_model_id' => $this->smallInteger(6),
            'boyoqhona_color_id' => $this->integer(),
        ]);

        // creates index for column `goods_id`
        $this->createIndex(
            '{{%idx-tikuv_products-goods_id}}',
            '{{%tikuv_products}}',
            'goods_id'
        );

        // creates index for column `barcode`
        $this->createIndex(
            '{{%idx-tikuv_products-barcode}}',
            '{{%tikuv_products}}',
            'barcode'
        );

        // creates index for column `boyoqhona_model_id`
        $this->createIndex(
            '{{%idx-tikuv_products-boyoqhona_model_id}}',
            '{{%tikuv_products}}',
            'boyoqhona_model_id'
        );

        // creates index for column `boyoqhona_color_id`
        $this->createIndex(
            '{{%idx-tikuv_products-boyoqhona_color_id}}',
            '{{%tikuv_products}}',
            'boyoqhona_color_id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `goods_id`
        $this->dropIndex(
            '{{%idx-tikuv_products-goods_id}}',
            '{{%tikuv_products}}'
        );

        // drops index for column `barcode`
        $this->dropIndex(
            '{{%idx-tikuv_products-barcode}}',
            '{{%tikuv_products}}'
        );

        // drops index for column `boyoqhona_model_id`
        $this->dropIndex(
            '{{%idx-tikuv_products-boyoqhona_model_id}}',
            '{{%tikuv_products}}'
        );
        // drops index for column `boyoqhona_color_id`
        $this->dropIndex(
            '{{%idx-tikuv_products-boyoqhona_color_id}}',
            '{{%tikuv_products}}'
        );

        $this->dropTable('{{%tikuv_products}}');
    }
}
