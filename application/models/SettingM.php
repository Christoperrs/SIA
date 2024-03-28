<?php defined('BASEPATH') or exit('No direct script access allowed');

class SettingM extends CI_Model
{
    private $t_setting = "KMS_SETTING";

    public function getSettings()
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_SETTING "
        );
        return $query->result();
    }

    public function editSetting($data, $where)
    {
        return $this->db->update($this->t_setting, $data, $where);
    }

    public function getSettingValue($kode)
    {
        $query = $this->db->query(
            "   SELECT  SETTING_VALUE
                FROM    KMS_SETTING
                WHERE   SETTING_KEY = '$kode'"
        );
        return $query->row()->SETTING_VALUE;
    }
}