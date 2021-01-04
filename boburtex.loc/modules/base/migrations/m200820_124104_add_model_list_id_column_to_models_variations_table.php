<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%models_variations}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%models_list}}`
 */
class m200820_124104_add_model_list_id_column_to_models_variations_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /** Add Unique Two Columns */
        $this->createIndex(
            'idx-models_variations-model_list_id_unique',
            'models_variations',
            ['model_list_id', 'wms_color_id'],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        /** Add Unique Two Columns */
        $this->dropIndex(
            'idx-models_variations-model_list_id_unique',
            'models_variations'
        );
    }
}
