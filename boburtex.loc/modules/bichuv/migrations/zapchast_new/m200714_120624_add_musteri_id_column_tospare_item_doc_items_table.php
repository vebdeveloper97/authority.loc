<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item_doc_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 */
class m200714_120624_add_musteri_id_column_tospare_item_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item_doc_items}}', 'musteri_id', $this->bigInteger());

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-spare_item_doc_items-musteri_id}}',
            '{{%spare_item_doc_items}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-spare_item_doc_items-musteri_id}}',
            '{{%spare_item_doc_items}}',
            'musteri_id',
            '{{%musteri}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-spare_item_doc_items-musteri_id}}',
            '{{%spare_item_doc_items}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-spare_item_doc_items-musteri_id}}',
            '{{%spare_item_doc_items}}'
        );

        $this->dropColumn('{{%spare_item_doc_items}}', 'musteri_id');
    }
}
