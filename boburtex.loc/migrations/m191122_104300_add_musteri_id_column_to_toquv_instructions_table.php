<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_instructions}}`.
 */
class m191122_104300_add_musteri_id_column_to_toquv_instructions_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_instructions','musteri_id', $this->bigInteger());
        $this->createIndex(
            '{{%idx-toquv_instructions-musteri_id}}',
            '{{%toquv_instructions}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri_id}}`
        $this->addForeignKey(
            '{{%fk-toquv_instructions-musteri_id}}',
            '{{%toquv_instructions}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%musteri_id}}`
        $this->dropForeignKey(
            '{{%fk-toquv_instructions-musteri_id}}',
            '{{%toquv_instructions}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-toquv_instructions-musteri_id}}',
            '{{%toquv_instructions}}'
        );
        $this->dropColumn('toquv_instructions', 'musteri_id');
    }
}
