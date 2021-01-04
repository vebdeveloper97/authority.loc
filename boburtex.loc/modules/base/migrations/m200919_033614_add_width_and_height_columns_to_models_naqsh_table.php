<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_naqsh}}`.
 */
class m200919_033614_add_width_and_height_columns_to_models_naqsh_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_naqsh}}', 'width', $this->integer());
        $this->addColumn('{{%models_naqsh}}', 'height', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_naqsh}}', 'width');
        $this->dropColumn('{{%models_naqsh}}', 'height');
    }
}
