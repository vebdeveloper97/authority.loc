<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_attachment_relations}}`.
 */
class m200719_090337_add_created_at_column_to_model_orders_attachment_relations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%model_orders_attachment_relations}}', 'create_at');
        $this->addColumn('{{%model_orders_attachment_relations}}', 'created_at', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%model_orders_attachment_relations}}', 'create_at', $this->integer());
        $this->dropColumn('{{%model_orders_attachment_relations}}', 'created_at');
    }
}
