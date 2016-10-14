<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
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
            NavBar::begin([
                'brandLabel' => 'Meta#Grid'.(stristr(Yii::$app->homeUrl, 'dev') ? ' DEV' : ''),
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                    'style' => (stristr(Yii::$app->homeUrl, 'dev') ? 'background-color: darkblue;' : ''),
                ],
            ]);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => [
                    ['label' => 'Home', 'url' => ['/site/index']],
                    ['label' => 'Search', 'url' => ['mapobject2object/appglobalsearch','q'=>""]],                    
                	['label' => 'About', 'url' => ['/site/about']],
// 	                ['label' => 'Contact', 'url' => ['/site/contact']],                    
// 					    ['label' => '_Client', 'url' => ['/client']],
// 					    ['label' => '_Project', 'url' => ['/project']],
// 					    ['label' => '_Sourcesystem', 'url' => ['/sourcesystem']],
// 					    ['label' => '_Glossary', 'url' => ['/glossary']],
// 			            ['label' => '_Object Comment', 'url' => ['/objectcomment']],
// 			            ['label' => '_DB Database', 'url' => ['/dbdatabase']],
//                 		['label' => '_Map', 'url' => ['/mapobject2object']],

//                		Yii::$app->user->isGuest ?
//                        ['label' => 'Login', 'url' => ['/site/login']] :
//                        ['label' => 'Logout (' . Yii::$app->user->identity->username . ')',
//                            'url' => ['/site/logout'],
//                            'linkOptions' => ['data-method' => 'post']],
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
            <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
