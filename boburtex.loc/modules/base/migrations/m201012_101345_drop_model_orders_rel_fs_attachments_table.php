<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%model_orders_rel_fs_attachments}}`.
 */
class m201012_101345_drop_model_orders_rel_fs_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey(
            'fk-model_orders_rel_fs_attachments-attachments_id',
            'model_orders_rel_fs_attachments',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey(
            'fk-model_orders_rel_fs_attachments-attachments_id',
            'model_orders_rel_fs_attachments',
            'attachments_id',
            'attachments',
            'id'
        );
    }
}
