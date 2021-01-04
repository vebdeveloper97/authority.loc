<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%toquv_makine}}`.
 */
class m200320_102345_add_some_column_to_toquv_makine_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('toquv_makine');
        if(!isset($table->columns['norma_kg'])) {
            $this->execute("ALTER TABLE toquv_makine ADD norma_kg INT(5) NOT NULL AFTER raw_material_type_id;");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $table = Yii::$app->db->schema->getTableSchema('toquv_makine');
        if(isset($table->columns['norma_kg'])) {
            $this->execute("ALTER TABLE `toquv_makine` DROP `norma_kg`;");
        }
    }
}
