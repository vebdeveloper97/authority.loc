<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users_desktop_ruhsati}}`.
 */
class m200513_064304_add_kumas_kontrol_column_to_users_desktop_ruhsati_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            'users_desktop_ruhsati',
            'kumas_kontrol',
            $this->tinyInteger(1)->defaultValue(0)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users_desktop_ruhsati','kumas_kontrol');
    }
}
