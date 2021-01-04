<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%moi_rel_dept}}`.
 */
class m191030_063107_add_some_column_to_moi_rel_dept_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%moi_rel_dept}}', 'start_date', $this->dateTime());
        $this->addColumn('{{%moi_rel_dept}}', 'end_date', $this->dateTime());
        $this->addColumn('{{%moi_rel_dept}}', 'add_info', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%moi_rel_dept}}', 'start_date');
        $this->dropColumn('{{%moi_rel_dept}}', 'end_date');
        $this->dropColumn('{{%moi_rel_dept}}', 'add_info');
    }
}
