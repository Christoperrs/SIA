<?php defined('BASEPATH') or exit('No direct script access allowed');

class TrainingM extends CI_Model
{
    private $t_header = "KMS_TRNHDR";
    private $t_detail = "KMS_TRNSUB";
    private $t_access = "KMS_TRNACC";
    private $t_tagdetail = "KMS_DETLBL";
    private $t_progress = "KMS_TRNPRG";

    public function isAdmin()
    {
        return $this->session->userdata('role') == 'admin';
    }

    public function saveTraining()
    {
        $data = array(
            'TRNHDR_STATUS'     => 1,
            'TRNHDR_TITLE'      => $this->input->post('temaTraining'),
            'TRNHDR_INSTRUCTOR' => $this->input->post('pemateri'),
            'TRNHDR_CREADATE'   => date('Y/m/d H:i:s'),
            'TRNHDR_MODIDATE'   => date('Y/m/d H:i:s'),
            'TRNHDR_CREABY'     => $this->session->userdata('npk'),
            'TRNHDR_MODIBY'     => $this->session->userdata('npk'),
        );
        return $this->db->insert($this->t_header, $data);
    }

    public function saveSubstance($path, $judul, $id)
    {
        $data = array(
            'TRNSUB_PATH'       => $path,
            'TRNSUB_TITLE'      => $judul,
            'TRNHDR_ID'         => $id,
            'TRNSUB_STATUS'     => $this->isAdmin() ? 1 : 2,
            'TRNSUB_CREADATE'   => date('Y/m/d H:i:s'),
            'TRNSUB_MODIDATE'   => date('Y/m/d H:i:s'),
            'TRNSUB_CREABY'     => $this->session->userdata('npk'),
            'TRNSUB_MODIBY'     => $this->session->userdata('npk'),
        );
        return $this->db->insert($this->t_detail, $data);
    }

    public function saveParticipant($npk, $id)
    {
        $data = array(
            'TRNACC_PERMISSION' => $this->isAdmin() ? 1 : 2,
            'AWIEMP_NPK'        => $npk,
            'TRNHDR_ID'         => $id,
            'TRNACC_CREADATE'   => date('Y/m/d H:i:s'),
            'TRNACC_MODIDATE'   => date('Y/m/d H:i:s'),
            'TRNACC_CREABY'     => $this->session->userdata('npk'),
            'TRNACC_MODIBY'     => $this->session->userdata('npk'),
        );
        return $this->db->insert($this->t_access, $data);
    }

    public function saveLabelDetail($label, $header)
    {
        $data = array(
            'TRNLBL_ID' => $label,
            'TRNHDR_ID' => $header,
        );
        return $this->db->insert($this->t_tagdetail, $data);
    }

    public function saveProgress($id)
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNPRG
                WHERE   AWIEMP_NPK  = '$npk'
                AND     TRNSUB_ID   = $id   "
        );

        $data = array(
            'AWIEMP_NPK'        => $npk,
            'TRNSUB_ID'         => $id,
            'TRNPRG_STATUS'     => 1,
            'TRNPRG_CREADATE'   => date('Y/m/d H:i:s'),
            'TRNPRG_MODIDATE'   => date('Y/m/d H:i:s'),
        );

        if ($query->row()) {
            return $this->db
                ->where('AWIEMP_NPK', $npk)
                ->where('TRNSUB_ID', $id)
                ->set('TRNPRG_STATUS', 'TRNPRG_STATUS + 1', FALSE)
                ->set('TRNPRG_MODIDATE', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                ->update($this->t_progress);
        } else {
            return $this->db->insert($this->t_progress, $data);
        }
    }

    public function modifyApproval($npk, $status)
    {
        if ($npk != '') {
            return $this->db
                ->where('TRNHDR_ID', $this->input->post('id'))
                ->where('AWIEMP_NPK', $npk)
                ->set('TRNACC_PERMISSION', $status, FALSE)
                ->set('TRNACC_MODIDATE', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                ->set('TRNACC_MODIBY', $this->session->userdata('npk'), FALSE)
                ->update($this->t_access);
        } else {
            return $this->db
                ->where('TRNHDR_ID', $this->input->post('idDetail'))
                ->set('TRNSUB_STATUS', $status, FALSE)
                ->set('TRNSUB_MODIDATE', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                ->set('TRNSUB_MODIBY', $this->session->userdata('npk'), FALSE)
                ->update($this->t_detail);
        }
    }

    public function modifyAccess($key, $value)
    {
        $data = array(
            $key => $value,
            'TRNACC_MODIBY'     => $this->session->userdata('npk'),
            'TRNACC_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'TRNHDR_ID' => $this->input->post('header'),
            'AWIEMP_NPK' => $this->input->post('npk'),
        );
        return $this->db->update($this->t_access, $data, $where);
    }

    public function modifyTraining($id, $code)
    {
        $data = array(
            'TRNHDR_STATUS'     => $code,
            'TRNHDR_MODIBY'     => $this->session->userdata('npk'),
            'TRNHDR_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'TRNHDR_ID'         => $id,
        );
        return $this->db->update($this->t_header, $data, $where);
    }

    public function modifyTrainingHeader()
    {
        $data = array(
            'TRNHDR_TITLE'      => $this->input->post('temaTraining'),
            'TRNHDR_INSTRUCTOR' => $this->input->post('pemateri'),
            'TRNHDR_MODIBY'     => $this->session->userdata('npk'),
            'TRNHDR_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'TRNHDR_ID'         => $this->input->post('idTraining')
        );
        return $this->db->update($this->t_header, $data, $where);
    }

    public function modifySubstance($id)
    {
        return $this->db
            ->where('TRNSUB_ID', $id)
            ->set('TRNSUB_STATUS', 0, FALSE)
            ->set('TRNSUB_MODIDATE', "'" . date('Y/m/d H:i:s') . "'", FALSE)
            ->set('TRNSUB_MODIBY', $this->session->userdata('npk'), FALSE)
            ->update($this->t_detail);
    }

    public function modifyParticipant($npk, $id)
    {
        $this->db
            ->where('TRNHDR_ID', $id)
            ->where('AWIEMP_NPK', $npk);

        $TRNACC_PERMISSION = $this->db->get($this->t_access)->row()->TRNACC_PERMISSION;

        $this->db
            ->where('TRNHDR_ID', $id)
            ->where('AWIEMP_NPK', $npk);
        $this->db->set('TRNACC_PERMISSION', $this->isAdmin() ? 1 : 2, FALSE)
            ->set('TRNACC_MODIDATE', "'" . date('Y/m/d H:i:s') . "'", FALSE)
            ->set('TRNACC_MODIBY', $this->session->userdata('npk'), FALSE);

        if ($TRNACC_PERMISSION == 0 || $TRNACC_PERMISSION == 3) {
            $this->db
                ->set('TRNACC_CREADATE', "'" . date('Y/m/d H:i:s') . "'", FALSE)
                ->set('TRNACC_CREABY', $this->session->userdata('npk'), FALSE);
        }

        return $this->db->update($this->t_access);
    }

    public function resetParticipant($npks, $id)
    {
        if (empty($npks)) {
            return;
        }

        $npk = implode("','", $npks);
        $status = $this->isAdmin() ? '0' : '2';
        $this->db
            ->where('TRNHDR_ID', $id)
            ->set('TRNACC_PERMISSION', $status, FALSE)
            ->set('TRNACC_MODIDATE', "'" . date('Y/m/d H:i:s') . "'", FALSE)
            ->set('TRNACC_MODIBY', $this->session->userdata('npk'), FALSE);

        if ($this->isAdmin()) {
            $this->db
                ->where_not_in('AWIEMP_NPK', $npk);
        } else {
            $this->db
                ->where_in('AWIEMP_NPK', $npks, FALSE)
                ->where('TRNACC_PERMISSION !=', 1);
        }
        $this->db->update($this->t_access);
    }

    public function resetLabels($tagID, $id)
    {
        $tag = implode(",", $tagID);
        $this->db
            ->where('TRNHDR_ID', $id)
            ->where_not_in('TRNLBL_ID', explode(",", $tag));

        $this->db->delete($this->t_tagdetail);
    }

    public function getAllSubstances()
    {
        $status = $this->isAdmin() ? '> 0' : '= 1';
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNSUB
                WHERE   TRNSUB_STATUS " . $status
        );
        return $query->result();
    }

    public function getTrainingHeader($id)
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNHDR
                WHERE   TRNHDR_ID = " . $id
        );
        return $query->result();
    }

    public function searchTraining($isAll, $keyword, $tag)
    {
        $status = $this->isAdmin() ? '> 0' : '= 2';
        $npk = $this->session->userdata('npk');
        $column = $isAll ? '' : 'KMS_TRNACC.*,';
        $column .= $tag == '' ? '' : 'KMS_DETLBL.*,';
        $table = $isAll ? '' : ' JOIN KMS_TRNACC ON KMS_TRNHDR.TRNHDR_ID = KMS_TRNACC.TRNHDR_ID';
        $table .= $tag == '' ? '' : ' JOIN KMS_DETLBL ON KMS_TRNHDR.TRNHDR_ID = KMS_DETLBL.TRNHDR_ID';
        $queryAdd = $isAll ? '' : " AND KMS_TRNACC.AWIEMP_NPK = '$npk' AND KMS_TRNACC.TRNACC_PERMISSION = 1";
        $queryAdd .= $tag == '' ? '' : " AND KMS_DETLBL.TRNLBL_ID = $tag";
        $searchBy = $keyword == '' ? '' : " AND LOWER(KMS_TRNHDR.TRNHDR_TITLE) LIKE '%$keyword%'";

        $query = $this->db->query(
            "   SELECT " . $column . " KMS_TRNHDR.*,
                    (   SELECT  COUNT(*)
                        FROM    KMS_TRNSUB
                        WHERE   KMS_TRNSUB.TRNHDR_ID    = KMS_TRNHDR.TRNHDR_ID
                        AND     KMS_TRNSUB.TRNSUB_STATUS= 1) AS detail_count,
                    (   SELECT  COUNT(*)
                        FROM    KMS_TRNACC
                        WHERE   KMS_TRNACC.TRNHDR_ID        = KMS_TRNHDR.TRNHDR_ID
                        AND     KMS_TRNACC.TRNACC_PERMISSION= 1) AS participant_count,
                    IIF((   SELECT COUNT(*)
                            FROM    KMS_TRNSUB
                            WHERE   KMS_TRNSUB.TRNHDR_ID    = KMS_TRNHDR.TRNHDR_ID
                            AND     KMS_TRNSUB.TRNSUB_STATUS= 2) > 0, 'true', 'false') AS detail_request,
                    IIF((   SELECT  COUNT(*)
                            FROM    KMS_TRNACC
                            WHERE   KMS_TRNACC.TRNHDR_ID        = KMS_TRNHDR.TRNHDR_ID
                            AND     KMS_TRNACC.TRNACC_PERMISSION= 2) > 0, 'true', 'false') AS participant_request
                FROM KMS_TRNHDR "
                . $table . "
                WHERE KMS_TRNHDR.TRNHDR_STATUS " . $status . $queryAdd . $searchBy . " 
                ORDER BY detail_request, participant_request DESC, KMS_TRNHDR.TRNHDR_STATUS DESC, KMS_TRNHDR.TRNHDR_TITLE"
        );

        return $query->result();
    }

    public function filterTraining($status)
    {
        $quer = '';
        if ($status == '> x') {
            $status = '> 0';
            $quer = "WHERE subquery.detail_request = 'true' OR subquery.participant_request = 'true'";
        }
        $query = $this->db->query(
            "   SELECT *
                FROM (
                    SELECT KMS_TRNHDR.*,
                        (   SELECT  COUNT(*)
                            FROM    KMS_TRNSUB
                            WHERE   KMS_TRNSUB.TRNHDR_ID    = KMS_TRNHDR.TRNHDR_ID
                            AND     KMS_TRNSUB.TRNSUB_STATUS= 1) AS detail_count,
                        (   SELECT COUNT(*)
                            FROM    KMS_TRNACC
                            WHERE   KMS_TRNACC.TRNHDR_ID        = KMS_TRNHDR.TRNHDR_ID
                            AND     KMS_TRNACC.TRNACC_PERMISSION= 1) AS participant_count,
                        IIF((   SELECT  COUNT(*) FROM KMS_TRNSUB
                                WHERE   KMS_TRNSUB.TRNHDR_ID    = KMS_TRNHDR.TRNHDR_ID
                                AND     KMS_TRNSUB.TRNSUB_STATUS= 2) > 0, 'true', 'false') AS detail_request,
                        IIF((   SELECT  COUNT(*) FROM KMS_TRNACC
                                WHERE   KMS_TRNACC.TRNHDR_ID        = KMS_TRNHDR.TRNHDR_ID
                                AND     KMS_TRNACC.TRNACC_PERMISSION= 2) > 0, 'true', 'false') AS participant_request
                    FROM KMS_TRNHDR
                    WHERE KMS_TRNHDR.TRNHDR_STATUS $status
                ) AS subquery " . $quer . "
                ORDER BY subquery.detail_request, subquery.participant_request DESC, TRNHDR_STATUS DESC, TRNHDR_TITLE"
        );

        return $query->result();
    }

    public function getSubstanceByTraining($id)
    {
        $status = $this->isAdmin() ? '> 0 AND TRNSUB_STATUS < 3' : '= 1';
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNSUB
                WHERE   TRNHDR_ID = $id
                AND     TRNSUB_STATUS " . $status
        );
        return $query->result();
    }

    public function getPackageByTraining($id)
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNPCK
                WHERE   TRNHDR_ID = $id
                AND     TRNPCK_STATUS = 1   "
        );
        return $query->result();
    }
    public function getParticipantByTraining($id)
    {
        $status = $this->isAdmin() ? '> 0 AND TRNACC_PERMISSION < 3' : '= 1';
        $query = $this->db->query(
            "   SELECT  AWIEMP_NPK
                FROM    KMS_TRNACC
                WHERE   TRNHDR_ID = $id
                AND     TRNACC_PERMISSION " . $status
        );
        return $query->result();
    }

    public function getAccessByNPKID($npk, $id)
    {
        $query = $this->db->query(
            "   SELECT  TRNACC_PERMISSION
                FROM    KMS_TRNACC
                WHERE   TRNHDR_ID = $id
                AND     AWIEMP_NPK = '$npk'                  "
        );
        return $query->row();
    }


    public function getResumePersonal($npk, $id)
    {
        $query = $this->db->query(
            "   SELECT  TRNACC_RESUME
                FROM    KMS_TRNACC
                WHERE   TRNHDR_ID = $id
                AND     AWIEMP_NPK = '$npk'                  "
        );
        if ($query->num_rows() > 0) {
            // If there are rows, return the result
            return $query->row();
        } else {
            // If there are no rows, return an object with TRNACC_RESUME set to null
            return (object) array('TRNACC_RESUME' => null);
        }
    }

    public function getProgress($id, $npk)
    {
        $query = $this->db->query(
            "   WITH Counts AS (
                    SELECT 
                    (   SELECT  COUNT(*) 
                        FROM    KMS_TRNPRG
                        JOIN    KMS_TRNSUB
                            ON  KMS_TRNSUB.TRNSUB_ID    = KMS_TRNPRG.TRNSUB_ID 
                        WHERE   KMS_TRNPRG.AWIEMP_NPK   = '$npk'
                        AND     KMS_TRNSUB.TRNHDR_ID    = $id 
                        AND     KMS_TRNSUB.TRNSUB_STATUS= 1
                    ) AS progress_count,
                    (   SELECT  COUNT(*) 
                        FROM    KMS_TRNSUB
                        JOIN    KMS_TRNACC
                            ON  KMS_TRNACC.TRNHDR_ID        = KMS_TRNSUB.TRNHDR_ID 
                        WHERE   KMS_TRNACC.AWIEMP_NPK       = '$npk'
                        AND     KMS_TRNSUB.TRNHDR_ID        = $id
                        AND     KMS_TRNACC.TRNACC_PERMISSION= 1
                        AND     KMS_TRNSUB.TRNSUB_STATUS    = 1
                    ) AS total_count
                )
                SELECT 
                    CONCAT(
                        COALESCE(progress_count, 0),
                        '/',
                        COALESCE(total_count, 0)
                    ) AS progress,
                    ISNULL(
                        CAST(
                            COALESCE(progress_count, 0) * 100.0 / NULLIF(COALESCE(total_count, 0), 0) 
                            AS DECIMAL(10,2)
                        ), 
                        0
                    ) AS percentage
                FROM Counts"
        );
        return $query->row();
    }

    public function hasRead($id)
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT  COUNT(*) as TOTAL
                FROM    KMS_TRNPRG
                WHERE   AWIEMP_NPK  = '$npk'
                AND     TRNSUB_ID   = $id   "
        );
        return $query->row()->TOTAL > 0;
    }

    public function getAccessData($npk, $id)
    {
        $query = $this->db->query(
            "   SELECT  TOP 1 
                    COALESCE(TRNACC_FILE, 0) AS TRNACC_FILE,
                    COALESCE(TRNACC_PART, 0) AS TRNACC_PART
                FROM    KMS_TRNACC
                WHERE   AWIEMP_NPK          = '$npk'
                AND     TRNACC_PERMISSION   = 1
                AND     TRNHDR_ID           = $id
                UNION ALL
                SELECT 
                    0, 0
                WHERE   NOT EXISTS (
                    SELECT  1
                    FROM    KMS_TRNACC 
                    WHERE   AWIEMP_NPK          = '$npk'
                    AND     TRNHDR_ID           = $id
                    AND     TRNACC_PERMISSION   = 1
                )"
        );
        return $query->result();
    }

    public function isLabelExist($idTag, $id)
    {
        $query = $this->db->query(
            "   SELECT  COUNT(*) AS TOTAL
                FROM    KMS_DETLBL
                WHERE   TRNHDR_ID   = $id
                AND     TRNLBL_ID   = $idTag    "
        );
        return $query->row()->TOTAL > 0;
    }

    //notif
    public function getNotif($npk)
    {
        $query = $this->db->query(
            "   SELECT  KMS_TRNHDR.TRNHDR_TITLE AS judul, KMS_TRNHDR.TRNHDR_ID AS id_training_header,
                    CONVERT(DATE, KMS_TRNACC.TRNACC_CREADATE) AS created_date, KMS_TRNACC.AWIEMP_NPK AS npk
                FROM    KMS_TRNACC
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_TRNHDR.TRNHDR_ID = KMS_TRNACC.TRNHDR_ID
                WHERE   KMS_TRNACC.TRNACC_PERMISSION = 3
                AND     KMS_TRNACC.TRNACC_CREABY = '$npk'                                          "
        );
        return $query->result();
    }

    public function getNotifMateri($npk)
    {
        $query = $this->db->query(
            "   SELECT  KMS_TRNSUB.TRNSUB_TITLE AS judul_training_detail,
                    KMS_TRNHDR.TRNHDR_TITLE AS judul,
                    KMS_TRNSUB.TRNSUB_ID AS id_training_detail
                FROM    KMS_TRNSUB
                INNER JOIN KMS_TRNHDR
                    ON  KMS_TRNHDR.TRNHDR_ID = KMS_TRNSUB.TRNHDR_ID
                WHERE   KMS_TRNSUB.TRNSUB_STATUS ='3'
                AND     KMS_TRNSUB.TRNSUB_CREABY = '$npk'                              "
        );
        return $query->result();
    }

    public function getNotifRejectApproveFPET($npk)
    {
        $query = $this->db->query(
            "SELECT  *
            FROM    KMS_FPETFM
            WHERE   (FPETFM_HRAPPROVED = '3'
            OR     FPETFM_APPROVED = '3')  
            AND     FPETFM_CREABY = $npk AND  FPETFM_STATUS != '0'                        "
        );
        return $query->result();
    }

    public function removeNotif($id, $npk)
    {
        $data = array(
            'TRNACC_PERMISSION' => 0,
            'TRNACC_MODIBY'     => $this->session->userdata('npk'),
            'TRNACC_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'TRNHDR_ID'    => $id,
            'AWIEMP_NPK' => $npk
        );

        return $this->db->update($this->t_access, $data, $where);
    }

    public function removeNotifMateri($id)
    {
        $data = array(
            'TRNSUB_STATUS'        => 0,
            'TRNSUB_MODIBY'   => $this->session->userdata('npk'),
            'TRNSUB_MODIDATE' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'TRNSUB_ID'    => $id
        );

        return $this->db->update($this->t_detail, $data, $where);
    }

    public function removeNotifFPET($id, $code)
    {
        $data = array(
            'FPETFM_MODIBY'   => $this->session->userdata('npk'),
            'FPETFM_MODIDATE' => date('Y/m/d H:i:s'),
        );

        if ($code == 1) {
            $data['FPETFM_APPROVED'] = 0;
        } else if ($code == 2) {
            $data['FPETFM_HRAPPROVED'] = 0;
        }

        $where = array(
            'FPETFM_ID' => $id
        );

        return $this->db->update("KMS_FPETFM", $data, $where);
    }

    public function checkPretest($npk, $id)
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT  TRNACC_PRESCORE as preScore, TRNACC_POSTSCORE as postScore
                FROM    KMS_TRNACC
                WHERE   TRNHDR_ID   = $id
                AND     AWIEMP_NPK  = $npk  "
        );

        return $query->result();
    }

    public function modifyResume($data, $idHeader)
    {
        $where = array(
            'TRNHDR_ID'    => $idHeader,
            'AWIEMP_NPK'   => $this->session->userdata('npk'),
        );

        return $this->db->update('KMS_TRNACC', $data, $where);
    }
}
