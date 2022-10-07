<?php
namespace app\commands;

use yii\console\Controller;

class MetagridController extends Controller
{
    /**
     * This method (re-)creates all permissions for all objecttypes
     */
    public function actionRegisterRoles()
    {
        $u = new \vendor\meta_grid\helper\Utils();
        $list_of_controllers = $u->eval_if_controller_has_method_registerControllerRole();
        $metagrid_role_management = new \vendor\meta_grid\helper\Rolemanagement();
        foreach($list_of_controllers as $controllerid=>$has_registerControllerRole)
        {
            if ($has_registerControllerRole)
            {
                $metagrid_role_management->registerControllerRole($controllerid);
            }
        }
    }
}
