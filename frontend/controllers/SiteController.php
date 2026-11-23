<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\LoginForm;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use frontend\models\VerifyResetCodeForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\bootstrap5\ActiveForm;
use yii\captcha\CaptchaAction;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\mail\MailerInterface;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function __construct(
        $id,
        $module,
        private readonly MailerInterface $mailer,
        $config = [],
    ) {
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
                    'resend-reset-code' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return string|Response
     */
    public function actionLogin(): string|Response
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
     * Logs out the current user.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string|Response
     */
    public function actionContact(): string|Response
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $sent = $model->sendEmail(
                $this->mailer,
                Yii::$app->params['adminEmail'],
                Yii::$app->params['senderEmail'],
                Yii::$app->params['senderName'],
            );

            if ($sent) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

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
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return string|Response
     */
    public function actionSignup(): string|Response
    {
        $model = new SignupForm();

        // Validación en tiempo real (AJAX) para correo y usuario duplicado
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            return $this->asJson(ActiveForm::validate($model));
        }

        $signed = $model->load(Yii::$app->request->post()) && $model->signup(
            $this->mailer,
            Yii::$app->params['supportEmail'],
            Yii::$app->name,
        );

        if ($signed) {
            Yii::$app->session->setFlash('success', 'Tu cuenta ha sido creada exitosamente.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Solicita el restablecimiento de contraseña enviando una clave de 6 dígitos al correo.
     *
     * @return string|Response
     */
    public function actionRequestPasswordReset(): string|Response
    {
        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $sent = $model->sendEmail(
                $this->mailer,
                Yii::$app->params['supportEmail'],
                Yii::$app->name,
            );

            if ($sent) {
                Yii::$app->session->set('reset_password_email', $model->email);
                Yii::$app->session->set('reset_code_last_sent', time());
                Yii::$app->session->setFlash('success', 'Hemos enviado una clave de 6 dígitos a tu correo electrónico (válida por 30 minutos).');
                return $this->redirect(['site/verify-reset-code']);
            }

            Yii::$app->session->setFlash('error', 'No se pudo enviar la clave al correo proporcionado.');
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Verifica la clave de 6 dígitos enviada al correo.
     *
     * @return string|Response
     */
    public function actionVerifyResetCode(): string|Response
    {
        $email = Yii::$app->session->get('reset_password_email');
        if (!$email) {
            return $this->redirect(['site/request-password-reset']);
        }

        $model = new VerifyResetCodeForm();
        $model->email = (string) $email;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            return $this->redirect(['site/reset-password', 'token' => $model->getToken()]);
        }

        // Calcular segundos restantes para poder reenviar (bloqueo de 30s)
        $lastSent = (int) Yii::$app->session->get('reset_code_last_sent', 0);
        $remainingSeconds = max(0, 30 - (time() - $lastSent));

        return $this->render('verifyResetCode', [
            'model' => $model,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    /**
     * Reenvía una nueva clave de 6 dígitos respetando la espera de 30 segundos.
     *
     * @return Response
     */
    public function actionResendResetCode(): Response
    {
        $email = Yii::$app->session->get('reset_password_email');
        if (!$email) {
            return $this->redirect(['site/request-password-reset']);
        }

        $lastSent = (int) Yii::$app->session->get('reset_code_last_sent', 0);
        $elapsed = time() - $lastSent;

        if ($elapsed < 30) {
            $faltan = 30 - $elapsed;
            Yii::$app->session->setFlash('warning', "Debes esperar {$faltan} segundos antes de solicitar un nuevo código.");
            return $this->redirect(['site/verify-reset-code']);
        }

        $model = new PasswordResetRequestForm();
        $model->email = (string) $email;

        if ($model->validate() && $model->sendEmail(
            $this->mailer,
            Yii::$app->params['supportEmail'],
            Yii::$app->name,
        )) {
            Yii::$app->session->set('reset_code_last_sent', time());
            Yii::$app->session->setFlash('success', 'Se ha enviado un nuevo código de 6 dígitos a tu correo (válido por 30 minutos).');
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo reenviar el código. Inténtalo nuevamente.');
        }

        return $this->redirect(['site/verify-reset-code']);
    }

    /**
     * Actualiza la contraseña con validación de confirmación.
     *
     * @param string $token
     * @return string|Response
     * @throws BadRequestHttpException
     */
    public function actionResetPassword(string $token): string|Response
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->remove('reset_password_email');
            Yii::$app->session->remove('reset_code_last_sent');
            Yii::$app->session->setFlash('success', 'Tu contraseña ha sido actualizada correctamente. Ya puedes iniciar sesión.');
            return $this->redirect(['site/login']);
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @return Response
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail(string $token): Response
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->verifyEmail()) {
            Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
            return $this->goHome();
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return string|Response
     */
    public function actionResendVerificationEmail(): string|Response
    {
        $model = new ResendVerificationEmailForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $sent = $model->sendEmail(
                $this->mailer,
                Yii::$app->params['supportEmail'],
                Yii::$app->name,
            );

            if ($sent) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model,
        ]);
    }
}