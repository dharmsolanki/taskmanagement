<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\RegistrationForm;
use app\models\Task;
use app\models\User;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
                'class' => VerbFilter::class,
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
    public function actionIndex()
    {
        return $this->redirect(['login']);
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
            return $this->redirect(['site/dashboard']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Displays the dashboard page.
     *
     * @return string
     */
    public function actionDashboard()
    {
        // Access the current user's identity
        $user = Yii::$app->user->identity;

        // Check if the user is logged in
        if ($user) {

            $userTask = Task::find()->where(['user_id' => $user->id])->all();
            // You can now use $user to access the user's attributes
            $username = $user->username;
            $email = $user->email;

            return $this->render('dashboard', [
                'username' => $username,
                'email' => $email,
                'userTask' => $userTask
            ]);
        } else {
            // Redirect to the login page if the user is not logged in
            return $this->redirect(['site/login']);
        }
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

    public function actionRegistrationForm()
    {

        $model = new RegistrationForm();
        return $this->render('register', ['model' => $model]);
    }

    public function actionRegister()
    {
        // echo '<pre>'; print_r('here');exit();
        $model = new RegistrationForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Save the user to the database or perform other registration logic
            // For simplicity, let's assume User model exists with 'username', 'password', and 'email' attributes
            $user = new User();
            $user->username = $model->username;
            $user->password_hash = Yii::$app->security->generatePasswordHash($model->password);
            $user->email = $model->email;

            if ($user->save()) {
                // Registration successful, you may redirect to the login page or any other page
                Yii::$app->session->setFlash('success', 'Registration successful!');
                return $this->redirect(['site/login']);
            }
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionAdd()
    {
        // echo '<pre>'; print_r(Yii::$app->request->post());exit();
        $model = new Task();

        if (Yii::$app->request->isPost) {
            $model->task_name = Yii::$app->request->post('taskName');
            $model->start_date = Yii::$app->request->post('startDate');
            $model->end_date = Yii::$app->request->post('endDate');
            $model->progress = Yii::$app->request->post('progress');
            $model->description = Yii::$app->request->post('taskDescription');
            $model->user_id = Yii::$app->user->identity->id;
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'Task Added successful!');
            } else {
                Yii::$app->session->setFlash('danger', 'Try Again!');
            }
        }

        return $this->redirect('dashboard');
    }

    public function actionEdit($id)
    {
        // Find the task by its ID
        $task = Task::findOne($id);

        if (!$task) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // Render the edit view with the task details
        return $this->render('edit', [
            'task' => $task,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Task::findOne($id);

        if (Yii::$app->request->isPost) {
            $model->task_name = Yii::$app->request->post('taskName');
            $model->description = Yii::$app->request->post('taskDescription');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Task updated successfully!');
            } else {
                Yii::$app->session->setFlash('danger', 'Error updating task. Try again!');
            }
        }
        // rule add in web.php for site/dashboard 
        return $this->redirect(['dashboard']);
    }
}
