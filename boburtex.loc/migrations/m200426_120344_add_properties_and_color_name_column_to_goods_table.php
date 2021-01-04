<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%goods}}`.
 */
class m200426_120344_add_properties_and_color_name_column_to_goods_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%goods}}', 'properties', $this->string());
        $this->addColumn('{{%goods}}', 'color_name', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%goods}}', 'properties');
        $this->dropColumn('{{%goods}}', 'color_name');
    }
}
