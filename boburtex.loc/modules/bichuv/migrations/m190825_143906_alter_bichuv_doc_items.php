<?php

use yii\db\Migration;

/**
 * Class m190825_143906_alter_bichuv_doc_items
 */
class m190825_143906_alter_bichuv_doc_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bichuv_doc_items','quantity', $this->decimal(20, 3)->notNull());
        $this->alterColumn('bichuv_doc_items','document_quantity', $this->decimal(20, 3)->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }


}
