<?php

use yii\db\Migration;

/**
 * Class m200206_153341_alter_pantone_column_bichuv_sub_doc_items_table
 */
class m200206_153341_alter_pantone_column_bichuv_sub_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bichuv_sub_doc_items', 'pantone', $this->string(50));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return true;
    }
}
