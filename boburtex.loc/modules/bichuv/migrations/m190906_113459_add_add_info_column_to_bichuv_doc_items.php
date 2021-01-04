<?php

use yii\db\Migration;

/**
 * Class m190906_113459_add_add_info_column_to_bichuv_doc_items
 */
class m190906_113459_add_add_info_column_to_bichuv_doc_items extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('bichuv_doc_items', 'add_info', $this->string(100));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('bichuv_doc_items', 'add_info');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190906_113459_add_add_info_column_to_bichuv_doc_items cannot be reverted.\n";

        return false;
    }
    */
}
