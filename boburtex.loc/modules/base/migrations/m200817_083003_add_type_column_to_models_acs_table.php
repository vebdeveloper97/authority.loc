<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_acs}}`.
 */
class m200817_083003_add_type_column_to_models_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%models_acs}}', 'type', $this->smallInteger());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%models_acs}}', 'type');
    }
}
