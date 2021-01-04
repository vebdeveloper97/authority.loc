<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%some changes}}`.
 */
class m191105_084833_add_some_changes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_goods_doc_pack', 'is_incoming', $this->smallInteger(1)->defaultValue(1));

        // creates index for column `model_id`
        $this->createIndex(
            '{{%idx-goods-model_id}}',
            '{{%goods}}',
            'model_id'
        );

        // add foreign key for table `{{%goods}}`
        $this->addForeignKey(
            '{{%fk-goods-model_id}}',
            '{{%goods}}',
            'model_id',
            '{{%models_list}}',
            'id'
        );

        // creates index for column `color`
        $this->createIndex(
            '{{%idx-goods-color}}',
            '{{%goods}}',
            'color'
        );

        // add foreign key for table `{{%goods}}`
        $this->addForeignKey(
            '{{%fk-goods-color}}',
            '{{%goods}}',
            'color',
            '{{%color_pantone}}',
            'id'
        );

        // creates index for column `size_type`
        $this->createIndex(
            '{{%idx-goods-size_type}}',
            '{{%goods}}',
            'size_type'
        );

        // add foreign key for table `{{%goods}}`
        $this->addForeignKey(
            '{{%fk-goods-size_type}}',
            '{{%goods}}',
            'size_type',
            '{{%size_type}}',
            'id'
        );

        // creates index for column `size`
        $this->createIndex(
            '{{%idx-goods-size}}',
            '{{%goods}}',
            'size'
        );

        // add foreign key for table `{{%goods}}`
        $this->addForeignKey(
            '{{%fk-goods-size}}',
            '{{%goods}}',
            'size',
            '{{%size}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tikuv_goods_doc_pack', 'is_incoming');

        // drops foreign key for table `{{%color}}`
        $this->dropForeignKey(
            '{{%fk-goods-color}}',
            '{{%goods}}'
        );

        // drops index for column `color`
        $this->dropIndex(
            '{{%idx-goods-color}}',
            '{{%goods}}'
        );

        // drops foreign key for table `{{%size}}`
        $this->dropForeignKey(
            '{{%fk-goods-size}}',
            '{{%goods}}'
        );

        // drops index for column `size`
        $this->dropIndex(
            '{{%idx-goods-size}}',
            '{{%goods}}'
        );

        // drops foreign key for table `{{%size_type}}`
        $this->dropForeignKey(
            '{{%fk-goods-size_type}}',
            '{{%goods}}'
        );

        // drops index for column `size`
        $this->dropIndex(
            '{{%idx-goods-size_type}}',
            '{{%goods}}'
        );

        // drops foreign key for table `{{%model_id}}`
        $this->dropForeignKey(
            '{{%fk-goods-model_id}}',
            '{{%goods}}'
        );

        // drops index for column `model_id`
        $this->dropIndex(
            '{{%idx-goods-model_id}}',
            '{{%goods}}'
        );
    }
}
