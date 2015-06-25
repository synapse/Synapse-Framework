<?php defined('_INIT') or die;

Class FilesModel extends Model {

    public $languages = array("af","ar","at","az","be","bg","bn","br","bs","ca","ch","cy","cz","da","de","dk","el","en","eo","es","et","fa","fi","fr","gd","he","hi","hk","hr","hu","hy","id","is","it","ja","ka","km","ko","ku","lo","lt","lv","mk","mn","nl","no","pl","ps","pt","ro","ru","sk","sl","sr","sv","sw","sy","ta","th","tr","tw","uk","ur","us","uz","vi","zh");

	public function getFiles()
	{
        $files = array();

        $JSONFiles = glob(LANGUAGES."/*.json");

        foreach($JSONFiles as $JSONFile){
            $file = new stdClass();
            //$file->path = $JSONFile;
            //$file->filename = File::getName($JSONFile);
            $filename = File::getName($JSONFile);
            $file->name = File::stripExt($filename);
            $file->translations = json_decode(file_get_contents($JSONFile));
            $files[] = $file;
        }

		return $files;
	}

    public function save($data)
    {
        $file = LANGUAGES.'/'.$data->file.'.json';

        if(!File::exists($file)){
            $this->setError('JSON file not found! '.$file.'.json');
            return false;
        }

        $fileRawData = file_get_contents($file);
        $fileData = json_decode($fileRawData);
        $oldHash = $data->hash;
        $newHash = md5($data->original);


        // the original text was found
        if(isset($fileData->$oldHash))
        {
            if($oldHash === $newHash){
                unset($fileData->$oldHash->translations);
                $translations = new stdClass();

                foreach($data->translations as $i => $t) {
                    $lang = $t->lang;
                    $translations->$lang = $t->text;
                }
                $fileData->$oldHash->translations = $translations;
            } else {
                unset($fileData->$oldHash);
                $info = new stdClass();
                $translations = new stdClass();

                $info->original = $data->original;

                foreach($data->translations as $i => $t) {
                    $lang = $t->lang;
                    $translations->$lang = $t->text;
                }
                $info->translations = $translations;
                $fileData->$newHash = $info;
            }
        }

        // the original text was not found in the specified file
        else
        {
            $info = new stdClass();
            $translations = new stdClass();

            $info->original = $data->original;

            foreach($data->translations as $i => $t) {
                $lang = $t->lang;
                $translations->$lang = $t->text;
            }
            $info->translations = $translations;
            $fileData->$newHash = $info;
        }

        if(file_put_contents($file, json_encode($fileData, JSON_PRETTY_PRINT)) === false){
            $this->setError('Unable to update the JSON file!');
            return false;
        }

        return $newHash;
    }

    public function newFile($data)
    {
        $file = LANGUAGES.'/'.$data->name.'.json';

        if(File::exists($file)){
            $this->setError('JSON file already exists! '.$file.'.json');
            return false;
        }

        $fileContents = new stdClass();

        if(file_put_contents($file, json_encode($fileContents, JSON_PRETTY_PRINT)) === false){
            $this->setError('Unable to generate the JSON file!');
            return false;
        }

        return true;
    }

    public function delete($data)
    {
        $file = LANGUAGES.'/'.$data->file.'.json';

        if(!File::exists($file)){
            $this->setError('JSON file not found! '.$file.'.json');
            return false;
        }

        $fileRawData = file_get_contents($file);
        $fileData = json_decode($fileRawData);
        $hash = $data->hash;

        if(!property_exists($fileData, $hash)){
            $this->setError('Translation not found!');
            return false;
        }

        unset($fileData->$hash);

        if(file_put_contents($file, json_encode($fileData, JSON_PRETTY_PRINT)) === false){
            $this->setError('Unable to update the JSON file!');
            return false;
        }

        return true;
    }
}
