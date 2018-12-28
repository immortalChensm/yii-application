<?php

class m181226_071631_create_news_table extends CDbMigration
{
	public function up()
	{
	    //之所以能得到这个数据库连接资源是因为
        //在执行命令时，基类已经得到
//	    $transaction = $this->dbConnection()->beginTransaction();
//	    try{
            $this->createTable('tbl_news', array(
                'id' => 'pk',
                'title' => 'string NOT NULL',
                'content' => 'text',
            ));
//            $transaction->commit();
//        }catch (Exception $exception){
//            echo "Exception: ".$e->getMessage()."\n";
//            $transaction->rollback();
//            return false;
//        }

	}

	public function down()
	{
		//echo "m181226_071631_create_news_table does not support migration down.\n";
		//return false;
        $this->dropTable('tbl_news');
	}


//	// Use safeUp/safeDown to do migration with transaction
//	public function safeUp()
//	{
//	    $this->createTable('tbl_news', array(
//                'id' => 'pk',
//                'title' => 'string NOT NULL',
//                'content' => 'text',
//            ));
//	}
//
//	public function safeDown()
//	{
//        $this->dropTable('tbl_news');
//	}

}