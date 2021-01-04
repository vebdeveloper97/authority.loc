<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%hr_departments}}`.
 */
class m200701_053519_add_token_column_to_hr_departments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%hr_departments}}', 'token', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%hr_departments}}', 'token');
    }
}
