<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_nastel_aks_balance}}`.
 */
class m200602_051244_create_bichuv_nastel_aks_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_nastel_aks_balance}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'lot' => $this->string(30),
            'count' => $this->decimal(20, 3),
            'inventory' => $this->decimal(20, 3),
            'quantity' => $this->decimal(20, 3),
            'quantity_inventory' => $this->decimal(20, 3),
            'nastel_no' => $this->string(30),
            'department_id' => $this->integer(),
            'from_department' => $this->integer(),
            'to_department' => $this->integer(),
            'musteri_id' => $this->bigInteger(),
            'from_musteri' => $this->bigInteger(),
            'to_musteri' => $this->bigInteger(),
            'doc_id' => $this->integer(),
            'size_id' => $this->integer(),
            'document_type' => $this->smallInteger(),
            'comment' => $this->text(),
            'status' => $this->smallInteger(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->addForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-department_id}}',
            '{{%bichuv_nastel_aks_balance}}',
            'department_id',
            '{{%toquv_departments}}',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-from_department}}',
            '{{%bichuv_nastel_aks_balance}}',
            'from_department',
            '{{%toquv_departments}}',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-to_department}}',
            '{{%bichuv_nastel_aks_balance}}',
            'to_department',
            '{{%toquv_departments}}',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-musteri_id}}',
            '{{%bichuv_nastel_aks_balance}}',
            'musteri_id',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-from_musteri}}',
            '{{%bichuv_nastel_aks_balance}}',
            'from_musteri',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );

        $this->addForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-to_musteri}}',
            '{{%bichuv_nastel_aks_balance}}',
            'to_musteri',
            '{{%musteri}}',
            'id',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-department_id}}',
            '{{%bichuv_nastel_aks_balance}}'
        );
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-from_department}}',
            '{{%bichuv_nastel_aks_balance}}'
        );
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-to_department}}',
            '{{%bichuv_nastel_aks_balance}}'
        );
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-musteri_id}}',
            '{{%bichuv_nastel_aks_balance}}'
        );
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-from_musteri}}',
            '{{%bichuv_nastel_aks_balance}}'
        );
        $this->dropForeignKey(
            '{{%fk-bichuv_nastel_aks_balance-to_musteri}}',
            '{{%bichuv_nastel_aks_balance}}'
        );
        $this->dropTable('{{%bichuv_nastel_aks_balance}}');
    }
}
