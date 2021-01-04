<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_items}}`.
 */
class m200717_142515_add_files_and_url_and_extension_columns_to_model_orders_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_items}}', 'files', $this->char(255));
        $this->addColumn('{{%model_orders_items}}', 'url', $this->char(255));
        $this->addColumn('{{%model_orders_items}}', 'extension', $this->char(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_items}}', 'files');
        $this->dropColumn('{{%model_orders_items}}', 'url');
        $this->dropColumn('{{%model_orders_items}}', 'extension');
    }
}
