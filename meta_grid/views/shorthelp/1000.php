
<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Shorthelp');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="shorthelp-index">
	<h1>SQLite database connection settings for foreign key support</h1>
	<div>
	This system is configured to use SQLite as database backend engine.<br>
	SQLite is able to make use of foreign contraints to ensure data consistency (<a href="https://www.sqlite.org/foreignkeys.html">SQLite Foreign Key Support</a>).<br>
	<br>
	Per default SQLite doesn't use this setting. Therefore is has to be configured by <?= Yii::$app->name ?>.<br>
	<br>
	Please make sure your connection contains the <span style="background-color: #FFFF00;" >highlighted</span> section as in the example below:
	<pre>
return [
<span>	'class' => 'yii\db\Connection',</span>
<span>	'dsn' => 'sqlite:../../../../dwh_meta.sqlite',	</span>
<span>	'charset' => 'utf8'<span style="background-color: #FFFF00;" >,</span></span>
		<span style="background-color: #FFFF00;" >'on afterOpen' => function($event) { </span>
			<span style="background-color: #FFFF00;" >$event->sender->createCommand("PRAGMA foreign_keys = ON")->execute(); </span>
		<span style="background-color: #FFFF00;" >},</span>
];
</pre>

The connection parameter is located in <pre>config/db.php</pre>

<i>The path to the SQLite database file may look different depending on the OS. The example above shows this for an *nix OS.</i>
	</div>
</div>
