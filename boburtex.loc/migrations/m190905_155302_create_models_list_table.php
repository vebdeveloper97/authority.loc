<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_list}}`.
 */
class m190905_155302_create_models_list_table extends Migration
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
        $this->createTable('{{%models_list}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'article' => $this->string(50)->unique(),
            'view_id' => $this->integer(),
            'type_id' => $this->integer(),
            'type_child_id' => $this->integer(),
            'type_2x_id' => $this->integer(),
            'add_info' => $this->text(),
            'washing_notes' => $this->text(),
            'finishing_notes' => $this->text(),
            'packaging_notes' => $this->text(),
            'status' => $this->smallInteger()->defaultValue(1),
            'created_by' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $tableOptions);

        //view_id
        $this->createIndex(
            'idx-models_list-view_id',
            'models_list',
            'view_id'
        );

        $this->addForeignKey(
            'fk-models_list-view_id',
            'models_list',
            'view_id',
            'model_view',
            'id'
        );

        //type_id
        $this->createIndex(
            'idx-models_list-type_id',
            'models_list',
            'type_id'
        );

        $this->addForeignKey(
            'fk-models_list-type_id',
            'models_list',
            'type_id',
            'model_types',
            'id'
        );

        //type_child_id
        $this->createIndex(
            'idx-models_list-type_child_id',
            'models_list',
            'type_child_id'
        );

        $this->addForeignKey(
            'fk-models_list-type_child_id',
            'models_list',
            'type_child_id',
            'model_types',
            'id'
        );

        //type_2x_id
        $this->createIndex(
            'idx-models_list-type_2x_id',
            'models_list',
            'type_2x_id'
        );

        $this->addForeignKey(
            'fk-models_list-type_2x_id',
            'models_list',
            'type_2x_id',
            'model_types',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        //view_id
        $this->dropForeignKey(
            'fk-models_list-view_id',
            'models_list'
        );

        $this->dropIndex(
            'idx-models_list-view_id',
            'models_list'
        );

        //type_id
        $this->dropForeignKey(
            'fk-models_list-type_id',
            'models_list'
        );

        $this->dropIndex(
            'idx-models_list-type_id',
            'models_list'
        );

        //type_child_id
        $this->dropForeignKey(
            'fk-models_list-type_child_id',
            'models_list'
        );

        $this->dropIndex(
            'idx-models_list-type_child_id',
            'models_list'
        );

        //type_2x_id
        $this->dropForeignKey(
            'fk-models_list-type_2x_id',
            'models_list'
        );

        $this->dropIndex(
            'idx-models_list-type_2x_id',
            'models_list'
        );

        $this->dropTable('{{%models_list}}');
    }
}
