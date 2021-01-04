<?php

use yii\db\Migration;

/**
 * Class m200204_130054_insert_aks_makine_to_toquv_makine_table
 */
class m200204_130054_insert_aks_makine_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->upsert('{{%toquv_makine}}', ['m_code' => '12F1', 'name' => "Mayer yaka 1", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '12F2', 'name' => "Mayer yaka 2", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '12F3', 'name' => "Mayer yaka 3", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '12F4', 'name' => "Mayer yaka 4", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '12F5', 'name' => "Mayer yaka 5", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '12F6', 'name' => "Mayer yaka 6", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '14F1', 'name' => "Mayer yaka 7", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '14F2', 'name' => "Mayer yaka 8", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '14F3', 'name' => "Mayer yaka 9", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '14F4', 'name' => "Mayer yaka 10", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '14F5', 'name' => "Mayer yaka 11", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '14F6', 'name' => "Mayer yaka 12", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '16F1', 'name' => "Mayer yaka 13", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 44]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => '16F2', 'name' => "Mayer yaka 14", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 44]);

        $this->upsert('{{%toquv_makine}}', ['m_code' => 'shnur1', 'name' => "Mayer shnur 1", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'shnur2', 'name' => "Mayer shnur 2", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'shnur3', 'name' => "Mayer shnur 3", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'shnur4', 'name' => "Mayer shnur 4", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'shnur5', 'name' => "Mayer shnur 5", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);

        $this->upsert('{{%toquv_makine}}', ['m_code' => 'ekstrafor1', 'name' => "Mayer ekstrafor 1", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'ekstrafor2', 'name' => "Mayer ekstrafor 2", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'ekstrafor3', 'name' => "Mayer ekstrafor 3", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'ekstrafor4', 'name' => "Mayer ekstrafor 4", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'ekstrafor5', 'name' => "Mayer ekstrafor 5", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);

        $this->upsert('{{%toquv_makine}}', ['m_code' => 'dahu1', 'name' => "Mayer lampas(dahu)", 'type' => "2", 'raw_material_type_id' => 11, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 47]);

        $this->upsert('{{%toquv_makine}}', ['m_code' => 'rezinka1', 'name' => "Mayer rezinka 1", 'type' => "2", 'raw_material_type_id' => 10, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 45]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'rezinka2', 'name' => "Mayer rezinka 2", 'type' => "2", 'raw_material_type_id' => 10, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 45]);
        $this->upsert('{{%toquv_makine}}', ['m_code' => 'rezinka3', 'name' => "Mayer rezinka 3", 'type' => "2", 'raw_material_type_id' => 10, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 45]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('{{%toquv_makine}}', ['m_code' => '12F1', 'name' => "Mayer yaka 1", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '12F2', 'name' => "Mayer yaka 2", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '12F3', 'name' => "Mayer yaka 3", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '12F4', 'name' => "Mayer yaka 4", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '12F5', 'name' => "Mayer yaka 5", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '12F6', 'name' => "Mayer yaka 6", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 42]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '14F1', 'name' => "Mayer yaka 7", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '14F2', 'name' => "Mayer yaka 8", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '14F3', 'name' => "Mayer yaka 9", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '14F4', 'name' => "Mayer yaka 10", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '14F5', 'name' => "Mayer yaka 11", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '14F6', 'name' => "Mayer yaka 12", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 43]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '16F1', 'name' => "Mayer yaka 13", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 44]);
        $this->delete('{{%toquv_makine}}', ['m_code' => '16F2', 'name' => "Mayer yaka 14", 'type' => "2", 'raw_material_type_id' => 14, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 44]);

        $this->delete('{{%toquv_makine}}', ['m_code' => 'shnur1', 'name' => "Mayer shnur 1", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'shnur2', 'name' => "Mayer shnur 2", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'shnur3', 'name' => "Mayer shnur 3", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'shnur4', 'name' => "Mayer shnur 4", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'shnur5', 'name' => "Mayer shnur 5", 'type' => "2", 'raw_material_type_id' => 12, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 41]);

        $this->delete('{{%toquv_makine}}', ['m_code' => 'ekstrafor1', 'name' => "Mayer ekstrafor 1", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'ekstrafor2', 'name' => "Mayer ekstrafor 2", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'ekstrafor3', 'name' => "Mayer ekstrafor 3", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'ekstrafor4', 'name' => "Mayer ekstrafor 4", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'ekstrafor5', 'name' => "Mayer ekstrafor 5", 'type' => "2", 'raw_material_type_id' => 13, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 46]);

        $this->delete('{{%toquv_makine}}', ['m_code' => 'dahu1', 'name' => "Mayer lampas(dahu)", 'type' => "2", 'raw_material_type_id' => 11, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 47]);

        $this->delete('{{%toquv_makine}}', ['m_code' => 'rezinka1', 'name' => "Mayer rezinka 1", 'type' => "2", 'raw_material_type_id' => 10, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 45]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'rezinka2', 'name' => "Mayer rezinka 2", 'type' => "2", 'raw_material_type_id' => 10, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 45]);
        $this->delete('{{%toquv_makine}}', ['m_code' => 'rezinka3', 'name' => "Mayer rezinka 3", 'type' => "2", 'raw_material_type_id' => 10, 'created_by' => 1, 'created_at' => strtotime(date('Y-m-d H:i:s')), 'pus_fine_id' => 45]);
    }
}
