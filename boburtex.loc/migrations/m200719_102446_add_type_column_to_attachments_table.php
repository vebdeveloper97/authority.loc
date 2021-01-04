<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%attachments}}`.
 */
class m200719_102446_add_type_column_to_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%attachments}}', 'type', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%attachments}}', 'type');
    }
}
