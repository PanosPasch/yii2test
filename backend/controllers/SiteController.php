<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\CreatePage;
use common\models\Page;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Site controller
 */
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
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'create-page', 'my-pages'],
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
                'class' => \yii\web\ErrorAction::class,
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
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
     * Display page builder
     * 
     * @return Response
     */
    public function actionCreatePage()
    {
        $model = new CreatePage();
        if ($model->load(Yii::$app->request->post()) ) {
            $model->image = UploadedFile::getInstance($model, 'image');
            if($model->create())
            {
                return $this->goHome();
            }
        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Display My Pages
     * 
     * @return Response
     */
    public function actionMyPages()
    {
        $model = new CreatePage();
        $request = Yii::$app->request;

        //if deletion request is sent, delete page
        if ($request->post() && $request->post('type') === 'delete')
        {
            if ($model->delete($request->post('id')))
            {
                return $this->render('list', [
                    'model' => $model
                ]);
            }
        }
        //if edit request is sent, setup edit page
        if ($request->post() && $request->post('type') === 'edit')
        {
            if ($page = $model->getPageData($request->post('id')))
            {
                $model->id = $page->id;
                $model->title = $page->title;
                $model->pretty_url = $page->pretty_url;
                $model->category = $page->category;
                $model->image = $page->image;
                $model->text = $page->text;
                return $this->render('edit', [
                    'model' => $model
                ]);
            }
        }

        //submit edited page
        if ($model->load($request->post()) ) {
            if($model->create())
            {
                return $this->goHome();
            }
        }
        

        return $this->render('list', [
            'model' => $model
        ]);
    }

}
