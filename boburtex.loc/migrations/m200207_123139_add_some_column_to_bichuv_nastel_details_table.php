<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_nastel_details}}`.
 */
class m200207_123139_add_some_column_to_bichuv_nastel_details_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_nastel_details','required_count', $this->integer(8));
        $this->addColumn('bichuv_nastel_details','required_weight', $this->decimal(20,3));
        $this->addColumn('bichuv_nastel_details','entity_id', $this->integer());
        $this->addColumn('bichuv_nastel_details','doc_id', $this->integer());
        $this->addColumn('bichuv_nastel_details','entity_type', $this->smallInteger(1)->defaultValue(1));
        $this->addColumn('bichuv_detail_types','token', $this->string(50));

        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-bichuv_nastel_details-nastel_no}}',
            '{{%bichuv_nastel_details}}',
            'nastel_no'
        );

        // creates index for column `entity_id`
        $this->createIndex(
            '{{%idx-bichuv_nastel_details-entity_id}}',
            '{{%bichuv_nastel_details}}',
            'entity_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column `nastel_no`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_details-nastel_no}}',
            '{{%bichuv_nastel_details}}'
        );

        // drops index for column `entity_id`
        $this->dropIndex(
            '{{%idx-bichuv_nastel_details-entity_id}}',
            '{{%bichuv_nastel_details}}'
        );

        $this->dropColumn('bichuv_nastel_details','required_count');
        $this->dropColumn('bichuv_nastel_details','required_weight');
        $this->dropColumn('bichuv_nastel_details','entity_id');
        $this->dropColumn('bichuv_nastel_details','doc_id');
        $this->dropColumn('bichuv_nastel_details','entity_type');
        $this->dropColumn('bichuv_detail_types','token');
    }
}
