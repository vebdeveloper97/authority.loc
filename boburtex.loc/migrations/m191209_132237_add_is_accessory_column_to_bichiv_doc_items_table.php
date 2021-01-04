<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%bichiv_doc_items}}`.
 */
class m191209_132237_add_is_accessory_column_to_bichiv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items','is_accessory', $this->smallInteger(2)->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc_items','is_accessory');
    }
}
