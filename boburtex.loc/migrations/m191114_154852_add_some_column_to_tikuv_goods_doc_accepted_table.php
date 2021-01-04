<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc_accepted}}`.
 */
class m191114_154852_add_some_column_to_tikuv_goods_doc_accepted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tikuv_goods_doc_accepted','doc_number', $this->string());
        $this->addColumn('tikuv_goods_doc_accepted','reg_date', $this->dateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tikuv_goods_doc_accepted','doc_number');
        $this->dropColumn('tikuv_goods_doc_accepted','reg_date');
    }
}
