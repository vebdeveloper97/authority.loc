<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%base_qc_document}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%mobile_process_production}}`
 * - `{{%base_norm_standart}}`
 * - `{{%sort_name}}`
 */
class m200926_123608_create_base_qc_document_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%base_qc_document}}', [
            'id' => $this->primaryKey(),
            'nastel_no' => $this->string(),
            'mobile_process_production_id' => $this->integer(),
            'norm_standart_id' => $this->integer(),
            'reg_date' => $this->dateTime(),
            'sort_id' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `mobile_process_production_id`
        $this->createIndex(
            '{{%idx-base_qc_document-mobile_process_production_id}}',
            '{{%base_qc_document}}',
            'mobile_process_production_id'
        );

        // add foreign key for table `{{%mobile_process_production}}`
        $this->addForeignKey(
            '{{%fk-base_qc_document-mobile_process_production_id}}',
            '{{%base_qc_document}}',
            'mobile_process_production_id',
            '{{%mobile_process_production}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `norm_standart_id`
        $this->createIndex(
            '{{%idx-base_qc_document-norm_standart_id}}',
            '{{%base_qc_document}}',
            'norm_standart_id'
        );

        // add foreign key for table `{{%base_norm_standart}}`
        $this->addForeignKey(
            '{{%fk-base_qc_document-norm_standart_id}}',
            '{{%base_qc_document}}',
            'norm_standart_id',
            '{{%base_norm_standart}}',
            'id',
            'RESTRICT'
        );

        // creates index for column `sort_id`
        $this->createIndex(
            '{{%idx-base_qc_document-sort_id}}',
            '{{%base_qc_document}}',
            'sort_id'
        );

        // add foreign key for table `{{%sort_name}}`
        $this->addForeignKey(
            '{{%fk-base_qc_document-sort_id}}',
            '{{%base_qc_document}}',
            'sort_id',
            '{{%sort_name}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%mobile_process_production}}`
        $this->dropForeignKey(
            '{{%fk-base_qc_document-mobile_process_production_id}}',
            '{{%base_qc_document}}'
        );

        // drops index for column `mobile_process_production_id`
        $this->dropIndex(
            '{{%idx-base_qc_document-mobile_process_production_id}}',
            '{{%base_qc_document}}'
        );

        // drops foreign key for table `{{%base_norm_standart}}`
        $this->dropForeignKey(
            '{{%fk-base_qc_document-norm_standart_id}}',
            '{{%base_qc_document}}'
        );

        // drops index for column `norm_standart_id`
        $this->dropIndex(
            '{{%idx-base_qc_document-norm_standart_id}}',
            '{{%base_qc_document}}'
        );

        // drops foreign key for table `{{%sort_name}}`
        $this->dropForeignKey(
            '{{%fk-base_qc_document-sort_id}}',
            '{{%base_qc_document}}'
        );

        // drops index for column `sort_id`
        $this->dropIndex(
            '{{%idx-base_qc_document-sort_id}}',
            '{{%base_qc_document}}'
        );

        $this->dropTable('{{%base_qc_document}}');
    }
}
