<?php defined('BASEPATH') or exit('No direct script access allowed');

class ChartM extends CI_Model
{

    private $t_header = "KMS_TRNHDR";
    private $t_detail = "KMS_TRNSUB";
    private $t_access = "KMS_TRNACC";
    private $t_progress = "KMS_TRNPRG";

    //chart
    public function getCountTraining()
    {
        $query = $this->db->query(
            "   SELECT  COUNT(*) AS COUNT
                FROM    KMS_TRNHDR
                WHERE   TRNHDR_STATUS = 2   "
        );

        return $query->row()->COUNT;
    }

    public function getCountSubstance()
    {
        $query = $this->db->query(
            "   SELECT  COUNT(*) AS COUNT
                FROM    KMS_TRNSUB
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                WHERE   KMS_TRNHDR.TRNHDR_STATUS    = 2
                AND     KMS_TRNSUB.TRNSUB_STATUS    = 1             "
        );

        return $query->row()->COUNT;
    }

    public function getCountDoneLesson()
    {
        $query = $this->db->query(
            "   SELECT  COUNT(DISTINCT KMS_TRNPRG.TRNSUB_ID) AS COUNT
                FROM    KMS_TRNPRG
                INNER JOIN  KMS_TRNSUB
                    ON  KMS_TRNSUB.TRNSUB_ID = KMS_TRNPRG.TRNSUB_ID
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                WHERE   KMS_TRNPRG.TRNPRG_STATUS >= 1
                AND     KMS_TRNHDR.TRNHDR_STATUS = 2
                AND     KMS_TRNSUB.TRNSUB_STATUS = 1;
                
                "
        );

        return $query->row()->COUNT;
    }

    public function getCountNotDoneEmp()
    {
        $query = $this->db->query(
            "   SELECT
                    CASE
                        WHEN
                        (   SELECT  COUNT(*) AS count_value
                            FROM    KMS_TRNSUB
                            INNER JOIN  KMS_TRNHDR
                                ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                            WHERE   KMS_TRNHDR.TRNHDR_STATUS = 2
                            AND     KMS_TRNSUB.TRNSUB_STATUS = 1
                        ) = 0 THEN 0  -- Handling divide by zero by returning 0
                        ELSE
                        (
                            (   SELECT  COUNT(*) AS count_value
                                FROM    KMS_TRNSUB
                                INNER JOIN  KMS_TRNHDR
                                    ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                WHERE   KMS_TRNHDR.TRNHDR_STATUS = 2
                                AND     KMS_TRNSUB.TRNSUB_STATUS = 1
                            ) - 
                            (   SELECT  COUNT(DISTINCT KMS_TRNSUB.TRNSUB_ID) AS count_value
                                FROM    KMS_TRNPRG
                                INNER JOIN  KMS_TRNSUB
                                    ON  KMS_TRNSUB.TRNSUB_ID = KMS_TRNPRG.TRNSUB_ID
                                INNER JOIN  KMS_TRNHDR
                                    ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                WHERE   KMS_TRNPRG.TRNPRG_STATUS >= 1
                                AND     KMS_TRNHDR.TRNHDR_STATUS = 2
                                AND     KMS_TRNSUB.TRNSUB_STATUS = 1
                            )
                        ) * 100 /
                        (   SELECT  COUNT(*) AS count_value
                            FROM    KMS_TRNSUB
                            INNER JOIN  KMS_TRNHDR
                                ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                            WHERE   KMS_TRNHDR.TRNHDR_STATUS = 2
                            AND     KMS_TRNSUB.TRNSUB_STATUS = 1
                        )
                    END AS result;"
        );

        return $query->row()->result;
    }


    public function getFavoriteSubstance()
    {
        $query = $this->db->query(
            "WITH RankedProgress AS (
                    SELECT
                        KMS_TRNPRG.TRNSUB_ID,
                        KMS_TRNPRG.AWIEMP_NPK,
                        KMS_TRNPRG.TRNPRG_STATUS,
                        KMS_TRNPRG.TRNPRG_CREADATE,
                        SUM(KMS_TRNPRG.TRNPRG_STATUS) OVER (PARTITION BY KMS_TRNPRG.TRNSUB_ID) AS total,
                        KMS_TRNHDR.TRNHDR_TITLE,
                        KMS_TRNSUB.TRNSUB_TITLE
                    FROM
                        KMS_TRNPRG
                        INNER JOIN  KMS_TRNSUB
                            ON  KMS_TRNSUB.TRNSUB_ID = KMS_TRNPRG.TRNSUB_ID
                        INNER JOIN  KMS_TRNHDR
                            ON  KMS_TRNHDR.TRNHDR_ID = KMS_TRNSUB.TRNHDR_ID
                        WHERE   KMS_TRNSUB.TRNSUB_STATUS = 1
                        AND     KMS_TRNHDR.TRNHDR_STATUS = 2
                )
                SELECT top 10
                    rp.TRNSUB_ID,
                    MAX([total]) AS total,
                    rp.TRNHDR_TITLE,
                    rp.TRNSUB_TITLE
                FROM
                    RankedProgress rp
                GROUP BY
                    rp.TRNSUB_ID,
                    rp.TRNHDR_TITLE,
                    rp.TRNSUB_TITLE
                ORDER BY
                    MAX([total]) DESC;
                "
        );

        return $query->result();
    }

    public function getFavoriteTraining()
    {
        $query = $this->db->query(
            "  WITH RankedProgress AS (
                    SELECT
                        KMS_TRNPRG.TRNSUB_ID,
                        KMS_TRNPRG.AWIEMP_NPK,
                        KMS_TRNPRG.TRNPRG_STATUS,
                        KMS_TRNPRG.TRNPRG_CREADATE,
                        SUM(KMS_TRNPRG.TRNPRG_STATUS) OVER (PARTITION BY KMS_TRNPRG.TRNSUB_ID) AS total,
                        KMS_TRNHDR.TRNHDR_TITLE,
                        KMS_TRNHDR.TRNHDR_ID,
                        KMS_TRNSUB.TRNSUB_TITLE
                    FROM
                        KMS_TRNPRG
                        INNER JOIN KMS_TRNSUB
                            ON  KMS_TRNSUB.TRNSUB_ID = KMS_TRNPRG.TRNSUB_ID
                        INNER JOIN KMS_TRNHDR
                            ON  KMS_TRNHDR.TRNHDR_ID = KMS_TRNSUB.TRNHDR_ID
                        WHERE   KMS_TRNSUB.TRNSUB_STATUS = 1
                        AND     KMS_TRNHDR.TRNHDR_STATUS = 2
                )
                , AggregatedTotal AS (
                    SELECT
                        rp.TRNSUB_ID,
                        rp.TRNHDR_ID,
                        MAX([total]) AS total,
                        rp.TRNHDR_TITLE,
                        rp.TRNSUB_TITLE,
                        ROW_NUMBER() OVER (PARTITION BY rp.TRNHDR_ID ORDER BY MAX([total]) DESC) AS RowNum
                    FROM
                        RankedProgress rp
                    GROUP BY
                        rp.TRNSUB_ID,
                        rp.TRNHDR_ID,
                        rp.TRNHDR_TITLE,
                        rp.TRNSUB_TITLE
                )
                SELECT top 10
                    at.TRNSUB_ID,
                    at.TRNHDR_ID,
                    at.total AS total,
                    at.TRNHDR_TITLE TRNHDR_TITLE,
                    at.TRNSUB_TITLE,
                    SUM(at.total) OVER (PARTITION BY at.TRNHDR_ID) AS total2
                FROM
                    AggregatedTotal at
                WHERE
                    at.RowNum = 1
                ORDER BY
                    at.TRNHDR_ID; 
                "
        );

        return $query->result();
    }

    public function getHighestEmployee()
    {
        $status = $this->session->userdata('role') == 'admin' ? '> 0' : '= 2';
        $query = $this->db->query(
            "  SELECT TOP 10 KMS_TRNACC.AWIEMP_NPK, SUM(KMS_TRNPRG.TRNPRG_STATUS) AS total_progress
            FROM KMS_TRNPRG
            INNER JOIN KMS_TRNACC ON KMS_TRNPRG.AWIEMP_NPK = KMS_TRNACC.AWIEMP_NPK 
            INNER JOIN KMS_TRNSUB ON KMS_TRNSUB.TRNSUB_ID = KMS_TRNPRG.TRNSUB_ID
            INNER JOIN KMS_TRNHDR h ON KMS_TRNSUB.TRNHDR_ID = h.TRNHDR_ID
            WHERE KMS_TRNACC.TRNACC_PERMISSION = '1' -- and KMS_TRNSUB.status = '1' and h.status = '2'
            GROUP BY KMS_TRNACC.AWIEMP_NPK
            ORDER BY total_progress DESC;
            
            
             "
        );

        return $query->result();
    }

    public function getNotDoneEmployee()
    {
        $status = $this->session->userdata('role') == 'admin' ? '> 0' : '= 2';
        $query = $this->db->query(
            "   
                SELECT DISTINCT KMS_TRNACC.AWIEMP_NPK
                FROM KMS_TRNACC
                INNER JOIN KMS_TRNHDR ON KMS_TRNHDR.TRNHDR_ID = KMS_TRNACC.TRNHDR_ID
                INNER JOIN KMS_TRNSUB ON KMS_TRNHDR.TRNHDR_ID = KMS_TRNSUB.TRNHDR_ID
                WHERE KMS_TRNACC.TRNACC_PERMISSION = '1' AND KMS_TRNHDR.TRNHDR_STATUS = '2' AND KMS_TRNSUB.TRNSUB_STATUS = '1'
                AND KMS_TRNACC.AWIEMP_NPK NOT IN (
                    SELECT ta1.AWIEMP_NPK
                    FROM KMS_TRNACC ta1
                    INNER JOIN KMS_TRNHDR th1 ON th1.TRNHDR_ID = ta1.TRNHDR_ID
                    INNER JOIN KMS_TRNSUB td1 ON th1.TRNHDR_ID = td1.TRNHDR_ID
                    INNER JOIN KMS_TRNPRG tp1 ON tp1.TRNSUB_ID = td1.TRNSUB_ID
                    WHERE ta1.TRNACC_PERMISSION = '1' AND th1.TRNHDR_STATUS = '2' AND td1.TRNSUB_STATUS = '1'
                );
                
              "
        );

        return $query->result();
    }

    public function getNotOpenTrain()
    {
        $status = $this->session->userdata('role') == 'admin' ? '> 0' : '= 2';
        $query = $this->db->query(
            "   
                SELECT DISTINCT KMS_TRNACC.AWIEMP_NPK
              FROM KMS_TRNACC
              INNER JOIN KMS_TRNHDR ON KMS_TRNHDR.TRNHDR_ID = KMS_TRNACC.TRNHDR_ID
              INNER JOIN KMS_TRNSUB ON KMS_TRNHDR.TRNHDR_ID = KMS_TRNSUB.TRNHDR_ID
              WHERE KMS_TRNACC.TRNACC_PERMISSION = 1 AND KMS_TRNHDR.TRNHDR_STATUS = 2 AND KMS_TRNSUB.TRNSUB_STATUS = 1
                AND KMS_TRNACC.AWIEMP_NPK NOT IN (
                  SELECT DISTINCT ta1.AWIEMP_NPK
                  FROM KMS_TRNACC ta1
                  INNER JOIN KMS_TRNHDR th1 ON th1.TRNHDR_ID = ta1.TRNHDR_ID
                  INNER JOIN KMS_TRNSUB td1 ON th1.TRNHDR_ID = td1.TRNHDR_ID
                  INNER JOIN KMS_TRNPRG tp1 ON tp1.TRNSUB_ID = td1.TRNSUB_ID
                  WHERE ta1.TRNACC_PERMISSION = 1 AND th1.TRNHDR_STATUS = 2 AND td1.TRNSUB_STATUS = 1
                );
              "
        );

        return $query->result();
    }

    public function getNotDoneLesson()
    {
        $status = $this->session->userdata('role') == 'admin' ? '> 0' : '= 2';
        $query = $this->db->query(
            "   SELECT DISTINCT KMS_TRNACC.AWIEMP_NPK, KMS_TRNHDR.TRNHDR_TITLE as TRNHDR_TITLE, KMS_TRNSUB.TRNSUB_TITLE as TRNSUB_TITLE
                FROM KMS_TRNACC
                INNER JOIN KMS_TRNHDR ON KMS_TRNHDR.TRNHDR_ID = KMS_TRNACC.TRNHDR_ID
                INNER JOIN KMS_TRNSUB ON KMS_TRNHDR.TRNHDR_ID = KMS_TRNSUB.TRNHDR_ID
                WHERE   KMS_TRNACC.TRNACC_PERMISSION = 1
                AND     KMS_TRNHDR.TRNHDR_STATUS = 2
                AND     KMS_TRNSUB.TRNSUB_STATUS = 1
              "
        );

        return $query->result();
    }

    public function getTrendAccess()
    {
        $query = $this->db->query(
            "   
                SELECT
                CONCAT(
                    YEAR(TRNPRG_MODIDATE),
                    ' ',
                    FORMAT(TRNPRG_MODIDATE, 'MMMM', 'id-ID')
                ) AS YearMonth,
                COUNT(*) AS RecordCount
            FROM
                KMS_TRNPRG
            WHERE
                TRNPRG_MODIDATE >= DATEADD(MONTH, -5, GETDATE()) 
            GROUP BY
                YEAR(TRNPRG_MODIDATE),
                FORMAT(TRNPRG_MODIDATE, 'MMMM', 'id-ID'),
                MONTH(TRNPRG_MODIDATE)
            ORDER BY
                YEAR(TRNPRG_MODIDATE) ASC,
                MONTH(TRNPRG_MODIDATE) ASC;
            
            
              "
        );

        return $query->result();
    }

    public function getCountMyTraining()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT  COUNT(*) AS COUNT 
                FROM    KMS_TRNACC
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_TRNHDR.TRNHDR_ID = KMS_TRNACC.TRNHDR_ID
                WHERE   KMS_TRNACC.TRNACC_PERMISSION = '1'
                AND     KMS_TRNHDR.TRNHDR_STATUS = '2'
                AND     KMS_TRNACC.AWIEMP_NPK = '$npk'"
        );

        return $query->row()->COUNT;
    }


    public function getCountMySubstance()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT  COALESCE(COUNT(*), 0) AS COUNT
                FROM    KMS_TRNSUB
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                INNER JOIN  KMS_TRNACC
                    ON  KMS_TRNACC.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                WHERE   KMS_TRNHDR.TRNHDR_STATUS = 2
                AND     KMS_TRNACC.TRNACC_PERMISSION = 1
                AND     KMS_TRNSUB.TRNSUB_STATUS = 1
                AND     KMS_TRNACC.AWIEMP_NPK = '$npk'              "
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->COUNT;
    }

    public function getCountMyDoneLesson()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT  COUNT(*) AS COUNT
                FROM    KMS_TRNPRG
                INNER JOIN  KMS_TRNSUB
                    ON  KMS_TRNSUB.TRNSUB_ID = KMS_TRNPRG.TRNSUB_ID
                INNER JOIN  KMS_TRNHDR
                    ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                WHERE   KMS_TRNPRG.TRNPRG_STATUS >= 1
                AND     KMS_TRNHDR.TRNHDR_STATUS = 2
                AND     KMS_TRNSUB.TRNSUB_STATUS = 1
                AND     KMS_TRNPRG.AWIEMP_NPK = '$npk'         "
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->COUNT;
    }

    public function getCountMyDonePercent()
    {
        $npk = $this->session->userdata('npk');

        $query = $this->db->query(
            "   WITH HeaderInfo AS (
                SELECT KMS_TRNHDR.TRNHDR_ID, KMS_TRNHDR.TRNHDR_TITLE, KMS_TRNACC.AWIEMP_NPK
                FROM KMS_TRNHDR
                INNER JOIN KMS_TRNACC ON KMS_TRNACC.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                WHERE KMS_TRNHDR.TRNHDR_STATUS = '2' AND KMS_TRNACC.AWIEMP_NPK = '$npk' AND KMS_TRNACC.TRNACC_PERMISSION = '1'
            )
            , Counts AS (
                SELECT
                    hi.TRNHDR_ID,
                    hi.AWIEMP_NPK, 
                    (SELECT COUNT(*)
                     FROM KMS_TRNPRG
                     JOIN KMS_TRNSUB ON KMS_TRNSUB.TRNSUB_ID = KMS_TRNPRG.TRNSUB_ID
                     WHERE KMS_TRNPRG.AWIEMP_NPK = hi.AWIEMP_NPK  
                       AND KMS_TRNSUB.TRNHDR_ID = hi.TRNHDR_ID
                       AND KMS_TRNSUB.TRNSUB_STATUS = 1) AS progress_count,
                    (SELECT COUNT(*)
                    FROM KMS_TRNSUB
                    JOIN KMS_TRNACC ON KMS_TRNACC.TRNHDR_ID = KMS_TRNSUB.TRNHDR_ID
                    inner join KMS_TRNHDR on KMS_TRNACC.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                    WHERE KMS_TRNACC.AWIEMP_NPK = hi.AWIEMP_NPK 
                      AND KMS_TRNSUB.TRNHDR_ID = hi.TRNHDR_ID
                      AND KMS_TRNACC.TRNACC_PERMISSION = 1 AND KMS_TRNHDR.TRNHDR_STATUS = '2' and KMS_TRNSUB.TRNSUB_STATUS = '1') AS total_count
                FROM HeaderInfo hi
            )
            SELECT
                hi.TRNHDR_ID,
                hi.TRNHDR_TITLE,
                CONCAT(COALESCE(c.progress_count, 0), '/', COALESCE(c.total_count, 0)) AS progress,
                ISNULL(CAST(COALESCE(c.progress_count, 0) * 100.0 / NULLIF(COALESCE(c.total_count, 0), 0) AS DECIMAL(10, 2)), 0) AS percentage
            FROM HeaderInfo hi
            LEFT JOIN Counts c ON hi.TRNHDR_ID = c.TRNHDR_ID;
            ");

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->result();
    }


    public function getCountMyNotDone()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   SELECT
                    CASE
                        WHEN COALESCE(
                            (   SELECT  COALESCE(COUNT(*), 0) as count_value
                                FROM    KMS_TRNSUB
                                INNER JOIN  KMS_TRNHDR
                                    ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                INNER JOIN  KMS_TRNACC
                                    ON  KMS_TRNACC.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                WHERE   KMS_TRNHDR.TRNHDR_STATUS = 2
                                AND     KMS_TRNSUB.TRNSUB_STATUS = 1
                                AND     KMS_TRNACC.AWIEMP_NPK = $1
                                AND     KMS_TRNSUB.TRNHDR_ID IS NOT NULL
                            ), 0
                        ) = 0 THEN 0  -- Handling divide by zero by returning 0
                        ELSE
                            COALESCE(
                                (   COALESCE(
                                        (   SELECT  COALESCE(COUNT(*), 0) as count_value
                                            FROM    KMS_TRNSUB
                                            INNER JOIN  KMS_TRNHDR
                                                ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                            INNER JOIN  KMS_TRNACC
                                                ON  KMS_TRNACC.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                            WHERE   KMS_TRNHDR.TRNHDR_STATUS = 2
                                            AND     KMS_TRNSUB.TRNSUB_STATUS = 1
                                            AND     KMS_TRNACC.AWIEMP_NPK = $npk
                                            AND     KMS_TRNSUB.TRNHDR_ID IS NOT NULL
                                        ), 0
                                    ) -
                                    COALESCE(
                                        (   SELECT  COALESCE(COUNT(*), 0) AS count_value
                                            FROM    KMS_TRNPRG
                                            INNER JOIN  KMS_TRNSUB
                                                ON  KMS_TRNSUB.TRNSUB_ID = KMS_TRNPRG.TRNSUB_ID
                                            INNER JOIN  KMS_TRNHDR
                                                ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                            WHERE TRNPRG_STATUS >= 1 AND KMS_TRNHDR.TRNHDR_STATUS = 2 AND KMS_TRNSUB.TRNSUB_STATUS = 1 AND AWIEMP_NPK = $npk
                                        ), 0
                                    )
                                ) * 100
                                /
                                COALESCE(
                                    (
                                        SELECT  COALESCE(COUNT(*), 0) as count_value
                                        FROM    KMS_TRNSUB
                                        INNER JOIN  KMS_TRNHDR
                                            ON  KMS_TRNSUB.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                        INNER JOIN  KMS_TRNACC
                                            ON  KMS_TRNACC.TRNHDR_ID = KMS_TRNHDR.TRNHDR_ID
                                        WHERE   KMS_TRNHDR.TRNHDR_STATUS = 2 AND KMS_TRNSUB.TRNSUB_STATUS = 1 AND KMS_TRNACC.AWIEMP_NPK = $npk AND KMS_TRNSUB.TRNHDR_ID IS NOT NULL
                                    ), 0
                                ),0
                            )
                        END AS result"
        );

        // Assuming $this->t_header is the table name, you can modify it accordingly
        return $query->row()->result;
    }


    public function getMyTrendAccess()
    {
        $npk = $this->session->userdata('npk');
        $query = $this->db->query(
            "   
            SELECT
            CONCAT(
                YEAR(KMS_TRNPRG.TRNPRG_MODIDATE),
                ' ',
                FORMAT(KMS_TRNPRG.TRNPRG_MODIDATE, 'MMMM', 'id-ID')
            ) AS YearMonth,
            COUNT(*) AS RecordCount
        FROM
            KMS_TRNPRG
            inner join KMS_TRNSUB on  KMS_TRNPRG.TRNSUB_ID = KMS_TRNSUB.TRNSUB_ID
            inner join KMS_TRNHDR on KMS_TRNHDR.TRNHDR_ID = KMS_TRNSUB.TRNHDR_ID
        WHERE
            KMS_TRNPRG.TRNPRG_MODIDATE >= DATEADD(MONTH, -5, GETDATE()) 
            and AWIEMP_NPK =  '$npk'
            and KMS_TRNHDR.TRNHDR_STATUS = 2
            and KMS_TRNSUB.TRNSUB_STATUS =1
        GROUP BY
            YEAR(KMS_TRNPRG.TRNPRG_MODIDATE),
            FORMAT(KMS_TRNPRG.TRNPRG_MODIDATE, 'MMMM', 'id-ID'),
            MONTH(KMS_TRNPRG.TRNPRG_MODIDATE)
        ORDER BY
            YEAR(KMS_TRNPRG.TRNPRG_MODIDATE) ASC,
            MONTH(KMS_TRNPRG.TRNPRG_MODIDATE) ASC;
        
            
              "
        );

        return $query->result();
    }
}
