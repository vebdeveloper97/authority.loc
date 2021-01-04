<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_item_balance}}`.
 */
class m190823_045223_alter_bichuv_item_balance_doc_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('bichuv_item_balance','document_type', $this->smallInteger()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('bichuv_item_balance','document_type', $this->string(50));
    }
}
