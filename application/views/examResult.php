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

                    <div id="showScore">
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
                                                    <th scope="col" class="text-center" style="width: 700px;">Skor Akhir</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tBodymainTable">
                                                <td style="text-align: center;"><?php echo $score; ?></td>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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

    function backExam() {
        Swal.fire({
            title: 'Konfirmasi Selesai',
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
</script>

</html>