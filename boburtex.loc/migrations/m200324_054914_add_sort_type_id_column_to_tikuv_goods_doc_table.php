<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%sort_type}}`
 */
class m200324_054914_add_sort_type_id_column_to_tikuv_goods_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_goods_doc}}', 'sort_type_id', $this->integer());

        // creates index for column `sort_type_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc-sort_type_id}}',
            '{{%tikuv_goods_doc}}',
            'sort_type_id'
        );

        // add foreign key for table `{{%sort_name}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc-sort_type_id}}',
            '{{%tikuv_goods_doc}}',
            'sort_type_id',
            '{{%sort_name}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%sort_name}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc-sort_type_id}}',
            '{{%tikuv_goods_doc}}'
        );

        // drops index for column `sort_type_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc-sort_type_id}}',
            '{{%tikuv_goods_doc}}'
        );

        $this->dropColumn('{{%tikuv_goods_doc}}', 'sort_type_id');
    }
}
