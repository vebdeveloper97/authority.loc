<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_saldo}}`.
 */
class m190806_092016_create_toquv_saldo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%toquv_saldo}}', [
            'id' => $this->primaryKey(),
            'credit1' => $this->decimal(20,2)->defaultValue(0),
            'credit2' => $this->decimal(20,2)->defaultValue(0),
            'debit1' => $this->decimal(20,2)->defaultValue(0),
            'debit2' => $this->decimal(20,2)->defaultValue(0),
            'musteri_id' => $this->bigInteger(),
            'department_id' => $this->integer(),
            'operation' => $this->string(),
            'comment' => $this->text(),
            'reg_date' => $this->dateTime()->defaultValue(date('Y-m-d H:i:s')),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
         ], $tableOptions);

        //musteri_ida
        $this->createIndex(
            'idx-toquv_saldo-musteri_id',
            'toquv_saldo',
            'musteri_id'
        );

        $this->addForeignKey(
            'fk-toquv_saldo-musteri_id',
            'toquv_saldo',
            'musteri_id',
            'musteri',
            'id'
        );

        //department_id
        $this->createIndex(
            'idx-toquv_saldo-department_id',
            'toquv_saldo',
            'department_id'
        );

        $this->addForeignKey(
            'fk-toquv_saldo-department_id',
            'toquv_saldo',
            'department_id',
            'toquv_departments',
            'id'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //musteri_id
        $this->dropForeignKey(
            'fk-toquv_saldo-musteri_id',
            'toquv_saldo'
        );

        $this->dropIndex(
            'idx-toquv_saldo-musteri_id',
            'toquv_saldo'
        );

        //department_id
        $this->dropForeignKey(
            'fk-toquv_saldo-department_id',
            'toquv_saldo'
        );

        $this->dropIndex(
            'idx-toquv_saldo-department_id',
            'toquv_saldo'
        );

        $this->dropTable('{{%toquv_saldo}}');
    }
}
