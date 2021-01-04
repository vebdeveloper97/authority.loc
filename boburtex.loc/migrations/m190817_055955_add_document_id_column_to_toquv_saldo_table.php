<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_saldo}}`.
 */
class m190817_055955_add_document_id_column_to_toquv_saldo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_saldo}}', 'td_id', $this->integer());

        //td_id
        $this->createIndex(
            'idx-toquv_saldo-td_id',
            'toquv_saldo',
            'td_id'
        );

        $this->addForeignKey(
            'fk-toquv_saldo-td_id',
            'toquv_saldo',
            'td_id',
            'toquv_documents',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //td_id
        $this->dropForeignKey(
            'fk-toquv_saldo-td_id',
            'toquv_saldo'
        );

        $this->dropIndex(
            'idx-toquv_saldo-td_id',
            'toquv_saldo'
        );
        $this->dropColumn('{{%toquv_saldo}}', 'td_id');
    }
}
