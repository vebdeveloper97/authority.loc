<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_ip_tarkibi}}`.
 */
class m190805_141919_create_toquv_ip_tarkibi_table extends Migration
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

        $this->createTable('{{%toquv_ip_tarkibi}}', [
            'id' => $this->primaryKey(),
            'fabric_type_id' => $this->integer(11),
            'quantity' => $this->integer(11),
            'ip_id' => $this->integer(11),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ], $tableOptions);

        $this->createIndex(
            'idx-toquv_ip_tarkibi-ip_id',
            'toquv_ip_tarkibi',
            'ip_id'
        );


        $this->addForeignKey(
            'fk-toquv_ip_tarkibi-ip_id',
            'toquv_ip_tarkibi',
            'ip_id',
            'toquv_ip',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-toquv_ip_tarkibi-ip_id',
            'toquv_ip_tarkibi'
        );


        $this->dropIndex(
            'idx-toquv_ip_tarkibi-ip_id',
            'toquv_ip_tarkibi'
        );

        $this->dropTable('{{%toquv_ip_tarkibi}}');
    }
}
