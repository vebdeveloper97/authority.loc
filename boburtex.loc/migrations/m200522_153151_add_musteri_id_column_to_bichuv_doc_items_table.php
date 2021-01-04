<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc_items}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 */
class m200522_153151_add_musteri_id_column_to_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc_items}}', 'musteri_id', $this->bigInteger());

        // creates index for column `musteri_id`
        $this->createIndex(
            '{{%idx-bichuv_doc_items-musteri_id}}',
            '{{%bichuv_doc_items}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc_items-musteri_id}}',
            '{{%bichuv_doc_items}}',
            'musteri_id',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc_items-musteri_id}}',
            '{{%bichuv_doc_items}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc_items-musteri_id}}',
            '{{%bichuv_doc_items}}'
        );

        $this->dropColumn('{{%bichuv_doc_items}}', 'musteri_id');
    }
}
