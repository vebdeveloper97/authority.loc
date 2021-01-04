<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%color_pantone}}`.
 */
class m200409_103455_add_some_column_to_color_pantone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%color_pantone}}', 'status', $this->smallInteger(6));
        $this->addColumn('{{%color_pantone}}', 'created_by', $this->integer());
        $this->addColumn('{{%color_pantone}}', 'created_at', $this->integer());
        $this->addColumn('{{%color_pantone}}', 'updated_by', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%color_pantone}}', 'status');
        $this->dropColumn('{{%color_pantone}}', 'created_by');
        $this->dropColumn('{{%color_pantone}}', 'created_at');
        $this->dropColumn('{{%color_pantone}}', 'updated_by');
    }
}
