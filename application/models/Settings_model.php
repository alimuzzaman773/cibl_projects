<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of vendor_model
 *
 * @author USER
 */
Class Settings_model extends CI_Model {

    var $appName = "NRBC";
    var $vduRefreshTime = 15000; //15 secs (miliseconds)
    var $centralDashboardRefreshTime = 60000; //
    var $language = array("code" => "en", "name" => "English");

    function __construct() {
        parent::__construct();
    }

    /**
     * Returns cached settings
     * @return array
     */
    function getCachedSettings($p = array()) {
        $settingsInfo = array();
        $settings = array();
        if (file_exists(APPPATH . "cache/settings.cache")):
            $settingsInfo = json_decode(file_get_contents(APPPATH . "cache/settings.cache"));
        else:
            $result = $this->getAppSettings($p);
            if ($result):
                $settingsInfo = $result->result();
            endif;
        endif;

        foreach ($settingsInfo as $r) {
            $settings[$r->code] = unserialize($r->value);
        }
        return $settings;
    }

    function getAppSettings() {
        $result = $this->db->get(TBL_ADMIN_SETTINGS);
        return ($result->num_rows() > 0) ? $result : FALSE;
    }

    function getSettings($code, $resultObject) {
        if (isset($resultObject->$code) && strlen(trim($resultObject->$code)) > 0):
            return $resultObject->$code;
        endif;

        return (isset($this->$code)) ? $this->$code : "";
    }

    function getLanguage($params = array()) {
        $this->db->select("l.*")
                ->from(TBL_LANGUAGES . " l");

        if (isset($params['languageId']) && (int) $params['languageId'] > 0) {
            $this->db->where('l.languageId', $params['languageId']);
        }

        if (isset($params['code'])) {
            $this->db->where('l.code', $params['code']);
        }

        $result = $this->db->get();
        return($result->num_rows() > 0) ? $result : false;
    }

    /**
     * Loads langugage array and returns the array 
     * @param string $languageFilePath  path to language file
     * @return array loaded language array
     */
    function loadLanguage($languageFilePath, $returnCacheData = true, $writeToCache = true) {
        /* if($returnCacheData){
          $data = $this->getCacheData('language_array');
          if($data){
          return $data;
          }
          } */

        if (!file_exists($languageFilePath)) {
            return array();
        }

        echo "<pre> loading file : " . $languageFilePath . "</pre>";
        $fileContents = file_get_contents($languageFilePath);
        $fileArray = explode(PHP_EOL, $fileContents);
        var_dump($fileArray);
        $innerArray = array();
        foreach ($fileArray as $k => $val) {
            $lines = explode("=", $val);   // array("assdsd" => '"sdsdsdsds"')
            $langKey = "";
            $langValue = "";
            foreach ($lines as $ik => $iv) {
                if ((int) $ik == 0) {
                    $langKey = ltrim($iv, "\"");
                    $langKey = rtrim($iv, "\"");
                }

                if ((int) $ik > 0) {
                    $langValue = ltrim($iv, "\"");
                    $langValue = rtrim($iv, "\"");
                }
            }

            $innerArray[$langKey] = $langValue;
        }
        var_dump($innerArray);
        /* if($writeToCache){
          $this->writeToCache('language_array', $innerArray, 300);
          } */

        return $innerArray;
    }

    function writeToCache($key, $data, $ttl) {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        if ($this->cache->apc->is_supported()) {
            $this->cache->apc->save($key, $data, $ttl);
        }
    }

    function getCacheData($key) {
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        if ($this->cache->apc->is_supported()) {
            $data = $this->cache->apc->get($key);
            if ($data) {
                return $data;
            }
        }
        return false;
    }

    function getSettingsInfo($params = array()) {
        $this->db->select("*")
                ->from(TBL_ADMIN_SETTINGS);

        if (isset($params['code'])) {
            $params['code'] = (!is_array($params['code'])) ? array($params['code']) : $params['code'];
            $this->db->where_in("code", $params['code']);
        }

        $result = $this->db->get();
        return($result->num_rows() > 0) ? $result : false;
    }

    function getItem($item, $settings, $defaultValue = NULL) {
        if (isset($settings[$item])) {
            return $settings[$item];
        }

        return $defaultValue;
    }

    /**
     * Get settings from serialized data
     * @param string $code
     * @param object $settings
     */
    function get($code, $settings) {
        if (isset($settings->code) && strtolower($settings->code) == strtolower($code)) {
            return unserialize($settings->value);
        }
        return false;
    }

    function save($data) {
        $new_items = array();
        $old_items = array();
        $old_existing = array();

        $keys = array_keys($data);
        $result = $this->getSettingsInfo();
        if ($result) {
            foreach ($result->result() as $r) {
                $old_existing[$r->code] = isset($data[$r->code]) ? $data[$r->code] : $r;
            }
        }

        $time = date("Y-m-d H:i:d");
        foreach ($data as $k => $v) {
            if (!isset($old_existing[$k])) {
                $v['creationDtTm'] = $time;
                $new_items[] = $v;
            } else {
                $old_items[] = $v;
            }
        }

        //return $new_items;
        $q = array();
        try {
            $this->db->trans_begin();

            if (count($new_items) > 0) {
                $this->db->insert_batch(TBL_ADMIN_SETTINGS, $new_items);
                $q[] = $this->db->last_query();
            }

            if (count($old_items) > 0) {
                $this->db->update_batch(TBL_ADMIN_SETTINGS, $old_items, "code");
                $q[] = $this->db->last_query();
            }

            if ($this->db->trans_status() === false) {
                throw new Exception("Transaction failed. Please check");
            }

            $this->db->trans_commit();

            //cache
            $res = $this->getAppSettings();
            if ($res):
                $settingsData = json_encode($res->result());
                $this->load->helper("file");
                write_file(APPPATH . 'cache/settings.cache', $settingsData, "w");
            endif;

            return array(
                "success" => true,
                "q" => $q
            );
        } catch (Exception $e) {
            $this->db->trans_rollback();
            return array(
                "success" => false,
                "msg" => $e->getMessage(),
                "code" => $e->getCode(),
                "q" => $q
            );
        }
    }

}
