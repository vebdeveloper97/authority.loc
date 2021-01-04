<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%mato_info}}`.
 */
class m200215_055339_add_column_to_mato_info_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%mato_info}}', 'model_musteri_id', $this->integer());
        $this->addColumn('{{%mato_info}}', 'color_pantone_id', $this->integer());
        $this->addColumn('{{%mato_info}}', 'model_code', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%mato_info}}', 'model_musteri_id');
        $this->dropColumn('{{%mato_info}}', 'color_pantone_id');
        $this->dropColumn('{{%mato_info}}', 'model_code');
    }
}
