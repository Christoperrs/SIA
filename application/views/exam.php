<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Sistem Informasi Training</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/fonts/fonts.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/ready.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/demo.css') ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css') ?>">

</head>
<?php
$mode = '';
$detailEmployee = [];
function isActive($url)
{
    return (strpos(current_url(), $url) != false) ? 'active' : '';
}
?>

<body>
    <div class="loader-container" id="loader">
        <div class="loader">
            <div class="loader-reverse"></div>
        </div>
        <p class="m-0">&emsp;Loading data...</p>
    </div>
    <div class="wrapper">
        <div class="main-header">
            <div class="logo-header">
                <a href="<?php echo base_url('Training') ?>" class="logo">
                    Sistem Informasi Training
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <button class="topbar-toggler more"><i class="la la-ellipsis-v"></i></button>
            </div>
            <nav class="navbar navbar-header navbar-expand-lg">
                <div class="container-fluid">
                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret p-1">
                            <span id="timestamp"></span>
                        </li>
                        <li class="nav-item dropdown hidden-caret">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="la la-bell"></i>
                                <span class="notification" id="totalNotif"><?php echo $totalNotif; ?></span>
                            </a>
                            <ul class="dropdown-menu notif-box" aria-labelledby="navbarDropdown">
                                <li>
                                    <div class="dropdown-title" id="totalNotifTitle">You have <?php echo $totalNotif == 0 ? 'no new' : $totalNotif ?> notifications</div>
                                </li>
                                <li>
                                    <div class="notif-center">
                                        <?php foreach ($notif as $e) { ?>
                                            <div class="notification-container" data-id="<?= $e->npk ?>">
                                                <a href="javascript:void(0)" onclick="removeNotification('<?= $e->npk ?>', <?= $e->TRNHDR_ID ?>, $('#totalNotif'));" class="time">
                                                    <div class="notif-icon notif-danger"> <i class="la la-trash"></i> </div>
                                                    <div class="notif-content">
                                                        <span class="block">
                                                            <?php echo $e->judul; ?>
                                                        </span>
                                                        <span class="time"> Pengajuan <?php echo $e->npk; ?> ditolak</span><br>
                                                        <span class="time">(Tandai telah dibaca)</span>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } ?>
                                        <?php foreach ($notifMateri as $m) { ?>
                                            <div class="notification-container" data-id="<?= $m->TRNSUB_ID ?>">
                                                <a href="javascript:void(0)" onclick="removeNotifMateri(<?= $m->TRNSUB_ID ?>, $('#totalNotif'));" class="time">
                                                    <div class="notif-icon notif-danger"> <i class="la la-trash"></i> </div>
                                                    <div class="notif-content">
                                                        <span class="block">
                                                            <?php echo $m->judul; ?>
                                                        </span>
                                                        <span class="time">Pengajuan <?php echo $m->TRNSUB_TITLE ?> Ditolak</span><br>
                                                        <span class="time">Klik untuk Hapus</span>
                                                    </div>
                                                </a>
                                            </div>

                                        <?php } ?>

                                    </div>
                                </li>
                                <!-- <li>
									<a class="see-all" href="javascript:void(0);"> <strong>See all notifications</strong> <i class="la la-angle-right"></i> </a>
								</li> -->
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="sidebar">
            <div class="scrollbar-inner sidebar-wrapper">
                <div class="user" style="overflow: hidden;">
                    <div class="info">
                        <a>
                            <span>
                                <span><span class="truncate">Hai,&ensp;</span><b><span id="username" class="truncate"><?php echo $this->session->userdata('nama'); ?></span></b></span>
                                <span class="user-level truncate" id="user-department" style="max-width: 100%;"><?php echo $this->session->userdata('departemen'); ?></span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="row" style="padding-left: 10px; padding-right: 10px; padding-top: 20px">

                    <?php
                    $i = 1;
                    $sectionNumber = 1;
                    // foreach ($maxQuestShow as $x) {
                    //     $maxQuestShow =  $x->settings;
                    // }
                    foreach ($preExam as $t) {

                        if ($i <= $maxQuestShow) {
                    ?>

                            <div class="col-4">
                                <div class="card bg-primary  text-white text-center p-2" id=numbNavBtn<?php echo $i; ?> onclick="handleCardClick(<?php echo $i ?>, 'x')">
                                    <?php echo $i; ?>
                                </div>
                            </div>
                        <?php
                        } else {
                        ?>
                            <div class=" col-4">
                                <div class="card  bg-secondary text-white text-center p-2" id=numbNavBtn<?php echo $i; ?> onclick="handleCardClick(<?php echo $i ?>, 'x')">
                                    <?php echo $i; ?>
                                </div>
                            </div>
                    <?php

                        }
                        $i++;
                    }
                    ?>
                </div>
                <ul class=" nav" style="margin-top: 5px;" id="examMenu">

                    <li class="nav-item <?php echo isActive('Question/getPackage') ?>">
                        <a href="javascript:void(0)" onclick="backExam()">
                            <i class="la la-pencil-square"></i>
                            <p>Keluar Ujian</p>
                            <!-- <span class="badge badge-count">5</span> -->
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="main-panel">
            <div class="content">
                <div class="container-fluid">

                    <div id="showQuestion">
                        <div class="card p-2">

                            <div class="col-md-12">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="card-title">Penilaian </h4>
                                            <p class="card-category">Kerjakan Ujian dengan Jujur</p>
                                        </div>
                                    </div>
                                </div>
                                <form id="formExam" method="post" action="<?php echo base_url('Question/saveExam/' . $idTraining) ?>" enctype="multipart/form-data" role="form">

                                    <?php if (!empty($preExam)) {
                                        foreach ($preExam as $t) { ?>
                                            <input type="text" name="idPackage" id="idPackage" value="<?php echo $t->TRNPCK_ID ?>" hidden>

                                        <?php }
                                        ?> <input type="text" name="idTraining" id="idTraining" value="<?php echo $idTraining ?>" hidden>

                                    <?php
                                    } ?>
                                    <div class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">

                                        <?php
                                        $i = 1;
                                        $section = 1;
                                        $j = 0;
                                        foreach ($preExam as $t) {
                                            $j++;
                                        }
                                        $max_j = isset($max_j) ? max($j, $max_j) : $j;
                                        if (!empty($preExam)) {
                                            foreach ($preExam as $t) {

                                        ?>
                                                <div id="questionNumber<?php echo $i ?>">
                                                    <div class="form-check">
                                                        <input type="text" name="idQuestion<?php echo $i ?>" id="idQuestion" value="<?php echo $t->TRNQUE_ID ?>" hidden>
                                                        <label><?php echo ($i) . '. ' . $t->TRNQUE_QUESTION ?> <span style="color: red;">*</span></label><br />
                                                        <div class="row">
                                                            <label class="form-radio-label ml-3">
                                                                <input class="form-radio-input" type="radio" name="answer<?php echo $i ?>" id="answer<?php echo $i ?>" value="A" onclick="updateNumbNavBtn(<?php echo $i ?>)">
                                                                <span class=" form-radio-sign">A. <?php echo $t->TRNQUE_AOPT ?></span>
                                                            </label>
                                                        </div>
                                                        <div class="row">
                                                            <label class="form-radio-label ml-3">
                                                                <input class="form-radio-input" type="radio" name="answer<?php echo $i ?>" id="answer<?php echo $i ?>" value="B" onclick="updateNumbNavBtn(<?php echo $i ?>)">
                                                                <span class=" form-radio-sign">B. <?php echo $t->TRNQUE_BOPT ?></span>
                                                            </label>
                                                        </div>
                                                        <div class="row">
                                                            <label class="form-radio-label ml-3">
                                                                <input class="form-radio-input" type="radio" name="answer<?php echo $i ?>" id="answer<?php echo $i ?>" value="C" onclick="updateNumbNavBtn(<?php echo $i ?>)">
                                                                <span class=" form-radio-sign">C. <?php echo $t->TRNQUE_COPT ?> </span>
                                                            </label>
                                                        </div>
                                                        <div class="row">
                                                            <label class="form-radio-label ml-3">
                                                                <input class="form-radio-input" type="radio" name="answer<?php echo $i ?>" id="answer<?php echo $i ?>" value="D" onclick="updateNumbNavBtn(<?php echo $i ?>)">
                                                                <span class=" form-radio-sign">D. <?php echo $t->TRNQUE_DOPT ?></span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php if ($i % $maxQuestShow  == 0 || $max_j == $i) { ?>
                                                        <div class="card-body" id="divBackSub">
                                                            <?php if ($max_j != $i) { ?>
                                                                <button type="button" id="nextBtn<?php echo $i; ?>" class="btn btn-success float-right" onclick="handleCardClick('x',(<?php echo $i; ?>+1) )">Selanjutnya</button>
                                                                <button type="button" id="backBtn<?php echo $i; ?>" class="btn btn-danger" onclick="handleCardClick2('x', <?php echo $i; ?>)">Kembali</button>
                                                            <?php } else { ?>
                                                                <button type="button" id="submitExam<?php echo $i; ?>" class="btn btn-success float-right" onclick="saveAnswer()">Kirim</button>
                                                                <button type="button" id="backBtn<?php echo $i; ?>" class="btn btn-danger" onclick="handleCardClick2('x', <?php echo $i; ?>)">Kembali</button>
                                                            <?php } ?>
                                                        </div>

                                                    <?php
                                                    } ?>
                                                </div>
                                            <?php
                                                $i++;
                                            }
                                        } else {
                                            ?>
                                            <div class=" card-body d-flex justify-content-center" style="border-bottom: 1px solid #ebedf2 !important;">
                                                <div class="row justify-content-center">
                                                    <div class="col-md-12">

                                                        <img src="<?php echo base_url("assets/img/dataEmpty1.jpg") ?>" style="max-height: 163px">
                                                        <h4 class="card-title">Tidak ada data soal!</h4>
                                                    </div>
                                                </div>
                                            </div>



                                        <?php } ?>



                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="showScore" style="display: none;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card p-2 mb-3">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col">
                                                <h4 class="card-title">Nilai </h4>
                                                <p class="card-category">Nilai Ujian</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <table name="table" class="table table-hover table-head-bg-info my-2">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="text-center" style="width: 500px;">Jumlah Jawaban Benar</th>
                                                    <th scope="col" class="text-center" style="width: 700px;">Skor Akhir</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tBodymainTable">

                                                <tr>
                                                    <th id="trueAns"></th>
                                                    <th id="score"></th>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // function handleRadioCheck(questionNumber, answerValue) {
                //     var numbNavBtn = document.getElementById('numbNavBtn' + questionNumber);
                //     if (numbNavBtn) {
                //         if (document.querySelector('input[name="answer' + questionNumber + '"]:checked').value === answerValue) {
                //             numbNavBtn.classList.remove('bg-primary');
                //             numbNavBtn.classList.add('bg-success');
                //         } else {
                //             numbNavBtn.classList.remove('bg-success');
                //             numbNavBtn.classList.add('bg-primary');
                //         }
                //     }
                // }

                // function submitExam() {
                //     var formElements = document.getElementById(" formExam");
                //     formElements.submit();
                // }
                function updateNumbNavBtn(i) {
                    var answerRadio = document.querySelector('input[name="answer' + i + '"]:checked');
                    var numbNavBtn = document.getElementById('numbNavBtn' + i);
                    if (answerRadio) {
                        numbNavBtn.classList.add('bg-success');
                    } else {
                        numbNavBtn.classList.remove('bg-success');
                    }
                }

                document.addEventListener("DOMContentLoaded", function() {
                    var quest = document.querySelectorAll('[id^="questionNumber"]');
                    var maxNumber = 0;
                    var maxShowQuestion = <?php echo $maxQuestShow; ?>;
                    // Loop through each element with ID starting with "questionNumber"
                    quest.forEach(function(element) {
                        // Extract the numerical portion from the ID and convert it to an integer
                        var questNumber = parseInt(element.id.replace('questionNumber', ''));

                        // Update maxNumber if questNumber is greater than current max
                        if (questNumber > maxNumber) {
                            maxNumber = questNumber;
                        }
                    });
                    document.getElementById('backBtn<?php echo $maxQuestShow; ?>').style.display = 'none';

                    // Loop through each element again to set display style
                    quest.forEach(function(element) {
                        var questNumber = parseInt(element.id.replace('questionNumber', ''));
                        if (questNumber <= maxShowQuestion) {
                            console.log(questNumber + "questNumber" + maxShowQuestion + "max");
                            element.style.display = 'block';
                        } else {
                            element.style.display = 'none';
                        }
                    });



                });
            </script>
            <?php include __DIR__ . '/script.php'; ?>
            <?php

            ?>
        </div>

    </div>
    <footer class="footer">
        <div class="container-fluid">
            <div class="copyright ml-auto ">
                <i class="la la-copyright"></i> 2023 - IT <i class="la la-heart heart text-danger"></i> - PT. Akashi Wahana Indonesia. All Rights Reserved.
            </div>
        </div>
    </footer>
    </div>
    </div>
</body>
<script src="<?php echo base_url('assets/js/core/jquery.3.2.1.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/core/popper.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/core/bootstrap.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/chartist/chartist.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/jquery-mapael/jquery.mapael.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/jquery-mapael/maps/world_countries.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/chart-circle/circles.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/ready.min.js') ?>"></script>
<script src="<?php echo base_url('assets/js/demo.js') ?>"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js') ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js') ?>"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js') ?>"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: 'Apakah Anda yakin ingin keluar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?php echo base_url("Login/logout"); ?>';
            }
        });
    }

    function confirmDeleteAdmin(id) {
        Swal.fire({
            title: 'Konfirmasi Hapus Admin',
            text: 'Apakah Anda yakin ingin menghapus data ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('Admin/deleteAdmin/') ?>' + id;
            }
        });
    }

    function confirmDeleteTag(id, total) {
        Swal.fire({
            title: 'Konfirmasi Hapus Tagar',

            text: total < 1 ? 'Apakah Anda yakin ingin menghapus data ini?' : 'Tag masih terhubung dengan training!',
            icon: total < 1 ? 'warning' : 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: total < 1 ? 'Ya' : 'Ok',
            cancelButtonText: 'Tidak',
            cancelButtonAriaLabel: 'Tidak',
            didOpen: () => {
                if (total >= 1) {
                    const cancelButton = Swal.getCancelButton();
                    cancelButton.style.display = 'none';
                }
            }
        }).then((result) => {
            if (result.isConfirmed && total < 1) {
                window.location.href = '<?= base_url('Admin/deleteTag/') ?>' + id;
            }
        });
    }

    setTimeout(function() {
        document.getElementById('loader').classList.add('fade-out');
        console.log('fadingout');
        setTimeout(function() {
            document.getElementById('loader').style.display = 'none';
            console.log('settingnone');
        }, 500);
    }, 1000);

    function removeNotification(npk, id, totalNotifElement) {
        $.ajax({
            url: '<?= base_url('Training/removeNotif/') ?>',
            type: 'POST',
            data: {
                id: id,
                npk: npk
            },
            success: function() {
                console.log(id + "sf");
                $('.notification-container[data-id="' + npk + '"]').hide();

                // Update totalNotif dynamically
                totalNotifElement.text(function(i, text) {
                    // Extract the current totalNotif value
                    var currentTotalNotif = parseInt(text, 10);
                    // Decrease the totalNotif count
                    currentTotalNotif--;
                    // Return the updated text
                    return currentTotalNotif;
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                if (jqXHR.status === 404) {
                    alert('Notification not found.');
                } else {
                    alert('Failed to remove notification. Please try again.');
                }
            }
        });
    }

    function removeNotifMateri(id, totalNotifElement) {
        $.ajax({
            url: '<?= base_url('Training/removeNotifMateri/') ?>' + id,
            method: 'POST',
            success: function() {
                console.log(id + "sf");
                $('.notification-container[data-id="' + id + '"]').hide();

                // Update totalNotif dynamically
                totalNotifElement.text(function(i, text) {
                    // Extract the current totalNotif value
                    var currentTotalNotif = parseInt(text, 10);
                    // Decrease the totalNotif count
                    currentTotalNotif--;
                    // Return the updated text
                    return currentTotalNotif;
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
                if (jqXHR.status === 404) {
                    alert('Notification not found.');
                } else {
                    alert('Failed to remove notification. Please try again.');
                }
            }
        });
    }

    function updateDateTime() {
        var now = new Date();
        var formattedTime = now.toLocaleTimeString();

        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        var dayName = days[now.getDay()];
        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var monthName = months[now.getMonth()];
        var formattedDate = now.getDate() + ' ' + monthName + ' ' + now.getFullYear();

        $("#timestamp").text(dayName + ", " + formattedDate + " " + formattedTime + "\u2003");
    }

    $(document).ready(function() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
        callLoader();
    });

    function callLoader() {
        var loader = document.getElementById('loaderDiv');
        setTimeout(function() {
            loader.classList.add('fade-out');
            setTimeout(function() {
                loader.style.display = 'none';
            }, 500);
        }, 500);
        loader.style.display = '';
        loader.classList.remove('fade-out');
    }

    function handleCardClick(numberQuestNav, btnQuest) {
        var allnumbNavBtns = document.querySelectorAll('[id^="numbNavBtn"]');
        var maxnumbNavBtn = 0;
        allnumbNavBtns.forEach(function(element) {
            var idNumber = parseInt(element.id.replace('numbNavBtn', '')); // Extract numeric part of ID
            maxnumbNavBtn = Math.max(maxnumbNavBtn, idNumber); // Update maxnumbNavBtn if necessary
        });

        var allquestionNumber = document.querySelectorAll('[id^="questionNumber"]');

        allnumbNavBtns.forEach(function(page) {
            if (!page.classList.contains('bg-success')) {
                if (page.classList.contains('bg-primary')) {
                    page.classList.remove('bg-primary');
                    page.classList.add('bg-secondary');
                }
            }
        });

        allquestionNumber.forEach(function(page) {
            page.style.display = 'block';
            page.style.display = 'none';
        });
        var maxNumber = 0;

        if (numberQuestNav != 'x') {
            var minNumberQuestShow = 0;
            var maxNumberQuestShow = <?php echo $maxQuestShow; ?>;

            var result = 0;
            if (numberQuestNav % <?php echo $maxQuestShow; ?> == 0) {
                result = (numberQuestNav / <?php echo $maxQuestShow; ?>) - 1
            } else {
                result = (Math.floor(numberQuestNav / <?php echo $maxQuestShow; ?>));

            }
            if (result >= 1) {
                minNumberQuestShow = (result * <?php echo $maxQuestShow; ?>);
                maxNumberQuestShow = (result + 1) * <?php echo $maxQuestShow; ?>;
            }
            while (minNumberQuestShow < maxNumberQuestShow && minNumberQuestShow < maxnumbNavBtn) {

                minNumberQuestShow++;
                var currentElementId = "questionNumber" + minNumberQuestShow;
                var currentElement = document.getElementById(currentElementId);

                if (currentElement) {
                    currentElement.style.display = 'block';
                }
                var numbNavBtn = document.getElementById('numbNavBtn' + minNumberQuestShow);
                if (numbNavBtn && !numbNavBtn.classList.contains('bg-success')) {
                    numbNavBtn.classList.remove('bg-secondary');
                    numbNavBtn.classList.add('bg-primary');
                }

            }

        }
        if (btnQuest != 'x') {
            var minNumberQuestShow = 0;
            var maxNumberQuestShow = <?php echo $maxQuestShow; ?>;
            var result = 0;
            if (btnQuest % <?php echo $maxQuestShow; ?> == 0) {
                result = (btnQuest / <?php echo $maxQuestShow; ?>) - 1
            } else {
                result = (Math.floor(btnQuest / <?php echo $maxQuestShow; ?>));
                if (result >= 1) {
                    minNumberQuestShow = (result * <?php echo $maxQuestShow; ?>);
                    maxNumberQuestShow = (result + 1) * <?php echo $maxQuestShow; ?>;
                }
            }

            while (minNumberQuestShow < maxNumberQuestShow && minNumberQuestShow < maxnumbNavBtn) {

                minNumberQuestShow++;
                var currentElementId = "questionNumber" + minNumberQuestShow;
                var currentElement = document.getElementById(currentElementId);

                if (currentElement) {
                    currentElement.style.display = 'block';
                }
                var numbNavBtn = document.getElementById('numbNavBtn' + minNumberQuestShow);
                if (numbNavBtn && !numbNavBtn.classList.contains('bg-success')) {
                    numbNavBtn.classList.remove('bg-secondary');
                    numbNavBtn.classList.add('bg-primary');
                }

            }
        }

    }

    function handleCardClick2(numberQuestNav, btnQuest) {
        var allnumbNavBtns = document.querySelectorAll('[id^="numbNavBtn"]');
        var maxnumbNavBtn = 0;
        allnumbNavBtns.forEach(function(element) {
            var idNumber = parseInt(element.id.replace('numbNavBtn', '')); // Extract numeric part of ID
            maxnumbNavBtn = Math.max(maxnumbNavBtn, idNumber); // Update maxnumbNavBtn if necessary
        });

        var allquestionNumber = document.querySelectorAll('[id^="questionNumber"]');

        allnumbNavBtns.forEach(function(page) {
            if (!page.classList.contains('bg-success')) {
                if (page.classList.contains('bg-primary')) {
                    page.classList.remove('bg-primary');
                    page.classList.add('bg-secondary');
                }
            }
        });


        allquestionNumber.forEach(function(page) {
            page.style.display = 'block';
            page.style.display = 'none';
        });
        var maxNumber = 0;
        if (btnQuest != 'x') {
            var minNumberQuestShow = 0;
            var maxNumberQuestShow = <?php echo $maxQuestShow; ?>;
            var result = 0;
            if (btnQuest % <?php echo $maxQuestShow; ?> == 0) {
                result = (btnQuest / <?php echo $maxQuestShow; ?>) - 2;
            } else {
                result = (Math.floor(btnQuest / <?php echo $maxQuestShow; ?>));
                if (result >= 1) {
                    minNumberQuestShow = (result - 1) * <?php echo $maxQuestShow; ?>;
                    maxNumberQuestShow = (result * <?php echo $maxQuestShow; ?>);
                }
            }
            console.log("minNumberQuestShow " + minNumberQuestShow + "max" + maxNumberQuestShow);
            while (minNumberQuestShow < maxNumberQuestShow && minNumberQuestShow < maxnumbNavBtn) {

                minNumberQuestShow++;
                var currentElementId = "questionNumber" + minNumberQuestShow;
                var currentElement = document.getElementById(currentElementId);

                if (currentElement) {
                    currentElement.style.display = 'block';
                }
                var numbNavBtn = document.getElementById('numbNavBtn' + minNumberQuestShow);
                if (numbNavBtn && !numbNavBtn.classList.contains('bg-success')) {
                    numbNavBtn.classList.remove('bg-secondary');
                    numbNavBtn.classList.add('bg-primary');
                }

            }
        }
    }

    function backExam() {
        Swal.fire({
            title: 'Konfirmasi Keluar Ujian',
            text: 'Jawaban Anda tidak akan tersimpan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?php echo base_url("Training"); ?>';
            }
        });
    }

    // async function saveAnswer() {
    //     var formElements = document.getElementById("formExam");
    //     formElements.submit();
    //     fetch('<?= base_url('Question/getScore/') ?>')
    //         .then(response => {
    //             return response.json(); // Parse response as JSON
    //         })
    //         .then(data => {
    //             console.log(data);
    //             const score = data.score;
    //             document.getElementById('showScore').style.display = 'block';
    //             document.getElementById('showQuestion').style.display = 'none';

    //             document.getElementById('score').value = score;
    //         })
    //         .catch(error => {
    //             console.error('Error fetching data showdetail:', error);
    //         });


    // }

    function saveAnswer() {
        // Get all elements with name starting with 'answer'

        var answerInputs = document.querySelectorAll('input[name^="answer"]');
        var isFilled = true;

        // Check if any radio button is not checked
        answerInputs.forEach(function(input) {
            // Extract the question number from the input name
            var questionNumber = input.name.replace("answer", "");

            // Check if the corresponding radio button for this question is checked
            if (!document.querySelector('input[name="answer' + questionNumber + '"]:checked')) {
                console.log("Unchecked radio button ID: " + input.id);
                isFilled = false;
                return;
            }
        });
        // Display SweetAlert if any radio button is not checked
        if (!isFilled) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Please answer all questions before submitting!'
            });
            return;
        }

        // If all radio buttons are checked, show confirmation dialog
        Swal.fire({
            title: 'Confirmation',
            text: 'Apakah Anda yakin ingin mengirim data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.isConfirmed) {
                // If user confirms, submit the form
                document.getElementById('formExam').submit();
            }
        });
    }
</script>

</html>