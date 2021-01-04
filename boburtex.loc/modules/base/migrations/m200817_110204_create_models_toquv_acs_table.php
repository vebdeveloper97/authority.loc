<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%models_toquv_acs}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 * - `{{%models_variations}}`
 */
class m200817_110204_create_models_toquv_acs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%models_toquv_acs}}', [
            'id' => $this->primaryKey(),
            'models_list_id' => $this->integer(),
            'models_var_id' => $this->integer(),
            'toquv_acs_id' => $this->integer(),
            'qty' => $this->decimal(20,3),
            'add_info' => $this->text(),
            'status' => $this->integer(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);

        // creates index for column `models_list_id`
        $this->createIndex(
            '{{%idx-models_toquv_acs-models_list_id}}',
            '{{%models_toquv_acs}}',
            'models_list_id'
        );

        // add foreign key for table `{{%models_list}}`
        $this->addForeignKey(
            '{{%fk-models_toquv_acs-models_list_id}}',
            '{{%models_toquv_acs}}',
            'models_list_id',
            '{{%models_list}}',
            'id',
            'CASCADE'
        );

        // creates index for column `models_var_id`
        $this->createIndex(
            '{{%idx-models_toquv_acs-models_var_id}}',
            '{{%models_toquv_acs}}',
            'models_var_id'
        );

        // add foreign key for table `{{%models_variations}}`
        $this->addForeignKey(
            '{{%fk-models_toquv_acs-models_var_id}}',
            '{{%models_toquv_acs}}',
            'models_var_id',
            '{{%models_variations}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%models_list}}`
        $this->dropForeignKey(
            '{{%fk-models_toquv_acs-models_list_id}}',
            '{{%models_toquv_acs}}'
        );

        // drops index for column `models_list_id`
        $this->dropIndex(
            '{{%idx-models_toquv_acs-models_list_id}}',
            '{{%models_toquv_acs}}'
        );

        // drops foreign key for table `{{%models_variations}}`
        $this->dropForeignKey(
            '{{%fk-models_toquv_acs-models_var_id}}',
            '{{%models_toquv_acs}}'
        );

        // drops index for column `models_var_id`
        $this->dropIndex(
            '{{%idx-models_toquv_acs-models_var_id}}',
            '{{%models_toquv_acs}}'
        );

        $this->dropTable('{{%models_toquv_acs}}');
    }
}
