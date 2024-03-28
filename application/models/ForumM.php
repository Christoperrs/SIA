<?php defined('BASEPATH') or exit('No direct script access allowed');

class ForumM extends CI_Model
{



    public function getForum()
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_FRM
                WHERE   FRM_STATUS >= 1 "
        );
        return $query->result();
    }

    public function saveFRM($data)
    {
        return $this->db->insert('KMS_FRM', $data);
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
