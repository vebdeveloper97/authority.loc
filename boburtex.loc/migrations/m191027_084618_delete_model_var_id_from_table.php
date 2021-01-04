<?php

use yii\db\Migration;

/**
 * Class m191027_084618_delete_model_var_id_from_table
 */
class m191027_084618_delete_model_var_id_from_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //model_var_baski
        $this->dropForeignKey(
            'fk-model_var_baski-model_var_id',
            'model_var_baski'
        );
        $this->dropIndex('idx-model_var_baski-model_var_id', 'model_var_baski');
        $this->dropColumn('model_var_baski','model_var_id');
        //model_var_prints
        $this->dropForeignKey(
            'fk-model_var_prints-model_var_id',
            'model_var_prints'
        );
        $this->dropIndex('idx-model_var_prints-model_var_id', 'model_var_prints');
        $this->dropColumn('model_var_prints','model_var_id');
        //model_var_stone
        $this->dropForeignKey(
            'fk-model_var_stone-model_var_id',
            'model_var_stone'
        );
        $this->dropIndex('idx-model_var_stone-model_var_id', 'model_var_stone');
        $this->dropColumn('model_var_stone','model_var_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //model_var_baski
        $this->addColumn('model_var_baski','model_var_id', $this->integer());
        $this->createIndex('idx-model_var_baski-model_var_id', 'model_var_baski', 'model_var_id');
        $this->addForeignKey(
            'fk-model_var_baski-model_var_id',
            'model_var_baski',
            'model_var_id',
            'models_variations',
            'id',
            'CASCADE',
            'CASCADE'
        );
        //model_var_prints
        $this->addColumn('model_var_prints','model_var_id', $this->integer());
        $this->createIndex('idx-model_var_prints-model_var_id', 'model_var_prints', 'model_var_id');
        $this->addForeignKey(
            'fk-model_var_prints-model_var_id',
            'model_var_prints',
            'model_var_id',
            'models_variations',
            'id',
            'CASCADE',
            'CASCADE'
        );
        //model_var_stone
        $this->addColumn('model_var_stone','model_var_id', $this->integer());
        $this->createIndex('idx-model_var_stone-model_var_id', 'model_var_stone', 'model_var_id');
        $this->addForeignKey(
            'fk-model_var_stone-model_var_id',
            'model_var_stone',
            'model_var_id',
            'models_variations',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }
}
