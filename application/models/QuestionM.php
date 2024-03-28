<?php defined('BASEPATH') or exit('No direct script access allowed');

class QuestionM extends CI_Model
{
    private $t_question = "KMS_TRNQUE";
    private $t_package = "KMS_TRNPCK";

    public function getPackages()
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNPCK
                WHERE   TRNPCK_STATUS   = 1 "
        );
        return $query->result();
    }

    public function getPackage($id)
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNPCK
                WHERE   TRNPCK_ID       = $id
                AND     TRNPCK_STATUS   = 1     "
        );
        return $query->row();
    }

    public function getQuestions($id)
    {
        $query = $this->db->query(
            "   SELECT  *
                FROM    KMS_TRNQUE
                WHERE   TRNQUE_STATUS   = 1        
                AND     TRNPCK_ID       = $id   "
        );
        return $query->result();
    }

    public function saveQuestion($data)
    {
        return $this->db->insert($this->t_question, $data);
    }

    public function editQuestion($data, $where)
    {
        return $this->db->update($this->t_question, $data, $where);
    }

    public function savePackage($data)
    {
        return $this->db->insert($this->t_package, $data);
    }

    public function editPackage($data, $where)
    {
        return $this->db->update($this->t_package, $data, $where);
    }

    public function getGlobalScore()
    {
        $query = $this->db->query(
            "   SELECT  *, KMS_TRNPCK.TRNHDR_ID, KMS_TRNPCK.TRNPCK_NAME 
                FROM    KMS_TRNACC
                inner join  KMS_TRNPCK
                    ON  KMS_TRNACC.TRNPCK_ID_PRE = KMS_TRNPCK.TRNPCK_ID
           "
        );
        return $query->result();
    }

    public function getPostExam($id)
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT  *, KMS_TRNPCK.TRNPCK_NAME AS package_name
                FROM    KMS_TRNQUE
                INNER JOIN  KMS_TRNPCK
                    ON      KMS_TRNPCK.TRNPCK_ID = KMS_TRNQUE.TRNPCK_ID
                WHERE   KMS_TRNQUE.TRNPCK_ID IN 
                    (   SELECT  TRNPCK_ID_POST
                        FROM    KMS_TRNACC
                        WHERE   AWIEMP_NPK  = $npk
                        AND     TRNHDR_ID   = $id   )"
        );
        return $query->result();
    }

    public function getPreExam($id)
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT  *, KMS_TRNPCK.TRNPCK_NAME AS package_name
                FROM    KMS_TRNQUE
                INNER JOIN  KMS_TRNPCK
                    ON      KMS_TRNPCK.TRNPCK_ID = KMS_TRNQUE.TRNPCK_ID
                WHERE   KMS_TRNQUE.TRNPCK_ID IN 
                    (   SELECT  TRNPCK_ID_PRE
                        FROM    KMS_TRNACC
                        WHERE   AWIEMP_NPK  = $npk
                        AND     TRNHDR_ID   = $id   )"
        );
        return $query->result();
    }

    public function getMaxQuestShow()
    {
        $query = $this->db->query(
            "   SELECT  SETTING_VALUE
                FROM    KMS_SETTING
                WHERE   SETTING_KEY = 'TRNQUE_MAX'"
        );
        return $query->row()->SETTING_VALUE;
    }

    public function getpackageQuest($id)
    {
        $query = $this->db->query(
            "   SELECT  * 
                FROM    KMS_TRNPCK
                WHERE   TRNHDR_ID = $id "
        );
        return $query->result();
    }

    public function getTotalQuestion($idPackage)
    {
        $query = $this->db->query(
            "   SELECT  COUNT(*) as totalQ
                FROM    KMS_TRNQUE
                WHERE   TRNQUE_STATUS   = 1
                AND     TRNPCK_ID       = $idPackage    "
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->totalQ;
    }

    public function getAnswerKey($id)
    {
        $query = $this->db->query(
            "   SELECT  TRNQUE_ANSWER
                FROM    KMS_TRNQUE
                WHERE   TRNQUE_ID       = $id
                AND     TRNQUE_STATUS   = 1     "
        );
        return $query->row()->TRNQUE_ANSWER;
    }

    // public function saveAnswerUser($data)
    // {
    //     return $this->db->insert('training_userAnswer', $data);
    // }

    public function savePreExam($data, $npk, $idTraining)
    {
        $where = array(
            'AWIEMP_NPK'=> $npk,
            'TRNHDR_ID' => $idTraining
        );
        return $this->db->update('KMS_TRNACC', $data, $where);
    }

    public function savePostExam($data)
    {
        return $this->db->insert('training_score', $data);
    }

    public function checkPreOrPost($npk, $idTraining)
    {
        $query = $this->db->query(
            "   SELECT  TRNACC_PRESCORE as preOrPost
                FROM    KMS_TRNACC
                WHERE   AWIEMP_NPK = $npk
                AND     TRNHDR_ID = $idTraining  "
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->preOrPost;
    }
}