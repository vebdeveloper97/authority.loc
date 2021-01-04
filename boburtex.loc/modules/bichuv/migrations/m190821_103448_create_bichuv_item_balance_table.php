<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bichuv_item_balance}}`.
 */
class m190821_103448_create_bichuv_item_balance_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bichuv_item_balance}}', [
            'id' => $this->primaryKey(),
            'entity_id' => $this->integer(),
            // 1 - Ip, 2 - Mato, 3-kraska, 4 - etc
            'entity_type' => $this->smallInteger()->defaultValue(1),
            'lot' => $this->string(255),
            'count' => $this->decimal(20,2)->defaultValue(0),
            'inventory' => $this->float()->defaultValue(0),
            'reg_date' => $this->dateTime()->defaultValue(date('Y-m-d H:i:s')),
            'department_id' => $this->integer(),
            //1 -Bizniki, 2- uniki
            'is_own' => $this->smallInteger()->defaultValue(1),
            'price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'price_usd' => $this->decimal(20,2)->defaultValue(0),
            'price_rub' => $this->decimal(20,2)->defaultValue(0),
            'price_eur' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_usd' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_rub' => $this->decimal(20,2)->defaultValue(0),
            'sold_price_eur' => $this->decimal(20,2)->defaultValue(0),
            'sum_uzs' => $this->decimal(20,2)->defaultValue(0),
            'sum_usd' => $this->decimal(20,2)->defaultValue(0),
            'sum_rub' => $this->decimal(20,2)->defaultValue(0),
            'sum_eur' => $this->decimal(20,2)->defaultValue(0),
            'document_id' => $this->integer(),
            'document_type' => $this->string(),
            'version' => $this->smallInteger()->defaultValue(0),
            'comment' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-bichuv_item_balance-department_id',
            'bichuv_item_balance',
            'department_id'
        );

        $this->addForeignKey(
            'fk-bichuv_item_balance-department_id',
            'bichuv_item_balance',
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
        $this->dropForeignKey(
            'fk-bichuv_item_balance-department_id',
            'bichuv_item_balance'
        );

        $this->dropIndex(
            'idx-bichuv_item_balance-department_id',
            'bichuv_item_balance'
        );

        $this->dropTable('{{%bichuv_item_balance}}');
    }
}
