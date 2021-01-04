<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process_production}}`.
 */
class m200825_123122_add_doc_items_id_and_table_name_columns_to_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process_production}}', 'doc_items_id', $this->integer());
        $this->addColumn('{{%mobile_process_production}}', 'table_name', $this->string(60));

        $this->createIndex(
            'idx-mobile_process_production-doc_items_id',
            '{{%mobile_process_production}}',
            'doc_items_id'
        );
        $this->createIndex(
            'idx-mobile_process_production-table_name',
            '{{%mobile_process_production}}',
            'table_name'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'idx-mobile_process_production-doc_items_id',
            '{{%mobile_process_production}}'
        );
        $this->dropIndex(
            'idx-mobile_process_production-table_name',
            '{{%mobile_process_production}}'
        );

        $this->dropColumn('{{%mobile_process_production}}', 'doc_items_id');
        $this->dropColumn('{{%mobile_process_production}}', 'table_name');
    }
}
