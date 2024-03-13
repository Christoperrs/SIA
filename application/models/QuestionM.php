<?php defined('BASEPATH') or exit('No direct script access allowed');

class QuestionM extends CI_Model
{
    private $t_question = "training_question";

    public function getQuestions($id)
    {
        $query = $this->db->query(
            "   SELECT question_id, question, answer AS answerSelect,
                q_level AS levelSelect, a AS aOption, b AS bOption,
                c AS cOption, d AS dOption, package_id
                FROM $this->t_question
                WHERE status = 1        
                AND package_id = $id    "
        );
        return $query->result();
    }

    public function getTrains()
    {
        $query = $this->db->query(
            "   SELECT *
                FROM training_header
                WHERE status != 0        "
        );
        return $query->result();
    }
    public function getQuestion($id)
    {
        $query = $this->db->query(
            "   SELECT  question_id, question, answer AS answerSelect,
                        q_level AS levelSelect, a AS aOption, b AS bOption,
                        c AS cOption, d AS dOption
                FROM $this->t_question
                WHERE question_id = $id AND status = 1"
        );
        return $query->row();
    }

    public function saveQuestion($data)
    {
        return $this->db->insert($this->t_question, $data);
    }

    public function editQuestion($data, $where)
    {
        return $this->db->update($this->t_question, $data, $where);
    }

    public function deleteQuestion($id)
    {
        $data = array(
            'status'        => 0,
            'modified_date' => date('Y/m/d H:i:s'),
            'modified_by'   => $this->session->userdata('npk'),
        );
        $where = array(
            'question_id'    => $id
        );
        return $this->db->update($this->t_question, $data, $where);
    }

    public function getPackage($id)
    {
        $query = $this->db->query(
            "   SELECT *
                FROM training_question_package
                WHERE package_id = $id and status = 1"
        );
        return $query->row();
    }

    public function getPackages()
    {
        $query = $this->db->query(
            "   SELECT *
                FROM training_question_package
                WHERE status = 1"
        );
        return $query->result();
    }
    public function savePackage()
    {
        $data = array(
            'status'            => 1,
            'package_uniqueId'  => $this->input->post('idUniqPaket'),
            'package_name'      => $this->input->post('namePaket'),
            'training_id'       => $this->input->post('chooseTrain'),
            'created_date'      => date('Y/m/d H:i:s'),
            'modified_date'     => date('Y/m/d H:i:s'),
            'created_by'        => $this->session->userdata('npk'),
            'modified_by'       => $this->session->userdata('npk'),
        );
        return $this->db->insert('training_question_package', $data);
    }

    public function editPackage()
    {
        $data = array(
            'package_name'      => $this->input->post('namePaket'),
            'package_uniqueId'  => $this->input->post('idUniqPaket'),
            'training_id'       => $this->input->post('chooseTrain'),
            'modified_date'     => date('Y/m/d H:i:s'),
            'modified_by'       => $this->session->userdata('npk'),
        );
        $where = array(
            'package_id'    => $this->input->post('package_id')
        );
        return $this->db->update('training_question_package', $data, $where);
    }

    public function deletePackage($id)
    {
        $data = array(
            'status'        => 0,
            'modified_date' => date('Y/m/d H:i:s'),
            'modified_by'   => $this->session->userdata('npk'),
        );
        $where = array(
            'package_id'    => $id
        );
        return $this->db->update('training_question_package', $data, $where);
    }


    public function getGlobalScore()
    {
        $query = $this->db->query(
            "  SELECT *, tp.training_id, tp.package_name 
            FROM training_score as ts inner join training_question_package as tp  on ts.package_id = tp.package_id
           "
        );
        return $query->result();
    }

    public function getPreExam($id)
    {
        $query = $this->db->query(
            "  SELECT * 
            FROM training_question 
            WHERE package_id IN (SELECT package_id 
                                 FROM training_question_package 
                                 WHERE status = 1 AND training_id = $id) "
        );
        return $query->result();
    }

    public function getpackageQuest($id)
    {
        $query = $this->db->query(
            "  SELECT * 
            FROM training_question_package
            where training_id = $id "
        );
        return $query->result();
    }

    public function getTotalQuestion($idPackage)
    {
        $query = $this->db->query(
            "SELECT COUNT(*) as totalQ
                 FROM training_question
                 WHERE status = 1 and package_id = $idPackage  "
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->totalQ;
    }

    public function getAnswerKey($id)
    {
        $query = $this->db->query(
            "   SELECT  answer
                FROM $this->t_question
                WHERE question_id = $id AND status = 1"
        );
        return $query->row();
    }

    // public function saveAnswerUser($data)
    // {
    //     return $this->db->insert('training_userAnswer', $data);
    // }

    public function savePreExam($data)
    {
        return $this->db->insert('training_score', $data);
    }

    public function savePostExam($data)
    {
        return $this->db->insert('training_score', $data);
    }
}
