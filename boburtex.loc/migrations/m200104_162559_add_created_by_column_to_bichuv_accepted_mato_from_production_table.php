<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_accepted_mato_from_production}}`.
 */
class m200104_162559_add_created_by_column_to_bichuv_accepted_mato_from_production_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_accepted_mato_from_production','created_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_accepted_mato_from_production','created_by');
    }
}
