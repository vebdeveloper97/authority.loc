<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%tikuv_goods_doc_accepted}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%tgdp}}`
 */
class m200501_140049_add_tgdp_id_column_to_tikuv_goods_doc_accepted_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%tikuv_goods_doc_accepted}}', 'tgdp_id', $this->integer());
        $this->alterColumn('tikuv_goods_doc_accepted','barcode',$this->bigInteger(20));
        // creates index for column `tgdp_id`
        $this->createIndex(
            '{{%idx-tikuv_goods_doc_accepted-tgdp_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'tgdp_id'
        );

        // add foreign key for table `{{%tgdp}}`
        $this->addForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-tgdp_id}}',
            '{{%tikuv_goods_doc_accepted}}',
            'tgdp_id',
            '{{%tikuv_goods_doc_pack}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%tgdp}}`
        $this->dropForeignKey(
            '{{%fk-tikuv_goods_doc_accepted-tgdp_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        // drops index for column `tgdp_id`
        $this->dropIndex(
            '{{%idx-tikuv_goods_doc_accepted-tgdp_id}}',
            '{{%tikuv_goods_doc_accepted}}'
        );

        $this->dropColumn('{{%tikuv_goods_doc_accepted}}', 'tgdp_id');
    }
}
