<?php

namespace backend\controllers;

use Yii;
use common\models\Ad;
use backend\models\AdSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\AdImage;
use yii\web\Response;

/**
 * AdController implements the CRUD actions for Ad model.
 */
class AdController extends Controller
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
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'approve' => ['POST'],
                    'reject' => ['POST'],
                    'delete-image' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Ad models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Ad model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Ad model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Ad();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            if ($model->save()) {
                $this->processImages($model);
                $this->logAction($model, 'Создание объявления администратором');

                Yii::$app->session->setFlash('success', 'Объявление успешно создано.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Ad model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');

            if ($model->save()) {
                $this->processImages($model);
                $this->logAction($model, 'Обновление объявления администратором');

                Yii::$app->session->setFlash('success', 'Объявление успешно обновлено.');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Ad model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Ad::STATUS_DELETED;
        $model->save(false);

        $this->logAction($model, 'Удаление объявления администратором');

        Yii::$app->session->setFlash('success', 'Объявление помечено как удаленное.');
        return $this->redirect(['index']);
    }

    /**
     * Approves an ad that is in moderation
     * @param integer $id
     * @return mixed
     */
    public function actionApprove($id)
    {
        $model = $this->findModel($id);

        if ($model->status !== Ad::STATUS_MODERATION) {
            Yii::$app->session->setFlash('error', 'Только объявления на модерации могут быть одобрены.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $model->status = Ad::STATUS_ACTIVE;
        $model->save(false);

        $this->logAction($model, 'Одобрение объявления администратором');

        // Отправка уведомления пользователю
        $this->sendStatusNotification($model, 'Ваше объявление одобрено');

        Yii::$app->session->setFlash('success', 'Объявление успешно одобрено.');
        return $this->redirect(['index']);
    }

    /**
     * Rejects an ad that is in moderation
     * @param integer $id
     * @return mixed
     */
    public function actionReject($id)
    {
        $model = $this->findModel($id);

        if ($model->status !== Ad::STATUS_MODERATION) {
            Yii::$app->session->setFlash('error', 'Только объявления на модерации могут быть отклонены.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $model->status = Ad::STATUS_INACTIVE;
        $model->save(false);

        $this->logAction($model, 'Отклонение объявления администратором');

        // Отправка уведомления пользователю
        $this->sendStatusNotification($model, 'Ваше объявление отклонено');

        Yii::$app->session->setFlash('success', 'Объявление отклонено.');
        return $this->redirect(['index']);
    }

    /**
     * Deletes an image
     * @param integer $id Image ID
     * @return mixed
     */
    public function actionDeleteImage($id)
    {
        $image = AdImage::findOne($id);

        if (!$image) {
            throw new NotFoundHttpException('Изображение не найдено.');
        }

        $adId = $image->ad_id;
        $image->delete();

        Yii::$app->session->setFlash('success', 'Изображение успешно удалено.');
        return $this->redirect(['view', 'id' => $adId]);
    }

    /**
     * Finds the Ad model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Ad the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ad::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая страница не существует.');
    }

    /**
     * Process uploaded images for ad
     * @param Ad $model
     * @return void
     */
    protected function processImages($model)
    {
        if ($model->imageFiles) {
            foreach ($model->imageFiles as $file) {
                $image = new AdImage();
                $image->ad_id = $model->id;
                $image->saveImage($file);
            }
        }
    }

    /**
     * Log action performed on ad
     * @param Ad $model
     * @param string $action
     * @return void
     */
    protected function logAction($model, $action)
    {
        $log = new \common\models\AdLog();
        $log->ad_id = $model->id;
        $log->user_id = Yii::$app->user->id;
        $log->action = $action;
        $log->save();
    }

    /**
     * Send notification about status change
     * @param Ad $model
     * @param string $subject
     * @return void
     */
    protected function sendStatusNotification($model, $subject)
    {
        if ($model->user && $model->user->email) {
            try {
                Yii::$app->mailer->compose('adStatus', ['model' => $model])
                    ->setTo($model->user->email)
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setSubject($subject)
                    ->send();
            } catch (\Exception $e) {
                Yii::error('Не удалось отправить уведомление: ' . $e->getMessage());
            }
        }
    }
}
