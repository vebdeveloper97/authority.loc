<?php

use yii\db\Migration;

/**
 * Class m200417_195844_change_charset_models_variations_table
 */
class m200417_195844_change_charset_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE models_variations CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }

}
