<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tool".
 */
class Tool extends \app\models\base\Tool
{
	public $_vendorNameVersion;
	
	public function setVendorNameVersion($value)
	{
		$this->_vendorNameVersion = (string) $value; 
	}
	
	public function getVendorNameVersion()
	{
		if ($this->_vendorNameVersion === null) {
            $this->setVendorNameVersion(
                $this->vendor . " " . $this->tool_name . " " . $this->version
            );
        }
		
		// return $this->_vendorNameVersion;
		return $this->vendor . " " . $this->tool_name . " " . $this->version;
	}
	
	public static function findBySql($sql, $params = [])
	{
		throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'Implementation deactivated.'));
		return null;
			
	}
}
