<?php namespace Fencus\HierarchyRelation\Models;

use Model;

/**
 * Hierarchy Model
 */
class Hierarchy extends Model
{

	public $relationUp = 'parent';
	public $relationDown = 'childs';
	
	public function beforeSave()
	{
		if($this->{$this->relationUp})
		{
			$branch = $this->getBranchOfChild($this->{$this->relationUp});
			if($branch)
			{
				$branch->{$this->relationUp} = $this::find($this->id)->{$this->relationUp};
				$branch->save();
			}
		}
	}
	
	public function beforeDelete()
	{
		if($this->{$this->relationDown})
		{
			foreach($this->{$this->relationDown} as $child)
			{
				$child->{$this->relationUp} = $this->{$this->relationUp};
				$child->save();
			}
		}
	}
	
	protected function getBranchOfChild($model)
	{
		foreach($this->{$this->relationDown} as $child)
		{
			if($child->id == $model->id)
			{
				return $child;
			}
			else
			{
				$childFound = $this->getIfChild($child,$model);
				if($childFound)
				{
					return $child;
				}
			}
		}
		return null;
	}
	
	protected function getIfChild($model,$find)
	{
		$childFound = null;
		foreach($model->{$this->relationDown} as $child)
		{
			if($child->id == $find->id)
				return $child;
			else
				$childFound = $this->getIfChild($child,$find);
		}
		return $childFound;
	}

}