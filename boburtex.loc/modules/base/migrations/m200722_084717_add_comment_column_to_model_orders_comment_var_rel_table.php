<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_orders_comment_var_rel}}`.
 */
class m200722_084717_add_comment_column_to_model_orders_comment_var_rel_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_orders_comment_var_rel}}', 'comment', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_orders_comment_var_rel}}', 'comment');
    }
}
