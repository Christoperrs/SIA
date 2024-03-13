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
                <a href="index.html" class="logo">
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
                                                <a href="javascript:void(0)" onclick="removeNotification('<?= $e->npk ?>', <?= $e->id_training_header ?>, $('#totalNotif'));" class="time">
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
                                            <div class="notification-container" data-id="<?= $m->id_training_detail ?>">
                                                <a href="javascript:void(0)" onclick="removeNotifMateri(<?= $m->id_training_detail ?>, $('#totalNotif'));" class="time">
                                                    <div class="notif-icon notif-danger"> <i class="la la-trash"></i> </div>
                                                    <div class="notif-content">
                                                        <span class="block">
                                                            <?php echo $m->judul; ?>
                                                        </span>
                                                        <span class="time">Pengajuan <?php echo $m->judul_training_detail ?> Ditolak</span><br>
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
                    foreach ($preExam as $t) {
                        if (($i - 1) % 2 == 0) {
                            if ($i != 0) {
                                echo '</div>';
                            }
                            echo '<div class="row" style="padding-left:30px; id="sectionNumber-' . $sectionNumber . '">';
                            $sectionNumber++;
                        }
                        if ($i == 1 || $i == 2) {
                    ?>
                            <div class="col-4">
                                <div class="card bg-secondary text-white text-center p-2" id=numberPage<?php echo $i; ?> onclick="handleCardClick((<?php echo $sectionNumber; ?>)-2, <?php echo $sectionNumber ?>)">
                                    <?php echo $i; ?>
                                </div>
                            </div>
                        <?php
                            $i++;
                        } else {
                        ?>
                            <div class=" col-4">
                                <div class="card bg-primary text-white text-center p-2" id=numberPage<?php echo $i; ?> onclick="handleCardClick((<?php echo $sectionNumber; ?>)-2, <?php echo $sectionNumber ?>)">
                                    <?php echo $i; ?>
                                </div>
                            </div>
                    <?php
                            $i++;
                        }
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
                        <div class="col-md-12">
                            <form id="formExam" method="post" action="<?php echo base_url('Question/saveExam') ?>" ; enctype="multipart/form-data" role="form">
                                <?php if (!empty($preExam)) {
                                    foreach ($preExam as $t) { ?>
                                        <input type="text" name="idPackage" id="idPackage" value="<?php echo $t->package_id ?>" hidden>
                                <?php }
                                } ?>
                                <div class="card p-2">
                                    <?php
                                    $i = 0;
                                    $section = 1;
                                    if (!empty($preExam)) {
                                        foreach ($preExam as $t) {
                                            if ($i % 2 == 0) {
                                                if ($i != 0) {
                                                    echo '</div>'; // Close the previous section
                                                }
                                                echo '<div class="question-section" id="section-' . $section . '">'; // Start a new section
                                                $section++;
                                            }
                                    ?>
                                            <div class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-check" id="number<?php echo $i ?>">
                                                            <script>
                                                                var count = document.querySelectorAll('[id^="number"]').length;

                                                                // Output the count
                                                                console.log(count);
                                                            </script>
                                                            <input type="text" name="idQuestion<?php echo $i ?>" id="idQuestion" value="<?php echo $t->question_id ?>" hidden>
                                                            <label><?php echo ($i + 1) . '. ' . $t->question ?> <span style="color: red;">*</span></label><br />
                                                            <div class="row">
                                                                <label class="form-radio-label ml-3">
                                                                    <input class="form-radio-input" type="radio" name="answer<?php echo $i ?>" id="answer<?php echo $i ?>" value="<?php echo $t->a ?>">
                                                                    <span class="form-radio-sign">A. <?php echo $t->a ?></span>
                                                                </label>
                                                            </div>
                                                            <div class="row">
                                                                <label class="form-radio-label ml-3">
                                                                    <input class="form-radio-input" type="radio" name="answer<?php echo $i ?>" id="answer<?php echo $i ?>" value="<?php echo $t->b ?>">
                                                                    <span class="form-radio-sign">B. <?php echo $t->b ?></span>
                                                                </label>
                                                            </div>
                                                            <div class="row">
                                                                <label class="form-radio-label ml-3">
                                                                    <input class="form-radio-input" type="radio" name="answer<?php echo $i ?>" id="answer<?php echo $i ?>" value="<?php echo $t->c ?>">
                                                                    <span class="form-radio-sign">C. <?php echo $t->c ?> </span>
                                                                </label>
                                                            </div>
                                                            <div class="row">
                                                                <label class="form-radio-label ml-3">
                                                                    <input class="form-radio-input" type="radio" name="answer<?php echo $i ?>" id="answer<?php echo $i ?>" value="<?php echo $t->d ?>">
                                                                    <span class="form-radio-sign">D. <?php echo $t->d ?></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (($i + 1) % 2 == 0) { ?>
                                                <div class="card-body" id="divBackSub">
                                                    <script>
                                                        console.log(<?php echo $section - 1; ?> + "secBtn")
                                                    </script>

                                                    <button type="button" id="nextBtn<?php echo $section - 1; ?>" class="btn btn-success float-right" onclick="handleCardClick((<?php echo $section; ?>)-1, <?php echo $section ?>)">Selanjutnya</button>
                                                    <button type="button" id="submitExam<?php echo $section - 1; ?>" class="btn btn-success float-right" onclick="saveAnswer()" style="display: none;">Kirim</button>
                                                    <button type="button" id="backBtn<?php echo $section - 1; ?>" class="btn btn-danger" onclick="handleCardClick2((<?php echo $section; ?>)-2, <?php echo $section ?>)">Kembali</button>
                                                </div>
                                        <?php
                                            }
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
                document.addEventListener("DOMContentLoaded", function() {
                    var sections = document.querySelectorAll('.question-section');
                    var currentSectionIndex = 0;
                    var pageNumb = 0;
                    var pageNumb2 = 0;
                    var backButton = document.getElementById('backBtn1');
                    if (backButton) {
                        backButton.style.display = 'none';
                    }
                    hideSections();

                    function hideSections() {
                        sections.forEach(function(section, index) {
                            if (index !== currentSectionIndex) {
                                section.style.display = 'none';
                            } else {
                                section.style.display = 'block';
                            }
                        });
                    }

                });
            </script>
            <?php include __DIR__ . '/script2.php'; ?>
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
    });

    function handleCardClick(cardNumber, cardBegin) {
        var allNumberPages = document.querySelectorAll('[id^="numberPage"]');
        allNumberPages.forEach(function(page) {
            page.classList.remove('bg-secondary');
            page.classList.add('bg-primary');
        });

        var questionSections = document.getElementsByClassName('question-section');
        var lastSectionId = questionSections[questionSections.length - 1].id;
        var lastSectionNumber = lastSectionId.split('-')[1];
        console.log("lastSectionNumber" + lastSectionNumber);
        console.log("cardNumb" + (cardNumber + 1));
        if (lastSectionNumber == (cardNumber + 1)) {
            document.getElementById('submitExam' + lastSectionNumber).style.display = 'block';
            document.getElementById('nextBtn' + lastSectionNumber).style.display = 'none';
        }
        var sections = document.querySelectorAll('.question-section');

        pageNumb = (cardNumber + 1) * 2;
        pageNumb2 = (cardNumber + 1) * 2 - 1;
        document.getElementById('numberPage' + pageNumb2).classList.add('bg-secondary');
        document.getElementById('numberPage' + pageNumb).classList.add('bg-secondary');
        document.getElementById('numberPage' + pageNumb2).classList.remove('bg-primary');
        document.getElementById('numberPage' + pageNumb).classList.remove('bg-primary');


        // if (cardBegin < 1) {

        //     document.getElementById('backBtn').style.display = 'none';
        // } else {


        // }

        sections.forEach(function(section, index) {
            if (index !== cardNumber) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });
    }

    function handleCardClick2(cardNumber, cardBegin) {
        var allNumberPages = document.querySelectorAll('[id^="numberPage"]');
        allNumberPages.forEach(function(page) {
            page.classList.remove('bg-secondary');
            page.classList.add('bg-primary');
        });
        var sections = document.querySelectorAll('.question-section');

        pageNumb = (cardNumber) * 2;
        pageNumb2 = (cardNumber) * 2 - 1;
        document.getElementById('numberPage' + pageNumb2).classList.add('bg-secondary');
        document.getElementById('numberPage' + pageNumb).classList.add('bg-secondary');
        document.getElementById('numberPage' + pageNumb2).classList.remove('bg-primary');
        document.getElementById('numberPage' + pageNumb).classList.remove('bg-primary');


        // if (cardBegin <script 1) {

        //     document.getElementById('backBtn').style.display = 'none';


        if (pageNumb2 == 1) {

            document.getElementById('backBtn1').style.display = 'none';

        }
        sections.forEach(function(section, index) {
            if (index !== (cardNumber - 1)) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        });
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

    async function saveAnswer() {
        var formElements = document.getElementById("formExam");
        formElements.submit();
        fetch('<?= base_url('Question/getScore/') ?>')
            .then(response => {
                return response.json(); // Parse response as JSON
            })
            .then(data => {
                console.log(data);
                const score = data.score;
                document.getElementById('showScore').style.display = 'block';
                document.getElementById('showQuestion').style.display = 'none';

                document.getElementById('score').value = score;
            })
            .catch(error => {
                console.error('Error fetching data showdetail:', error);
            });
        // const score = data.score;
        // const totalQuestion = data.totalQuestion;
        // const trueAnswer = data.trueAnswer;

        // document.getElementById('showScore').style.display = 'block';
        // document.getElementById('showQuestion').style.display = 'none';
        // document.getElementById('trueAns').value = score;
        // document.getElementById('score').value = score;
        // document.getElementById('xx').value = trueAnswer + "/" + totalQuestion;

    }

    document.addEventListener('DOMContentLoaded', function() {
        // Select all elements with id starting with "number"
        var elements = document.querySelectorAll('[id^="number"]');

        // Loop through each element
        elements.forEach(function(element) {
            // Get the id attribute value
            var id = element.id;

            // Extract the number from the id
            var number = parseInt(id.replace("number", "")); // Parse the number as an integer

            // Check if the current number is higher than the highestNumber
            if (number > highestNumber) {
                highestNumber = number; // Update highestNumber if the current number is higher
            }
            // Output the number
            console.log("Number:", number);
        });
    });
</script>

</html>