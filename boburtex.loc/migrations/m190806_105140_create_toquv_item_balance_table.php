<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_item_balance}}`.
 */
class m190806_105140_create_toquv_item_balance_table extends Migration
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
        $this->createTable('{{%toquv_item_balance}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            // 1 - Ip, 2 - Mato, 3-kraska, 4 - etc
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'count' => $this->decimal(20,2)->defaultValue(0),
            'inventory' => $this->float()->defaultValue(0),
            'reg_date' => $this->dateTime()->defaultValue(date('Y-m-d H:i:s')),
            'department_id' => $this->integer(),
            //1 -Bizniki, 2- uniki
            'is_own' => $this->smallInteger()->defaultValue(1),
            'price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'price_usd' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_usd' => $this->decimal(20,2)->defaultValue(0),
            'sum_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sum_usd' => $this->decimal(20,2)->defaultValue(0),
            'document_id' => $this->integer(),
            'document_type' => $this->string(),
            'version' => $this->smallInteger()->defaultValue(0),
            'comment' => $this->text(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //department_id
        $this->createIndex(
            'idx-toquv_item_balance-department_id',
            'toquv_item_balance',
            'department_id'
        );

        $this->addForeignKey(
            'fk-toquv_item_balance-department_id',
            'toquv_item_balance',
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
        //department_id
        $this->dropForeignKey(
            'fk-toquv_item_balance-department_id',
            'toquv_item_balance'
        );

        $this->dropIndex(
            'idx-toquv_item_balance-department_id',
            'toquv_item_balance'
        );

        $this->dropTable('{{%toquv_item_balance}}');
    }
}
