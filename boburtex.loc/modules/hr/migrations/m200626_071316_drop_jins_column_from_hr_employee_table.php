<?php

use yii\db\Migration;

/**
 * Handles dropping columns from table `{{%hr_employee}}`.
 */
class m200626_071316_drop_jins_column_from_hr_employee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%hr_employee}}', 'jins');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%hr_employee}}', 'jins', $this->text());
    }
}
