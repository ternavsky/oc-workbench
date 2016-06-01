<?php namespace Fencus\HierarchyRelation;

use Backend;
use System\Classes\PluginBase;

/**
 * HierarchyRelation Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'fencus.hierarchyrelation::lang.plugin.name',
            'description' => 'fencus.hierarchyrelation::lang.plugin.description',
            'author'      => 'Elias M. Mariani',
            'icon'        => 'icon-list-alt',
        	'homepage'    => 'http://www.fencus.com.ar'
        ];
    }

    public function registerComponents()
    {
        return [];
    }

    public function registerPermissions()
    {
        return [];
    }

    public function registerNavigation()
    {
        return [];
    }
    
    public function registerFormWidgets()
    {
    	return [
    			'Fencus\HierarchyRelation\FormWidgets\HierarchyRelation' => [
    					'label' => 'Hierarchy Relation',
    					'code'  => 'hierarchy-relation'
    			]
    	];
    }

}
