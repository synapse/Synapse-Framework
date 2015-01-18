Controllers are called by the current route. So for every route path there should be a Controller method.

***
### Creating a new controller
```php
<?php defined('_INIT') or die;

Class NameController extends Controller {

	public function index()
	{
		
	}
}
```

***
### Loading a View
```php
<?php defined('_INIT') or die;

Class NameController extends Controller {

	public function index()
	{
		$this->getView()
			->setTemplate('components')
			->render();
	}
}
```

***
### Loading a Model
The model should be located inside the "models" folder.

```php
<?php defined('_INIT') or die;

Class NameController extends Controller {

	public function index()
	{
		$model = $this->getModel('model-name');

		$this->getView()
			->setTemplate('components')
			->render();
	}
}
```

> To load a model from a sub folder of the model folder you should add the sub folder's name in front of the model name
> `$this->getModel('sub-folder/model-name');`
>
> The model file must have the same name used in the $this->getModel() method with a .php extension.
> `$this->getModel('my-model') => my-model.php`
>
> You may load multiple models by using the same method several times.
> `$model1 = $this->getModel('model-name-1');`
> `$model2 = $this->getModel('sub-folder/model-name-2');`


***
### Get the Query
The query object contains all the request parameters and values.

```php
<?php defined('_INIT') or die;

Class NameController extends Controller {

	public function index()
	{
		$query = $this->getQuery();

		$this->getView()
			->setTemplate('components')
			->render();
	}
}
```

> The query object is a simple PHP object.
> `$someValue = $query->someParam;`

***
### Get the params
The parameters are the dynamic values used in the URL segments. **`/users/:id`**

```php
<?php defined('_INIT') or die;

Class NameController extends Controller {

	public function index()
	{
		$params = $this->getParams();

		$this->getView()
			->setTemplate('components')
			->render();
	}
}
```

> The params object is a simple PHP object.
> `$someValue = $params->someParam;`
>
> Use the Controller method getParam()
> `$someValue = $this->getParam('someParam');`

***
### Assign data to the View
The data can be read directly from your HTML layout.

```php
<?php defined('_INIT') or die;

Class NameController extends Controller {

    public function index()
    {
        $model = $this->getModel('some-model');
        $data = $model->getData();

        $this->getView()
            ->setTemplate('components')
            ->setData($data, 'name')
            ->render();
    }
}
```

and then in the view use the assigned variable inside the HTML layout

```html
<html>
    <body>
        <h1><?= $name ?></h1>
    </body>
</html>
```