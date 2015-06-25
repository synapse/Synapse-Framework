<?php defined('_INIT') or die;

Class MainController extends ModuleController {

	public function index()
	{
		$model = $this->getModel('files');
		$languages = $model->languages;

		$this->getView()
			->setTemplate('translate')
			->setData($languages, 'languages')
			->render();
	}

}
