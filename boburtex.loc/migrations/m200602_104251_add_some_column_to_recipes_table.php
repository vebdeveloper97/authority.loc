<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%recipes}}`.
 */
class m200602_104251_add_some_column_to_recipes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn("{{%recipes}}", "pamuk_weight", $this->decimal(20, 3));
        $this->addColumn("{{%recipes}}", "pol_weight", $this->decimal(20, 3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn("{{%recipes}}", "pamuk_weight");
        $this->dropColumn("{{%recipes}}", "pol_weight");
    }
}
