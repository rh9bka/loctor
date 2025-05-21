<?php

namespace api\modules\v1\controllers;

use api\controllers\BaseController;
use common\models\User;
use common\models\Sms;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class AuthController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        return $behaviors;
    }

    public function actionRegister()
    {
        $params = Yii::$app->request->getBodyParams();
        
        // Проверяем обязательные поля
        if (empty($params['phone']) || empty($params['password']) || empty($params['password_confirm'])) {
            throw new BadRequestHttpException('Необходимо указать телефон, пароль и подтверждение пароля');
        }

        // Проверяем совпадение паролей
        if ($params['password'] !== $params['password_confirm']) {
            throw new BadRequestHttpException('Пароли не совпадают');
        }

        // Проверяем существование пользователя
        if (User::findByPhone($params['phone'])) {
            throw new BadRequestHttpException('Пользователь с таким телефоном уже существует');
        }

        // Создаем пользователя
        $user = new User();
        $user->phone = $params['phone'];
        $user->fname = $params['fname'] ?? null;
        $user->lname = $params['lname'] ?? null;
        $user->setPassword($params['password']);
        $user->generateAuthKey();
        $user->status = User::STATUS_INACTIVE;

        if (!$user->save()) {
            Yii::error('Ошибка при создании пользователя: ' . print_r($user->errors, true));
            throw new ServerErrorHttpException('Не удалось создать пользователя');
        }

        // Отправляем SMS
        $code = rand(100000, 999999);
        $sms = new Sms();
        $sms->user_id = $user->id;
        $sms->type = Sms::TYPE_REGISTRATION;
        $sms->msg = (string)$code;
        $sms->phone = $user->phone;
        $sms->expired_at = time() + 3600; // Код действителен 1 час

        if (!$sms->save()) {
            $firstError = is_array($sms->errors) ? reset($sms->errors) : null;
            $firstErrorMsg = is_array($firstError) ? reset($firstError) : 'Неизвестная ошибка';
            throw new ServerErrorHttpException('Не удалось создать SMS: ' . $firstErrorMsg);
        }

        // TODO: Отправка SMS через сервис
        // Yii::$app->sms->send($user->phone, "Ваш код подтверждения: {$code}");

        return [
            'success' => true,
            'message' => 'Код подтверждения отправлен на ваш телефон',
            'user_id' => $user->id,
        ];
    }

    public function actionVerify()
    {
        $params = Yii::$app->request->getBodyParams();
        
        // Создаем модель для валидации
        $model = new \yii\base\DynamicModel(['user_id', 'code']);
        $model->addRule(['user_id', 'code'], 'required')
            ->addRule(['user_id'], 'integer', ['min' => 1])
            ->addRule(['code'], 'string', ['min' => 6, 'max' => 6])
            ->addRule(['code'], 'match', ['pattern' => '/^[0-9]{6}$/'])
            ->addRule(['user_id'], 'exist', [
                'targetClass' => User::class,
                'targetAttribute' => 'id',
                'message' => 'Пользователь не найден'
            ]);

        // Загружаем данные
        if (!$model->load($params, '') || !$model->validate()) {
            throw new BadRequestHttpException(json_encode($model->errors));
        }

        $user = User::findOne($model->user_id);
        if (!$user) {
            throw new NotFoundHttpException('Пользователь не найден');
        }

        // Проверяем, не активирован ли уже пользователь
        if ($user->status === User::STATUS_ACTIVE) {
            throw new BadRequestHttpException('Пользователь уже активирован');
        }

        // Ищем последнюю SMS для регистрации
        $sms = Sms::find()
            ->where([
                'user_id' => $user->id,
                'type' => Sms::TYPE_REGISTRATION,
                'msg' => $model->code,
                'status' => Sms::STATUS_PENDING,
            ])
            ->andWhere(['>', 'expired_at', time()]) // Проверяем срок действия
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if (!$sms) {
            throw new BadRequestHttpException('Неверный или истекший код подтверждения');
        }

        // Проверяем количество попыток ввода кода
        $attempts = Sms::find()
            ->where([
                'user_id' => $user->id,
                'type' => Sms::TYPE_REGISTRATION,
                'status' => Sms::STATUS_PENDING,
            ])
            ->andWhere(['>', 'created_at', time() - 3600]) // За последний час
            ->count();

        if ($attempts > 5) {
            throw new BadRequestHttpException('Превышено количество попыток. Попробуйте позже');
        }

        // Активируем пользователя
        $user->status = User::STATUS_ACTIVE;
        if (!$user->save()) {
            throw new ServerErrorHttpException('Не удалось активировать пользователя');
        }

        // Отмечаем SMS как использованную
        $sms->verify();

        // Генерируем новый токен авторизации
        $user->generateAuthKey();
        $user->save();

        return [
            'success' => true,
            'message' => 'Аккаунт успешно активирован',
            'token' => $user->auth_key,
        ];
    }

    public function actionLogin()
    {
        $params = Yii::$app->request->getBodyParams();
        
        if (empty($params['phone']) || empty($params['password'])) {
            throw new BadRequestHttpException('Необходимо указать телефон и пароль');
        }

        $user = User::findByPhone($params['phone']);
        if (!$user || !$user->validatePassword($params['password'])) {
            throw new BadRequestHttpException('Неверный телефон или пароль');
        }

        if ($user->status !== User::STATUS_ACTIVE) {
            throw new BadRequestHttpException('Аккаунт не активирован');
        }

        return [
            'success' => true,
            'token' => $user->auth_key,
            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,
                'fname' => $user->fname,
                'lname' => $user->lname,
            ],
        ];
    }
} 