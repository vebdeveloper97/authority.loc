<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%moi_rel_dept}}`.
 */
class m200606_084433_add_some_column_to_moi_rel_dept_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%moi_rel_dept}}', 'thread_length', $this->string(50));
        $this->addColumn('{{%moi_rel_dept}}', 'finish_en', $this->string(50));
        $this->addColumn('{{%moi_rel_dept}}', 'finish_gramaj', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%moi_rel_dept}}', 'thread_length');
        $this->dropColumn('{{%moi_rel_dept}}', 'finish_en');
        $this->dropColumn('{{%moi_rel_dept}}', 'finish_gramaj');
    }
}
