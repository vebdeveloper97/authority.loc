<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 * - `{{%musteri}}`
 */
class m200526_162503_add_some_column_to_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_doc}}', 'is_service', $this->smallInteger()->defaultValue(0));
        $this->addColumn('{{%tikuv_doc}}', 'from_musteri', $this->bigInteger());
        $this->addColumn('{{%tikuv_doc}}', 'to_musteri', $this->bigInteger());
        $this->addColumn('{{%tikuv_doc}}', 'usluga_doc_id', $this->integer());

        // creates index for column `from_musteri`
        $this->createIndex(
            '{{%idx-tikuv_doc-from_musteri}}',
            '{{%tikuv_doc}}',
            'from_musteri'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-from_musteri}}',
            '{{%tikuv_doc}}',
            'from_musteri',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `to_musteri`
        $this->createIndex(
            '{{%idx-tikuv_doc-to_musteri}}',
            '{{%tikuv_doc}}',
            'to_musteri'
        );

        // add foreign key for table `{{%musteri}}`
        $this->addForeignKey(
            '{{%fk-tikuv_doc-to_musteri}}',
            '{{%tikuv_doc}}',
            'to_musteri',
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
            '{{%fk-tikuv_doc-from_musteri}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `from_musteri`
        $this->dropIndex(
            '{{%idx-tikuv_doc-from_musteri}}',
            '{{%tikuv_doc}}'
        );

        // drops foreign key for table `{{%musteri}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_doc-to_musteri}}',
            '{{%tikuv_doc}}'
        );

        // drops index for column `to_musteri`
        $this->dropIndex(
            '{{%idx-tikuv_doc-to_musteri}}',
            '{{%tikuv_doc}}'
        );

        $this->dropColumn('{{%tikuv_doc}}', 'is_service');
        $this->dropColumn('{{%tikuv_doc}}', 'from_musteri');
        $this->dropColumn('{{%tikuv_doc}}', 'to_musteri');
        $this->dropColumn('{{%tikuv_doc}}', 'usluga_doc_id');
    }
}
