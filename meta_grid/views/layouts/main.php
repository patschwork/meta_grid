<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\assets\ShortcutAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
ShortcutAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            $isAdmin = FALSE;
            if (isset(Yii::$app->user->identity->isAdmin))
            {
                if (Yii::$app->user->identity->isAdmin) $isAdmin = TRUE;
            }

            $app_config_web_app_header_bckcolor = \vendor\meta_grid\helper\Utils::get_app_config("web_app_header_bckcolor");
            $app_config_web_app_header_brandlabel_additional_text = \vendor\meta_grid\helper\Utils::get_app_config("web_app_header_brandlabel_additional_text");

            NavBar::begin([
                // 'brandLabel' => 'Meta#Grid'.(stristr(Yii::$app->homeUrl, 'dev') ? ' DEV' : ''),
                'brandLabel' => 'Meta#Grid'.' '.$app_config_web_app_header_brandlabel_additional_text,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                    // 'style' => (stristr(Yii::$app->homeUrl, 'dev') ? 'background-color: darkblue;' : ''),
                    'style' => 'background-color: '.$app_config_web_app_header_bckcolor.';',
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'encodeLabels' => false,
                'items' => [
                    ['label' => 'Home', 'url' => ['/site/index']],                    
                		
	                    (Yii::$app->User->can('view-global-search') || ($isAdmin)) ? ['label' => Yii::t('general', 'Search'), 'url' => ['/global-search']] : "",                    
                		[
                		'label' => Yii::t('general', 'Perspective'),
                		'items' => vendor\meta_grid\helper\PerspectiveHelper::getReadyToUseNavDropDownElements()
                		],
                		
						[	'label' => Yii::t('general', 'Settings'), 
							// 'url' => ['/user/registration/register'], 
							'visible' => (!Yii::$app->user->isGuest && Yii::$app->user->identity->isAdmin),
							'items' => 
							[
								['url' => ['/user/admin/index'], 
											'label' => Yii::t('general','Displays user management interface'), 'visible' => True],
								['url' => ['/client/createclientpermissions'], 
											'label' => Yii::t('general','Rebuild Client Permissions'), 'visible' => True],
								['url' => ['/project/createprojectpermissions'], 
											'label' => Yii::t('general','Rebuild Project Permissions'), 'visible' => True],
								['label' => Yii::t('general','Translate'), 'url' => ['/translatemanager']],
								['label' => Yii::t('general','Gii - Yii2 Code Generator'), 'url' => ['/gii']],
																					  
							]
						],

                		['label' => Yii::t('general','About'), 'url' => ['/site/about']],
                		
                		Yii::$app->user->isGuest ?
                		['label' => Yii::t('general','Sign in'), 'url' => ['/user/security/login']] :
                		['label' => Yii::t('general','Sign out').' (' . Yii::$app->user->identity->username . ')',
                		'url' => ['/user/security/logout'],
                		'linkOptions' => ['data-method' => 'post']],
                		['label' => Yii::t('general','Register'), 'url' => ['/user/registration/register'], 'visible' => Yii::$app->user->isGuest],                		
                ],
            ]);

            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; <?= \vendor\meta_grid\helper\ApplicationVersion::getApplicationName() . " " . date('Y') ?></p>
            <p class="pull-right"><?= "v" . \vendor\meta_grid\helper\ApplicationVersion::getVersion() ?></p>

        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>