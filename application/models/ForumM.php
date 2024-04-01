<?php defined('BASEPATH') or exit('No direct script access allowed');

class ForumM extends CI_Model
{
    public function getForum()
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_FRM
                WHERE   FRM_STATUS >= 1 
                ORDER BY    FRM_STATUS DESC, FRM_TITLE"
        );
        return $query->result();
    }

    public function isAdmin()
    {
        return $this->session->userdata('role') == 'admin';
    }

    public function saveFRM($data)
    {
        return $this->db->insert('KMS_FRM', $data);
    }

    public function searchArticles()
    {
        $key    = $this->input->post('keyword');
        $status = $this->isAdmin() ? '> 0' : '= 2';
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_FRM
                WHERE   LOWER(FRM_TITLE) LIKE '%$key%' AND FRM_STATUS " . $status . "
                ORDER BY    FRM_STATUS DESC, FRM_TITLE"
        );
        return $query->result();
    }

    public function deleteFRM($data, $where)
    {
        // $where = array(  
        //     'FRM_ID'    => $idHeader,
        //     'AWIEMP_NPK'   => $this->session->userdata('npk'),
        // );
        return $this->db->update('KMS_FRM', $data, $where);
    }

    public function publishFRM($id)
    {
        $data = array(
            'FRM_STATUS'     => 2,
            'FRM_MODIBY'     => $this->session->userdata('npk'),
            'FRM_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'FRM_ID' => $id
        );

        return $this->db->update('KMS_FRM', $data, $where);
    }
    public function detailFRM($id)
    {
        $query = $this->db->query(
            "   SELECT  * 
                FROM    KMS_FRM
                WHERE   FRM_ID = $id"
        );
        return $query->row();
    }

    public function modifyFRM($data, $id)
    {
        $where = array(
            'FRM_ID' => $id
        );
        return $this->db->update('KMS_FRM', $data, $where);
    }
}
