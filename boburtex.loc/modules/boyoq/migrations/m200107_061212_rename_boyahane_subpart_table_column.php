<?php

use yii\db\Migration;

/**
 * Class m200107_061212_rename_boyahane_subpart_table_column
 */
class m200107_061212_rename_boyahane_subpart_table_column extends Migration
{
    public function safeUp()
    {
        $this->renameColumn('boyahane_siparis_subpart', 'tamirdan_qaytgan_holati', 'is_tamir');
    }

    public function safeDown()
    {
        $this->renameColumn('boyahane_siparis_subpart', 'is_tamir', 'tamirdan_qaytgan_holati');
    }

}
