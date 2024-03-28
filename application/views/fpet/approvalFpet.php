<?php
ob_start();
?>
<?php
$combinedData = [];
$uniqueIds = [];
foreach ($substance as $s) {
    $title = $s->TRNSUB_TITLE;
    $id_header = $s->TRNHDR_ID;
    $id_detail = $s->TRNSUB_ID;
    $path = $s->TRNSUB_PATH;
    $status = $s->TRNSUB_STATUS;
    $combinedData[] = array(
        'title' => $title, 'id_header' => $id_header,
        'id_detail' => $id_detail, 'path' => $path,
        'status' => $status
    );
    if (!in_array($id_header, $uniqueIds)) {
        $uniqueIds[] = $id_header;
    }
}
$combinedDataJSON = json_encode($combinedData);
?>
<div class="container-fluid">
    <div id="showListFpet">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-2 mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title">Approval FPET</h4>
                                <p class="card-category">Form Penilaian dan Evaluasi Training</p>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <table name="table" class="table table-hover table-head-bg-info my-2">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center" style="width: 50px;">No.</th>
                                    <th scope="col" class="text-center" style="width: 500px;">Peserta Pelatihan</th>
                                    <th scope="col" class="text-center" style="width: 700px;">Target</th>
                                    <th scope="col" class="text-center" style="width: 500px;">Approval Atasan</th>
                                    <th scope="col" class="text-center" style="width: 500px;">Approval HR</th>
                                    <th scope="col" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tBodymainTable">
                                <?php
                                if (empty($fpet)) {
                                    echo '<tr><td colspan="6" class="text-center">Belum ada data</td></tr>';
                                } else {
                                    $i = 1;
                                    foreach ($fpet as $t) {
                                        // Define status text based on the value of statusApproved
                                        if (isset($t['approvedHr']) == $this->session->userdata('npk') && isset($t['approved']) != $this->session->userdata('npk') && isset($t['statusApproved == 2)'])) {
                                        } else {
                                            $statusText = '';
                                            switch (isset($t['statusApproved']) ? $t['statusApproved'] : '') {
                                                case 0:
                                                    $statusText = 'Ditolak';
                                                    break;
                                                case 1:
                                                    $statusText = 'Disetujui';
                                                    break;
                                                case 2:
                                                default:
                                                    $statusText = 'Belum disetujui';
                                                    break;
                                            }

                                            $statusTextHr = '';
                                            switch (isset($t['statusApprovedHr']) ? $t['statusApprovedHr'] : '') {
                                                case 0:
                                                    $statusTextHr = 'Ditolak';
                                                    break;
                                                case 1:
                                                    $statusTextHr = 'Disetujui';
                                                    break;
                                                case 2:
                                                default:
                                                    $statusTextHr = 'Belum disetujui';
                                                    break;
                                            }
                                ?>
                                            <tr>
                                                <td><?php echo $i ?></td>
                                                <td><?php echo isset($t['nama']) ? $t['nama'] : ''; ?></td>
                                                <td><?php echo isset($t['target']) ? $t['target'] : ''; ?></td>
                                                <td><?php echo $statusText ?></td>
                                                <td><?php echo $statusTextHr ?></td>
                                                <td class="text-center"><a href="javascript:void(0)" onclick="showDetailApprovalFpet(<?php echo isset($t['idFpet']) ? $t['idFpet'] : ''; ?>)" class="btn btn-primary"></i>Detail</a></th>

                                            </tr>
                                <?php
                                            $i++;
                                        }
                                    }
                                    if ($i == 1) {
                                        echo '<tr><td colspan="6" class="text-center">Belum ada data</td></tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="addFpet" style="display: none;">
        <div class="loader-container" id="loaderDiv">
            <div class="loader">
                <div class="loader-reverse"></div>
            </div>
            <p class="m-0">&emsp;Loading data...</p>
        </div>
        <div class="col-md-12">
            <form id="formFpet" method="post" enctype="multipart/form-data" role="form">
                <div class="card p-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <div class="card-title" id="cardTitle">Form Pengajuan dan Evaluasi Training</div>
                                <p class="card-category" id="cardCategory">FPET / Tambah FPET</p>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-end" id="btnDetailFpet" style="display: none;">
                                    <a id="approveBtnFpet" class="btn btn-info" href="javascript:void(0)" style="margin-right: 9px; display: none;"></i> Approve</a>
                                    <a id="rejectBtnFpet" class="btn btn-danger " href="javascript:void(0)" style="margin-right: 9px; display: none;"></i> Reject</a>
                                    <a id="approveBtnFpetHr" href="javascript:void(0)" onclick="showFormTrainModal()" class="btn btn-info" style="margin-right: 9px; display: none;"></i> Approve HR</a>
                                    <a id="rejectBtnFpetHr" class="btn btn-danger " href="javascript:void(0)" style="margin-right: 9px; display: none;"></i> Reject HR</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pilih Calon Partisipan <span style="color: red;">*</span></label>
                                    <select class="form-control input-pill mb-3" id="partisipanTraining" name="partisipanTraining">
                                        <option disabled selected>Pilih</option>
                                        <?php foreach ($employee as $e) : ?>
                                            <option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Saran training <span style="color: red;">*</span></label>
                                    <input type="text" maxlength="40" class="form-control input-pill mb-3" name="trainSuggest" id="trainSuggest" placeholder="Masukkan Saran Training">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="my-2">Kondisi Aktual</label>
                                <textarea class="form-control" id="actual" name="actual" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label>Kemampuan saat ini <span style="color: red;">*</span></label><br />
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="0" required>
                                        <span class="form-radio-sign">0%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="25">
                                        <span class="form-radio-sign">25%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="50">
                                        <span class="form-radio-sign">50%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="75">
                                        <span class="form-radio-sign">75%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rActual" id="rActual" value="100">
                                        <span class="form-radio-sign">100%</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="my-2">Target / Standard</label>
                                <textarea class="form-control" id="target" name="target" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label>Kemampuan Yang diinginkan<span style="color: red;">*</span></label> <br />
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="0" required>
                                        <span class="form-radio-sign">0%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="25">
                                        <span class="form-radio-sign">25%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="50">
                                        <span class="form-radio-sign">50%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="75">
                                        <span class="form-radio-sign">75%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="100">
                                        <span class="form-radio-sign">100%</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-6">
                                <label class="my-2">Evaluasi Hasil</label>
                                <textarea class="form-control" id="eval" name="eval" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda"></textarea>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label for="chooseTrain">Evaluasi Hasil<span style="color: red;">*</span></label> <br />
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="0" required>
                                        <span class="form-radio-sign">0%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="25">
                                        <span class="form-radio-sign">25%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="50">
                                        <span class="form-radio-sign">50%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="75">
                                        <span class="form-radio-sign">75%</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEval" id="rEval" value="100">
                                        <span class="form-radio-sign">100%</span>
                                    </label>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <div class="col-md-12">
                                <label class="my-2">Keterangan dan Saran</label>
                                <textarea class="form-control" id="notes" name="notes" rows="1" maxlength="200" placeholder="Masukkan pendapat Anda"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="approvedHR">Pilih Pihak HRD yang Menyetujui <span style="color: red;">*</span></label>
                                    <select class="form-control input-pill mb-3" id="approvedHR" name="approvedHr">
                                        <option disabled selected>Pilih </option>
                                        <?php foreach ($employee as $e) : ?>
                                            <option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="approved">Pilih Pihak yang Menyetujui <span style="color: red;">*</span></label>
                                    <select class="form-control input-pill mb-3" id="approved" name="approved">
                                        <option disabled selected>Pilih </option>
                                        <?php foreach ($employee as $e) : ?>
                                            <option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row" id="makeTrain" style=" display: none;">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <label>Apakah Anda ingin mengambil usulan training bedasar training yang ada? <span style="color: red;">*</span></label><br />
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="rEstablished" id="rEstablishedY" value="Ya" required onchange="toggleTrainSections()">
                                        <span class="form-radio-sign">Ya</span>
                                    </label>
                                    <label class="form-radio-label ml-3">
                                        <input class="form-radio-input" type="radio" name="rEstablished" id="rEstablishedN" value="Tidak" onchange="toggleTrainSections()">
                                        <span class="form-radio-sign">Tidak</span>
                                    </label>
                                </div>
                            </div>
                            <div id="trainSection1" style="display: none;">
                                <div class="row">
                                    <div class="col">
                                        <input type="text" hidden class="form-control input-pill mb-3" name="idFpet" id="idFpet">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="chooseTrain">Pilih Training <span style="color: red;">*</span></label>
                                    <select class="form-control" id="chooseTrain" name="chooseTrain">
                                        <option disabled selected>Pilih </option>
                                        <?php foreach ($training as $t) : ?>
                                            <option value="<?php echo $t->TRNHDR_ID; ?>"><?php echo $t->TRNHDR_TITLE; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="trainSection2" style="display: none;">
                            <div class="row">
                                <div class="col">
                                    <label class="my-2">Judul Training</label>
                                    <input type="text" class="form-control input-pill mb-3" name="title" id="title" placeholder="Masukkan Judul Training">
                                </div>
                                <div class="col">
                                    <label class="my-2">Lembaga Pelaksana</label><br />

                                    <input type="text" class="form-control input-pill mb-3" name="educator" id="educator" placeholder="Masukkan Lembaga Pelaksana">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label class="my-2">Jadwal training</label>
                                    <input type="date" min="<?php echo date('Y-m-d') ?>" class="form-control input-pill mb-3" name="schedule" id="schedule" placeholder="Pilih Jadwal">
                                </div>
                                <div class="col">
                                    <label class="my-2">Biaya Pelaksanaan</label><br />
                                    <input type="text" class="form-control input-pill mb-3" name="cost" id="cost" placeholder="Masukkan Biaya ">
                                </div>
                            </div>
                        </div> -->
                        <div class="card-body" id="divBackSub">
                            <button type="button" id="btnSubApprove" class="btn btn-success float-right">Simpan</button>
                            <a href="javascript:void(0)" onclick="changeFormFpet('main')" class="btn btn-danger"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="trainModal" tabindex="-1" role="dialog" aria-labelledby="trainModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 950px">
            <div class="modal-content" style="width: 950px;">
                <div class="card p-2 mb-0">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title" id="trainModalLabel">Tambah Train</h4>
                                <p class="card-category">Approval FPET / Tambah Training</p>
                            </div>
                            <div class="col d-flex justify-content-end">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form id="formMakeTrain" method="post" enctype="multipart/form-data" role="form">
                            <div class="card-body p-0">
                                <div class="row" id="makeTrain" style=" display: none;">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <label>Apakah Anda ingin mengambil usulan training bedasar training yang ada? <span style="color: red;">*</span></label><br />
                                            <label class="form-radio-label ml-3">
                                                <input class="form-radio-input" type="radio" name="rEstablished" id="rEstablishedY" value="1" onchange="toggleTrainSections()">
                                                <span class="form-radio-sign">Ya</span>
                                            </label>
                                            <label class="form-radio-label ml-3">
                                                <input class="form-radio-input" type="radio" name="rEstablished" id="rEstablishedN" value="0" onchange="toggleTrainSections()">
                                                <span class="form-radio-sign">Tidak</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Saran training <span style="color: red;">*</span></label>
                                            <input type="text" maxlength="40" class="form-control input-pill mb-3" name="trainSuggestModal" id="trainSuggestModal" placeholder="Masukkan Saran Training" readonly>
                                        </div>
                                    </div>
                                    <div id="trainSection1" style="display: none;">
                                        <div class="row">
                                            <div class="col">
                                                <input type="text" hidden class="form-control input-pill mb-3" name="idFpet" id="idFpet">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="chooseTrain">Pilih Training <span style="color: red;">*</span></label>
                                            <select class="form-control" id="chooseTrain" name="chooseTrain">
                                                <option disabled selected>Pilih </option>
                                                <?php foreach ($training as $t) : ?>
                                                    <option value="<?php echo $t->TRNHDR_ID; ?>"><?php echo $t->TRNHDR_TITLE; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="trainSection2" style="display: none;">
                                    <div class="row">
                                        <div class="col">
                                            <label class="my-2">Judul Training</label>
                                            <input type="text" class="form-control input-pill mb-3" name="title" id="title" placeholder="Masukkan Judul Training">
                                        </div>
                                        <div class="col">
                                            <label class="my-2">Lembaga Pelaksana</label><br />

                                            <input type="text" class="form-control input-pill mb-3" name="educator" id="educator" placeholder="Masukkan Lembaga Pelaksana">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col">
                                            <label class="my-2">Jadwal training</label>
                                            <input type="date" min="<?php echo date('Y-m-d') ?>" class="form-control input-pill mb-3" name="schedule" id="schedule" placeholder="Pilih Jadwal">
                                        </div>
                                        <div class="col">
                                            <label class="my-2">Biaya Pelaksanaan</label><br />
                                            <input type="number" class="form-control input-pill mb-3" name="cost" id="cost" placeholder="Masukkan Biaya ">
                                        </div>
                                    </div>
                                    <input type="text" hidden class="form-control input-pill mb-3" name="npk" id="npk">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <label>Pilih Jenis Traininsg <span style="color: red;">*</span></label><br />
                                                <label class="form-radio-label">
                                                    <input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="Inhouse">
                                                    <span class="form-radio-sign" name="categoryTrainText">In-House</span>
                                                </label>
                                                <label class="form-radio-label ml-3">
                                                    <input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="Outhouse">
                                                    <span class="form-radio-sign" name="categoryTrainText">Out-House</span>
                                                </label>
                                                <label class="form-radio-label ml-3">
                                                    <input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="Elearning">
                                                    <span class="form-radio-sign" name="categoryTrainText">Elearning</span>
                                                </label>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" onclick="submitTrain()" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Function to disable options in rTarget greater than selected rActual
    function disableRTargetOptions(checkedValue) {
        var rTargetRadios = document.getElementsByName('rTarget');
        rTargetRadios.forEach(radio => {
            if (parseInt(radio.value) < parseInt(checkedValue)) {
                radio.disabled = true;
            } else {
                radio.disabled = false;
            }
        });
    }

    // Function to disable options in rActual less than selected rTarget
    function disableRActualOptions(checkedValue) {
        var rActualRadios = document.getElementsByName('rActual');
        rActualRadios.forEach(radio => {
            if (parseInt(radio.value) > parseInt(checkedValue)) {
                radio.disabled = true;
            } else {
                radio.disabled = false;
            }
        });
    }

    // Event listener for rActual radio buttons
    var rActualRadios = document.getElementsByName('rActual');
    rActualRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                disableRTargetOptions(this.value);
            }
        });
    });

    // Event listener for rTarget radio buttons
    var rTargetRadios = document.getElementsByName('rTarget');
    rTargetRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                disableRActualOptions(this.value);
            }
        });
    });

    function doUpdate() {
        document.getElementById('btnSubApprove').style.display = 'block';
        enableFormElements();
    }

    function clearFormFpet() {
        // Reset input values
        document.getElementById('idFpet').value = '';
        document.getElementById('partisipanTraining').value = '';
        document.getElementById('actual').value = '';
        document.getElementById('target').value = '';
        document.getElementById('notes').value = '';
        document.getElementById('approvedHR').value = '';
        document.getElementById('approved').value = '';

        // Reset radio buttons for rActual
        var rActualRadios = document.getElementsByName('rActual');
        rActualRadios.forEach(radio => {
            radio.checked = false;
        });

        // Reset radio buttons for rTarget
        var rTargetRadios = document.getElementsByName('rTarget');
        rTargetRadios.forEach(radio => {
            radio.checked = false;
        });

        // Reset radio buttons for rEval
        // var rEvalRadios = document.getElementsByName('rEval');
        // rEvalRadios.forEach(radio => {
        //     radio.checked = false;
        // });

        // Reset select elements
        document.getElementById('chooseTrain').selectedIndex = 0;
        document.getElementById('approvedHR').selectedIndex = 0;
        document.getElementById('approved').selectedIndex = 0;

        // Reset radio buttons for rEstablished
        document.getElementById('rEstablishedY').checked = false;
        document.getElementById('rEstablishedN').checked = false;
    }


    function submitTrain() {
        var formElements = document.getElementById("formMakeTrain");
        var idTrain = document.getElementById("chooseTrain").value;
        var npk = document.getElementById("npk").value;
        var rEstablishedValue = document.querySelector('input[name="rEstablished"]:checked').value;
        if (rEstablishedValue == 1) {
            var dropdowns = ["chooseTrain"];
            var isValid = true;
            dropdowns.forEach(dropdownId => {
                var dropdownValue = document.getElementById(dropdownId).value;
                if (!dropdownValue || dropdownValue === "Pilih") {
                    document.getElementById(dropdownId).style.border = "1px solid red";
                    isValid = false;
                } else {
                    document.getElementById(dropdownId).style.border = "1px solid #ced4da";
                }
            });

            if (isValid) {

                fetch('<?= base_url('FPET/checkParticipant/') ?>?npk=' + npk + '&id=' + idTrain)
                    .then(response => {
                        return response.json();
                    })
                    .then(dataExists => {
                        console.log('Data exists:', dataExists);
                        // Check if participant exists
                        if (dataExists) {
                            // Participant exists
                            Swal.fire({
                                icon: 'error',
                                title: 'Data sudah Ada !',
                                text: 'Data sudah ada dalam database.',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Konfirmasi Persetujuan Training',
                                text: 'Apakah Anda yakin ingin data diisi dengan benar?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya',
                                cancelButtonText: 'Tidak'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    formElements.submit();
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

            }
        } else {
            var requiredFields = ["title", "schedule", "educator", "cost"];
            var isValid = true;

            requiredFields.forEach(fieldId => {
                var fieldValue = document.getElementById(fieldId).value.trim();

                if (!fieldValue) {
                    document.getElementById(fieldId).style.border = "1px solid red";
                    isValid = false;
                } else {
                    document.getElementById(fieldId).style.border = "1px solid #ced4da";
                }
            });

            var actualRadioChecked = document.querySelector('input[name="categoryTrain"]:checked');
            if (!actualRadioChecked) {
                document.querySelectorAll('span[name="categoryTrainText"]').forEach(function(span) {
                    span.style.color = "red";
                });
                isValid = false;
            } else {
                document.querySelectorAll('span[name="categoryTrainText"] .text-content').forEach(function(span) {
                    span.style.color = "";
                });
            }
            if (isValid) {
                Swal.fire({
                    title: 'Konfirmasi Persetujuan Training',
                    text: 'Apakah Anda yakin ingin data diisi dengan benar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.isConfirmed) {
                        formElements.submit();
                    }
                });
            }

        }
    }

    function disableFormElements() {
        // Get all form elements
        var formElements = document.getElementById("formFpet").elements;

        // Iterate through each form element
        for (var i = 0; i < formElements.length; i++) {
            // Set disabled attribute to true
            formElements[i].disabled = true;
        }
    }

    function enableFormElements() {
        // Get all form elements
        var formElements = document.getElementById("formFpet").elements;

        // Iterate through each form element
        for (var i = 0; i < formElements.length; i++) {
            // Set disabled attribute to false
            formElements[i].disabled = false;
        }
    }



    var rowFtpe = 0;

    function changeFormFpet() {
        var formElement = document.getElementById('formFpet');
        formElement.removeAttribute('action');
        document.getElementById("showListFpet").style.display = 'block';
        document.getElementById("addFpet").style.display = 'none';
        document.getElementById("approveBtnFpetHr").style.display = 'none';
        document.getElementById("rejectBtnFpetHr").style.display = 'none';
        document.getElementById("rejectBtnFpet").style.display = 'none';
        document.getElementById("approveBtnFpet").style.display = 'none';
    }

    function showAdd(kode) {
        enableFormElements();
        clearFormFpet();

        var formElement = document.getElementById('formFpet');
        formElement.setAttribute('action', '<?php echo base_url('FPET/saveFpet/') ?>');
        document.getElementById("showListFpet").style.display = 'none';
        document.getElementById("addFpet").style.display = 'block';
    }

    // Function to toggle visibility of trainSection1 and trainSection2
    function toggleTrainSections() {
        var rEstablishedValue = document.querySelector('input[name="rEstablished"]:checked').value;

        if (rEstablishedValue == "1") {
            document.getElementById('trainSection1').style.display = 'block';
            document.getElementById('trainSection2').style.display = 'none';
        } else {
            document.getElementById('trainSection1').style.display = 'none';
            document.getElementById('trainSection2').style.display = 'block';
        }
    }


    // Call toggleTrainSections initially to set the initial visibility state
    toggleTrainSections();



    async function showDetailApprovalFpet(id) {
        if (id != '0') {
            var formElement = document.getElementById('formFpet');
            formElement.setAttribute('action', '<?php echo base_url('FPET/modifyFpet/') ?>' + id);
            fetch('<?= base_url('FPET/showDetail/') ?>' + id)
                .then(response => {
                    return response.json(); // Parse response as JSON
                })
                .then(data => {
                    console.log(data);
                    var dataFpet = data.dataFpet; // Extract dataFpet object from response

                    if (dataFpet) {
                        disableFormElements();
                        // Update input values

                        document.getElementById('trainSuggest').value = dataFpet.FPETFM_TRAINSUGGEST || '';
                        document.getElementById('trainSuggestModal').value = dataFpet.FPETFM_TRAINSUGGEST || '';
                        document.getElementById('idFpet').value = dataFpet.FPETFM_ID || '';
                        document.getElementById('partisipanTraining').value = dataFpet.AWIEMP_NPK || '';
                        document.getElementById('actual').value = dataFpet.FPETFM_ACTUAL || '';
                        document.getElementById('target').value = dataFpet.FPETFM_TARGET || '';
                        document.getElementById('npk').value = dataFpet.AWIEMP_NPK || '';
                        document.getElementById('notes').value = dataFpet.FPETFM_NOTES || '';
                        document.getElementById('approvedHR').value = dataFpet.FPETFM_HRAPPROVER || '';

                        document.getElementById('approved').value = dataFpet.FPETFM_APPROVER || '';
                        var rActualRadios = document.getElementsByName('rActual');
                        rActualRadios.forEach(radio => {
                            if (radio.value === dataFpet.FPETFM_PACTUAL.toString()) {
                                radio.checked = true;
                            }
                        });
                        // Set the radio button for rTarget based on the value received
                        var rTargetRadios = document.getElementsByName('rTarget');
                        rTargetRadios.forEach(radio => {
                            if (radio.value === dataFpet.FPETFM_PTARGET.toString()) {
                                radio.checked = true;
                            }
                        });


                        var formElement = document.getElementById('formMakeTrain');
                        formElement.setAttribute('action', '<?php echo base_url('FPET/approveHrFpet/') ?>' + id);

                        // document.getElementById('rEval' + (dataFpet.FPETFM_PEVAL || '')).checked = true;
                        document.getElementById('btnSubApprove').style.display = 'none';
                        // Show the buttons
                        document.getElementById('btnDetailFpet').style.display = 'block';
                        if (dataFpet.FPETFM_STATUS == '1') {
                            if (dataFpet.FPETFM_APPROVER == <?php echo $this->session->userdata('npk'); ?> && dataFpet.FPETFM_APPROVED === 2) {
                                document.getElementById('rejectBtnFpet').style.display = 'inline-block';
                                document.getElementById('approveBtnFpet').style.display = 'inline-block';
                                var rejectBtnFpet = document.getElementById('rejectBtnFpet');
                                //  rejectBtnFpet.setAttribute('href', '<?= base_url('FPET/rejectFpet/') ?>' + id);
                                rejectBtnFpet.setAttribute('onclick', "confirmApproval(0, '" + id + "')");
                                var approveBtnFpet = document.getElementById('approveBtnFpet');
                                // approveBtnFpet.setAttribute('href', '<?= base_url('FPET/approveFpet/') ?>' + id);
                                approveBtnFpet.setAttribute('onclick', "confirmApproval(1, '" + id + "')");
                            }
                            if (dataFpet.FPETFM_HRAPPROVER == <?php echo $this->session->userdata('npk'); ?> && (dataFpet.FPETFM_APPROVED === 1 || dataFpet.FPETFM_APPROVED === 0) && dataFpet.FPETFM_HRAPPROVED == 2) {
                                document.getElementById('rejectBtnFpetHr').style.display = 'inline-block';
                                document.getElementById('approveBtnFpetHr').style.display = 'inline-block';
                                var rejectBtnFpet = document.getElementById('rejectBtnFpetHr');
                                // rejectBtnFpet.setAttribute('href', '<?= base_url('FPET/rejectHrFpet/') ?>' + id);
                                rejectBtnFpet.setAttribute('onclick', "confirmApproval(3, '" + id + "')");
                                document.getElementById('makeTrain').style.display = 'block';

                            }
                        }
                        document.getElementById("showListFpet").style.display = 'none';
                        document.getElementById("addFpet").style.display = 'block';
                        callLoader();
                    } else {
                        console.error('Error: No data found for id ' + id);
                    }
                })
                .catch(error => {
                    console.error('Error fetching data showdetail:', error);
                });
        }
    }

    function enableFormElements(container) {
        // Get all form elements within the container 
        var elements = container.querySelectorAll('input, select');
        // Iterate through each form element
        for (var i = 0; i < elements.length; i++) {
            // Remove the disabled attribute
            elements[i].disabled = false;
        }

    }

    function showFormTrainModal() {
        new bootstrap.Modal(document.getElementById('trainModal')).show();

    };

    function confirmApproval(code, id) {
        if (code == 1) {
            Swal.fire({
                title: 'Konfirmasi Persetujuan FPET',
                text: 'Apakah Anda yakin ingin menyetujui data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('FPET/rejectApproveFpet/') ?>' + code + '/' + id;
                }
            });
        } else if (code == 0 || code == 3) {
            Swal.fire({
                title: 'Konfirmasi Penolakan FPET',
                text: 'Apakah Anda yakin ingin menolak data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (code == 0) {
                        window.location.href = '<?= base_url('FPET/rejectApproveFpet/') ?>' + code + '/' + id;
                    } else if (code == 3) {
                        window.location.href = '<?= base_url('FPET/rejectApproveFpet/') ?>' + code + '/' + id;
                    }
                }
            });
        }
    }
</script>

<?php include __DIR__ . '/../script2.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/../layout.php";
?>