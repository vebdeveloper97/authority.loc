<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_aks_model}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_raw_materials}}`
 */
class m200327_045410_create_toquv_aks_model_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%toquv_aks_model}}', [
            'id' => $this->primaryKey(),
            'trm_id' => $this->integer(),
            'name' => $this->string(),
            'code' => $this->string(40),
            'image' => $this->string(),
            'width' => $this->double(),
            'height' => $this->double(),
            'qavat' => $this->smallInteger(),
            'palasa' => $this->smallInteger(),
            'price' => $this->double(),
            'pb_id' => $this->integer(),
            'musteri_id' => $this->integer(),
            'color_pantone_id' => $this->integer(),
            'color_boyoq_id' => $this->integer(),
            'raw_material_type' => $this->integer(),
            'color_type' => $this->integer(),
            'status' => $this->smallInteger(2)->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ]);

        // creates index for column `trm_id`
        $this->createIndex(
            '{{%idx-toquv_aks_model-trm_id}}',
            '{{%toquv_aks_model}}',
            'trm_id'
        );

        // add foreign key for table `{{%toquv_raw_materials}}`
        $this->addForeignKey(
            '{{%fk-toquv_aks_model-trm_id}}',
            '{{%toquv_aks_model}}',
            'trm_id',
            '{{%toquv_raw_materials}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_raw_materials}}`
        $this->dropForeignKey(
            '{{%fk-toquv_aks_model-trm_id}}',
            '{{%toquv_aks_model}}'
        );

        // drops index for column `trm_id`
        $this->dropIndex(
            '{{%idx-toquv_aks_model-trm_id}}',
            '{{%toquv_aks_model}}'
        );

        $this->dropTable('{{%toquv_aks_model}}');
    }
}
