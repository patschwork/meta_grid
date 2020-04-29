<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2> This the application version: <?= \vendor\meta_grid\helper\ApplicationVersion::getVersion() ?></h2>
    <p>
		a1682446effe from 2020-04-30
    </p>

</div>
