<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%models_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_pechat}}`
 * - `{{%models_naqsh}}`
 */
class m200921_113816_drop_pechat_id_and_naqsh_id_columns_from_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%models_variations}}', 'pechat_id');
        $this->dropColumn('{{%models_variations}}', 'naqsh_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%models_variations}}', 'pechat_id', $this->integer());
        $this->addColumn('{{%models_variations}}', 'naqsh_id', $this->integer());
    }
}
