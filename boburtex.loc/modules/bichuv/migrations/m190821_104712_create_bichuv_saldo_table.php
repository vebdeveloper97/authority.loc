<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_saldo}}`.
 */
class m190821_104712_create_bichuv_saldo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_saldo}}', [
            'id' => $this->primaryKey(),
            'credit1' => $this->decimal(20,2)->defaultValue(0),
            'credit2' => $this->decimal(20,2)->defaultValue(0),
            'debit1' => $this->decimal(20,2)->defaultValue(0),
            'debit2' => $this->decimal(20,2)->defaultValue(0),
            'musteri_id' => $this->bigInteger(),
            'department_id' => $this->integer(),
            'pb_id' => $this->integer(),
            'bd_id' => $this->integer(),
            'operation' => $this->string(),
            'comment' => $this->text(),
            'reg_date' => $this->dateTime()->defaultValue(date('Y-m-d H:i:s')),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);

        //musteri_id
        $this->createIndex(
            'idx-bichuv_saldo-musteri_id',
            'bichuv_saldo',
            'musteri_id'
        );

        $this->addForeignKey(
            'fk-bichuv_saldo-musteri_id',
            'bichuv_saldo',
            'musteri_id',
            'musteri',
            'id'
        );

        //department_id
        $this->createIndex(
            'idx-bichuv_saldo-department_id',
            'bichuv_saldo',
            'department_id'
        );

        $this->addForeignKey(
            'fk-bichuv_saldo-department_id',
            'bichuv_saldo',
            'department_id',
            'toquv_departments',
            'id'
        );
        // bd_id
        $this->createIndex(
            'idx-bichuv_saldo-bd_id',
            'bichuv_saldo',
            'bd_id'
        );

        $this->addForeignKey(
            'fk-bichuv_saldo-bd_id',
            'bichuv_saldo',
            'bd_id',
            'bichuv_doc',
            'id'
        );

        //pb_id
        $this->createIndex(
            'idx-bichuv_saldo-pb_id',
            'bichuv_saldo',
            'pb_id'
        );

        $this->addForeignKey(
            'fk-bichuv_saldo-pb_id',
            'bichuv_saldo',
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
            'fk-bichuv_saldo-pb_id',
            'bichuv_saldo'
        );

        $this->dropIndex(
            'idx-bichuv_saldo-pb_id',
            'bichuv_saldo'
        );

        //bd_id
        $this->dropForeignKey(
            'fk-bichuv_saldo-bd_id',
            'bichuv_saldo'
        );

        $this->dropIndex(
            'idx-bichuv_saldo-bd_id',
            'bichuv_saldo'
        );

        //musteri_id
        $this->dropForeignKey(
            'fk-bichuv_saldo-musteri_id',
            'bichuv_saldo'
        );

        $this->dropIndex(
            'idx-bichuv_saldo-musteri_id',
            'bichuv_saldo'
        );

        //department_id
        $this->dropForeignKey(
            'fk-bichuv_saldo-department_id',
            'bichuv_saldo'
        );

        $this->dropIndex(
            'idx-bichuv_saldo-department_id',
            'bichuv_saldo'
        );

        $this->dropTable('{{%bichuv_saldo}}');
    }
}
