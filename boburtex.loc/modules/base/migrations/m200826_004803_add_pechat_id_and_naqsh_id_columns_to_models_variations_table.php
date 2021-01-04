<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variations}}`.
 */
class m200826_004803_add_pechat_id_and_naqsh_id_columns_to_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_variations}}', 'pechat_id', $this->integer());
        $this->addColumn('{{%models_variations}}', 'naqsh_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_variations}}', 'pechat_id');
        $this->dropColumn('{{%models_variations}}', 'naqsh_id');
    }
}
