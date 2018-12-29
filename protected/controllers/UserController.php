<?php

class UserController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);

        /**
        [
        [
        allow,
        'actions'=>['index','view'],
        users=>['*']
        ]
        ,
        [
        allow,
        'actions'=>['create','update'],
        users=>['@']
        ]
        ]

         **/
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new User;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('User');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new User('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['User']))
			$model->attributes=$_GET['User'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return User the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param User $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	public function actionVerify()
    {
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }$serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
        $serviceId = Yii::app()->session['serviceid'];
        $deptId    = Yii::app()->request->getParam("deptid");
        $groupId   = Yii::app()->request->getParam("groupid");

        Yii::app()->session['admin_breadcrumbs'] = array(
            "数据统计管理" => array('Datacount/index'),
            "首页数据 "  => array('Datacount/indexData'),
        );
        /*
	 		 * 判断时间，今天，昨天，还是前天
	 		 */
        if (Yii::app()->request->isAjaxRequest) {
            $day = Yii::app()->request->getParam('day');
            if ($day == 'yesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 1, date('Y'));
            } else if ($day == 'tbyesterday') {
                $beginTime = mktime(0, 0, 0, date('m'), date('d') - 2, date('Y'));
                $endTime   = mktime(23, 59, 59, date('m'), date('d') - 2, date('Y'));
            }
        } else {
// 	 			$timeArr = array();
            $beginTime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            $endTime   = mktime(23, 59, 59, date('m'), date('d'), date('Y'));
        }
//  			$timeArr[0]['begintime'] = $begintime;
//  			$timeArr[0]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-1,date('Y'));
//  			$timeArr[1]['begintime'] = $begintime;
//  			$timeArr[1]['endtime'] = $endtime;
//  			$begintime = mktime(0,0,0,date('m'),date('d')-2,date('Y'));
//  			$endtime = mktime(23,59,59,date('m'),date('d')-2,date('Y'));
//  			$timeArr[2]['begintime'] = $begintime;
//  			$timeArr[2]['endtime'] = $endtime;
        $db = Yii::app()->db;
        //班组查询
        $gwhere  = "";  //
        $gcwhere = "";
        $ugwhere = "";
        $wdeptId = "";
        if (isset($deptId) && $deptId != "all") {
            $res = RABC::GetGroupId($deptId);//得到所有组。
            //echo $res;
            if ($res != "") {
                $cres = RABC::GetCustomId("IN ($res)");//得到符合条件的Customid
                //echo $cres;exit; 1,2,26,128,153,154
                if (!empty($cres)) {
                    $gcwhere .= "AND customid IN ($cres)";
                    $ugwhere .= "AND u.customid IN ($cres)";
                }
                $gwhere .= "AND group_id IN ($res)";
            } else {
                $gcwhere .= "AND customid=-1";
                $ugwhere .= "AND u.customid=1";
                $gwhere .= "AND group_id=-1";
            }
            $wdeptId .= "AND deptid=$deptId";
        }
        if (isset($groupId) && $groupId != "all") {
            $gres = RABC::GetCustomId("=$groupId");
            if (!empty($gres)) {
                $gcwhere .= "AND customid IN ($gres)";
                $ugwhere .= "AND u.customid IN ($gres)";
            }
            $gwhere .= "AND group_id =$groupId";
        }


        //数据权限控制
        $cprivilege  = "";
        $iprivilege  = "";
        $ucprivilege = "";
        $leaderName  = "";
        $gprivilege  = "";
        if (Yii::app()->session['privilege_f'] != 'deptleader') {
            if (Yii::app()->session['privilege_gid']) {
                $custom_id = RABC::GetCustomId(Yii::app()->session['privilege_gid']);
                if (!empty($custom_id)) {
                    $custom_id = "IN ($custom_id)";
                    //echo $custom_id;exit;
                    $cprivilege .= "AND customid ";
                    $cprivilege .= $custom_id;
                } else {
                    $cprivilege = 'AND customid=-1';
                }
                $iprivilege  .= "AND group_id ";
                $iprivilege  .= Yii::app()->session['privilege_gid'];
                $ucprivilege .= "AND u.customid ";
                $ucprivilege .= $custom_id;
                $gprivilege  .= "AND id ";
                $gprivilege  .= Yii::app()->session['privilege_gid'];
                $leaderName  = Yii::app()->session['leaderaccount'];
                $leaderName  = "AND leaderaccount ='$leaderName'";
            }
        }
        //循环取出三天的数据
// 	 		$threedayData = array();
// 	 		foreach($timeArr as $_k=>$_t) {
// 	 			$begintime = $_t['begintime'];
// 	 			$endtime = $_t['endtime'];
        //粉丝数据
        $cancelCount  = User::model()->count("serviceid=$serviceId AND unsubscribe_time BETWEEN $beginTime AND $endTime AND subscribe=0 $cprivilege  $gcwhere");    //当日取关人数
        $subCount     = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime AND subscribe=1 $cprivilege  $gcwhere");    //净增人数
        $newFansCount = User::model()->count("serviceid=$serviceId AND subscribe_time BETWEEN $beginTime AND $endTime $cprivilege  $gcwhere");    //当日关注人数
        $fansCount    = User::model()->count("serviceid=$serviceId AND subscribe=1");                                                                    //粉丝总数

        //消息数据
        $sql       = "SELECT id FROM {{custom}} WHERE serviceid=$serviceId $iprivilege $gwhere";
        $customIds = $db->createCommand($sql)->queryColumn();
        $customIds = implode(',', $customIds);
        $msgData   = array('ps' => 0, 'ms' => 0, 'rs' => 0);
// 		 		if(!empty($customids)) {
// 				 		for($i=0;$i<10;$i++) {
// 				  			$sql = "SELECT COUNT(DISTINCT r1.openid) ps,COUNT(r2.id) rs,COUNT(r3.id) ms FROM {{record_$i}} r1
// 				  			LEFT JOIN {{record_$i}} r2 ON r1.id=r2.id AND r2.is_system=0 AND r2.status=0
// 				  			LEFT JOIN {{record_$i}} r3 ON r1.id=r3.id AND r3.is_system=0 AND r3.status=1
// 				  			WHERE r1.customid IN($customids) AND r1.createtime BETWEEN $begintime AND $endtime";
// 				  			$data = $db->createCommand($sql)->queryAll();
// 				  			if(isset($data[0]['ps'])) $msgdata['ps'] += $data[0]['ps'];			//互动人数
// 				  			if(isset($data[0]['ms'])) $msgdata['ms'] += $data[0]['ms'];			//发送消息数
// 				  			if(isset($data[0]['rs'])) $msgdata['rs'] += $data[0]['rs'];			//接收消息数（或者互动次数）
// 				  		}
// 			 		}

        //订单统计
        if ($serviceId == 8) {
            $orderIds      = (new MoonCake())->findByCreateTime($beginTime, $endTime);
            $orderIdsWhere = '';
            if ($orderIds) {
                $orderIdsWhere = "AND id NOT IN ({$orderIds})";
            }
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere $orderIdsWhere";
        } else {
            $sql = "SELECT COUNT(*) count,SUM(total_price) total FROM {{order}} WHERE order_status IN (1,2,4,6,8,9) AND createtime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId AND giftSendType != 1 $cprivilege $gcwhere";
        }
        $orderData = $db->createCommand($sql)->queryAll();

        $orderData = $orderData[0];
        if ($orderData['count'] == 0) {
            $per = 0;
        } else {
            $per = intval(($orderData['total'] / $orderData['count']));        //客单价
        }

        //其他互动数
// 		 		$sginincount = FansIntegralDetail::model()->count("signTime BETWEEN $begintime AND $endtime AND serviceid=$serviceid AND integralGetType=1");				//当日签到总数
        $sql           = "SELECT COUNT(DISTINCT openid) count FROM {{fans_integral_detail}} WHERE signTime>=$beginTime AND signTime<=$endTime AND serviceid=$serviceId AND integralGetType=1 $cprivilege $gcwhere";
        $sgininCount   = $db->createCommand($sql)->queryScalar();
        $satisFaction  = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=2 $cprivilege $gcwhere");            //非常满意
        $satisfy       = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=1 $cprivilege $gcwhere");                //满意
        $yawp          = Evaluate::model()->count("serviceid=$serviceId AND createTime BETWEEN $beginTime AND $endTime AND evaluate=0 $cprivilege $gcwhere");                    //不满意
        $evaluateCount = $satisFaction + $satisfy + $yawp;
        $sql           = "SELECT SUM(mind) m FROM {{mind}} WHERE createTime BETWEEN $beginTime AND $endTime AND serviceid=$serviceId $cprivilege $gcwhere";
        $mindCount     = $db->createCommand($sql)->queryScalar();            //当日送出心意

// 		 		//当天新增粉丝的订单数
// 		 		$sql = "SELECT COUNT(DISTINCT u.id) num FROM {{user}} u JOIN {{order}} o ON u.openid=o.openid AND o.order_status IN(1,2,4,6) AND o.createtime BETWEEN $begintime AND $endtime AND giftSendType != 1
// 		 				WHERE u.serviceid=$serviceid AND subscribe_time BETWEEN $begintime AND $endtime $ucprivilege $ugwhere";
// 		 		$newfansordernum = $db->createCommand($sql)->queryScalar();

        //今天关注的并且从今天起72小时内有成交的粉丝数
        $newFansOrderNum = $this->getNeworderfansnum($serviceId, $beginTime, $endTime, $ucprivilege, $ugwhere);
// 		 		//昨天关注的并且从昨天起72小时内有成交的粉丝数
// 		 		$yesterfansordernum = $this->getNeworderfansnum($serviceid, $begintime-24*3600, $endtime-24*3600, $ucprivilege, $ugwhere);
// 		 		//前天关注的并且从前天起72小时内有成交的粉丝数
// 		 		$befoyesfansordernum = $this->getNeworderfansnum($serviceid, $begintime-48*3600, $endtime-48*3600, $ucprivilege, $ugwhere);

        //当前服务号下的微医生总数
        $onCount = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status<>0 $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //忙碌状态的微医生总数
        $cstatus = Custom::model()->count(array(
            'condition' => "serviceid=:serviceid AND status=:status $iprivilege $gwhere",
            'params'    => array(':serviceid' => $serviceId, ':status' => 2),
        ));
        //正在抢粉的微医生总数
        $grabCount = CustomGrab::model()->count(array(
            'condition' => "serviceid=:serviceid AND grabstatus=1 $cprivilege $gcwhere",
            'params'    => array(':serviceid' => $serviceId),
        ));

        //该时间段内抢粉数
        $sqlGrab = "SELECT COUNT(DISTINCT openid) from crm_grabfans_detail WHERE serviceid=$serviceId AND createtime BETWEEN $beginTime AND $endTime $cprivilege $gcwhere";
        $grabFansCount = $db->createCommand($sqlGrab)->queryScalar();

// 		        $grabfanscount = GrabfansDetail::model()->count(array(
// 		        		'condition'=>"serviceid=:serviceid AND createtime BETWEEN $begintime AND $endtime $cprivilege $gcwhere",
// 		        		'params'=>array(':serviceid'=>$serviceid),
// 		        ));


        //新粉成交及占比：最近三天关注的粉丝今天的总营业额  占比：新粉成交/今天的营业额
        $sql11 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						and u.subscribe_time > ($endTime-72*3600) $ucprivilege $ugwhere";
        $newFansTotal = $db->createCommand($sql11)->queryScalar();

        //老粉首次成交及占比：三天前关注的并且没有成交过订单的粉丝今天的总营业额  占比：老粉首次成交/今天的营业额
        $sql22 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND NOT EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansFirstTotal = $db->createCommand($sql22)->queryScalar();

        //复购及占比：三天前关注的并且有过成交过订单的粉丝今天的总营业额  占比：复购/今天的营业额
        $sql33 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
						INNER JOIN crm_user u on a.openid=u.openid
						where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
						AND u.subscribe_time<=($endTime-72*3600)
						AND EXISTS(SELECT 1 from crm_order c where c.openid=u.openid AND c.order_status IN (1,2,4,6,8,9) AND c.giftSendType != 1 AND c.createtime<=($endTime-72*3600)) $ucprivilege $ugwhere";
        $oldFansSecondTotal = $db->createCommand($sql33)->queryScalar();

        //当天新粉成交额：当天关注的新粉在当天的成交总额
        $sql44 = "SELECT SUM(a.total_price) AS totalprice from crm_order a
		        INNER JOIN crm_user u on a.openid=u.openid
		        where a.serviceid=$serviceId AND a.order_status IN (1,2,4,6,8,9) AND a.giftSendType != 1 AND (a.createtime BETWEEN $beginTime AND $endTime)
		        and u.createtime > $beginTime $ucprivilege $ugwhere";
        $newFansTodayTotal = $db->createCommand($sql44)->queryScalar();

        //每天群发人数。
        $sendNumSql = "select SUM(sendnum) as totalSendnum from crm_massmsg where status=1 and createtime BETWEEN $beginTime and $endTime and serviceid=$serviceId $gcwhere";
        $sendNumCount = $db->createCommand($sendNumSql)->queryScalar();

        $data = array('msgdata'           => $msgData, 'orderdata' => $orderData, 'cancelCount' => $cancelCount, 'subCount' => $subCount, 'fansCount' => $fansCount, 'newfanscount' => $newFansCount,
                      'per'               => $per, 'sginincount' => $sgininCount, 'satisfaction' => $satisFaction, 'satisfy' => $satisfy, 'evalcount' => $satisFaction + $satisfy + $yawp, 'newfansordernum' => $newFansOrderNum,
                      'yawp'              => $yawp, 'evaluatecount' => $evaluateCount, 'mindcount' => $mindCount,
                      'oncount'           => $onCount, 'cstatus' => $cstatus, 'grabcount' => $grabCount, "grabfanscount" => $grabFansCount,
                      'newfanstotal'      => $newFansTotal, 'oldfansfirsttotal' => $oldFansFirstTotal, 'oldfanssecondtotal' => $oldFansSecondTotal, "SendNumTotal" => $sendNumCount != '' ? $sendNumCount : 0,
                      'newfanstodaytotal' => $newFansTodayTotal
        );


        if (date("d", $beginTime) > date("d", time())) {
            $beginMonth = mktime(0, 0, 0, date('m') - 1, 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m') - 1, date('t'), date('Y'));        //当月结束时间
        } else {
            $beginMonth = mktime(0, 0, 0, date('m'), 1, date('Y'));                //当月开始时间
            $endMonth = mktime(23, 59, 59, date('m'), date('t'), date('Y'));        //当月结束时间

        }
        $sql = "SELECT SUM(total_price) total FROM {{order}} WHERE order_status IN(1,2,4,6,8,9) AND paytype in(1,2) and giftSendType!=1  and createtime BETWEEN $beginMonth AND $endMonth AND serviceid=$serviceId $cprivilege $gcwhere
			 	";


        $monthTotal = $db->createCommand($sql)->queryScalar();

        //班数据
        $depts  = CustomDept::model()->findAll("serviceid=$serviceId $leaderName");
        $groups = CustomGroup::model()->findAll("serviceid=$serviceId $wdeptId $gprivilege");

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('dataSlice', array('data' => $data, 'monthtotal' => $monthTotal, "dept" => $depts, "groups" => $groups));
        } else {
            //管理员待办
            $untreatedOrder    = Order::model()->count("order_status IN (1,4) AND serviceid=$serviceId and del_num='' $cprivilege $gcwhere");        //未处理订单
            $untreatedComplain = Complain::model()->count("is_dispose=0 AND serviceid=$serviceId $cprivilege");        //未处理投诉
            $notAudi           = Reissue::model()->count("serviceid=$serviceId AND status=0 $cprivilege");                    //未审核补发
            $notSend           = Reissue::model()->count("serviceid=$serviceId AND status=1 AND reissue_del_num='' $cprivilege");        //未寄出订单
            $notreFund         = Refund::model()->count("audit_status='M' $cprivilege");   //未审核退款
            $this->render('homedata', array('data' => $data, 'monthtotal' => $monthTotal, 'untreatedOrder' => $untreatedOrder, 'untreatedComplain' => $untreatedComplain
            , 'notaudi'                            => $notAudi, 'notsend' => $notSend, "notrefund" => $notreFund, "dept" => $depts, "groups" => $groups));
        }
    }
}
