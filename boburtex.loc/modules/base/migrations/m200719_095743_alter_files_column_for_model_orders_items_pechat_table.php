<?php

use yii\db\Migration;

/**
 * Class m200719_095743_alter_files_column_for_model_orders_items_pechat_table
 */
class m200719_095743_alter_files_column_for_model_orders_items_pechat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%model_orders_items_pechat}}', 'files', 'attachment_id');

        $this->alterColumn('{{%model_orders_items_pechat}}', 'attachment_id', $this->integer());

        $this->createIndex(
            'idx-model_orders_items_pechat-attachment_id',
            '{{%model_orders_items_pechat}}',
            'attachment_id'
        );

        $this->addForeignKey(
            'fk-model_orders_items_pechat-attachment_id',
            '{{%model_orders_items_pechat}}',
            'attachment_id',
            '{{%attachments}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-model_orders_items_pechat-attachment_id',
            '{{%model_orders_items_pechat}}'
        );

        $this->dropIndex(
            'idx-model_orders_items_pechat-attachment_id',
            '{{%model_orders_items_pechat}}'
        );

        $this->alterColumn('{{%model_orders_items_pechat}}', 'attachment_id', $this->string());

        $this->renameColumn('{{%model_orders_items_pechat}}', 'attachment_id', 'files');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200719_095743_alter_files_column_for_model_orders_items_pechat_table cannot be reverted.\n";

        return false;
    }
    */
}
