<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tool_type".
 */
class ToolType extends \app\models\base\ToolType
{
	public static function findBySql($sql, $params = [])
	{
		throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Implementation deactivated.'));
		return null;
			
	}
}
