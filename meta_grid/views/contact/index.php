<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Contacts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
    'modelClass' => 'Contact',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
             'label' => 'Contact Group',
             'value' => function($model) {
             		return $model->fk_contact_group_id == "" ? $model->fk_contact_group_id : $model->fkContactGroup->name;
             		},
            ],
            [
             'label' => 'Client',
             'value' => function($model) {
             		return $model->fk_client_id == "" ? $model->fk_client_id : $model->fkClient->name;
             		},
            ],
            'clientName',
            'givenname:ntext',
            'surname:ntext',
            'email:email',
            // 'phone:ntext',
            // 'mobile:ntext',
            // 'ldap_cn:ntext',
            // 'description:html',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
