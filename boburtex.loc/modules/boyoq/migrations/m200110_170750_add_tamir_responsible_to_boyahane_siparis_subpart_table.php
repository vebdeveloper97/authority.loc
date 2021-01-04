<?php

use yii\db\Migration;

/**
 * Class m200110_170750_add_tamir_responsible_to_boyahane_siparis_subpart_table
 */
class m200110_170750_add_tamir_responsible_to_boyahane_siparis_subpart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%boyahane_siparis_subpart}}', 'tamir_responsible', $this->string(255)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%boyahane_siparis_subpart}}', 'tamir_responsible');
    }
}
