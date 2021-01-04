<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichuv_doc_items}}`.
 */
class m191223_170302_add_party_no_column_to_bichuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','party_no', $this->string(50));
        $this->addColumn('bichuv_doc_items','musteri_party_no', $this->string(50));
        $this->alterColumn('bichuv_doc_items', 'roll_count', $this->decimal(20,2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc_items','party_no');
        $this->dropColumn('bichuv_doc_items','musteri_party_no');
        $this->alterColumn('bichuv_doc_items', 'roll_count', $this->decimal(5,2)->defaultValue(0));
    }
}
