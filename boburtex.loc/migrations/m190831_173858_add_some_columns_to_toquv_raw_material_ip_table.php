<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_raw_material_ip}}`.
 */
class m190831_173858_add_some_columns_to_toquv_raw_material_ip_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('toquv_raw_material_ip', 'ne_id', $this->smallInteger());
        $this->addColumn('toquv_raw_material_ip', 'thread_id', $this->smallInteger());

        //ne_id
        $this->createIndex(
            'idx-toquv_raw_material_ip-ne_id',
            'toquv_raw_material_ip',
            'ne_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_material_ip-ne_id',
            'toquv_raw_material_ip',
            'ne_id',
            'toquv_ne',
            'id'
        );
        
        //thread_id
        $this->createIndex(
            'idx-toquv_raw_material_ip-thread_id',
            'toquv_raw_material_ip',
            'thread_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_material_ip-thread_id',
            'toquv_raw_material_ip',
            'thread_id',
            'toquv_thread',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //ne_id
        $this->dropForeignKey(
            'fk-toquv_raw_material_ip-ne_id',
            'toquv_document_items'
        );

        $this->dropIndex(
            'idx-toquv_raw_material_ip-ne_id',
            'toquv_document_items'
        );

        //thread_id
        $this->dropForeignKey(
            'fk-toquv_raw_material_ip-thread_id',
            'toquv_document_items'
        );

        $this->dropIndex(
            'idx-toquv_raw_material_ip-thread_id',
            'toquv_document_items'
        );
        $this->dropColumn('toquv_raw_material_ip', 'ne_id');
        $this->dropColumn('toquv_raw_material_ip', 'thread_id');
    }
}
