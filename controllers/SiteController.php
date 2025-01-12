<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\Product;
use app\models\ContactForm;
use yii\data\Pagination;
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
                        'actions' => [

                            'create',
                            'update',
                            'index',
                            'delete'
                        ],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
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
        $sql = 'SELECT COUNT(*) FROM produk LEFT JOIN kategori ON produk.kategori_id = kategori.id_kategori WHERE status_id = 1';
        if (!empty($_GET['id_produk'])) {
            $sql .= ' AND UPPER(id_produk) LIKE :id_produk';
        }
        if (!empty($_GET['nama_produk'])) {
            $sql .= ' AND UPPER(nama_produk) LIKE :nama_produk';
        }
        if (!empty($_GET['id_kategori'])) {
            $sql .= ' AND kategori.id_kategori = :kategori';
        }
        if (!empty($_GET['status_id'])) {
            $sql .= ' AND status_id = :status_id';
        }
        $cmd = Yii::$app->db->createCommand($sql);
        if (!empty($_GET['id_produk'])) {
            $cmd->bindValue(':id_produk', '%' . strtoupper($_GET['id_produk'] . '%'));
        }
        if (!empty($_GET['nama_produk'])) {
            $cmd->bindValue(':nama_produk', '%' . strtoupper($_GET['nama_produk'] . '%'));
        }
        if (!empty($_GET['id_kategori'])) {
            $cmd->bindValue(':kategori', $_GET['id_kategori']);
        }
        if (!empty($_GET['status_id'])) {
            $cmd->bindValue(':status', $_GET['status_id']);
        }
        $size = Yii::$app->params['pageSize'];
        $paging = new Pagination(['totalCount' => $cmd->queryScalar(), 'pageSize' => $size]);
        $sql = 'SELECT * FROM produk
			LEFT JOIN kategori ON produk.kategori_id = kategori.id_kategori
			WHERE status_id = 1';
        if (!empty($_GET['id_produk'])) {
            $sql .= ' AND UPPER(id_produk) LIKE :id_produk';
        }
        if (!empty($_GET['nama_produk'])) {
            $sql .= ' AND UPPER(nama_produk) LIKE :nama_produk';
        }
        if (!empty($_GET['id_kategori'])) {
            $sql .= ' AND kategori.id_kategori = :kategori';
        }
        if (!empty($_GET['status_id'])) {
            $sql .= ' AND status_id = :status';
        }

        $start = isset($_GET['page']) ? ($_GET['page'] - 1) * $size : 0;
        $sql .= " ORDER BY id_produk ASC LIMIT $start,$size";
        $cmd = Yii::$app->db->createCommand($sql);
        if (!empty($_GET['id_produk'])) {
            $cmd->bindValue(':id_produk', '%' . strtoupper($_GET['id_produk'] . '%'));
        }
        if (!empty($_GET['nama_produk'])) {
            $cmd->bindValue(':nama_produk', '%' . strtoupper($_GET['nama_produk'] . '%'));
        }
        if (!empty($_GET['id_kategori'])) {
            $cmd->bindValue(':kategori', $_GET['id_kategori']);
        }
        if (!empty($_GET['status_id'])) {
            $cmd->bindValue(':status', $_GET['status_id']);
        }
        $rows = $cmd->queryAll();

        return $this->render('index', [
            'rows' => $rows,
            'paging' => $paging,
        ]);
    }

    public function actionCreate()
    {

        $model = new Product();

        if ($this->request->isPost) {

            if ($_POST['Product']['nama_produk'] == '') {
                Yii::$app->session->setFlash('error', 'Input tidak valid! Periksa kembali inputan Anda. Kolom nama tidak boleh dikosongkan');
                return $this->redirect(['create']);
            }
            if (!is_numeric($_POST['Product']['harga'])) {
                Yii::$app->session->setFlash('error', 'Input tidak valid! Periksa kembali inputan Anda. Kolom harga harus berupa angka');
                return $this->redirect(['create']);
            }
            $model->load($this->request->post());
            // Validation

            $model->nama_produk = $_POST['Product']['nama_produk'];
            $model->harga = $_POST['Product']['harga'];
            $model->kategori_id = $_POST['Product']['kategori_id'];
            $model->status_id = $_POST['Product']['status_id'];
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost) {

            if ($_POST['Product']['nama_produk'] == '') {
                Yii::$app->session->setFlash('error', 'Input tidak valid! Periksa kembali inputan Anda. Kolom nama tidak boleh dikosongkan');
                return $this->redirect(['create']);
            }
            if (!is_numeric($_POST['Product']['harga'])) {
                Yii::$app->session->setFlash('error', 'Input tidak valid! Periksa kembali inputan Anda. Kolom harga harus berupa angka');
                return $this->redirect(['create']);
            }
            $model->load($this->request->post());
            $model->nama_produk = $_POST['Product']['nama_produk'];
            $model->harga = $_POST['Product']['harga'];
            $model->kategori_id = $_POST['Product']['kategori_id'];
            $model->status_id = $_POST['Product']['status_id'];
            if ($model->save()) {
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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

    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Data Barang tidak ditemukan.');
    }
}
