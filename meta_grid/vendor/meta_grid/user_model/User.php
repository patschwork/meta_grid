<?php

namespace vendor\meta_grid\user_model;

use Yii;
/**
 * {@inheritdoc}
 */
class User extends \Da\User\Model\User
{
    public function getPermProjectsCanSee()
    {
		$auth = Yii::$app->authManager;
		$userId = Yii::$app->User->Id;
		$userPerms = $auth->getPermissionsByUser($userId);
		$arrCanSee = array();
      	foreach($userPerms as $key => $perm){
			if ( (isset($perm->data['dataaccessfilter'])) && isset($perm->data['right']) )
			{
				if ($perm->data['dataaccessfilter'] === 'project' && $perm->data['right'] === "read")
				{	
					array_push($arrCanSee, $perm->data['id']);
				}
			}
       	}   
        return $arrCanSee;
	}
	     
	public function getPermProjectsCanSeeByUserId($userId)
    {
		$auth = Yii::$app->authManager;
		$userPerms = $auth->getPermissionsByUser($userId);
		$arrCanSee = array();
      	foreach($userPerms as $key => $perm){
			if ( (isset($perm->data['dataaccessfilter'])) && isset($perm->data['right']) )
			{
				if ($perm->data['dataaccessfilter'] === 'project' && $perm->data['right'] === "read")
				{	
					array_push($arrCanSee, $perm->data['id']);
				}
			}
       	}   
        return $arrCanSee;
    }     
	
	public function getPermProjectsCanEdit()
    {
		$auth = Yii::$app->authManager;
		$userId = Yii::$app->User->Id;
		$userPerms = $auth->getPermissionsByUser($userId);
		$arrCanEdit = array();
      	foreach($userPerms as $key => $perm){
			if ( (isset($perm->data['dataaccessfilter'])) && isset($perm->data['right']) )
			{
				if ($perm->data['dataaccessfilter'] === 'project' && $perm->data['right'] === "write")
				{	
					array_push($arrCanEdit, $perm->data['id']);
				}
			}
       	}   
        return $arrCanEdit;
	}
	
	public function getPermProjectsCanEditByUserId($userId)
	    {
		$auth = Yii::$app->authManager;
		$userPerms = $auth->getPermissionsByUser($userId);
		$arrCanEdit = array();
      	foreach($userPerms as $key => $perm){
			if ( (isset($perm->data['dataaccessfilter'])) && isset($perm->data['right']) )
			{
				if ($perm->data['dataaccessfilter'] === 'project' && $perm->data['right'] === "write")
				{	
					array_push($arrCanEdit, $perm->data['id']);
				}
			}
       	}   
        return $arrCanEdit;
    }    
	
	public function getPermClientsCanSee()
    {
		$auth = Yii::$app->authManager;
		$userId = Yii::$app->User->Id;
		$userPerms = $auth->getPermissionsByUser($userId);
		$arrCanSee = array();
      	foreach($userPerms as $key => $perm){
			if ( (isset($perm->data['dataaccessfilter'])) && isset($perm->data['right']) )
			{
				if ($perm->data['dataaccessfilter'] === 'client' && $perm->data['right'] === "read")
				{	
					array_push($arrCanSee, $perm->data['id']);
				}
			}
       	}   
        return $arrCanSee;
    }	
	
	public function getPermClientsCanEdit()
    {
		$auth = Yii::$app->authManager;
		$userId = Yii::$app->User->Id;
		$userPerms = $auth->getPermissionsByUser($userId);
		$arrCanEdit = array();
      	foreach($userPerms as $key => $perm){
			if ( (isset($perm->data['dataaccessfilter'])) && isset($perm->data['right']) )
			{
				if ($perm->data['dataaccessfilter'] === 'client' && $perm->data['right'] === "write")
				{	
					array_push($arrCanEdit, $perm->data['id']);
				}
			}
       	}   
        return $arrCanEdit;
	}

	/**
	 * Description of function getPermObjectTypesCanViewByUserId
	 *
	 * @author meta#grid (Patrick Schmitz)
	 * @since 3.0
	 * 
	 * @param $user_id: If not given or NULL on call, then the current user will be used. If user is of status "guest" an empty array is returned
	 * 
	 * @return array(key = autoincrement, value = Permission (without prefix "view-"))
	 */
	public function getPermObjectTypesCanViewByUserId($user_id = NULL)
	{
		$prefix = "view-";
		$strip_prefix_on_result = true; // remove prefix in result array e.g. view-keyfigure to keyfigure

		$auth = Yii::$app->authManager;
		$user_id = $user_id == NULL ? Yii::$app->user->id : $user_id;
		$userPerms = $auth->getPermissionsByUser($user_id);
		$arrCanView = array();
		foreach($userPerms as $key => $perm)
		{
			if (substr($perm->name,0,strlen($prefix)) === $prefix)
			{
				if ($strip_prefix_on_result) {array_push($arrCanView, substr($perm->name,strlen($prefix),strlen($perm->name)));}
				else {array_push($arrCanView, $perm->name);}
			}
		} 
		return $arrCanView;
	}

	// { ... phabricator-task: T80
	public function rules()
	{
		$rules = parent::rules();
		unset($rules['twoFactorEnabledNumber']);
		return $rules; 
	}
	// ...}
}
