<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_sub_doc_items}}`.
 */
class m191208_152245_create_bichuv_sub_doc_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_sub_doc_items}}', [
            'id' => $this->primaryKey(),
            'doc_item_id' => $this->integer(),
            'musteri_id' => $this->bigInteger(),
            'bss_id' => $this->bigInteger(20),
            'paket_id' => $this->bigInteger(26),
            'musteri_party_no' => $this->string(20),
            'party_no' => $this->string(20),
            'roll_weight' => $this->decimal(10,3)->defaultValue(0),
            'roll_order' => $this->string(15),
            'en' => $this->decimal(10,2),
            'gramaj' => $this->decimal(10, 3),
            'ne' => $this->string(10),
            'thread' => $this->string(50),
            'pus_fine' => $this->string(15),
            'ctone' => $this->string(50),
            'color_id' => $this->string(100),
            'pantone' => $this->string(25),
            'mato' => $this->string(100),
            'model' => $this->string(150),
            'paketlama' => $this->string(150),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `doc_item_id`
        $this->createIndex(
            '{{%idx-bichuv_sub_doc_items-doc_item_id}}',
            '{{%bichuv_sub_doc_items}}',
            'doc_item_id'
        );

        // add foreign key for table `{{%doc_item_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_sub_doc_items-doc_item_id}}',
            '{{%bichuv_sub_doc_items}}',
            'doc_item_id',
            '{{%bichuv_doc_items}}',
            'id'
        );

        // creates index for column `doc_item_id`
        $this->createIndex(
            '{{%idx-bichuv_sub_doc_items-musteri_id}}',
            '{{%bichuv_sub_doc_items}}',
            'musteri_id'
        );

        // add foreign key for table `{{%musteri_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_sub_doc_items-musteri_id}}',
            '{{%bichuv_sub_doc_items}}',
            'musteri_id',
            '{{%musteri}}',
            'id'
        );

        // creates index for column `bss_id`
        $this->createIndex(
            '{{%idx-bichuv_sub_doc_items-bss_id}}',
            '{{%bichuv_sub_doc_items}}',
            'bss_id'
        );

        // add foreign key for table `{{%bss_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_sub_doc_items-bss_id}}',
            '{{%bichuv_sub_doc_items}}',
            'bss_id',
            '{{%boyahane_siparis_subpart}}',
            'id'
        );

        // creates index for column `paket_id`
        $this->createIndex(
            '{{%idx-bichuv_sub_doc_items-paket_id}}',
            '{{%bichuv_sub_doc_items}}',
            'paket_id'
        );

        // add foreign key for table `{{%paket_id}}`
        $this->addForeignKey(
            '{{%fk-bichuv_sub_doc_items-paket_id}}',
            '{{%bichuv_sub_doc_items}}',
            'paket_id',
            '{{%paketlar}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%doc_item_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_sub_doc_items-doc_item_id}}',
            '{{%bichuv_sub_doc_items}}'
        );

        // drops index for column `doc_item_id`
        $this->dropIndex(
            '{{%idx-bichuv_sub_doc_items-doc_item_id}}',
            '{{%bichuv_sub_doc_items}}'
        );

        // drops foreign key for table `{{%musteri_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_sub_doc_items-musteri_id}}',
            '{{%bichuv_sub_doc_items}}'
        );

        // drops index for column `musteri_id`
        $this->dropIndex(
            '{{%idx-bichuv_sub_doc_items-musteri_id}}',
            '{{%bichuv_sub_doc_items}}'
        );

        // drops foreign key for table `{{%bss_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_sub_doc_items-bss_id}}',
            '{{%bichuv_sub_doc_items}}'
        );

        // drops index for column `bss_id`
        $this->dropIndex(
            '{{%idx-bichuv_sub_doc_items-bss_id}}',
            '{{%bichuv_sub_doc_items}}'
        );

        // drops foreign key for table `{{%paket_id}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_sub_doc_items-paket_id}}',
            '{{%bichuv_sub_doc_items}}'
        );

        // drops index for column `paket_id`
        $this->dropIndex(
            '{{%idx-bichuv_sub_doc_items-paket_id}}',
            '{{%bichuv_sub_doc_items}}'
        );

        $this->dropTable('{{%bichuv_sub_doc_items}}');
    }
}
