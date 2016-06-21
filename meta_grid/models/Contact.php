<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact".
 */
class Contact extends \app\models\base\Contact
{
	// Patrick, 2016-01-16, Labels angepaßt
    public function attributeLabels()
    {
        return [
        	'fk_contact_group_id' => Yii::t('app', 'Contact Group'),
            'fk_client_id' => Yii::t('app', 'Client'),
            'email' => Yii::t('app', 'E-Mail'),
            'ldap_cn' => Yii::t('app', 'LDAP CN'),
			'clientName' => Yii::t('app', 'Client X'),
        ];
    }
    
    // http://www.yiiframework.com/wiki/621/filter-sort-by-calculated-related-fields-in-gridview-yii-2-0/
    /* Getter for client name */
    public function getClientName() {
//     	return "Test getClientName";
    	return $this->fkClient->name;
    }
}
