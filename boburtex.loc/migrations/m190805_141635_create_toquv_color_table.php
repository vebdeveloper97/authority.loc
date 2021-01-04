<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_color}}`.
 */
class m190805_141635_create_toquv_color_table extends Migration
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

        $this->createTable('{{%toquv_ip_color}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(11),
            'updated_at' => $this->integer(11),
            'created_by' => $this->integer(11)
        ], $tableOptions);

        /*    toquv_ip_color    */

        $this->createIndex(
            'idx-toquv_ip-color_id',
            'toquv_ip',
            'color_id'
        );

        $this->addForeignKey(
            'fk-toquv_ip-color_id',
            'toquv_ip',
            'color_id',
            'toquv_ip_color',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-toquv_ip-color_id',
            'toquv_ip'
        );

        $this->dropIndex(
            'idx-toquv_ip-color_id',
            'toquv_ip'
        );

        $this->dropTable('{{%toquv_ip_color}}');
    }
}
