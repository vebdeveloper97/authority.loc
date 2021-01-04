<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_ip}}`.
 * and `{{%toquv_ip_tarkibi}}`.
 */
class m190802_062839_create_toquv_ip_va_toquv_ip_tarkibi_table extends Migration
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

        $this->createTable('{{%toquv_ip}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'ne_id' => $this->smallInteger(6)->notNull(),
            'thread_id' => $this->smallInteger(6)->notNull(),
            'color_id' => $this->integer(11),
            'barcode' => $this->string(100),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ], $tableOptions);

        /*    toquv_ne    */
        $this->createIndex(
            'idx-toquv_ip-ne_id',
            'toquv_ip',
            'ne_id'
        );

        $this->addForeignKey(
            'fk-toquv_ip-ne_id',
            'toquv_ip',
            'ne_id',
            'toquv_ne',
            'id'
        );

        /*    toquv_thread    */
        $this->createIndex(
            'idx-toquv_ip-thread_id',
            'toquv_ip',
            'thread_id'
        );

        $this->addForeignKey(
            'fk-toquv_ip-thread_id',
            'toquv_ip',
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
        $this->dropForeignKey(
            'fk-toquv_ip-thread_id',
            'toquv_ip'
        );

        $this->dropIndex(
            'idx-toquv_ip-thread_id',
            'toquv_ip'
        );
        /*  toquv_ne  */

        $this->dropForeignKey(
            'fk-toquv_ip-ne_id',
            'toquv_ip'
        );

        $this->dropIndex(
            'idx-toquv_ip-ne_id',
            'toquv_ip'
        );

        $this->dropTable('{{%toquv_ip}}');
    }
}
