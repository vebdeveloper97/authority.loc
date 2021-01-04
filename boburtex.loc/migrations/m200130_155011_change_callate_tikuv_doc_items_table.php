<?php

use yii\db\Migration;

/**
 * Class m200130_155011_change_callate_tikuv_doc_items_table
 */
class m200130_155011_change_callate_tikuv_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("ALTER TABLE `tikuv_doc_items` CHANGE `nastel_party_no` `nastel_party_no` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
