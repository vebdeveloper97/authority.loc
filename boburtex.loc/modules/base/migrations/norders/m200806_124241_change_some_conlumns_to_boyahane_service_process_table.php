<?php

use yii\db\Migration;

/**
 * Class m200806_124241_change_some_conlumns_to_boyahane_service_process_table
 */
class m200806_124241_change_some_conlumns_to_boyahane_service_process_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	
		$this->dropForeignKey('fk-boyahane_services-created_by','boyahane_services');
		$this->dropIndex('idx-boyahane_services-created_by','boyahane_services');
	
		$this->dropForeignKey('fk-boyahane_services-updated_by','boyahane_services');
		$this->dropIndex('idx-boyahane_services-updated_by','boyahane_services');
	
		$this->dropForeignKey('fk-boyahane_service_process-created_by','boyahane_service_process');
		$this->dropIndex('idx-boyahane_service_process-created_by','boyahane_service_process');
	
		$this->dropForeignKey('fk-boyahane_service_process-updated_by','boyahane_service_process');
		$this->dropIndex('idx-boyahane_service_process-updated_by','boyahane_service_process');

		$this->dropColumn('{{%boyahane_service_process}}', 'name');
		$this->dropColumn('{{%boyahane_service_process}}', 'description');
		$this->alterColumn('{{%boyahane_services}}', 'created_by', $this->integer());
		$this->alterColumn('{{%boyahane_services}}', 'updated_by', $this->integer());
		$this->alterColumn('{{%boyahane_service_process}}', 'created_by', $this->integer());
		$this->alterColumn('{{%boyahane_service_process}}', 'updated_by', $this->integer());
	
		$this->createIndex('idx-boyahane_service_process-created_by','boyahane_service_process','created_by');
		$this->createIndex('idx-boyahane_service_process-updated_by','boyahane_service_process','updated_by');
		$this->createIndex('idx-boyahane_services-created_by','boyahane_services','created_by');
		$this->createIndex('idx-boyahane_services-updated_by','boyahane_services','updated_by');
	
	}

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
    	
        return true;
    }
    
}
