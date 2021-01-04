<?php

use yii\db\Migration;

/**
 * Class m191014_213729_rename_top_accepted_table
 */
class m191014_213729_rename_top_accepted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('top_accepted', 'tikuv_top_accepted');
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('tikuv_top_accepted','top_accepted');
    }
}
