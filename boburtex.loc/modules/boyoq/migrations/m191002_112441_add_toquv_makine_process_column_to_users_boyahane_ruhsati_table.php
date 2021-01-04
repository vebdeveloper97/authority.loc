<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users_boyahane_ruhsati}}`.
 */
class m191002_112441_add_toquv_makine_process_column_to_users_boyahane_ruhsati_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users_boyahane_ruhsati','toquv_makine_process', $this->tinyInteger(2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users_boyahane_ruhsati', 'toquv_makine_process');
    }
}
