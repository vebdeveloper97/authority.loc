<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mobile_process_production}}`.
 */
class m200826_095246_add_doc_id_column_to_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mobile_process_production}}', 'doc_id', $this->integer());

        $this->createIndex('idx-mobile_process_production-doc_id', 'mobile_process_production', 'doc_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-mobile_process_production-doc_id', 'mobile_process_production');

        $this->dropColumn('{{%mobile_process_production}}', 'doc_id');
    }
}
