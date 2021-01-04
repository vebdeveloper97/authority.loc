<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_processes}}`.
 */
class m200227_071547_add_slug_column_to_bichuv_processes_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%bichuv_processes}}', 'slug', $this->string());
        $this->addColumn('{{%bichuv_tables}}', 'slug', $this->string());
        $this->addColumn('{{%bichuv_detail_types}}', 'slug', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%bichuv_processes}}', 'slug');
        $this->dropColumn('{{%bichuv_tables}}', 'slug');
        $this->dropColumn('{{%bichuv_detail_types}}', 'slug');
    }
}
