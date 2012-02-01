<?php

class Zwe_Acl extends Zend_Acl
{
    const USER_ROLE = '__user__';

    /**
     * @var Zwe_Acl
     */
    protected static $_instance = null;

    public static function create($force = false)
    {
        if((static::$_instance = Zend_Registry::get('Zend_Cache')->load('acl')) === false || $force) {
            static::$_instance = new static();

            static::_addRoles();
            static::_addResources();
            static::_addPermissions();

            Zend_Registry::get('Zend_Cache')->save(static::$_instance, 'acl');
        }
    }

    public static function getClone()
    {
        if(!isset(static::$_instance)) {
            static::create();
        }

        return clone static::$_instance;
    }

    protected static function _addRoles($IDParent = 0)
    {
        $groups = Zwe_Model_Group::findByIDParent($IDParent);

        if($groups) {
            foreach ($groups as $group) {
                static::$_instance->addRole(new Zend_Acl_Role($group->Name), $IDParent == 0 ? null : $group->findParentRow('Zwe_Model_Group')->Name);
                static::_addRoles($group->IDGroup);
            }
        }
    }

    protected static function _addResources($IDParent = 0, $prefix = '')
    {
        $resources = Zwe_Model_Resource::findByIDParent($IDParent);

        if($resources) {
            foreach ($resources as $resource) {
                $name = ($IDParent == 0 ? '' : ($prefix . '_')) . $resource->Name;
                static::$_instance->addResource(new Zend_Acl_Resource($name), $IDParent == 0 ? null : $prefix);
                static::_addResources($resource->IDResource, $name);
            }
        }
    }

    protected static function _addPermissions()
    {
        $permissions = Zwe_Model_Resource_Group::getInstance()->fetchAll();

        if($permissions) {
            foreach ($permissions as $permission) {
                $whatToDo = $permission->Deny == 1 ? 'deny' : 'allow';
                static::$_instance->$whatToDo($permission->findParentRow('Zwe_Model_Group')->Name, $permission->findParentRow('Zwe_Model_Resource')->FullName);
            }
        }
    }
}