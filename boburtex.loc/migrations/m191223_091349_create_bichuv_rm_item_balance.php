<?php

use yii\db\Migration;

/**
 * Class m191223_091349_create_bichuv_rm_item_balance
 */
class m191223_091349_create_bichuv_rm_item_balance extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_rm_item_balance}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'doc_type' => $this->smallInteger(2)->defaultValue(1),
            'inventory' => $this->decimal(20,3),
            'count' => $this->decimal(20,3),
            'roll_inventory' => $this->decimal(20, 1),
            'roll_count' => $this->decimal(20,1),
            'from_department' => $this->integer(),
            'to_department' => $this->integer(),
            'is_inside' => $this->smallInteger(1)->defaultValue(1),
            'from_musteri' => $this->bigInteger(),
            'to_musteri' => $this->bigInteger(),
            'type' => $this->smallInteger(1)->defaultValue(1),
            'party_no' => $this->string(50),
            'musteri_party_no' => $this->string(50),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        // creates index for column `from_department`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-from_department}}',
            '{{%bichuv_rm_item_balance}}',
            'from_department'
        );

        // add foreign key for table `{{%from_department}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-from_department}}',
            '{{%bichuv_rm_item_balance}}',
            'from_department',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `to_department`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-to_department}}',
            '{{%bichuv_rm_item_balance}}',
            'to_department'
        );

        // add foreign key for table `{{%to_department}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-to_department}}',
            '{{%bichuv_rm_item_balance}}',
            'to_department',
            '{{%toquv_departments}}',
            'id'
        );

        // creates index for column `to_musteri`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-to_musteri}}',
            '{{%bichuv_rm_item_balance}}',
            'to_musteri'
        );

        // add foreign key for table `{{%to_musteri}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-to_musteri}}',
            '{{%bichuv_rm_item_balance}}',
            'to_musteri',
            '{{%musteri}}',
            'id'
        );

        // creates index for column `from_musteri`
        $this->createIndex(
            '{{%idx-bichuv_rm_item_balance-from_musteri}}',
            '{{%bichuv_rm_item_balance}}',
            'from_musteri'
        );

        // add foreign key for table `{{%from_musteri}}`
        $this->addForeignKey(
            '{{%fk-bichuv_rm_item_balance-from_musteri}}',
            '{{%bichuv_rm_item_balance}}',
            'from_musteri',
            '{{%musteri}}',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%from_department}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-from_department}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `from_department`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-from_department}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops foreign key for table `{{%to_department}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-to_department}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `to_department`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-to_department}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops foreign key for table `{{%from_musteri}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-from_musteri}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `from_musteri`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-from_musteri}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops foreign key for table `{{%to_musteri}}`
        $this->dropForeignKey(
            '{{%fk-bichuv_rm_item_balance-to_musteri}}',
            '{{%bichuv_rm_item_balance}}'
        );

        // drops index for column `to_musteri`
        $this->dropIndex(
            '{{%idx-bichuv_rm_item_balance-to_musteri}}',
            '{{%bichuv_rm_item_balance}}'
        );

        $this->dropTable('{{%bichuv_rm_item_balance}}');
    }
}
