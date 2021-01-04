<?php

use yii\db\Migration;

/**
 * Class m200413_093406_alter_column_to_color_pantone_table
 */
class m200413_093406_alter_column_to_color_pantone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $sql = "UPDATE `color_pantone` SET `code` = REPLACE(code, ' TCX', '') WHERE color_panton_type_id = 3";
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200413_093406_alter_column_to_color_pantone_table cannot be reverted.\n";

        return true;
    }
}
