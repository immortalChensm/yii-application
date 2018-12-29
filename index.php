<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
//造的CWebApplication继承了如下类
//CApplication 【有构造器】
//CModule 【有构造器】
//CComponent【类的一些魔术方法如拦截器，__call等】

//控制器基类
//Controller
//CController
//CBaseController
//CComponent

/**
AR类继承如下
 * CActiveRecord
 * CModel
 * CComponent
 * IteratorAggregate, ArrayAccess
 **/
Yii::createWebApplication($config)->run();
