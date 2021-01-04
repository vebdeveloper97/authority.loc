<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_rm_order}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%color}}`
 */
class m200307_052326_add_some_column_to_toquv_rm_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_rm_order}}', 'color_id', $this->integer());

        // creates index for column `color_id`
        $this->createIndex(
            '{{%idx-toquv_rm_order-color_id}}',
            '{{%toquv_rm_order}}',
            'color_id'
        );

        // add foreign key for table `{{%color}}`
        $this->addForeignKey(
            '{{%fk-toquv_rm_order-color_id}}',
            '{{%toquv_rm_order}}',
            'color_id',
            '{{%color}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%color}}`
        $this->dropForeignKey(
            '{{%fk-toquv_rm_order-color_id}}',
            '{{%toquv_rm_order}}'
        );

        // drops index for column `color_id`
        $this->dropIndex(
            '{{%idx-toquv_rm_order-color_id}}',
            '{{%toquv_rm_order}}'
        );

        $this->dropColumn('{{%toquv_rm_order}}', 'color_id');
    }
}
