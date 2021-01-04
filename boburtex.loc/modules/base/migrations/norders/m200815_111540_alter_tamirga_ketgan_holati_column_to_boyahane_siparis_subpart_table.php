<?php

use yii\db\Migration;

/**
 * Class m200815_111540_alter_tamirga_ketgan_holati_column_to_boyahane_siparis_subpart_table
 */
class m200815_111540_alter_tamirga_ketgan_holati_column_to_boyahane_siparis_subpart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('{{%boyahane_siparis_subpart}}', 'tamirga_ketgan_holati', $this->tinyInteger()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200815_111540_alter_tamirga_ketgan_holati_column_to_boyahane_siparis_subpart_table cannot be reverted.\n";

        return false;
    }

}
