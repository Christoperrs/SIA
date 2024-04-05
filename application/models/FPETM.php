<?php defined('BASEPATH') or exit('No direct script access allowed');

class FPETM extends CI_Model
{
    private $t_fpet = "KMS_FPETFM";
    public function saveFpet($data)
    {
        $this->db->insert($this->t_fpet, $data);
    }

    public function makeTrain($data)
    {
        $this->db->insert('KMS_TRNHDR', $data);
    }

    public function getFpet()
    {
        $query = $this->db->query(
            "   SELECT  * 
                FROM    KMS_FPETFM
                WHERE   FPETFM_STATUS > 0"
        );
        return $query->result();
    }

    public function getApprovedFpet($npk)
    {
        $query = $this->db->query(
            "   SELECT  *,
                    CASE 
                        WHEN FPETFM_HRAPPROVER = $npk THEN 'HR'
                        WHEN FPETFM_APPROVER = $npk THEN 'BOSS'
                        WHEN FPETFM_CREABY = $npk THEN 'EVAL'
                        ELSE 'Unknown'
                    END AS role
                FROM    KMS_FPETFM
                WHERE   FPETFM_STATUS > 0
                AND     (   
                            FPETFM_HRAPPROVER = $npk
                            OR FPETFM_APPROVER = $npk  
                            OR FPETFM_CREABY = $npk  
                        )
                ORDER BY    FPETFM_STATUS, FPETFM_APPROVED, FPETFM_HRAPPROVED"
                        );
        return $query->result();
    }
    public function detailFpet($id)
    {
        $query = $this->db->query(
            "   SELECT  * 
                FROM    KMS_FPETFM
                WHERE   FPETFM_ID = $id"
        );
        return $query->row();
    }

    public function confirmPublishDeleteFPET($code, $id)
    {
        $data = array(
            'FPETFM_STATUS'     => $code,
            'FPETFM_MODIBY'     => $this->session->userdata('npk'),
            'FPETFM_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'FPETFM_ID' => $id
        );

        return $this->db->update($this->t_fpet, $data, $where);
    }



    public function rejectApproveFpet($id, $kode)
    {
        $data = array(
            'FPETFM_APPROVED'   => $kode,
            'FPETFM_MODIBY'     => $this->session->userdata('npk'),
            'FPETFM_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'FPETFM_ID'    => $id
        );
        return $this->db->update($this->t_fpet, $data, $where);
    }

    public function rejectApproveHrFpet($id, $kode, $idTrain, $rEstablished)
    {
        $data = array(
            'FPETFM_HRAPPROVED' => $kode,
            'FPETFM_ESTABLISHED'=> $rEstablished,
            'TRNHDR_ID'         => $idTrain,
            'FPETFM_MODIBY'     => $this->session->userdata('npk'),
            'FPETFM_MODIDATE'   => date('Y/m/d H:i:s'),
        );

        $where = array(
            'FPETFM_ID'     => $id
        );

        return $this->db->update($this->t_fpet, $data, $where);
    }

    public function addParticipantTraining($participant, $idTrain)
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNACC
                WHERE   TRNHDR_ID   = $idTrain
                AND     AWIEMP_NPK  = '$participant'  "
        );
        $row = $query->row();

        if ($row && $row->TRNACC_PERMISSION == 0) {
            $data = array(
                'TRNACC_PERMISSION' => 1,
                'TRNACC_MODIBY'     => $this->session->userdata('npk'),
                'TRNACC_MODIDATE'   => date('Y/m/d H:i:s'),
            );
            $where = array(
                'TRNHDR_ID'     => $idTrain,
                'AWIEMP_NPK'    => $participant
            );
            return $this->db->update('KMS_TRNACC', $data, $where);
        } else {
            $data = array(
                'TRNACC_PERMISSION' => 1,
                'TRNACC_MODIBY'     => $this->session->userdata('npk'),
                'TRNACC_MODIDATE'   => date('Y/m/d H:i:s'),
                'TRNACC_CREABY'     => $this->session->userdata('npk'),
                'TRNACC_CREADATE'   => date('Y/m/d H:i:s'),
                'TRNHDR_ID'         => $idTrain,
                'AWIEMP_NPK'        => $participant,
            );
            return $this->db->insert('KMS_TRNACC', $data);
        }
    }

    public function modifyFpet($data, $id)
    {
        $where = array(
            'FPETFM_ID' => $id
        );
        return $this->db->update($this->t_fpet, $data, $where);
    }

    public function checkParticipant($participant, $idTrain)
    {
        $query = $this->db->query(
            "   SELECT  count(*) AS REC
                FROM    KMS_TRNACC
                WHERE   TRNHDR_ID           = $idTrain
                AND     AWIEMP_NPK          = '$participant'
                AND     TRNACC_PERMISSION   = 1             "
        );
        return $query->row()->REC > 0;
    }

    public function getOverview($npk, $idHeader)
    {
        $query = $this->db->query(
            "   SELECT	KMS_TRNACC.TRNHDR_ID, KMS_FPETFM.FPETFM_ID, KMS_TRNACC.AWIEMP_NPK, KMS_TRNHDR.TRNHDR_TITLE, 
                        KMS_TRNACC.TRNACC_PRESCORE, KMS_TRNACC.TRNACC_POSTSCORE, KMS_FPETFM.FPETFM_ACTUAL, 
                        KMS_FPETFM.FPETFM_PACTUAL, KMS_FPETFM.FPETFM_TARGET, KMS_FPETFM.FPETFM_PTARGET, KMS_FPETFM.FPETFM_PEVAL,
                        KMS_FPETFM.FPETFM_APPROVER, KMS_FPETFM.FPETFM_HRAPPROVER, KMS_FPETFM.FPETFM_NOTES, KMS_FPETFM.FPETFM_EVAL,
                        KMS_TRNACC.TRNACC_RESUME, KMS_FPETFM.FPETFM_TRAINSUGGEST, KMS_FPETFM.FPETFM_CREABY, KMS_FPETFM.FPETFM_STATUS
                FROM    KMS_TRNACC
                INNER JOIN  KMS_FPETFM
                    ON  KMS_FPETFM.AWIEMP_NPK   = KMS_TRNACC.AWIEMP_NPK
                    AND KMS_FPETFM.TRNHDR_ID    = KMS_TRNACC.TRNHDR_ID
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_TRNHDR.TRNHDR_ID    = KMS_FPETFM.TRNHDR_ID
                WHERE   KMS_TRNACC.TRNHDR_ID            = $idHeader
                AND     KMS_TRNACC.AWIEMP_NPK           = '$npk'
                AND		KMS_TRNACC.TRNACC_PERMISSION    = 1                                                 "
        );
        return $query->row();
    }

    public function getResumes()
    {
        $query = $this->db->query(
            "   SELECT	KMS_TRNACC.TRNHDR_ID, KMS_FPETFM.FPETFM_ID, KMS_TRNACC.AWIEMP_NPK, KMS_TRNHDR.TRNHDR_TITLE, 
                        KMS_TRNACC.TRNACC_PRESCORE, KMS_TRNACC.TRNACC_POSTSCORE, KMS_FPETFM.FPETFM_ACTUAL, 
                        KMS_FPETFM.FPETFM_PACTUAL, KMS_FPETFM.FPETFM_TARGET, KMS_FPETFM.FPETFM_PTARGET, KMS_FPETFM.FPETFM_PEVAL,
                        KMS_FPETFM.FPETFM_APPROVER, KMS_FPETFM.FPETFM_HRAPPROVER, KMS_FPETFM.FPETFM_NOTES, KMS_FPETFM.FPETFM_EVAL,
                        KMS_TRNACC.TRNACC_RESUME, KMS_FPETFM.FPETFM_TRAINSUGGEST, KMS_FPETFM.FPETFM_CREABY, KMS_FPETFM.FPETFM_STATUS
                FROM    KMS_TRNACC
                INNER JOIN  KMS_FPETFM
                    ON  KMS_FPETFM.AWIEMP_NPK   = KMS_TRNACC.AWIEMP_NPK
                    AND KMS_FPETFM.TRNHDR_ID    = KMS_TRNACC.TRNHDR_ID
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_TRNHDR.TRNHDR_ID    = KMS_FPETFM.TRNHDR_ID"
        );
        return $query->result();
    }
}
