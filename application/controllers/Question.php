    <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class Question extends CI_Controller
    {
        public $score2 = 'x';
        public function __construct()
        {
            parent::__construct();

            $this->load->model("OracleDBM");
            $this->load->model("QuestionM");
            $this->load->model("TrainingM");
            $this->load->model("SettingM");
            $this->load->model("AdminM");
            $this->load->helper(array('form', 'url'));
            $this->load->library('session');
            $this->load->library('form_validation');
        }

        public function index()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            $data['package']        = $this->QuestionM->getPackages();
            $data['notif']        = $this->TrainingM->getNotif($npk);
            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

            $data['tags']          = $this->AdminM->getTags();
            $data['train']        = $this->TrainingM->filterTraining('> 0');
            $this->load->view('question_package', $data);
        }

        public function isAllowed()
        {
            return $this->session->userdata('isLogin') && $this->session->userdata('role') == 'admin';
        }

        public function getPackage($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $data['package'] = $this->QuestionM->getPackage($id);
            $data['questions'] = $this->QuestionM->getQuestions($id);
            echo json_encode($data);
        }

        public function savePackage()
        {
            if (!$this->isAllowed()) return redirect(site_url());

            // Saving package
            $data = array(
                'TRNPCK_STATUS'     => 1,
                'TRNPCK_UNIQUEID'   => $this->input->post('idUniqPaket'),
                'TRNPCK_NAME'       => $this->input->post('namePaket'),
                'TRNHDR_ID'         => $this->input->post('chooseTrain'),
                'TRNPCK_CREADATE'   => date('Y/m/d H:i:s'),
                'TRNPCK_MODIDATE'   => date('Y/m/d H:i:s'),
                'TRNPCK_CREABY'     => $this->session->userdata('npk'),
                'TRNPCK_MODIBY'     => $this->session->userdata('npk'),
            );
            $this->QuestionM->savePackage($data);
            $lastInsertedId = $this->db->insert_id();

            $count = 0;
            foreach ($this->input->post() as $key => $value) {
                if (strpos($key, 'TRNQUE_ANSWER') !== false) {
                    $count++;
                }
            }
            // Saving each question
            for ($i = 1; $i <= $count; $i++) {
                $que = array(
                    'TRNQUE_QUESTION'   => $this->input->post('TRNQUE_QUESTION' . $i),
                    'TRNQUE_ANSWER'     => $this->input->post('TRNQUE_ANSWER' . $i),
                    'TRNQUE_AOPT'       => $this->input->post('TRNQUE_AOPT' . $i),
                    'TRNQUE_BOPT'       => $this->input->post('TRNQUE_BOPT' . $i),
                    'TRNQUE_COPT'       => $this->input->post('TRNQUE_COPT' . $i),
                    'TRNQUE_DOPT'       => $this->input->post('TRNQUE_DOPT' . $i),
                    'TRNQUE_LEVEL'      => $this->input->post('TRNQUE_LEVEL' . $i),
                    'TRNQUE_CREADATE'   => date('Y/m/d H:i:s'),
                    'TRNQUE_MODIDATE'   => date('Y/m/d H:i:s'),
                    'TRNQUE_CREABY'     => $this->session->userdata('npk'),
                    'TRNQUE_MODIBY'     => $this->session->userdata('npk'),
                    'TRNQUE_STATUS'     => 1,
                    'TRNPCK_ID'         => $lastInsertedId,
                );
                $this->QuestionM->saveQuestion($que);
            }
            redirect('Question');
        }

        public function editPackage()
        {
            if (!$this->isAllowed()) return redirect(site_url());

            // Editing package
            $data = array(
                'TRNPCK_NAME'       => $this->input->post('namePaket'),
                'TRNPCK_UNIQUEID'   => $this->input->post('idUniqPaket'),
                'TRNHDR_ID'         => $this->input->post('chooseTrain'),
                'TRNPCK_MODIDATE'   => date('Y/m/d H:i:s'),
                'TRNPCK_MODIBY'     => $this->session->userdata('npk'),
            );
            $where = array(
                'TRNPCK_ID'         => $this->input->post('package_id')
            );
            $this->QuestionM->editPackage($data, $where);
            $oldCount = $this->QuestionM->getQuestions($this->input->post('package_id'));

            $arrNew = array();
            $count = 0;
            foreach ($this->input->post() as $key => $value) {
                if (strpos($key, 'answer') !== false) {
                    $arrNew[$count] = $this->input->post('questionId' . ($count + 1));
                    $count++;
                }
            }

            if (!empty($oldCount)) {
                $questionIds = array_column($oldCount, 'question_id');
            }

            // Deleting unrelated questions
            $difference = array_diff($questionIds, $arrNew);
            foreach ($difference as $value) {
                $data = array(
                    'TRNQUE_STATUS'     => 0,
                    'TRNQUE_MODIDATE'   => date('Y/m/d H:i:s'),
                    'TRNQUE_MODIBY'     => $this->session->userdata('npk'),
                );
                $where = array(
                    'TRNQUE_ID'         => $value
                );
                $this->QuestionM->editQuestion($data, $where);
            }

            // Saving new questions/updating existing questions
            for ($i = 1; $i <= $count; $i++) {
                $data = array(
                    'TRNQUE_QUESTION'   => $this->input->post('TRNQUE_QUESTION' . $i),
                    'TRNQUE_ANSWER'     => $this->input->post('TRNQUE_ANSWER' . $i),
                    'TRNQUE_AOPT'       => $this->input->post('TRNQUE_AOPT' . $i),
                    'TRNQUE_BOPT'       => $this->input->post('TRNQUE_BOPT' . $i),
                    'TRNQUE_COPT'       => $this->input->post('TRNQUE_COPT' . $i),
                    'TRNQUE_DOPT'       => $this->input->post('TRNQUE_DOPT' . $i),
                    'TRNQUE_LEVEL'      => $this->input->post('TRNQUE_LEVEL' . $i),
                    'TRNQUE_MODIDATE'   => date('Y/m/d H:i:s'),
                    'TRNQUE_MODIBY'     => $this->session->userdata('npk'),
                    'TRNQUE_STATUS'     => 1,
                );

                if ($i <= count($oldCount)) {
                    $where = array(
                        'TRNQUE_ID'     => $this->input->post('questionId' . $i),
                    );
                    $this->QuestionM->editQuestion($data, $where);
                } else {
                    $data['TRNQUE_CREABY']      = $this->session->userdata('npk');
                    $data['TRNQUE_CREADATE']    = date('Y/m/d H:i:s');
                    $data['TRNPCK_ID']          = $this->input->post('package_id');
                    $this->QuestionM->saveQuestion($data);
                }
            }
            redirect('Question');
        }

        public function deletePackage($id)
        {
            // Deleting package
            if (!$this->isAllowed()) return redirect(site_url());
            $data = array(
                'TRNPCK_STATUS'     => 0,
                'TRNPCK_MODIDATE'   => date('Y/m/d H:i:s'),
                'TRNPCK_MODIBY'     => $this->session->userdata('npk'),
            );
            $where = array(
                'TRNPCK_ID'         => $id
            );
            $result = $this->QuestionM->editPackage($data, $where);

            if ($result) {
                echo json_encode(array('success' => true));
            } else {
                echo json_encode(array('success' => false, 'error' => 'Deletion failed.'));
            }

            redirect('Question');
        }

        public function savePreExam()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->QuestionM->savePreExam();
            redirect('Question');
        }

        public function getQuestExam($id, $secondParameter)
        {
            // if (!$this->isAllowed()) return redirect(site_url());
            $id = $this->decodeMD5($id);
            $secondParameter = $this->decodeMD5($secondParameter);
            $npk = $this->session->userdata('npk');
            $data['notif']        = $this->TrainingM->getNotif($npk);
            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
            $data['score'] = 'x';
            $data['idTraining'] = $id;
            if ($secondParameter == 1) {
                $data['preExam']        = $this->QuestionM->getPreExam($id);
            } else {
                $data['preExam']        = $this->QuestionM->getPostExam($id);
            }
            $data['maxQuestShow'] = $this->SettingM->getSettingValue('TRNQUE_MAX');

            $this->load->view('exam', $data);
        }

        private function decodeMD5($hashedValue)
        {
            for ($i = 0; $i < 100000; $i++) {
                if (md5($i) == $hashedValue) {
                    return $i;
                }
            }
            return null;
        }

        // public function getPreExam($id)
        // {
        //     if (!$this->isAllowed()) return redirect(site_url());
        //     $npk = $this->session->userdata('npk');
        //     $data['notif']        = $this->TrainingM->getNotif($npk);
        //     $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
        //     $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
        //     $data['score'] = 'x';
        //     $data['preExam']        = $this->QuestionM->getPreExam($id);
        //     $this->load->view('exam', $data);
        // }

        public function saveExam($idTraining)
        {
            $npk = $this->session->userdata('npk');

            $count = 0;
            foreach ($this->input->post() as $key => $value) {
                if (strpos($key, 'answer') !== false) {
                    $count++;
                }
            }
            $idPackage = $this->input->post('idPackage');

            //   $idQuestion = $this->input->post('idQuestion');
            $totalQuestion = $this->QuestionM->getTotalQuestion($idPackage);

            $trueAnswer = 0;

            for ($i = 1; $i <= $count; $i++) {
                $idQuestion = $this->input->post('idQuestion' . $i);
                $answerUser = $this->input->post('answer' . $i);
                print_r($answerUser);
                $answerKey = $this->QuestionM->getAnswerKey($idQuestion);

                if ($answerKey       == $answerUser) {
                    $trueAnswer++;
                }
            }
            $score = round(($trueAnswer / $totalQuestion) * 100, 2);
            $data = array(
                'TRNACC_PRESCORE' => $score,
                'TRNPCK_ID' => $idPackage,
            );
            $this->score2 = $score;
            $this->session->set_userdata('score', $score);
            $checkPreorPost = $this->QuestionM->checkPreOrPost($npk, $idTraining);

            if ($checkPreorPost == null) {
                $data = array(
                    'TRNACC_PRESCORE' => $score,
                    'TRNPCK_ID_PRE' => $idPackage,
                );
                $this->QuestionM->savePreExam($data, $npk, $idTraining);
            } else {
                print_r($checkPreorPost);
                $data = array(
                    'TRNACC_POSTSCORE' => $score,
                    'TRNPCK_ID_POST' => $idPackage,
                );
                $this->QuestionM->savePreExam($data, $npk, $idTraining);
            }
            redirect('Question/getScore/');
        }

        public function getScore()
        {
            // if (!$this->isAllowed()) return redirect(site_url());

            $npk = $this->session->userdata('npk');
            $data['notif'] = $this->TrainingM->getNotif($npk);
            $data['package'] = $this->QuestionM->getPackages();
            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

            $data['score'] =  $this->session->userdata('score');
            $this->load->view('examResult', $data);
        }

        // public function getScoreTest($score3)
        // {
        //     if (!$this->isAllowed()) return redirect(site_url());
        //     $npk = $this->session->userdata('npk');
        //     $data['notif'] = $this->TrainingM->getNotif($npk);
        //     $data['package'] = $this->QuestionM->getPackages();
        //     $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
        //     $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
        //     $data['score'] = $this->QuestionM->getPackages();
        //     $score = $score3; // Remove the $ symbol
        //     $data['score'] = $score;
        //     $this->load->view('examResult', $data);
        // }

        public function getGlobalScore()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            //    $data['score'] = $this->QuestionM->getGlobalScore();
            $data['notif'] = $this->TrainingM->getNotif($npk);

            $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

            $getScoreExam2    = $this->QuestionM->getGlobalScore();

            $getData = [];
            foreach ($getScoreExam2 as $a) {
                $employee = $this->OracleDBM->getEmpByNPK($a->AWIEMP_NPK);
                if ($employee !== null && is_object($employee)) {
                    $combine = [
                        'npk' => $employee->NPK,
                        'nama' => $employee->NAMA,
                        'training_id' => $a->TRNHDR_ID,
                        'package_name' => $a->TRNPCK_NAME,
                        'scorePre' => $a->TRNACC_PRESCORE,
                        'scorePost' => $a->TRNACC_POSTSCORE,
                        'package_id' => $a->TRNPCK_ID_PRE
                    ];
                    $getData[] = $combine;
                } else {
                }
            }

            $data['score']   = $getData;

            $this->load->view('exam/score', $data);
        }
    }
