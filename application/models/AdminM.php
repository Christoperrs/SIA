<?php defined('BASEPATH') or exit('No direct script access allowed');

class AdminM extends CI_Model
{
    private $t_admin = "admin";
    private $t_tag = "training_tag";
    private $t_tagdetail = "training_tag_detail";

    public function saveAdmin($npk)
    {
        $data = array(
            'npk'           => $npk,
            'status'        => 1,
            'created_date'  => date('Y/m/d H:i:s'),
            'created_by'    => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
            'modified_by'   => $this->session->userdata('npk'),
        );
        return $this->db->insert($this->t_admin, $data);
    }

    public function deleteAdmin($npk)
    {
        $data = array(
            'status'        => 0,
            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'npk'    => $npk
        );
        return $this->db->update($this->t_admin, $data, $where);
    }

    public function saveTag($name, $color)
    {
        $data = array(
            'name_tag'      => $name,
            'color'         => $color,
            'created_date'  => date('Y/m/d H:i:s'),
            'created_by'    => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
            'modified_by'   => $this->session->userdata('npk'),
            'status'        => 1,
        );
        return $this->db->insert($this->t_tag, $data);
    }

    public function deleteTag($id)
    {
        $data = array(
            'status'        => 0,
            'modified_by'   => $this->session->userdata('npk'),
            'modified_date' => date('Y/m/d H:i:s'),
        );
        $where = array(
            'id_tag'    => $id
        );
        return $this->db->update($this->t_tag, $data, $where);
    }

    public function isNpkAdmin($npk)
    {
        return $this->db->where('npk', $npk)
            ->where('status', 1)
            ->get($this->t_admin)
            ->row() !== null;
    }

    public function getAdmins()
    {
        $query = $this->db->query(
            "   SELECT npk
                FROM $this->t_admin
                WHERE status = 1    "
        );
        return $query->result();
    }

    public function getTags()
    {
        $query = $this->db->query(
            "   SELECT h.*,
                (   SELECT COUNT(*)
                    FROM $this->t_tagdetail d
                    WHERE d.id_tag = h.id_tag
                ) AS total
                FROM $this->t_tag h
                WHERE h.status = 1
                ORDER BY h.name_tag"
        );
        return $query->result();
    }

    public function getTagsByID($id)
    {
        $query = $this->db->query(
            "   SELECT *
                FROM $this->t_tagdetail td
                INNER JOIN $this->t_tag t
                    ON td.id_tag = t.id_tag
                WHERE td.id_training_header = $id   "
        );
        return $query->result();
    }

    public function getAdminTotal()
    {
        $query = $this->db->query(
            "   SELECT COUNT(*) AS total
                FROM $this->t_admin
                WHERE status = 1           "
        );
        return $query->row()->total;
    }

    public function getCountTag($id)
    {
        $query = $this->db->query(
            "select count(*) as total from	training_tag_detail tg inner join training_header th on tg.id_training_header = th.id_training_header where th.status >= '1' and tg.id_tag = $id"
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->total;
    }
}
