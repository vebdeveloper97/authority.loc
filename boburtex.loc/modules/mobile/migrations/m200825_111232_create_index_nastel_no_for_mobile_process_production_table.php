<?php

use yii\db\Migration;

/**
 * create index `{{%index_nastel_no_for_mobile_process_production}}`.
 */
class m200825_111232_create_index_nastel_no_for_mobile_process_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx-mobile_process_production-nastel_no', '{{%mobile_process_production}}', 'nastel_no');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-mobile_process_production-nastel_no', '{{%mobile_process_production}}');
    }
}
