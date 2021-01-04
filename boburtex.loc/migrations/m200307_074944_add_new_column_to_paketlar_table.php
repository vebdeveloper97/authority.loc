<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%paketlar}}`.
 */
class m200307_074944_add_new_column_to_paketlar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%paketlar}}', 'brutto_kg', $this->decimal(20,3));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%paketlar}}', 'brutto_kg');
    }
}
