<?php

namespace app\controllers;

use app\models\CustomerSearch;
use app\models\GradeSearch;
use app\models\SalerecordSearch;
use app\models\Salerecord;
use app\models\Profile;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        /* return [
             'access' => [
                 'class' => AccessControl::className(),
                 'only' => ['logout'],
                 'rules' => [
                     [
                         'actions' => ['logout'],
                         'allow' => true,
                         'roles' => ['@'],
                     ],
                 ],
             ],
             'verbs' => [
                 'class' => VerbFilter::className(),
                 'actions' => [
                     'logout' => ['post'],
                 ],
             ],
         ];*/
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($plant_id = null,$filter = null)
    {

        /* $searchModel = new SalerecordSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$plant_id,$date);*/
        $model = new Salerecord();
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['deleted' => 0])->andWhere(['<>','id',9999])->orderBy(['name' => 'asc']);
        $dataProvider->setPagination(['pageSize' => 100]);
        $customers = $dataProvider->getModels();

        $searchModel = new GradeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['deleted' => 0])->andWhere(['<>','id',9999])->orderBy(['name' => 'asc']);
        $dataProvider->setPagination(['pageSize' => 100]);
        $grades = $dataProvider->getModels();
        // print_r($dataProvider->getModels());

        return $this->render('index', [
            /*'searchModel' => $searchModel, */
            'customers' => $customers,
            'grades' => $grades,
            'filter' => $filter,
            'model' => $model,
            'filter_plant' => $plant_id,
        ]);
    }

    /**
     * Displays monthly report.
     *
     * @return string
     */
    public function actionReport($plant_id = null,$filter = null)
    {

        /* $searchModel = new SalerecordSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$plant_id,$date);*/
        $model = new Salerecord();
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['deleted' => 0])->andWhere(['<>','id',9999])->orderBy(['name' => 'asc']);
        $dataProvider->setPagination(['pageSize' => 100]);
        $customers = $dataProvider->getModels();

        $searchModel = new GradeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['deleted' => 0])->andWhere(['<>','id',9999])->orderBy(['name' => 'asc']);
        $dataProvider->setPagination(['pageSize' => 100]);
        $grades = $dataProvider->getModels();
        // print_r($dataProvider->getModels());

        return $this->render('report', [
            /*'searchModel' => $searchModel, */
            'customers' => $customers,
            'grades' => $grades,
            'filter' => $filter,
            'model' => $model,
            'filter_plant' => $plant_id,
        ]);
    }

    /**
     * Displays dashboard.
     *
     * @return string
     */
    public function actionDashboard($plant_id = null,$filter = null)
    {

        /* $searchModel = new SalerecordSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$plant_id,$date);*/
        $model = new Salerecord();
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['deleted' => 0])->andWhere(['<>','id',9999])->orderBy(['name' => 'asc']);
        $dataProvider->setPagination(['pageSize' => 100]);
        $customers = $dataProvider->getModels();

        $searchModel = new GradeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['deleted' => 0])->andWhere(['<>','id',9999])->orderBy(['name' => 'asc']);
        $dataProvider->setPagination(['pageSize' => 100]);
        $grades = $dataProvider->getModels();
        // print_r($dataProvider->getModels());

        // Generate grapht data set
       if($plant_id==0) {
           $format_month = date("m");
           $year = date("Y");
           // loops according to month date count + query inside each loop
           $days_in_month = cal_days_in_month(CAL_GREGORIAN, $format_month, $year);
           $year_lastmonth_str = date('Y-m', strtotime('last month'));

           $year_lastmonth = explode("-", $year_lastmonth_str)[0];
           $format_lastmonth = explode("-", $year_lastmonth_str)[1];

           $day_in_lastmonth = cal_days_in_month(CAL_GREGORIAN, $format_lastmonth, $year_lastmonth);

           $graph_data = [];
           for ($i = 1; $i < ($days_in_month + 1); $i++) {
               $timestamp = strtotime($year . "-" . $format_month . "-" . $i);
               $format_date = date("Y-m-d", $timestamp);

               if (date("Y-m-d", $timestamp) > date("Y-m-d")) {
                   $show = false;
               } else {
                   $show = true;
               }

               $first_date = strtotime($year . "-" . $format_month . "-1");
               $format_first_date = date("Y-m-d", $first_date);

               $sql = "SELECT SUM(m3) as m3, DATE_FORMAT(display_date,'%d') as name FROM salerecord WHERE DATE_FORMAT(display_date,'%Y-%m-%d')>='" . $format_first_date . "' AND DATE_FORMAT(display_date,'%Y-%m-%d')<='" . $format_date . "'";
               $data = \Yii::$app->db->createCommand($sql)->queryAll();
               $exist = \Yii::$app->db->createCommand($sql)->execute();

               if ($exist != 0 && $show == true) {
                   foreach ($data as $d) {

                       $graph_data[] = [
                           intval($d["m3"])
                       ];
                   }
               } else {
                   $graph_data[] = [
                       null
                   ];
               }

           }

// LAST MONTH
           $graph_data2 = [];
           // $year_lastmonth_str = date('Y-m', strtotime('-1 months'));


           for ($i = 1; $i < ($day_in_lastmonth + 1); $i++) {
               $timestamp2 = strtotime($year_lastmonth_str . "-" . $i);
               $format_date2 = date("Y-m-d", $timestamp2);

               $show2 = true;


               $first_date2 = strtotime($year_lastmonth_str . "-1");
               $format_first_date2 = date("Y-m-d", $first_date2);

               $sql = "SELECT SUM(m3) as m3 FROM salerecord WHERE DATE_FORMAT(display_date,'%Y-%m-%d')>='" . $format_first_date2 . "' AND DATE_FORMAT(display_date,'%Y-%m-%d')<='" . $format_date2 . "'";
               $data2 = \Yii::$app->db->createCommand($sql)->queryAll();
               $exist2 = \Yii::$app->db->createCommand($sql)->execute();

               if ($exist2 != 0 && $show2 == true) {
                   foreach ($data2 as $d) {

                       $graph_data2[] = [
                           intval($d["m3"])
                       ];
                   }
               } else {
                   $graph_data2[] = [
                       null
                   ];
               }

           }
       }else{
           $format_month = date("m");
           $year = date("Y");
           // loops according to month date count + query inside each loop
           $days_in_month = cal_days_in_month(CAL_GREGORIAN, $format_month, $year);
           $year_lastmonth_str = date('Y-m', strtotime('last month'));

           $year_lastmonth = explode("-", $year_lastmonth_str)[0];
           $format_lastmonth = explode("-", $year_lastmonth_str)[1];

           $day_in_lastmonth = cal_days_in_month(CAL_GREGORIAN, $format_lastmonth, $year_lastmonth);

           $graph_data = [];
           for ($i = 1; $i < ($days_in_month + 1); $i++) {
               $timestamp = strtotime($year . "-" . $format_month . "-" . $i);
               $format_date = date("Y-m-d", $timestamp);

               if (date("Y-m-d", $timestamp) > date("Y-m-d")) {
                   $show = false;
               } else {
                   $show = true;
               }

               $first_date = strtotime($year . "-" . $format_month . "-1");
               $format_first_date = date("Y-m-d", $first_date);

               $sql = "SELECT SUM(m3) as m3, DATE_FORMAT(display_date,'%d') as name FROM salerecord WHERE plant_id= ".$plant_id." AND DATE_FORMAT(display_date,'%Y-%m-%d')>='" . $format_first_date . "' AND DATE_FORMAT(display_date,'%Y-%m-%d')<='" . $format_date . "'";
               $data = \Yii::$app->db->createCommand($sql)->queryAll();
               $exist = \Yii::$app->db->createCommand($sql)->execute();

               if ($exist != 0 && $show == true) {
                   foreach ($data as $d) {

                       $graph_data[] = [
                           intval($d["m3"])
                       ];
                   }
               } else {
                   $graph_data[] = [
                       null
                   ];
               }

           }

// LAST MONTH
           $graph_data2 = [];
           // $year_lastmonth_str = date('Y-m', strtotime('-1 months'));


           for ($i = 1; $i < ($day_in_lastmonth + 1); $i++) {
               $timestamp2 = strtotime($year_lastmonth_str . "-" . $i);
               $format_date2 = date("Y-m-d", $timestamp2);

               $show2 = true;


               $first_date2 = strtotime($year_lastmonth_str . "-1");
               $format_first_date2 = date("Y-m-d", $first_date2);

               $sql = "SELECT SUM(m3) as m3 FROM salerecord WHERE plant_id= ".$plant_id." AND DATE_FORMAT(display_date,'%Y-%m-%d')>='" . $format_first_date2 . "' AND DATE_FORMAT(display_date,'%Y-%m-%d')<='" . $format_date2 . "'";
               $data2 = \Yii::$app->db->createCommand($sql)->queryAll();
               $exist2 = \Yii::$app->db->createCommand($sql)->execute();

               if ($exist2 != 0 && $show2 == true) {
                   foreach ($data2 as $d) {

                       $graph_data2[] = [
                           intval($d["m3"])
                       ];
                   }
               } else {
                   $graph_data2[] = [
                       null
                   ];
               }

           }
       }
        return $this->render('dashboard', [
            /*'searchModel' => $searchModel, */
            'customers' => $customers,
            'grades' => $grades,
            'filter' => $filter,
            'model' => $model,
            'filter_plant' => $plant_id,
            'data' => $graph_data,
            'data2' => $graph_data2,
        ]);
    }
    /**
     * Displays monthly report.
     *
     * @return string
     */
    public function actionDriver($plant_id = null,$filter = null, $driver_id = null)
    {

        /* $searchModel = new SalerecordSearch();
         $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$plant_id,$date);*/

        $model = new Salerecord();
        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['deleted' => 0])->andWhere(['<>','id',9999])->orderBy(['name' => 'asc']);
        $dataProvider->setPagination(['pageSize' => 100]);
        $customers = $dataProvider->getModels();

        $searchModel = new GradeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->where(['deleted' => 0])->andWhere(['<>','id',9999])->orderBy(['name' => 'asc']);
        $dataProvider->setPagination(['pageSize' => 100]);
        $grades = $dataProvider->getModels();


        $date_arr = explode("-", $filter);
        $year = $date_arr[0];
        $month = $date_arr[1];
        if ($month == 'Jan') {
            $format_month = '01';
        } else if ($month == 'Feb') {
            $format_month = '02';
        } else if ($month == 'Mar') {
            $format_month = '03';
        } else if ($month == 'Apr') {
            $format_month = '04';
        } else if ($month == 'May') {
            $format_month = '05';
        } else if ($month == 'Jun') {
            $format_month = '06';
        } else if ($month == 'Jul') {
            $format_month = '07';
        } else if ($month == 'Aug') {
            $format_month = '08';
        } else if ($month == 'Sep') {
            $format_month = '09';
        } else if ($month == 'Oct') {
            $format_month = '10';
        } else if ($month == 'Nov') {
            $format_month = '11';
        } else if ($month == 'Dec') {
            $format_month = '12';
        }

        $year_month_str = $year . "-" . $format_month;

        $searchModel = new SalerecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if($plant_id==0) {
            $dataProvider->query->where(['deleted' => 0, 'driver_id' => $driver_id, 'DATE_FORMAT(display_date,"%Y-%m")' => $year_month_str])->orderBy(['display_date' => 'asc', 'batch_no' => 'asc']);
        }else {
            $dataProvider->query->where(['deleted' => 0, 'driver_id' => $driver_id, 'DATE_FORMAT(display_date,"%Y-%m")' => $year_month_str, 'plant_id' => $plant_id])->orderBy(['display_date' => 'asc', 'batch_no' => 'asc']);
        }
        $dataProvider->setPagination(['pageSize' => 100]);
        $driver_trip = $dataProvider->getModels();
        // print_r($dataProvider->getModels());

        return $this->render('driver-trip', [
            /*'searchModel' => $searchModel, */
            'customers' => $customers,
            'grades' => $grades,
            'drivertrips' => $driver_trip,
            'filter' => $filter,
            'model' => $model,
            'filter_plant' => $plant_id,
            'filter_driver' => $driver_id,
        ]);
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {


        if (!Yii::$app->user->isGuest) {

            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
           /* $user_role = Yii::$app->user->identity->getRole();
            $user_plant = Profile::findByUserId(Yii::$app->user->identity->getId())->plant->name;
            $user_plant_id = Profile::findByUserId(Yii::$app->user->identity->getId())->plant_id;
            return $this->redirect('index?plant_id='.$user_plant_id.'&'.'filter='.(date('Y-M'))); */
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
