<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_department_musteri_address}}`.
 */
class m200518_101808_create_toquv_department_musteri_address_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_department_musteri_address}}', [
            'id' => $this->primaryKey(),
            'toquv_department_id' => $this->integer(),
            'physical_location' => $this->string(),
            'legal_location' => $this->string(),
            'email' => $this->string(),
            'phone' => $this->string(50),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_by' => $this->bigInteger(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        $this->createIndex(
            '{{%idx-toquv_department_musteri_address-toquv_department_id}}',
            '{{%toquv_department_musteri_address}}',
            'toquv_department_id'
        );

        $this->addForeignKey(
            '{{%fk-toquv_department_musteri_address-toquv_department_id}}',
            '{{%toquv_department_musteri_address}}',
            'toquv_department_id',
            '{{%toquv_departments}}',
            'id'
        );

        $this->createIndex(
            '{{%idx-toquv_department_musteri_address-created_by}}',
            '{{%toquv_department_musteri_address}}',
            'created_by'
        );

        $this->addForeignKey(
            '{{%fk-toquv_department_musteri_address-created_by}}',
            '{{%toquv_department_musteri_address}}',
            'created_by',
            '{{%users}}',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-toquv_department_musteri_address-created_by}}',
            '{{%toquv_department_musteri_address}}'
        );
        $this->dropIndex(
            '{{%idx-toquv_department_musteri_address-created_by}}',
            '{{%toquv_department_musteri_address}}'
        );

        $this->dropForeignKey(
            '{{%fk-toquv_department_musteri_address-toquv_department_id}}',
            '{{%toquv_department_musteri_address}}'
        );
        $this->dropIndex(
            '{{%idx-toquv_department_musteri_address-toquv_department_id}}',
            '{{%toquv_department_musteri_address}}'
        );

        $this->dropTable('{{%toquv_department_musteri_address}}');
    }
}
