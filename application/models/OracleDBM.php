<?php defined('BASEPATH') or exit('No direct script access allowed');

class OracleDBM extends CI_Model
{
    private $second_db;
    
    public function __construct()
    {
        parent::__construct();
        $this->second_db = $this->load->database('orcl', TRUE);
    }

    public function getEmpByNPK($value)
    {
        $query = $this->second_db->query(
            "   SELECT  NPK AS NPK, NAMA AS NAMA, NM_SIE AS DEPARTEMEN
                FROM    VW_DATA_KAR
                WHERE   NPK = '$value'
                ORDER BY NAMA                                           "
        );
        return $query->row();
    }

    public function getAllEmp()
	{
        $query = $this->second_db->query(
            "   SELECT  NPK AS NPK, NAMA AS NAMA, NM_SIE AS DEPARTEMEN
                FROM    VW_DATA_KAR
                ORDER BY    NAMA                                        "
        );
        return $query->result();
	}

    public function getAllDept()
	{
        $query = $this->second_db->query(
            "   SELECT  NM_SIE AS DEPARTEMEN
                FROM    VW_DATA_KAR
                GROUP BY    NM_SIE
                ORDER BY    NM_SIE          "
        );
        return $query->result();
	}

    public function getEmployeeByKeyword()
	{
		$key = strtolower($this->input->post('search_employee'));
        $code = $this->input->post('code');

        $quer = $code == 'ALL' ? '' : "AND NM_SIE = '$code'";
        $query = $this->second_db->query(
            "   SELECT  NPK AS NPK, NAMA AS NAMA,   NM_SIE AS DEPARTEMEN
                FROM    VW_DATA_KAR
                WHERE   LOWER(NAMA) LIKE '%$key%'
                " . $quer . "
                ORDER BY    NAMA                                           "
        );
        if ($key == '' && $code == 'ALL') return $this->getAllEmp();
		return $query->result();
	}
}   