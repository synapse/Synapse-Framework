<?php defined('_INIT') or die;

Class FilesController extends ModuleController {

	public function index()
	{
        $model = $this->getModel('files');
        $files = $model->getFiles();

        header('Content-Type: application/json');
        echo json_encode($files);
        die;
	}

	public function save()
	{
		$data = $this->getRequest()->getJSON();
		$model = $this->getModel('files');
		$return = new stdClass();
		$return->success = true;

		$md5 = $model->save($data);

		if($md5 === false){
			$return->success = false;
			$return->error = $model->getError();
		} else {
			$return->hash = $md5;
		}

		header('Content-Type: application/json');
        echo json_encode($return);
        die;
	}

	public function newFile()
	{
		$data = $this->getRequest()->getJSON();
		$model = $this->getModel('files');

		$return = new stdClass();
		$return->success = true;

		if(!$model->newFile($data)){
			$return->success = false;
			$return->error = $model->getError();
		}

		header('Content-Type: application/json');
        echo json_encode($return);
        die;
	}

	public function delete()
	{
		$data = $this->getRequest()->getJSON();
		$model = $this->getModel('files');

		$return = new stdClass();
		$return->success = true;

		if(!$model->delete($data)){
			$return->success = false;
			$return->error = $model->getError();
		}

		header('Content-Type: application/json');
        echo json_encode($return);
        die;
	}
}
