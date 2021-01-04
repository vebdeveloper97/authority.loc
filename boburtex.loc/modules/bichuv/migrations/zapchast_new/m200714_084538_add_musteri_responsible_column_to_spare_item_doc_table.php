<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%spare_item_doc}}`.
 */
class m200714_084538_add_musteri_responsible_column_to_spare_item_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%spare_item_doc}}', 'musteri_responsible', $this->char(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%spare_item_doc}}', 'musteri_responsible');
    }
}
