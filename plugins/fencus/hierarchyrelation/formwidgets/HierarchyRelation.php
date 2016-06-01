<?php namespace Fencus\HierarchyRelation\formwidgets;

use Backend\Classes\FormWidgetBase;
use ApplicationException;
use Lang;

class HierarchyRelation extends FormWidgetBase
{
	public $allowChilds = true;
	public $allowNull = true;
	public $showSelf = true;
	public $nullString = '--None--';
	public $nameFrom = 'name';
	public $relationUp = 'parent';
	public $relationDown = 'childs';
	public $separator = '-';
	public $longFormat = false;
	
	public $optionsList;
	public $distance;
	
	protected $defaultAlias = 'hierarchy-relation';
	
    public function widgetDetails()
    {
        return [
            'name'        => 'fencus.hierarchyrelation::lang.plugin.name',
            'description' => 'fencus.hierarchyrelation::lang.plugin.description'
        ];
    }
	
    public function init()
    {
    	$this->fillFromConfig([
    			'allowNull',
    			'showSelf',
    			'allowChilds',
    			'nullString',
    			'nameFrom',
    			'separator',
    			'relationUp',
    			'relationDown',
    			'longFormat',
    	]);
    	
    	/*
    	 * Replace '?' characters for whitespaces.
    	 */
    	$this->separator = str_replace('?', ' ', $this->separator);
    	
    	$this->optionsList = array();
    }
    
    
    public function render()
    {
    	/*
    	 * Get array of Options
    	 */
   		$this->setOptionsList();
   		
   		/*
   		 * Add a NULL option to the array.
   		 */
   		if($this->allowNull)
   		{
   			$null = new \stdClass();
   		
   			$null->id = 'null';
   			$null->name = $this->nullString;
   			$null->selected = false;
   			$null->disabled = false;
   		
   			array_unshift($this->optionsList,$null);
   		}
   		
   		/*
   		 * Set variables for the partial.
   		 */
   		$this->vars['name'] = $this->formField->getName();
    	$this->vars['optionsList'] = $this->optionsList;
    	return $this->makePartial('hierarchyrelation');
    	
    }
    
    /**
     * Generates an array of options.
     * @return Options Array
     */
    public function setOptionsList()
    {
    	$this->modelAttribute = $this->valueFrom;
    	
    	/*
    	 * Check if the relations exists.
    	 * authors: Alexey Bobkov, Samuel Georges
    	 * extracted from: october/modules/backend/formwidgets/Relation.php
    	 */
    		
    	/*
    	 * Original Relation
    	 */
    	list($model, $attribute) = $this->resolveModelAttribute($this->modelAttribute);
    	if (!$model->hasRelation($attribute)) {
    		throw new ApplicationException(Lang::get('backend::lang.model.missing_relation', [
    				'class' => get_class($model),
    				'relation' => $attribute
    		]));
    	}
    	
    	/*
    	 * Relation Up
    	 */
    	$relationModel = $this->model->makeRelation($this->modelAttribute);
    	$dispose = new $relationModel();
    	if (!$dispose->hasRelation($this->relationUp)) {
    		throw new ApplicationException(Lang::get('backend::lang.model.missing_relation', [
    				'class' => get_class($dispose),
    				'relation' => $this->relationUp
    		]));
    	}
    	$relationType = $dispose->getRelationType($this->relationUp);
    	if(!in_array($relationType, ['belongsTo']))
    	{
    		throw new ApplicationException(Lang::get('fencus.hierarchyrelation::lang.errors.wrong_relation', [
    				'class' => get_class($dispose),
    				'relation' => $this->relationUp,
    				'type' => $relationType,
    				'needed' => 'belongsTo'
    		]));
    	}
    	
    	/*
    	 * Relation Down
    	 */
    	if (!$dispose->hasRelation($this->relationDown)) {
    		throw new ApplicationException(Lang::get('backend::lang.model.missing_relation', [
    				'class' => get_class($dispose),
    				'relation' => $this->relationDown
    		]));
    	}
    	$relationType = $dispose->getRelationType($this->relationDown);
    	if(!in_array($relationType, ['hasMany']))
    	{
    		throw new ApplicationException(Lang::get('fencus.hierarchyrelation::lang.errors.wrong_relation', [
    				'class' => get_class($dispose),
    				'relation' => $this->relationDown,
    				'type' => $relationType,
    				'needed' => 'hasMany'
    		]));
    	}
    	
        
        $this->areSameClass = get_class($relationModel) == get_class($this->model);
    	$this->relationValue = $this->model->{$this->modelAttribute};
    	$this->distance = 0;
    	$this->getNodes($relationModel::where($this->relationUp.'_id','=',0)->orWhere($this->relationUp.'_id','=',null)->get());
    }
    
    public function getNodes($childs, $append = '')
    {
   		foreach($childs as $child)
   		{
   			if($this->areSameClass && $this->model->id != null && $child->id == $this->model->id)
   			{
   				if($this->showSelf)
   				{
   					$option = new \stdClass();
   					$shortString = "";
   					for($i=0;($i <= $this->distance && !$this->longFormat);$i++)
   					{
   						$shortString .= $this->separator;
   					}
   					$option->id = $child->id;
   					$option->name = $append.$shortString.$child->{$this->nameFrom};
   					$option->disabled = true;
   					if($this->relationValue != null)
   						$option->selected = $child->id == $this->relationValue->id;
   					else
   						$option->selected = false;
   					array_push($this->optionsList, $option);
   					
   				}
   				if($this->allowChilds)
   				{
   					$this->distance += 1;
   					if(!$this->longFormat)
   						$this->getNodes($child->{$this->relationDown});
   					else
   						$this->getNodes($child->{$this->relationDown}, $option->name.$this->separator);
   					$this->distance -= 1;
   				}
   			}
   			else
   			{
   				$option = new \stdClass();
   				$shortString = "";
   				for($i=0;($i <= $this->distance && !$this->longFormat);$i++)
   				{
   					$shortString .= $this->separator;
   				}
   				$option->id = $child->id;
   				$option->name = $append.$shortString.$child->{$this->nameFrom};
   				$option->disabled = false;
   				if($this->relationValue != null)
   					$option->selected = $child->id == $this->relationValue->id;
   				else
   					$option->selected = false;
   				array_push($this->optionsList, $option);
   				if($child->{$this->relationDown}->count())
   				{
   					$this->distance += 1;
   					if(!$this->longFormat)
   						$this->getNodes($child->{$this->relationDown});
   					else
   						$this->getNodes($child->{$this->relationDown}, $option->name.$this->separator);
   					$this->distance -= 1;
   				}
   			}
   		}
    }
    
}
