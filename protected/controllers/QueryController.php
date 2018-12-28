<?php

class QueryController extends Controller
{

    public $defaultAction = 'index';
    public function actionIndex()
    {
        //1连接
        //2生成sql命令
        //3执行sql命令
        $test = Yii::app()->db->createCommand()->select("id,name")->from("test")->queryAll();
        print_r($test);
    }

    function actionselect()
    {
        $connnection = Yii::app()->db->createCommand();
//        $data        = $connnection->select()->from("test")->queryRow();
//        $data        = $connnection->select("name")->from("test")->queryRow();
//        $data        = $connnection->select(['id','name'])->from("test")->queryRow();
//        $data        = $connnection->select(['id','name as pname'])->from("test")->queryRow();
//        $data        = $connnection->selectDistinct('name,id')->from("test")->queryColumn();
//        $data        = $connnection->select('test.name,test.id,user.name as uname,user.id as uid')->from("test,user")->queryRow();
//        $data        = $connnection->select('test.name,test.id,user.name as uname,user.id as uid')->from("test,user")->queryAll();
//        $data        = $connnection->select('test.name,test.id,user.name as uname,user.id as uid')->from(['test','user'])->queryAll();
//        $data        = $connnection->select('t.name,t.id,u.name as uname,u.id as uid')->from(['test t','user u'])->queryAll();
        $data        = $connnection->select('t.name,t.id,u.name as uname')->from(['test t','(select name from user) u'])->queryAll();
        print_r($data);
    }

    function actionwhere()
    {
        $connection = Yii::app()->db->createCommand();
        //字符串
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where("id=1 or id=2")->queryAll();
        //参数绑定
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where("id=:id1 or id=:id2",['id1'=>2,'id2'=>'3'])->queryAll();
        //数组条件
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['or','id=4','id=5'])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['and','id=1',['or','name="jack"','name="tom"']])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['in','id',[1,2,3]])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['not in','id',[1,2,3]])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['like','name',"%jack%"])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['like','name',["%j%",'%a%']])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['or like','name',["%j%",'%t%']])->queryAll();
        //name not like 'x' or name not like 'y'
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['or not like','name',["%j%",'%t%']])->queryAll();

        //不支持between
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['between','id',[1,3]])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['not between','id',[1,3]])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['not between','id',[1,3]])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where(['any','id',[1,3]])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where("id=:id1",['id1'=>1])->andWhere("name='jack'")->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->where("id=:id1",['id1'=>1])->andWhere(['name','jack'])->queryAll();
//        $data       = $connection->select("t.id")
//                                 ->from('(select * from test) t')
////                                 ->where("id=:id1",['id1'=>1])
////                                 ->orWhere(['name','jack'])
//                                 ->order("id desc")
//                                 ->queryAll();

        //limit x offset y
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->limit(2,0)->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->limit(2)->offset(3)->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->leftJoin("user u","u.id=t.id")->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->rightJoin("user u","u.id=t.id and t.name=:name",['name'=>"jack"])->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->group("name")->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->having("id=1 or id=2")->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->union("select id,name from user")->queryAll();
//        $data       = $connection->select(['t.id','t.name'])->from('(select * from test) t')->union("select id,name from user")->text;


//        $data = Yii::app()->db->createCommand([
//            'select'=>['id','name'],
//            'from'=>'user',
//            'order'=>'id desc',
//            'where'=>'id<>1'
//        ])->text;

        $data = $connection->select("*")->from("test")->queryAll();
        $connection->reset();
        $data1= $connection->select("*")->from("user")->queryAll();
        print_r($data);
    }

    function actioninsert()
    {
        $row = Yii::app()->db->createCommand()->insert("user",[
            'id'=>100,
            'name'=>'liu'
        ]);

        echo $row;
    }

    function actionupdate()
    {
//        $row = Yii::app()->db->createCommand()->update('user',[
//            'name'=>'liudada'
//        ],'id=:id',['id'=>100]);
//        echo $row;

        $row = Yii::app()->db->createCommand()->update('user',[
            'name'=>'liubei'
        ],'id=100');
        echo $row;
    }

    function actiondelete()
    {
        $row = Yii::app()->db->createCommand()->delete("user",'id=:id',['id'=>100]);
        echo $row;
    }


}