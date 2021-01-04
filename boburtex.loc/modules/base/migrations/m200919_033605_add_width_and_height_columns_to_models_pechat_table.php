<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_pechat}}`.
 */
class m200919_033605_add_width_and_height_columns_to_models_pechat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_pechat}}', 'width', $this->integer());
        $this->addColumn('{{%models_pechat}}', 'height', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_pechat}}', 'width');
        $this->dropColumn('{{%models_pechat}}', 'height');
    }
}
