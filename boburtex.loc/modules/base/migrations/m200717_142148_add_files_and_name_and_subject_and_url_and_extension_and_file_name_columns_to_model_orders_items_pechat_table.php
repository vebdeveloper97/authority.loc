<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items_pechat}}`.
 */
class m200717_142148_add_files_and_name_and_subject_and_url_and_extension_and_file_name_columns_to_model_orders_items_pechat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items_pechat}}', 'files', $this->char(255));
        $this->addColumn('{{%model_orders_items_pechat}}', 'name', $this->char(255));
        $this->addColumn('{{%model_orders_items_pechat}}', 'subject', $this->text());
        $this->addColumn('{{%model_orders_items_pechat}}', 'url', $this->char(255));
        $this->addColumn('{{%model_orders_items_pechat}}', 'extension', $this->char(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items_pechat}}', 'files');
        $this->dropColumn('{{%model_orders_items_pechat}}', 'name');
        $this->dropColumn('{{%model_orders_items_pechat}}', 'subject');
        $this->dropColumn('{{%model_orders_items_pechat}}', 'url');
        $this->dropColumn('{{%model_orders_items_pechat}}', 'extension');
    }
}
