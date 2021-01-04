<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_konveyer}}`.
 */
class m200501_063342_add_dept_id_column_to_tikuv_konveyer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_konveyer}}', 'dept_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tikuv_konveyer}}', 'dept_id');
    }
}
