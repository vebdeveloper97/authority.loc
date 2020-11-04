<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%reference}}`.
 */
class m201103_100336_add_date_column_to_reference_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%reference}}', 'date', $this->date()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%reference}}', 'date');
    }
}
