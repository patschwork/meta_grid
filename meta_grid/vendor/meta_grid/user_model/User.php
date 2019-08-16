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
}
