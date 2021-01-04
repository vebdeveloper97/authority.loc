<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%boyahane_siparis_subpart}}`.
 */
class m191226_075309_add_some_column_to_boyahane_siparis_subpart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'status', $this->boolean()->defaultValue(1));
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'tamir_process', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'status');
        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'tamir_process');
    }
}
