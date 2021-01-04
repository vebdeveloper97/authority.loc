<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%toquv_aks_model_item}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%toquv_aks_model}}`
 */
class m200327_051701_create_toquv_aks_model_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('models_list', 'updated_by', $this->integer()->after('created_by'));
        $this->addColumn('toquv_kalite', 'updated_by', $this->integer()->after('created_by'));
        $this->addColumn('toquv_orders', 'updated_by', $this->integer()->after('created_by'));
        $this->addColumn('model_orders', 'updated_by', $this->integer()->after('created_by'));
        $this->createTable('{{%toquv_aks_model_item}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'toquv_aks_model_id' => $this->integer(),
            'indeks' => $this->double(),
            'height' => $this->double(),
            'toquv_ne_id' => $this->integer(),
            'toquv_thread_id' => $this->integer(),
            'toquv_ip_color_id' => $this->integer(),
            'color_pantone_id' => $this->integer(),
            'color_boyoq_id' => $this->integer(),
        ]);

        // creates index for column `toquv_aks_model_id`
        $this->createIndex(
            '{{%idx-toquv_aks_model_item-toquv_aks_model_id}}',
            '{{%toquv_aks_model_item}}',
            'toquv_aks_model_id'
        );

        // add foreign key for table `{{%toquv_aks_model}}`
        $this->addForeignKey(
            '{{%fk-toquv_aks_model_item-toquv_aks_model_id}}',
            '{{%toquv_aks_model_item}}',
            'toquv_aks_model_id',
            '{{%toquv_aks_model}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%toquv_aks_model}}`
        $this->dropForeignKey(
            '{{%fk-toquv_aks_model_item-toquv_aks_model_id}}',
            '{{%toquv_aks_model_item}}'
        );

        // drops index for column `toquv_aks_model_id`
        $this->dropIndex(
            '{{%idx-toquv_aks_model_item-toquv_aks_model_id}}',
            '{{%toquv_aks_model_item}}'
        );

        $this->dropTable('{{%toquv_aks_model_item}}');
        $this->dropColumn('models_list', 'updated_by');
        $this->dropColumn('toquv_kalite', 'updated_by');
        $this->dropColumn('model_orders', 'updated_by');
        $this->dropColumn('toquv_orders', 'updated_by');
    }
}
