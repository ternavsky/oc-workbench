# Fencus Hierarchy Relation Manager

A solution to manage hierarchical relationships.

## Description

This plugin provides a solution to manage hierarchical relationships of the type belongsTo-HasMany:
* Example 1: A **Category** has a **Parent Category** and many **Childs Categories**.
* Example 2: An **Item** belongs to a **Category** and a **Category** has many **Items**.

## Contents of the plugin

* A FormWidget to manage the relation on the backend.
* An Extensible Hierarchy Model to prevent loops in the hierarchy.

## Hierarchy Relation FormWidget

### Usage
* **type** - Must be `hierarchy-relation` to load the FormWidget.

#### Options
* **allowNull** - Enables the null option. `(boolean, default: true)`
* **nullString** - Specifies the null value for the field. `(string, default: '--None--')`
* **allowChilds** - Enables to show the childs of the current model been edited. `(boolean, default: true)`
* **showSelf** - If true shows the current model on the list but is unselectable. `(boolean, default: true)`
* **relationUp** - Name of the relation in the Hierarchy Model going up (belongsTo), in example: **Parent Category** relation. `(string, default: 'parent')`
* **relationDown** - Name of the relation in the Hierarchy Model going up (hasMany), in example: **Child Categories** relation. `(string, default: 'childs')`
* **nameFrom** - The column name to use in the relation used for displaying the name. `(string, default: 'name')`
* **separator** - String to separate the levels of hierarchy, you can use `?` to indicate whitespaces, se examples below. `(string, default: ' - ')`
* **longFormat** - Enables the long **format** style, se examples below. `(boolean, default: false)`

        parent:
            label: Parent Hierarchy
            type: hierarchy-relation
            allowNull: true
            nullString: --NONE--
            allowChilds: true
            showSelf: true
            relationUp: parent
            relationDown: childs
            nameFrom: id
            separator: ?->?
            longFormat: true

### Example 1

	longFormat: true
	separator: ?->?
**Output**

	```
	-> Grandfather 1
	-> Grandfather 1 -> Father 1
	-> Grandfather 1 -> Father 1 -> Child 1
	-> Grandfather 1 -> Father 1 -> Child 2
	-> Grandfather 1 -> Father 2
	-> Grandfather 2
	-> Grandfather 3
	-> Grandfather 3 -> Father 3
	```
	
### Example 2

	longFormat: false
	separator: ?->?
**Output**

	```
	-> Grandfather 1
	-> -> Father 1
	-> -> -> Child 1
	-> -> -> Child 2
	-> -> Father 2
	-> Grandfather 2
	-> Grandfather 3
	-> -> Father 3
	```

## Extensible Hierarchy Model

### Usage
You must define the variables `$relationUp` and `$relationDown` to match the names of the respective relations.
* `$relationUp` defaults to 'parent'.
* `$relationDown` defaults to 'childs'.

####For example:

	<?php namespace Acme\Demo\Models;
	
	use Model;
	use Fencus\HierarchyRelation\models\Hierarchy as HierarchyModel;
	
	/**
	 * Category Model
	 */
	class Category extends HierarchyModel
	{
		public $relationUp = 'parent';
		public $relationDown = 'childs';
		
		public $table = 'acme_demo_categories';
		
		public $hasMany = [
    		'childs' => ['Acme\Demo\models\Category', 'key' => 'parent_id'],
    		];
    	public $belongsTo = [
    		'parent' => 'Acme\Demo\models\Category',
    		];
	}
	
### Why should we use it?
Let's assume the next case:

	```
	-> Grandfather
	-> Grandfather -> Father
	-> Grandfather -> Father -> Child
	-> Grandfather -> Father -> Child -> Grandchild
	```

If we change the father of **Father** to **Grandchild** we would generate a loop:

	```
	-> Grandfather
	-> From Infinity -> Father -> Child -> Grandchild -> Father -> Child -> Grandchild -> Father -> To infinity
	```

If we do it using the Extensible Hierarchy Model, we would get the next result:

	```
	-> Grandfather
	-> Grandfather -> Child
	-> Grandfather -> Child -> Grandchild
	-> Grandfather -> Child -> Grandchild -> Father
	```

The Model detects that the change would generate a loop, so changes the father of the son of **Father** generating the loop (in this case: **Child**)  to the parent of **Father** (in this case: **Grandfather**).
