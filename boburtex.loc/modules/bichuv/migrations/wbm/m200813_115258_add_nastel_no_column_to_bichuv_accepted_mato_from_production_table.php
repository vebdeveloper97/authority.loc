<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_accepted_mato_from_production}}`.
 */
class m200813_115258_add_nastel_no_column_to_bichuv_accepted_mato_from_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_accepted_mato_from_production}}', 'nastel_no', $this->string(30));
        // creates index for column `nastel_no`
        $this->createIndex(
            '{{%idx-bichuv_accepted_mato_from_production-nastel_no}}',
            '{{%bichuv_accepted_mato_from_production}}',
            'nastel_no'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops index for column nastel_no
        $this->dropIndex(
            '{{%idx-bichuv_accepted_mato_from_production-nastel_no}}',
            '{{%bichuv_accepted_mato_from_production}}'
        );
        $this->dropColumn('{{%bichuv_accepted_mato_from_production}}', 'nastel_no');
    }
}
