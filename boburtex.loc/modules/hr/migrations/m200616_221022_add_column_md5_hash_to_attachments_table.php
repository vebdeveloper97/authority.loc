<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%attachments}}`.
 */
class m200616_221022_add_column_md5_hash_to_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%attachments}}', 'md5_hash', $this->char(32)->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%attachments}}', 'md5_hash');
    }
}
