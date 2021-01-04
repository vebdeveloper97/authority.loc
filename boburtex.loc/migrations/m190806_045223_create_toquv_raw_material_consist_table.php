<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_raw_material_consist}}`.
 */
class m190806_045223_create_toquv_raw_material_consist_table extends Migration
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
        $this->createTable('{{%toquv_raw_material_consist}}', [
            'id' => $this->primaryKey(),
            'fabric_type_id' => $this->integer(),
            'raw_material_id' => $this->integer(),
            'created_by' => $this->integer(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ], $tableOptions);

        //fabric_type_id
        $this->createIndex(
            'idx-toquv_raw_material_consist-fabric_type_id',
            'toquv_raw_material_consist',
            'fabric_type_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_material_consist-fabric_type_id',
            'toquv_raw_material_consist',
            'fabric_type_id',
            'fabric_types',
            'id'
        );

        //raw_material_id
        $this->createIndex(
            'idx-toquv_raw_material_consist-raw_material_id',
            'toquv_raw_material_consist',
            'raw_material_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_material_consist-raw_material_id',
            'toquv_raw_material_consist',
            'raw_material_id',
            'toquv_raw_materials',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //fabric_type_id
        $this->dropForeignKey(
            'fk-toquv_raw_material_consist-fabric_type_id',
            'toquv_raw_material_consist'
        );

        $this->dropIndex(
            'idx-toquv_raw_material_consist-fabric_type_id',
            'toquv_raw_material_consist'
        );

        //raw_material_id
        $this->dropForeignKey(
            'fk-toquv_raw_material_consist-raw_material_id',
            'toquv_raw_material_consist'
        );

        $this->dropIndex(
            'idx-toquv_raw_material_consist-raw_material_id',
            'toquv_raw_material_consist'
        );

        $this->dropTable('{{%toquv_raw_material_consist}}');
    }
}
