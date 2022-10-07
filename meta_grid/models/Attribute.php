<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attribute".
 */
class Attribute extends \app\models\base\Attribute
{
	// Erzeugt einen Link fuer die Referenz in der Formel sofern vorhanden
	public function getFormulaWithLinks()
	{
		if (strstr($this->formula, '##@', true) == "")
			return $this->formula;
		 
		$f_new = array();
		 
		$f = explode("##@",$this->formula);
		foreach($f as $key => $value)
		{
			if (strstr($value, '@##', true) != "")
			{
				try {
					$e = explode('@##',$value);
				 
					$objectdisplay = explode("|",$e[0])[0];
					$linkview = explode(" - ",$objectdisplay)[0];
					$linktooltip = explode(" - ",$objectdisplay)[1];
					$objecttype = explode("|",$e[0])[1];
					$objectid = explode(";",explode("|",$e[0])[2])[0];
		
					$link = Yii::$app->urlManager->createUrl ( str_replace("_","",$objecttype) )."/"."view&id=" . $objectid;
		
					$f_new[$key] = $link.'" target=\'_blank\' title="'.$linktooltip.'">'.$linkview.'</a>'.$e[1];
				} catch (\Throwable $th) {
					return $this->formula;
				}
			}
			else
			{
				$f_new[$key] = $value;
			}
		}
		return implode('<a href="',$f_new);
	}
	
	public static function findOne($condition)
	{
		$model=static::findByCondition($condition)->one();
		if ( (isset($model)) || (Yii::$app->User->identity->isAdmin))
		{
			return $model;
		}
		else
		{
			throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'You have no permission for this data.'));
			return null;
		}
	}
	
	public static function find()
	{
		$permProjectsCanSee = Yii::$app->User->identity->permProjectsCanSee;
	
		$obj=Yii::createObject(yii\db\ActiveQuery::className(), [get_called_class()]);
		if (!Yii::$app->User->identity->isAdmin)
		{
			$obj->Where([
					'in','fk_project_id', $permProjectsCanSee
			]);
		}
		return $obj;
	}
	
	public static function findBySql($sql, $params = [])
	{
		throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Implementation deactivated.'));
		return null;
		 
	}	
}
