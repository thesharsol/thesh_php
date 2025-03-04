<?php

session_start();
/**
 * @param $key
 * @return mixed|string
 */
if (!function_exists("lang")) {
    function lang($key = null, $params = null)
    {
        if (!is_null($key) && is_string($key) && isset($GLOBALS["lang"][$key])) {
            # code...
            if (isset($params)) {
                $ret = $GLOBALS["lang"][$key];
                foreach ($params as $key => $value) {
                    $ret = str_replace("{" . $key . "}", $value, $ret);
                }
                return $ret;
            } else {
                return $GLOBALS["lang"][$key];
            }
        }
        return "";
    }
}

if (!function_exists("is_date")) {
    function is_date($value)
    {
        if (!$value) {
            return false;
        }

        try {
            new \DateTime($value);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}

if (!function_exists("setErrors")) {
    /**
     * @param $errors
     * @return void
     */
    function setErrors($errors)
    {
        $GLOBALS["errors"] = $errors;
    }
}

if (!function_exists("getRandomStringUniqid")) {
    /**
     *
     * Using uniqid().
     *
     * @param int $length
     * @return string
     */
    function getRandomStringUniqid($length = 16)
    {
        $string = uniqid(rand());
        $randomString = substr($string, 0, $length);
        return $randomString;
    }
}

if (!function_exists("getErrors")) {
    /**
     * @param $label
     * @return string
     */
    function getErrors($label = null, $string = false)
    {

        switch (true) {
            case isset($label) && !$string:
                return isset($GLOBALS["errors"][$label]) ? $GLOBALS["errors"][$label] : [];
            case isset($label) && $string:
                return implode(',', $GLOBALS["errors"][$label]);
            case !isset($label) && !$string:
                return $GLOBALS["errors"];
            case !isset($label) && $string:
                $ret = "";
                foreach ($GLOBALS["errors"] as $name => $errors) {
                    $ret .= implode(",", $errors) . " ";
                }
                return $ret;
            default:
                break;
        }
    }
}

if (!function_exists("setLang")) {
    /**
     * @param $key
     * @param $value
     * @return void
     */
    function setLang($key, $value)
    {
        $GLOBALS["lang"][$key] = $value;
    }
}
if (!function_exists("url")) {
    /**
     * @param string $url
     * @return string
     */
    function url(string $url = ""): string
    {
        return (isset($_SERVER["REQUEST_SCHEME"]) ? $GLOBALS["config"]["base_url"] : "http://" . $_SERVER["HTTP_HOST"] . "/") . $url;
    }
}

if (!function_exists("app_name")) {
    /**
     * @param $url
     * @return string
     */
    function app_name($url = ""): string
    {
        return $GLOBALS["config"]["app_name"] . $url;
    }
}
if (!function_exists("setConfig")) {
    /**
     * @param $config
     * @return void
     */
    function setConfig($config)
    {
        $GLOBALS["config"] = $config;
    }
}
if (!function_exists("getLanguage")) {
    /**
     * @return array|mixed
     */
    function getLanguage()
    {
        return $_COOKIE["mpf_lang"] ?? getConfig("language");
    }
}
if (!function_exists("setLanguage")) {
    /**
     * @param $language
     * @return void
     */
    function setLanguage($language)
    {

        if (is_dir(getConfig('paths')['languages_folder'] . "/" . $language)) {
            setcookie("mpf_lang", $language, time() + (86400 * 30), "/");
        } else {
            echo lang('unset_language_dir');
        }
    }
}
if (!function_exists("sessionStorage")) {
    /**
     * @return void
     */
    function sessionStorage($fileSession = null)
    {
        $sessionStorage = getConfig("paths")["storage_folder"] . "sessions/";
        return $sessionStorage . ($fileSession ?? "");
    }
}
if (!function_exists("getConfig")) {
    /**
     * @param $item
     * @return array|mixed
     */
    function getConfig($item)
    {
        return $GLOBALS['config'][$item] ?? [];
    }
}

if (!function_exists("dbInfo")) {
    /**
     * @param $fields
     * @return mixed|string
     */
    function dbInfo($fields = null)
    {
        if (isset($GLOBALS["config"]["database"][$fields])) {
            //
            return $GLOBALS["config"]["database"][$fields];
        }
        return "";
    }
}

if (!function_exists("makeSecure")) {
    /**
     * @return mixed|string
     */
    function makeSecure()
    {
        if ($_SERVER['REQUEST_SCHEME'] != "https") {
            header("Location:" . url());
        }
    }
}
if (!function_exists("getUrlRoute")) {
    /**
     * @return false|mixed|string
     */
    function getUrlRoute()
    {
            $urlRoute = isset(
                $_SERVER['REQUEST_SCHEME']
            ) ?
            (explode(url(), ($_SERVER['REQUEST_SCHEME'] ?? "") . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])
            ) :
            explode("/", $_SERVER['REQUEST_URI'] . "/");

        return implode("/", array_filter($urlRoute));

    }
}
if (!function_exists("isDebug")) {
    # code...
    /**
     * 
     */
    function isDebug()
    {
        if (getConfig("mode") == "debug") {
            return true;
        }
        return false;
    }
}
