<?php

class TestController extends Controller
{

    public $defaultAction = 'index';
    public function actionIndex()
    {
        echo 'show';
    }

	public function actionShow()
    {
       echo CJSON::encode(['a'=>'b']);
    }

    function actions()
    {
        //return parent::actions(); // TODO: Change the autogenerated stub
        return [
            'add'=>'application.controllers.test.AddAction'
        ];
    }

    function actionCreate()
    {
        echo CJSON::encode($_GET);
    }

    function actionUpdate()
    {
        echo "update000";
    }

    public function filterUpdateFilter($filterChain)
    {
        // 调用 $filterChain->run() 以继续后续过滤器与动作的执行。

        if (0){
            $filterChain->run();
        }else{
            throw new InvalidArgumentException("error");
        }

    }

    function filters()
    {
        return [
            'UpdateFilter+update'
        ];
    }

    function Actiondb()
    {

        //连接
        $connection = Yii::app()->db;
        //var_dump($connection);

        //构建命令
        $command    = $connection->createCommand("show databases");

        //var_dump($command);

        //执行命令
        $data       = $command->query();

        //var_dump($data);

        //print_r($data->readAll());
//
//        foreach($data as $row){
//            print_r($row);
//        }

//        while ($row = $data->read()!==false){
//            print_r($row);
//        }

//        $createTableSql = <<<TABLE
//create table test(
//id int unsigned primary key auto_increment,
//name varchar(10)
//)engine innodb charset utf8;
//TABLE;
//
//        $createTableCommand = $connection->createCommand($createTableSql);
//        $ret = $createTableCommand->execute();
//        print_r($ret);

//        $insertTestSql = <<<SQL
//insert into test(id,name) values(5,'jack5');
//SQL;
//
//        $insertTestCommand = $connection->createCommand($insertTestSql);
//
//        $ret = $insertTestCommand->execute();
//        var_dump($ret);

        $selectTestSql = "select id,name from test";
        $selectTestCommand = $connection->createCommand($selectTestSql);
//        $data = $selectTestCommand->queryAll();
//        $data = $selectTestCommand->queryRow();
//        $data = $selectTestCommand->queryColumn();
        $data = $selectTestCommand->queryScalar();//queryFiled queryValue
        print_r($data);
    }

    function Actiontransaction()
    {
        $connection  = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        try{

            $connection->createCommand("insert into test(id,name) values(6,'tom')")->execute();
            $connection->createCommand("insert into test(id,name) values(dsf,'jiji')")->execute();

            echo $transaction->commit();

        }catch (Exception $e){

            $transaction->rollback();
            throw new RuntimeException("插入失败");
        }
    }

    function actionbindparam()
    {
        //参数绑定  仅能通过引用方式
        $connection = Yii::app()->db;
        $insertSql  = "INSERT INTO test(id,name) VALUES(:id,:name)";
        $id         = 9;
        $name       = "lucy";
        echo $connection->createCommand($insertSql)->bindParam(":id",$id,PDO::PARAM_INT)->bindParam(":name",$name,PDO::PARAM_STR)->execute();
    }

    function actionbindcolumn()
    {
        $connection = Yii::app()->db;
        $selectSql  = "SELECT id,name FROM test";
        $dataReader = $connection->createCommand($selectSql)->query();

        $id         = "ids";
        $name       = "huahua";
        $dataReader->bindColumn(1,$id);
        $dataReader->bindColumn(2,$name);

        print_r($dataReader->readAll());
    }

    function actionprefix()
    {
        $connection = Yii::app()->db;
        $connection->tablePrefix = "rt_";

        $selectSql  = "select * from {{test}}";
        $dataReader = $connection->createCommand($selectSql)->query();
        print_r($dataReader->readAll());
    }

    function actionarsave()
    {
        $test     = new Test();
        //$test->id = 30;
        $test->name = "lili";

        echo $test->save();
    }

    function actionarselect()
    {
//        $test = new Test();
//        echo $test->name;

//        $test = Test::model()->find("id=2");
//        $test = Test::model()->find("id=:id",['id'=>5]);

//        $criteria            = new CDbCriteria();
//        $criteria->select    = "id,name";
//        $criteria->condition = "id=:id";
//        $criteria->params    = [':id'=>1];
//        $test                = Test::model()->find($criteria);

//        $test = Test::model()->find([
//            "select"=>'id,name',
//            "condition"=>"id=:id",
//            "params"=>[':id'=>2]
//        ]);

//        $test = Test::model()->findByPk(5);
//        $test = Test::model()->findByPk(5,'name=:name',[':name'=>"jack6"]);
//        $test = Test::model()->findByAttributes(['name'=>'jack5'],'id=:id',[':id'=>90]);

//        $test = Test::model()->findBySql("select * from user where id=:id",[':id'=>"1"]);

//        $test = Test::model()->findByAttributes(['name'=>'lili']);

//        $test = Test::model()->findAll("name=:name",['name'=>"jack"]);
//        $test = Test::model()->findAllByAttributes(['name'=>"jack"]);
//
//        if (is_array($test)){
//            echo $test[0]->name;
//        }else{
//            echo $test->name;
//        }

//        $test = Test::model()->count("id=:id",["id"=>1]);
//        $test = Test::model()->count("id=1");
//        $test = Test::model()->count(['in','id',[1,2,3]]);

//        $test = Yii::app()->db->createCommand()->select("count(*) as p")->from("user")->queryAll();

//        $test = Test::model()->findAll();
//        $test = Test::model()->find();

//        $test = Test::model()->findByPk(1);
        echo json_encode($test);
    }

    function actionarupdate()
    {
//        $test = Test::model()->findByPk(1);
//        $test = Test::model()->find("name=:name",['name'=>"tom"]);
//        $test = Test::model()->findByAttributes([
//            "name"=>"jack",
//            "id"=>2
//        ]);
//        $test->name = "中jack";
//        echo $test->save();

        //$test = Test::model()->order("id desc")->findAll();
//        $test = Test::model()->updateAll([
//            "name"=>"huawei"
//        ],"id=:id",["id"=>1]);

//        $test = Test::model()->updateByPk([1,2],['name'=>"lulu"]);
//        $test = Test::model()->updateCounters(['id'=>1],"name=:name and id=:id",['name'=>"lili","id"=>32]);

        $test = Test::getTests();
        print_r($test);

    }

    //关联AR
    //关联模型
    function actionarelation()
    {
        $post = Post::model()->findByPk(1);
//        echo json_encode(Yii::app()->db->createCommand()->select("*")->from("tpl_post")->queryAll());

//        echo $post->author->username;

//        echo json_encode($post->categories[0]->name);

//        $data = $post->with('author')->findAll();
//        $data = $post->with('author','categories')->findAll();
//
//        foreach($data as $row){
//            print_r($row->author->username);
//            foreach($row->categories as $category){
//                print_r($category->name);
//            }
//        }

        //取得文章的作者资料，作者的文章列表，文章的所属分类
//        $data = $post->with(
//            'author.profile',
//            'author.posts',
//            'categories'
//        )->findAll();

//        $criterai = new CDbCriteria();
//        $criterai->with = [
//            'author.profile',
//            'author.posts',
//            'categories'
//        ];
//
//        $data = $post->findAll($criterai);

        $data = $post->findAll([
            'with'=>[
                'author.profile',
                'author.posts',
                'categories'
            ]
        ]);

        foreach ($data as $row){
            //echo $row->author->profile->username;
            //文章的作者，作者的资料
            //print_r($row->author->profile->photo);
            //print_r($row->author->posts);
            //文章的作者，作者发布的文章列表
//            foreach($row->author->posts as $article){
//                echo $article->title;
//            }

            //文章的分类列表
            foreach ($row->categories as $category){
                echo $category->name;
            }
        }
    }

    function actionParam($Id,$Name)
    {
        echo $Id.'-'.$Name;
    }

}