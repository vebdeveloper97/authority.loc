<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users_desktop_ruhsati}}`.
 */
class m200514_095412_add_recept_column_to_users_desktop_ruhsati_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users_desktop_ruhsati','recept', $this->smallInteger(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users_desktop_ruhsati','recept');
    }
}
