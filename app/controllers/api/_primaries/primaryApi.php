<?php

namespace app\controllers\api\_primaries;

use system\core\Controller;

use Exception;

abstract class primaryApi extends Controller
{
    public function __construct()
    {
        $this->lang("lang");
        $this->lang("errors");
        $this->lang("labels");
        $this->lang("messages");
    }
    /**
     * @param null $data
     * @param null $message
     * @param null $url
     * 
     * @return [type]
     */
    public function responseJson($data = null, $message = null, $url = null)
    {
        return parent::responseJson($data, $message, $url);
    }

    /**
     * @param mixed $model
     * @param mixed $idName
     * 
     * @return bool
     */
    protected function explodeMapAndAdd($model, $validation, $idName): bool
    {
        $insertState = true;
        $ids = explode(",", $_POST[$idName]);

        foreach ($ids as $id) {
            if (!empty($id)) {
                $_POST[$idName] = $id;
                $this->$model->hydrater($_POST);
                if ($this->$validation->run()) {
                    # code...
                    if (!$this->$model->add()) {
                        $insertState = false;
                    }
                } else {
                }
            }
        }
        return $insertState;
    }
    public function explodeMapAndAddFromTwo($model, $validation, $idName, $sourceId): bool
    {
        $sourceIds = isset($_POST[$sourceId]) ? explode(",", $_POST[$sourceId]) : [];
        $POST_COPY = $_POST;
        foreach ($sourceIds as $sId) {
            $_POST[$sourceId] = $sId;
            $_POST[$idName] = $POST_COPY[$idName];
            // echo "d";
            $res = $this->explodeMapAndAdd($model, $validation, $idName);
        }
        return $res;
    }
    /**
     * @param mixed $state
     * @param null $message
     * 
     * @return [type]
     */
    public function responseJsonFromState($state, $message = null)
    {
        return $state ? $this->responseJson($_POST, $message ?? lang("add_done")) : $this->responseJson(null, lang("fields_empty"));
    }
    /**
     * @param mixed $model
     * @param null $suffix
     * @param null $type
     * 
     * @return [type]
     */
    public function deleteItems($model, $suffix = null, $type = null)
    {
        if (isset($_GET["id"])) {
            $ids = explode(",", $_GET["id"]);
            $deleteMethod  = "";
            foreach ($ids as $id) {
                $this->$model->setId($id);
                $deleteMethod = 'delete' . ucfirst($suffix ?? "");
                if ($this->$model->$deleteMethod() && isset($type)) {
                    $this->deleteFiles($type, $id);
                }
            }
            $this->responseJson([$ids], count($ids) . " " . lang("delete_done"));
        } else {
            $this->responseJson(lang("no_id"));
        }
    }
    /**
     * @param mixed $type
     * @param mixed $id
     * 
     * @return [type]
     */
    public function deleteFiles($type, $id)
    {
        $dirname = "assets/upload/" . $type . "/" . $type . "_" . $id . "/";
        if (is_dir($dirname)) {
            $dir = opendir($dirname);
            while ($file = readdir($dir)) {
                if (($file != '.' && $file != '..') && !is_dir($dirname . '/' . $file)) {
                    unlink($dirname . $file);
                }
            }
            rmdir($dirname);
        }
    }
    /**
     * @param mixed $model
     * @param mixed $validation
     * 
     * @return [type]
     */
    public function genUpdate($model, $validation = null, $message = null, $return_bool = null)
    {
        return $this->genUpAdd($model, $validation, "update", $message, $return_bool);
    }
    /**
     * @param mixed $model
     * @param mixed $validation
     * 
     * @return [type]
     */
    public function genAdd($model, $validation = null, $message = null, $return_bool = null)
    {
        return $this->genUpAdd($model, $validation, "add", $message, $return_bool);
    }
    /**
     * @param mixed $model
     * @param mixed $validation
     * @param mixed $func
     * 
     * @return [type]
     */
    public function genUpAdd($model, $validation = null, $func, $message = null, $return_bool = null)
    {
        if ((isset($validation) && $this->$validation->run()) || !isset($validation)) {
            $this->$model->hydrater($_POST);
            if ($this->$model->$func()) {
                if (isset($return_bool)) {
                    return true;
                }
                $this->responseJson($_POST, $message ?? "done");
            } else {
                if (isset($return_bool)) {
                    return false;
                }
                $this->responseJson(null, getErrors(null, true));
            }
        } else {
            if (isset($return_bool)) {
                return false;
            }
            $this->responseJson(null, getErrors(null, true));
        }
    }
}
