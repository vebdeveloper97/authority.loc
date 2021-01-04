<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 */
class m200228_061411_add_some_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('goods','boyoqhona_model_id', $this->smallInteger(6));
        $this->addColumn('goods','boyoqhona_color_id', $this->integer());

        // creates index for column `boyoqhona_model_id`
        $this->createIndex(
            '{{%idx-goods-boyoqhona_model_id}}',
            '{{%goods}}',
            'boyoqhona_model_id'
        );
        // add foreign key for table `{{%product}}`
        $this->addForeignKey(
            '{{%fk-goods-boyoqhona_model_id}}',
            '{{%goods}}',
            'boyoqhona_model_id',
            '{{%product}}',
            'id'
        );

        // creates index for column `boyoqhona_color_id`
        $this->createIndex(
            '{{%idx-goods-boyoqhona_color_id}}',
            '{{%goods}}',
            'boyoqhona_color_id'
        );
        // add foreign key for table `{{%color}}`
        $this->addForeignKey(
            '{{%fk-goods-boyoqhona_color_id}}',
            '{{%goods}}',
            'boyoqhona_color_id',
            '{{%color}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%boyoqhona_model_id}}`
        $this->dropForeignKey(
            '{{%fk-goods-boyoqhona_model_id}}',
            '{{%goods}}'
        );

        // drops index for column `boyoqhona_model_id`
        $this->dropIndex(
            '{{%idx-goods-boyoqhona_model_id}}',
            '{{%goods}}'
        );

        // drops foreign key for table `{{%boyoqhona_color_id}}`
        $this->dropForeignKey(
            '{{%fk-goods-boyoqhona_color_id}}',
            '{{%goods}}'
        );

        // drops index for column `boyoqhona_color_id`
        $this->dropIndex(
            '{{%idx-goods-boyoqhona_color_id}}',
            '{{%goods}}'
        );

        $this->dropColumn('goods','boyoqhona_model_id');
        $this->dropColumn('goods','boyoqhona_color_id');
    }
}
