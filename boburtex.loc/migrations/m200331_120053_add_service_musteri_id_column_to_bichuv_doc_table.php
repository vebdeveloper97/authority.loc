<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%musteri}}`
 */
class m200331_120053_add_service_musteri_id_column_to_bichuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_doc}}', 'service_musteri_id', $this->bigInteger()->defaultValue(11));
        $this->addColumn('{{%bichuv_doc}}','deadline', $this->date());
        $this->addColumn('{{%bichuv_doc}}','is_service', $this->boolean()->defaultValue(0));

        // creates index for column `service_musteri_id`
        $this->createIndex(
            '{{%idx-bichuv_doc-service_musteri_id}}',
            '{{%bichuv_doc}}',
            'service_musteri_id'
        );

        // add foreign key for table `{{%service_museri}}`
        $this->addForeignKey(
            '{{%fk-bichuv_doc-service_musteri_id}}',
            '{{%bichuv_doc}}',
            'service_musteri_id',
            '{{%musteri}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%service_museri}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_doc-service_musteri_id}}',
            '{{%bichuv_doc}}'
        );

        // drops index for column `service_musteri_id`
        $this->dropIndex(
            '{{%idx-bichuv_doc-service_musteri_id}}',
            '{{%bichuv_doc}}'
        );

        $this->dropColumn('{{%bichuv_doc}}', 'service_musteri_id');
        $this->dropColumn('{{%bichuv_doc}}', 'deadline');
        $this->dropColumn('{{%bichuv_doc}}', 'is_service');
    }
}
