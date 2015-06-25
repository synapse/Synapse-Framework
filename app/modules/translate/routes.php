<?php defined('_INIT') or die;


class TranslateRoutes extends ModuleRouter
{
    public function getRoutes()
    {
        $this->get('/', 'main');
        $this->get('/files', 'Files');
        $this->post('/files', 'Files.save');
        $this->post('/files/new', 'Files.newFile');
        $this->post('/files/delete', 'Files.delete');

        //$this->post('/new', 'main.newTranslation');
        //$this->post('/save', 'main.save');
        //$this->get('/delete/:file/:id', 'main.delete');
    }
}
