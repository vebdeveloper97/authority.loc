<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%brand1}}`
 * - `{{%brand2}}`
 * - `{{%brand3}}`
 */
class m200409_102652_add_brand_id_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%goods}}', 'brand1', $this->integer());
        $this->addColumn('{{%goods}}', 'brand2', $this->integer());
        $this->addColumn('{{%goods}}', 'brand3', $this->integer());

        // creates index for column `brand1`
        $this->createIndex(
            '{{%idx-goods-brand1}}',
            '{{%goods}}',
            'brand1'
        );

        // add foreign key for table `{{%brand1}}`
        $this->addForeignKey(
            '{{%fk-goods-brand1}}',
            '{{%goods}}',
            'brand1',
            '{{%brend}}',
            'id'
        );

        // creates index for column `brand2`
        $this->createIndex(
            '{{%idx-goods-brand2}}',
            '{{%goods}}',
            'brand2'
        );

        // add foreign key for table `{{%brand2}}`
        $this->addForeignKey(
            '{{%fk-goods-brand2}}',
            '{{%goods}}',
            'brand2',
            '{{%brend}}',
            'id'
        );

        // creates index for column `brand3`
        $this->createIndex(
            '{{%idx-goods-brand3}}',
            '{{%goods}}',
            'brand3'
        );

        // add foreign key for table `{{%brand3}}`
        $this->addForeignKey(
            '{{%fk-goods-brand3}}',
            '{{%goods}}',
            'brand3',
            '{{%brend}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%brand1}}`
        $this->dropForeignKey(
            '{{%fk-goods-brand1}}',
            '{{%goods}}'
        );

        // drops index for column `brand1`
        $this->dropIndex(
            '{{%idx-goods-brand1}}',
            '{{%goods}}'
        );

        // drops foreign key for table `{{%brand2}}`
        $this->dropForeignKey(
            '{{%fk-goods-brand2}}',
            '{{%goods}}'
        );

        // drops index for column `brand2`
        $this->dropIndex(
            '{{%idx-goods-brand2}}',
            '{{%goods}}'
        );

        // drops foreign key for table `{{%brand3}}`
        $this->dropForeignKey(
            '{{%fk-goods-brand3}}',
            '{{%goods}}'
        );

        // drops index for column `brand3`
        $this->dropIndex(
            '{{%idx-goods-brand3}}',
            '{{%goods}}'
        );

        $this->dropColumn('{{%goods}}', 'brand1');
        $this->dropColumn('{{%goods}}', 'brand2');
        $this->dropColumn('{{%goods}}', 'brand3');
    }
}
