<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_saldo}}`.
 */
class m190817_055955_add_pul_birligi_id_column_to_toquv_saldo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%toquv_saldo}}', 'pb_id', $this->integer());

        //pb_id_id
        $this->createIndex(
            'idx-toquv_saldo-pb_id',
            'toquv_saldo',
            'pb_id'
        );

        $this->addForeignKey(
            'fk-toquv_saldo-pb_id',
            'toquv_saldo',
            'pb_id',
            'pul_birligi',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //pb_id
        $this->dropForeignKey(
            'fk-toquv_saldo-pb_id',
            'toquv_saldo'
        );

        $this->dropIndex(
            'idx-toquv_saldo-pb_id',
            'toquv_saldo'
        );
        $this->dropColumn('{{%toquv_saldo}}', 'pb_id');
    }
}
