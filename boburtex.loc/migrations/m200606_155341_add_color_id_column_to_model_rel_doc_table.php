<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%model_rel_doc}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%color}}`
 */
class m200606_155341_add_color_id_column_to_model_rel_doc_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%model_rel_doc}}', 'color_id', $this->integer());

        // creates index for column `color_id`
        $this->createIndex(
            '{{%idx-model_rel_doc-color_id}}',
            '{{%model_rel_doc}}',
            'color_id'
        );

        // add foreign key for table `{{%color}}`
        $this->addForeignKey(
            '{{%fk-model_rel_doc-color_id}}',
            '{{%model_rel_doc}}',
            'color_id',
            '{{%color_pantone}}',
            'id'
        );
        $sql = "select mrd.id, cp.id as cp_id  from model_rel_doc mrd
                left join models_variations mv on mrd.model_var_id = mv.id
                left join color_pantone cp on mv.color_pantone_id = cp.id WHERE mrd.color_id IS NULL";
        $results = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($results as $result) {
            $update = "UPDATE `model_rel_doc` SET `color_id` = %d WHERE `model_rel_doc`.`id` = %d;";
            $update = sprintf($update,$result['cp_id'],$result['id']);
            Yii::$app->db->createCommand($update)->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%color}}`
        $this->dropForeignKey(
            '{{%fk-model_rel_doc-color_id}}',
            '{{%model_rel_doc}}'
        );

        // drops index for column `color_id`
        $this->dropIndex(
            '{{%idx-model_rel_doc-color_id}}',
            '{{%model_rel_doc}}'
        );

        $this->dropColumn('{{%model_rel_doc}}', 'color_id');
    }
}
