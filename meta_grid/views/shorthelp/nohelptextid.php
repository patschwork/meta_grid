
<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Shorthelp');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="shorthelp-index">

<?php
	echo yii\bootstrap\Alert::widget([
					'options' => [
							'class' => 'alert-danger',
					],
					'body' => "The Helptext-ID $helptextid is not avaiable!",
					'closeButton' => false,
				]);   
?>
</div>
