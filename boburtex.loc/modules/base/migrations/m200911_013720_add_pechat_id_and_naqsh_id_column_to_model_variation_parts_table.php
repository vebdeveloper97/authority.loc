<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_variation_parts}}`.
 */
class m200911_013720_add_pechat_id_and_naqsh_id_column_to_model_variation_parts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_variation_parts}}', 'pechat_id', $this->integer());
        $this->addColumn('{{%model_variation_parts}}', 'naqsh_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_variation_parts}}', 'pechat_id');
        $this->dropColumn('{{%model_variation_parts}}', 'naqsh_id');
    }
}
