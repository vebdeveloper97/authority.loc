<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_raw_material_ip}}`.
 */
class m190806_045148_create_toquv_raw_material_ip_table extends Migration
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

        $this->createTable('{{%toquv_raw_material_ip}}', [
            'id' => $this->primaryKey(),
            'toquv_raw_material_id' => $this->integer(),
            'toquv_ip_id' => $this->integer(),
            'created_by' => $this->integer(),
            'status' => $this->integer()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //toquv_raw_material_id
        $this->createIndex(
            'idx-toquv_raw_material_ip-toquv_raw_material_id',
            'toquv_raw_material_ip',
            'toquv_raw_material_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_material_ip-toquv_raw_material_id',
            'toquv_raw_material_ip',
            'toquv_raw_material_id',
            'toquv_raw_materials',
            'id'
        );

        //toquv_ip_id
        $this->createIndex(
            'idx-toquv_raw_material_ip-toquv_ip_id',
            'toquv_raw_material_ip',
            'toquv_ip_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_material_ip-toquv_ip_id',
            'toquv_raw_material_ip',
            'toquv_ip_id',
            'toquv_ip',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //toquv_raw_material_id
        $this->dropForeignKey(
            'fk-toquv_raw_material_ip-toquv_raw_material_id',
            'toquv_raw_material_ip'
        );

        $this->dropIndex(
            'idx-toquv_raw_material_ip-toquv_raw_material_id',
            'toquv_raw_material_ip'
        );

        //toquv_ip_id
        $this->dropForeignKey(
            'fk-toquv_raw_material_ip-toquv_ip_id',
            'toquv_raw_material_ip'
        );

        $this->dropIndex(
            'idx-toquv_raw_material_ip-toquv_ip_id',
            'toquv_raw_material_ip'
        );
        $this->dropTable('{{%toquv_raw_material_ip}}');
    }
}
