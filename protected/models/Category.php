<?php
/**
 * Created by PhpStorm.
 * User: F1083
 * Date: 2018/12/26
 * Time: 8:48
 */

class Category extends CActiveRecord
{
    public function tableName()
    {
        //return parent::tableName(); // TODO: Change the autogenerated stub
        return "tpl_category";
    }

    public function primaryKey()
    {
        //parent::primaryKey(); // TODO: Change the autogenerated stub
        return 'id';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className); // TODO: Change the autogenerated stub
    }

    protected function beforeDelete()
    {
        return parent::beforeDelete(); // TODO: Change the autogenerated stub



    }



    function scopes()
    {
        //return parent::scopes(); // TODO: Change the autogenerated stub
        return [
            'published'=>[
                'condition'=>''
            ],
            'recently'=>[
                'order'=>'id desc'
            ]
        ];
    }
}