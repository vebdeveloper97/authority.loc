<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 */
class m200302_091708_add_is_inside_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('goods','is_inside', $this->smallInteger(1)->defaultValue(1));
        $this->addColumn('goods','doc_item_id', $this->integer());

        // creates index for column `doc_item_id`
        $this->createIndex(
            '{{%idx-goods-doc_item_id}}',
            '{{%goods}}',
            'doc_item_id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `doc_item_id`
        $this->dropIndex(
            '{{%idx-goods-doc_item_id}}',
            '{{%goods}}'
        );
        $this->dropColumn('goods','is_inside');
        $this->dropColumn('goods','doc_item_id');
    }
}
