<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_processes}}`.
 */
class m200213_070910_add_some_column_to_bichuv_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('bichuv_processes', ['name' => 'Bichuv Kesim']);
        $this->upsert('bichuv_processes', ['name' => 'Meto']);
        $this->upsert('bichuv_processes', ['name' => 'Tasnif']);
        $this->upsert('bichuv_processes', ['name' => 'Naqsh/Pechat']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
