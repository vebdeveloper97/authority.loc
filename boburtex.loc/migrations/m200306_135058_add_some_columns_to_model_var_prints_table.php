<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_var_prints}}`.
 */
class m200306_135058_add_some_columns_to_model_var_prints_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `model_var_prints` ADD `height` DOUBLE(8,2) NULL DEFAULT '0' AFTER `name`;");
        $this->execute("ALTER TABLE `model_var_prints` ADD `width` DOUBLE(8,2) NULL DEFAULT '0' AFTER `name`;");
        $this->addColumn('{{%model_var_prints}}', 'image', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%model_var_prints}}', 'width');
        $this->dropColumn('{{%model_var_prints}}', 'height');
        $this->dropColumn('{{%model_var_prints}}', 'image');
    }
}
