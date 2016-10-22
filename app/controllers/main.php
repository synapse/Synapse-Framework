<?php defined('_INIT') or die;

Class MainController extends Controller {

	public function index()
	{
		$model = $this->getModel('main');
		$helloString = $model->sayHello();
		$hello = 1;

		$this->getView()
			->setTemplate(array('main'))
			->setData($hello, 'hello')
			->render();
	}
}
