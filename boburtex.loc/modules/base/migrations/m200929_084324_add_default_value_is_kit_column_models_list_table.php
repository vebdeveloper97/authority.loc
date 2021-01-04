<?php

use yii\db\Migration;

/**
 * Class m200929_084324_add_default_value_is_kit_column_models_list_table
 */
class m200929_084324_add_default_value_is_kit_column_models_list_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `models_list` CHANGE `is_kit` `is_kit` SMALLINT NULL DEFAULT '0';");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
