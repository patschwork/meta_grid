<?php

namespace vendor\meta_grid\helper;

// use vendor\meta_grid\helper\Configs;

use Yii;
use yii\web\User;

/**
 * Description of Helper
 *
 * @author meta#grid (Patrick Schmitz)
 * @since 1.5
 * 
 * Idea taken from https://github.com/mdmsoft/yii2-admin/blob/master/components/Helper.php.
 */
class RBACHelper
{
//     private static $_userRoutes = [];
//     private static $_defaultRoutes;
//     private static $_routes;
//     public static function getRegisteredRoutes()
//     {
//         if (self::$_routes === null) {
//             self::$_routes = [];
//             // $manager = Configs::authManager();
//             $manager = Yii::$app->authManager;
//             foreach ($manager->getPermissions() as $item) {
//                 if ($item->name[0] === '/') {
//                     self::$_routes[$item->name] = $item->name;
//                 }
//             }
//         }
//         return self::$_routes;
//     }

//    /**
//      * Get assigned routes by default roles
//      * @return array
//      */
//     protected static function getDefaultRoutes()
//     {
//         if (self::$_defaultRoutes === null) {
//             // $manager = Configs::authManager();
//             $manager = Yii::$app->authManager;
//             $roles = $manager->defaultRoles;
//             $cache = Configs::cache();
//             if ($cache && ($routes = $cache->get($roles)) !== false) {
//                 self::$_defaultRoutes = $routes;
//             } else {
//                 $permissions = self::$_defaultRoutes = [];
//                 foreach ($roles as $role) {
//                     $permissions = array_merge($permissions, $manager->getPermissionsByRole($role));
//                 }
//                 foreach ($permissions as $item) {
//                     if ($item->name[0] === '/') {
//                         self::$_defaultRoutes[$item->name] = true;
//                     }
//                 }
//                 if ($cache) {
//                     $cache->set($roles, self::$_defaultRoutes, Configs::cacheDuration(), new TagDependency([
//                         'tags' => Configs::CACHE_TAG,
//                     ]));
//                 }
//             }
//         }
//         return self::$_defaultRoutes;
//     }
    
//      /**
//      * Get assigned routes of user.
//      * @param integer $userId
//      * @return array
//      */
//     public static function getRoutesByUser($userId)
//     {
//         if (!isset(self::$_userRoutes[$userId])) {
//             $cache = Configs::cache();
//             // $cache = FALSE;
//             if ($cache && ($routes = $cache->get([__METHOD__, $userId])) !== false) {
//                 self::$_userRoutes[$userId] = $routes;
//             } else {
//                 $routes = static::getDefaultRoutes();
//                 // $manager = Configs::authManager();
//                 $manager = Yii::$app->authManager;
//                 foreach ($manager->getPermissionsByUser($userId) as $item) {
//                     if ($item->name[0] === '/') {
//                         $routes[$item->name] = true;
//                     }
//                 }
//                 self::$_userRoutes[$userId] = $routes;
//                 if ($cache) {
//                     $cache->set([__METHOD__, $userId], $routes, Configs::cacheDuration(), new TagDependency([
//                         'tags' => Configs::CACHE_TAG,
//                     ]));
//                 }
//             }
//         }
//         return self::$_userRoutes[$userId];
//     }


//     /**
//      * Check access route for user.
//      * @param string|array $route
//      * @param integer|User $user
//      * @return boolean
//      */
//     public static function checkRoute($route, $params = [], $user = null)
//     {
//         $config = Configs::instance();
//         $r = static::normalizeRoute($route, $config->advanced);
//         if ($config->onlyRegisteredRoute && !isset(static::getRegisteredRoutes()[$r])) {
//             return true;
//         }

//         if ($user === null) {
//             $user = Yii::$app->getUser();
//         }
//         $userId = $user instanceof User ? $user->getId() : $user;

//         if ($config->strict) {
//             if ($user->can($r, $params)) {
//                 return true;
//             }
//             while (($pos = strrpos($r, '/')) > 0) {
//                 $r = substr($r, 0, $pos);
//                 if ($user->can($r . '/*', $params)) {
//                     return true;
//                 }
//             }
//             return $user->can('/*', $params);
//         } else {
//             $routes = static::getRoutesByUser($userId);
//             if (isset($routes[$r])) {
//                 return true;
//             }
//             while (($pos = strrpos($r, '/')) > 0) {
//                 $r = substr($r, 0, $pos);
//                 if (isset($routes[$r . '/*'])) {
//                     return true;
//                 }
//             }
//             return isset($routes['/*']);
//         }
//     }

//     /**
//      * Normalize route
//      * @param  string  $route    Plain route string
//      * @param  boolean|array $advanced Array containing the advanced configuration. Defaults to false.
//      * @return string            Normalized route string
//      */
//     protected static function normalizeRoute($route, $advanced = false)
//     {
//         if ($route === '') {
//             $normalized = '/' . Yii::$app->controller->getRoute();
//         } elseif (strncmp($route, '/', 1) === 0) {
//             $normalized = $route;
//         } elseif (strpos($route, '/') === false) {
//             $normalized = '/' . Yii::$app->controller->getUniqueId() . '/' . $route;
//         } elseif (($mid = Yii::$app->controller->module->getUniqueId()) !== '') {
//             $normalized = '/' . $mid . '/' . $route;
//         } else {
//             $normalized = '/' . $route;
//         }
//         // Prefix @app-id to route.
//         if ($advanced) {
//             $normalized = Route::PREFIX_ADVANCED . Yii::$app->id . $normalized;
//         }
//         return $normalized;
//     }

//    /**
//      * Filter menu items
//      * @param array $items
//      * @param integer|User $user
//      */
//     public static function filter($items, $user = null)
//     {
//         if ($user === null) {
//             $user = Yii::$app->getUser();
//         }
//         return static::filterRecursive($items, $user);
//     }

//     /**
//      * Filter menu recursive
//      * @param array $items
//      * @param integer|User $user
//      * @return array
//      */
//     protected static function filterRecursive($items, $user)
//     {
//         $result = [];
//         foreach ($items as $i => $item) {
//             $url = ArrayHelper::getValue($item, 'url', '#');
//             $allow = is_array($url) ? static::checkRoute($url[0], array_slice($url, 1), $user) : true;
//             if (isset($item['items']) && is_array($item['items'])) {
//                 $subItems = self::filterRecursive($item['items'], $user);
//                 if (count($subItems)) {
//                     $allow = true;
//                 }
//                 $item['items'] = $subItems;
//             }
//             if ($allow && !($url == '#' && empty($item['items']))) {
//                 $result[$i] = $item;
//             }
//         }
//         return $result;
//     }




//     /**
//      * Filter action column button. Use with [[yii\grid\GridView]]
//      * ```php
//      * 'columns' => [
//      *     ...
//      *     [
//      *         'class' => 'yii\grid\ActionColumn',
//      *         'template' => Helper::filterActionColumn(['view','update','activate'])
//      *     ]
//      * ],
//      * ```
//      * @param array|string $buttons
//      * @param integer|User $user
//      * @return string
//      */
//     public static function filterActionColumn($buttons = [], $user = null)
//     {
//         if (is_array($buttons)) {
//             $result = [];
//             foreach ($buttons as $button) {
//                 if (static::checkRoute($button, [], $user)) {
//                     $result[] = "{{$button}}";
//                 }
//             }
//             return implode(' ', $result);
//         }
//         return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($user) {
//             return static::checkRoute($matches[1], [], $user) ? "{{$matches[1]}}" : '';
//         }, $buttons);
//     }

    /**
     * Filter action column button. Use with [[yii\grid\GridView]]
     * ```php
     * 'columns' => [
     *     ...
     *     [
     *         'class' => 'yii\grid\ActionColumn',
     *         'template' => Helper::filterActionColumn(['view','update','activate'])
     *     ]
     * ],
     * ```
     * @param array|string $buttons
     * @param integer|User $user
     * @return string
     */    
    public static function filterActionColumn_meta_grid($buttons = [], $user = null)
    {
        if (Yii::$app->user->identity->isAdmin) {
            return $buttons;   
        }

        if(!Yii::$app->User->can('delete-' . Yii::$app->controller->id)) {
            $buttons = str_replace('{delete}','',$buttons);
        }
        if(!Yii::$app->User->can('create-' . Yii::$app->controller->id)) {
            $buttons = str_replace('{update}','',$buttons);
            $buttons = str_replace('{update-dbtablefield-individual}','',$buttons);
        }
        if(!Yii::$app->User->can('view-documentation')) {
            $buttons = str_replace('{documentation}','',$buttons);
        }

        
        return $buttons;
    }

    private static function abstractMayEditDeleteCheckTag($fk_user_id, $fk_project_id, $case)
    {
        $returnValue = false;
        if ($fk_user_id !== null)
        {
            if ($fk_user_id === Yii::$app->user->id)
            {
                $returnValue = true;
                return $returnValue; // Personal tag
            }
        }
        if(Yii::$app->User->can($case . '-' . "tag") || Yii::$app->user->identity->isAdmin)
        {
            if ($fk_user_id === null && $fk_project_id === null)
            {
                $returnValue = true;
                return $returnValue; // Global tag allowed           
            }
            if (in_array($fk_project_id, Yii::$app->User->identity->permProjectsCanEdit))
            {
                $returnValue = true;
                return $returnValue; // Project tag allowed                 
            }
        }
        return $returnValue;
    }

    public function mayEditTag($fk_user_id, $fk_project_id)
    {
        return static::abstractMayEditDeleteCheckTag($fk_user_id, $fk_project_id, 'create');
    }    
    
    public function mayDeleteTag($fk_user_id, $fk_project_id)
    {
        return static::abstractMayEditDeleteCheckTag($fk_user_id, $fk_project_id, 'delete');
    }

    public function matrixRoleTag($fk_user_id)
    {
        $matrix = array();

        $matrix["index"]["fk_user_id"][0]=$fk_user_id;
        $matrix["create_or_edit"]["fk_user_id"][0]=$fk_user_id;
        $matrix["delete"]["fk_user_id"][0]=$fk_user_id;
        
        $auth = Yii::$app->authManager;

        if ($auth->checkAccess($fk_user_id,"view" . '-' . "tag"))
        {
            $matrix["index"]["GLOBAL"][0]=1;
            foreach(Yii::$app->User->identity->getPermProjectsCanSeeByUserId($fk_user_id) as $key=>$fk_project_id)
            {
                $matrix["index"]["fk_project_id"][$key]=$fk_project_id;
            }
        }
        
        if ($auth->checkAccess($fk_user_id,"create" . '-' . "tag"))
        {
            $matrix["create_or_edit"]["GLOBAL"][0]=1;
            foreach(Yii::$app->User->identity->getPermProjectsCanEditByUserId($fk_user_id) as $key=>$fk_project_id)
            {
                $matrix["create_or_edit"]["fk_project_id"][$key]=$fk_project_id;
            }
        }

        if ($auth->checkAccess($fk_user_id,"delete" . '-' . "tag"))
        {
            $matrix["delete"]["GLOBAL"][0]=1;
            foreach(Yii::$app->User->identity->getPermProjectsCanEditByUserId($fk_user_id) as $key=>$fk_project_id)
            {
                $matrix["delete"]["fk_project_id"][$key]=$fk_project_id;
            }
        }
        return $matrix;
    }
}