<?php defined('BASEPATH') or exit('No direct script access allowed');

class AdminM extends CI_Model
{
    private $t_admin = "KMS_ADMIN";
    private $t_label = "KMS_TRNLBL";

    public function saveAdmin($data)
    {
        return $this->db->insert($this->t_admin, $data);
    }

    public function deleteAdmin($data, $where)
    {
        return $this->db->update($this->t_admin, $data, $where);
    }

    public function saveTag($data)
    {
        return $this->db->insert($this->t_label, $data);
    }

    public function deleteLabel($data, $where)
    {
        return $this->db->update($this->t_label, $data, $where);
    }

    public function isNpkAdmin($npk)
    {
        $query = $this->db->query(
            "   SELECT  TOP 1 1
                FROM    KMS_ADMIN
                WHERE   AWIEMP_NPK      = '$npk'
                AND     ADMAPP_STATUS   = 1     "
        );
        return $query->num_rows() > 0;
    }

    public function getAdmins()
    {
        $query = $this->db->query(
            "   SELECT  AWIEMP_NPK
                FROM    KMS_ADMIN
                WHERE   ADMAPP_STATUS = 1 "
        );
        return $query->result();
    }

    public function getTags()
    {
        $query = $this->db->query(
            "   SELECT  KMS_TRNLBL.*,
                (   SELECT  COUNT(*)
                    FROM    KMS_DETLBL
                    WHERE   KMS_DETLBL.TRNLBL_ID    = KMS_TRNLBL.TRNLBL_ID
                ) AS    TRNLBL_TOTAL
                FROM    KMS_TRNLBL
                WHERE   KMS_TRNLBL.TRNLBL_STATUS    = 1
                ORDER BY    KMS_TRNLBL.TRNLBL_NAME;"
        );
        return $query->result();
    }

    public function getTagsByID($id)
    {
        $query = $this->db->query(
            "   SELECT *
                FROM    KMS_DETLBL
                INNER JOIN  KMS_TRNLBL
                    ON  KMS_DETLBL.TRNLBL_ID    = KMS_TRNLBL.TRNLBL_ID
                WHERE   KMS_DETLBL.TRNHDR_ID    = $id                   "
        );
        return $query->result();
    }

    public function getAdminTotal()
    {
        $query = $this->db->query(
            "   SELECT  COUNT(*) AS TOTAL
                FROM    KMS_ADMIN
                WHERE   ADMAPP_STATUS   = 1 "
        );
        return $query->row()->TOTAL;
    }

    public function getLabelTotal($id)
    {
        $query = $this->db->query(
            "   SELECT  COUNT(*) AS TOTAL
                FROM    KMS_DETLBL
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_DETLBL.TRNHDR_ID        = KMS_TRNHDR.TRNHDR_ID
                WHERE   KMS_TRNHDR.TRNHDR_STATUS    >= 1
                AND     KMS_DETLBL.TRNLBL_ID        = $id                   "
        );
        return $query->row()->TOTAL;
    }
}
