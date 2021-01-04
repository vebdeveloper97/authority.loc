<?php

use yii\db\Migration;

/**
 * Class m201012_102154_alter_model_orders_rel_fs_attachments_table
 */
class m201012_102154_alter_model_orders_rel_fs_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('model_orders_rel_fs_attachments', 'attachments_id', $this->string(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('model_orders_rel_fs_attachments', 'attachments_id', $this->integer());
    }
}
