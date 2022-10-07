<?php

namespace vendor\meta_grid\helper;

use Yii;

/**
 * Everything about role management
 *
 * @author meta#grid (Patrick Schmitz)
 * @since 3.0
 * 
 */
class Rolemanagement
{
    private $flashMessages = "";
	public function registerControllerRole($controllerId, $withFlashmessage = false)
	{
		$this->flashMessages = "";
		$this->createRole("global-view", "Role", "May view all objectstypes", "isNotAGuest", null, null, $withFlashmessage);
		$this->createRole("global-create", "Role", "May create all objectstypes", "isNotAGuest", null, null, $withFlashmessage);
		$this->createRole("global-delete", "Role", "May delete all objectstypes", "isNotAGuest", null, null, $withFlashmessage);
		$newAuthorRole = $this->createRole("author", "Role", "May edit all objecttypes", "isNotAGuest", null, null, $withFlashmessage);		
		if (!is_null($newAuthorRole))
		{			
			Yii::$app->authManager->addChild($newAuthorRole, Yii::$app->authManager->getRole("global-view"));
			Yii::$app->authManager->addChild($newAuthorRole, Yii::$app->authManager->getRole("global-create"));
			Yii::$app->authManager->addChild($newAuthorRole, Yii::$app->authManager->getRole("global-delete"));
		}

		$newRoleName = 'view' ."-" . $controllerId;
		$this->createRole($newRoleName, "Perm", "May only view objecttype " . $controllerId, "isNotAGuest", "global-view", null, $withFlashmessage);
		
		$newRoleName = 'create' ."-" . $controllerId;
		$this->createRole($newRoleName, "Perm", "May only create objecttype " . $controllerId, "isNotAGuest", "global-create", null, $withFlashmessage);
		
		$newRoleName = 'delete' ."-" . $controllerId;
		$this->createRole($newRoleName, "Perm", "May only delete objecttype " . $controllerId, "isNotAGuest", "global-delete", null, $withFlashmessage);
		$this->createRole($newRoleName, "Perm", "May only delete objecttype " . Yii::$app->controller->id, "isNotAGuest", "global-delete", null, $withFlashmessage);
		if ($this->flashMessages !== "")
		{
			if ($withFlashmessage)
			{
				Yii::$app->session->setFlash('new_role_created', $this->flashMessages);
				$this->flashMessages = "";
			}
		}
	}

    private function createRole($newRoleOrPermName, $authType, $description, $ruleName, $childRole, $childPerm, $withFlashmessage = false)
    {
    	$auth = Yii::$app->authManager;
    	$checkRole = $auth->getRole($newRoleOrPermName);
    	$checkPerm = $auth->getPermission($newRoleOrPermName);
		$flashMessages = "";
    	if ((is_null($checkRole) && $authType==="Role") || (is_null($checkPerm) && $authType==="Perm"))
    	{
    		if ($authType==="Role")
    		{
    			$newAuthObj = $auth->createRole($newRoleOrPermName);
    		}
    		else 
    		{
    			if ($authType==="Perm")
    			{
    				$newAuthObj = $auth->createPermission($newRoleOrPermName);
    			}
    			else 
    			{
    				throw "No supported authType";
    			}
    		}

			if ($withFlashmessage)
			{
				$flashMessages .= Yii::t('app',"New {authType} created: {newRoleOrPermName}", ['authType' => $authType, 'newRoleOrPermName' => $newRoleOrPermName]) . "<br>";
				$this->flashMessages .= $flashMessages;
				Yii::warning(Yii::t('app',"New {authType} created: {newRoleOrPermName}", ['authType' => $authType, 'newRoleOrPermName' => $newRoleOrPermName]), 'New role created');
			}

			$newAuthObj->ruleName = $ruleName;
    		if (!is_null($description))
    		{
    			$newAuthObj->description = $description;
    		}
    	
    		$auth->add($newAuthObj);

    	    if (!is_null($childRole))
    		{	
    			$auth->addChild($auth->getRole($childRole), $newAuthObj);
    		}

    	    if (!is_null($childPerm))
    		{	
    			$auth->addChild($auth->getRole($childPerm), $newAuthObj);
    		}
    		return $newAuthObj;
    	}
    	return null; 
    }
}