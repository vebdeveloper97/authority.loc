<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_doc}}`.
 */
class m200415_150919_add_change_note_column_to_tikuv_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_doc}}', 'change_note', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%tikuv_doc}}', 'change_note');
    }
}
