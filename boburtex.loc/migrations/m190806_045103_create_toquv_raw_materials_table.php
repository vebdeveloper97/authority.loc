<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_raw_materials}}`.
 */
class m190806_045103_create_toquv_raw_materials_table extends Migration
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
        $this->createTable('{{%toquv_raw_materials}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'name_ru' => $this->string(),
            'raw_material_type_id' => $this->integer(),
            'created_by' => $this->integer(),
            'code' => $this->string(50),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),

        ], $tableOptions);

        //raw_material_type_id
        $this->createIndex(
            'idx-toquv_raw_materials-raw_material_type_id',
            'toquv_raw_materials',
            'raw_material_type_id'
        );

        $this->addForeignKey(
            'fk-toquv_raw_materials-raw_material_type_id',
            'toquv_raw_materials',
            'raw_material_type_id',
            'raw_material_type',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //raw_material_type_id
        $this->dropForeignKey(
            'fk-toquv_raw_materials-raw_material_type_id',
            'toquv_raw_materials'
        );

        $this->dropIndex(
            'idx-toquv_raw_materials-raw_material_type_id',
            'toquv_raw_materials'
        );
        $this->dropTable('{{%toquv_raw_materials}}');
    }
}
