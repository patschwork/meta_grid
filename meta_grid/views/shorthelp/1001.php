
<?php

use yii\helpers\Html;


$this->title = Yii::t('app', 'Shorthelp');
$this->params['breadcrumbs'][] = Yii::t('app', $this->title);
?>
<div class="shorthelp-index">
	<h1>Bower path has changed</h1>
	<div>
	Starting with meta#grid Release v2.4.1 the bower asset path has to be reconfigured.<br>
	If not changed, all elements with the use of the feature Float-Thead, will throw an error!<br>
	<br>
	Please make sure your config contains the <span style="background-color: #FFFF00;" >highlighted</span> section as in the example below:
	<pre>
	$params = require(__DIR__ . '/params.php');

<span>	$config = [</span>
<span style="background-color: #FFFF00;" >		'aliases' => [</span>
<span style="background-color: #FFFF00;" >			'@bower' => '@vendor/bower-asset',</span>
<span style="background-color: #FFFF00;" >			'@npm'   => '@vendor/npm-asset',</span>
<span style="background-color: #FFFF00;" >		],</span>
<span>	'id' => 'basic',</span>
<span>	'name' => 'meta#grid',</span>
</pre>

The connection parameter is located in <pre>config/web.php</pre>

	</div>

Additional reference can be found here: <a href="https://stackoverflow.com/questions/53116822/composer-yii2-bower-the-file-or-directory-to-be-published-does-not-exist-c-my">StackOverflow</a>.

</div>
