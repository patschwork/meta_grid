<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
$ApplicationVersion = new \vendor\meta_grid\helper\ApplicationVersion();
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2>This the application version: <?= $ApplicationVersion->getVersion() ?></h2>
    <?php if (!Yii::$app->user->isGuest): ?>
    <i>This information is only available for user which have a login:</i>
    <h4>Yii2 framework version: <?= Yii::getVersion() ?></h4>
    <h4>PHP version: <?= PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION.'.'.PHP_RELEASE_VERSION ?></h4>
    <?php endif ?> 
</div>
