<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_given_roll_items}}`.
 */
class m191226_182610_add_party_column_to_bichuv_given_roll_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_given_roll_items', 'party_no', $this->string(50));
        $this->addColumn('bichuv_given_roll_items', 'musteri_party_no', $this->string(50));
        $this->addColumn('bichuv_given_roll_items', 'roll_count', $this->decimal(20,2));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_given_roll_items', 'party_no');
        $this->dropColumn('bichuv_given_roll_items', 'musteri_party_no');
        $this->addColumn('bichuv_given_roll_items', 'roll_count');
    }
}
