<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contact_group".
 */
class ContactGroup extends \app\models\base\ContactGroup
{   
	public function attributeLabels()
    {
        return [
            'fk_client_id' => Yii::t('app', 'Client'),
        ];
    }
}
