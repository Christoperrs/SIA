<?php
ob_start();
?>
<script>
    <?php if ($this->session->flashdata('error_message')) : ?>
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: '<?php echo $this->session->flashdata('error_message'); ?>',
        });
    <?php endif; ?>
</script>
<div class="container-fluid">
    <div class="row" id="packagePage">
        <div class="col-md-12">
            <div class="card p-2 m-0">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <h4 class="card-title">Resume Training</h4>
                            <p class="card-category">Hasil Resume Karyawan</p>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="max-height: 480px; overflow-y: scroll; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
                    <?php
                    $i = 1;
                    if (empty($resumes)) {
                    ?>
                        <div class="avatar flex-shrink-0 me-3 justify-content-center d-flex">

                            <img src="<?= base_url('assets/img/dataEmpty1.jpg') ?>" alt="User" class="img-fluid" style="max-width: 310px; max-height: 190px;" />

                        </div>
                        <hr>
                        <h5 style="text-align: center;">Data Tidak Ada</h5>
                    <?php
                    } else { ?>
                        <div class="form-inline py-2">
                            <label class="col-md-1 p-0">Search:&nbsp;&nbsp;</label>
                            <div class="col-md-11 p-0">
                                <input type="text" class="form-control input-full" onkeyup="searchTableResume()" id="searchInputResume" name="searchInputResume">
                            </div>
                        </div>
                        <table id="resumeTable" name="table" class="table table-hover table-head-bg-info my-2">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 50px;">No.</th>
                                    <th scope="col" onclick="sortResume(1)">Nama Training</th>
                                    <th scope="col" onclick="sortResume(2)">Nama Karyawan</th>
                                    <th scope="col" style="width: 135px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;
                                foreach ($resumes as $e) { ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $e['TRNHDR_TITLE']; ?></td>
                                        <td><?php echo $e['NAMA']; ?></td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="javascript:void(0)" id="editBtn" onclick="getOverview(<?php echo $e['TRNHDR_ID']; ?>, <?php echo $e['NPK']; ?>)" class="btn btn-warning mr-2"><i class="la la-pencil" style="font-size: 16px;"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php $i++;
                                } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div id="listCardDiv" style="display: none;">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-2 mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title" id="overviewTitle"></h4>
                                <p class="card-category">Overview Training Result</p>
                            </div>
                        </div>
                    </div>
                    <form id="formEvaluasi" action="<?php echo base_url('Personal/evaluate') ?>" method="post">
                        <input type="hidden" id="FPETFM_ID" name="FPETFM_ID">
                        <div class="card-body pt-0">
                            <p class="card-category">Biodata</p>
                            <div class="row form-group px-0 pb-0">
                                <div class="col-md-5">
                                    <label for="NPK" class="pt-2">NPK</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="NPK" name="NPK" readonly>
                                </div>
                            </div>
                            <div class="row form-group px-0 pb-0">
                                <div class="col-md-5">
                                    <label for="NAMA" class="pt-2">NAMA</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="NAMA" name="NAMA" readonly>
                                </div>
                            </div>
                            <div class="row form-group px-0 pb-0">
                                <div class="col-md-5">
                                    <label for="DEPARTEMEN" class="pt-2">DEPARTEMEN</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="DEPARTEMEN" name="DEPARTEMEN" readonly>
                                </div>
                            </div>
                            <div class="row form-group px-0 pb-0">
                                <div class="col-md-5">
                                    <label for="FPETFM_TRAINSUGGEST" class="pt-2">TRAINING SUGGESTION</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="FPETFM_TRAINSUGGEST" name="FPETFM_TRAINSUGGEST" readonly>
                                </div>
                            </div>
                            <div class="row form-group px-0">
                                <div class="col-md-5">
                                    <label for="TRAINED_IN" class="pt-2">TRAINED IN</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="TRAINED_IN" name="TRAINED_IN" readonly>
                                </div>
                            </div>
                            <p class="card-category" style="border-top: 1px solid #ebedf2 !important;">Pre-test dan Post-test</p>
                            <div class="row row-card-no-pd p-0 mb-3">
                                <div class="col-md-2 px-0">
                                    <div class="card">
                                        <div class="card-body pl-0 pt-0">
                                            <label class="pt-2">Pre-test Score</label>
                                            <div id="preScore" class="chart-circle my-2"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 px-0">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <label class="pt-2">Post-test Score</label>
                                            <div id="postScore" class="chart-circle my-2"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 px-0">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <label class="pt-2 mb-1">Employee's Resume</label>
                                            <textarea class="form-control" id="TRNACC_RESUME" name="TRNACC_RESUME" rows="4" spellcheck="false" style="resize: none;" readonly></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="card-category" style="border-top: 1px solid #ebedf2 !important;">Evaluasi Hasil</p>
                            <div class="row row-card-no-pd p-0">
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body pl-0 pt-0">
                                            <label class="pt-2">Actual Condition</label>
                                            <textarea class="form-control mb-4" id="FPETFM_ACTUAL" name="FPETFM_ACTUAL" rows="5" spellcheck="false" style="resize: none;" readonly></textarea>
                                            <div class="progress-card">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Actual Percentage</span>
                                                    <span class="text-muted fw-bold" id="actPercentage"></span>
                                                </div>
                                                <div class="progress mb-2" style="height: 7px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" id="actBar" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title=""></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <label class="pt-2">Target Condition</label>
                                            <textarea class="form-control mb-4" id="FPETFM_TARGET" name="FPETFM_TARGET" rows="5" spellcheck="false" style="resize: none;" readonly></textarea>
                                            <div class="progress-card">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Target Percentage</span>
                                                    <span class="text-muted fw-bold" id="tarPercentage"></span>
                                                </div>
                                                <div class="progress mb-2" style="height: 7px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" id="tarBar" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title=""></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <label class="pt-2">Result Condition <span style="color: red;">*</span></label>
                                            <textarea class="form-control mb-4" id="FPETFM_EVAL" name="FPETFM_EVAL" rows="5" spellcheck="false" style="resize: none;" placeholder="Evaluate..." required></textarea>
                                            <div class="progress-card">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="text-muted">Result Percentage <span style="color: red;">*</span></span>
                                                    <span id="percentageValue" class="text-muted fw-bold"></span>
                                                </div>
                                                <div id="rangeContainer">
                                                    <input type="range" id="FPETFM_PEVAL" name="FPETFM_PEVAL" class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuemin="0" aria-valuemax="100" data-original-title="0%">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="card-category" style="border-top: 1px solid #ebedf2 !important;">Training Success Metric</p>
                            <div class="row row-card-no-pd p-0">
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body pl-0 py-0">
                                            <label class="pt-2 mb-2">Request Approved By</label>
                                            <h6 class="m-0" id="requestApprovedName"><b></b></h6>
                                            <label class="mb-2" id="requestApprovedDept"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body py-0">
                                            <label class="pt-2 mb-2">Request Approved By HR</label>
                                            <h6 class="m-0" id="hrApprovedName"><b></b></h6>
                                            <label class="mb-2" id="hrApprovedDept"></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body py-0">
                                            <label class="pt-2 mb-2">This Form is Evaluated by</label>
                                            <h6 class="m-0" id="evaluatorName"><b></b></h6>
                                            <label class="mb-2" id="evaluatorDept"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" onclick="sendEvaluation()" id="btnSimpan" class="btn btn-success float-right">Simpan</button>
                            <button type="button" onclick="backToResumes()" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function backToResumes() {
        window.location.reload();
    }

    function getOverview(id, npk) {
        var data = {
            TRNHDR_ID: id,
            AWIEMP_NPK: npk
        };

        $.ajax({
            url: '<?php echo base_url('Personal/Overview') ?>', // Replace 'controller/method' with the actual controller and method name
            type: 'POST',
            data: data,
            success: function(response) {
                console.log('Success:', JSON.parse(response));
                var data = JSON.parse(response);
                document.getElementById('listCardDiv').style.display = 'block';
                document.getElementById('packagePage').style.display = 'none';

                document.getElementById('overviewTitle').textContent = 'Overview of ' + data.employee.NAMA + ' in ' + data.overview.TRNHDR_TITLE;
                document.getElementById('FPETFM_ID').value = data.overview.FPETFM_ID;
                document.getElementById('NPK').value = data.employee.NPK;
                document.getElementById('NAMA').value = data.employee.NAMA;
                document.getElementById('DEPARTEMEN').value = data.employee.DEPARTEMEN;
                document.getElementById('FPETFM_TRAINSUGGEST').value = data.overview.FPETFM_TRAINSUGGEST;
                document.getElementById('TRAINED_IN').value = data.overview.TRNHDR_TITLE;
                document.getElementById('TRNACC_RESUME').value = data.overview.TRNACC_RESUME;
                document.getElementById('FPETFM_ACTUAL').value = data.overview.FPETFM_ACTUAL;
                document.getElementById('actPercentage').textContent = data.overview.FPETFM_PACTUAL + '%';
                document.getElementById('FPETFM_TARGET').value = data.overview.FPETFM_TARGET;
                document.getElementById('tarPercentage').textContent = data.overview.FPETFM_PTARGET + '%';
                document.getElementById('FPETFM_EVAL').value = data.overview.FPETFM_EVAL;
                document.getElementById('percentageValue').textContent = data.overview.FPETFM_PEVAL + '%';

                var actBar = document.getElementById('actBar');
                actBar.style.width = data.overview.FPETFM_PACTUAL + '%';
                actBar.setAttribute('aria-valuenow', data.overview.FPETFM_PACTUAL);
                actBar.setAttribute('data-original-title', data.overview.FPETFM_PACTUAL);

                var tarBar = document.getElementById('tarBar');
                tarBar.style.width = data.overview.FPETFM_PTARGET + '%';
                tarBar.setAttribute('aria-valuenow', data.overview.FPETFM_PTARGET);
                tarBar.setAttribute('data-original-title', data.overview.FPETFM_PTARGET);

                var evaRange = document.getElementById('FPETFM_PEVAL');
                evaRange.value = data.overview.FPETFM_PEVAL;
                evaRange.setAttribute('aria-valuenow', data.overview.FPETFM_PEVAL);
                evaRange.setAttribute('data-original-title', data.overview.FPETFM_PEVAL);

                document.getElementById('requestApprovedName').textContent = data.approver.NAMA;
                document.getElementById('requestApprovedDept').textContent = data.approver.DEPARTEMEN;
                document.getElementById('hrApprovedName').textContent = data.HRapprover.NAMA;
                document.getElementById('hrApprovedDept').textContent = data.HRapprover.DEPARTEMEN;
                document.getElementById('evaluatorName').textContent = data.evaluator.NAMA;
                document.getElementById('evaluatorDept').textContent = data.evaluator.DEPARTEMEN;

                // document.getElementById('btnSimpan').toggleAttribute("hidden", data.overview.FPETFM_PEVAL != null);


                console.log('dataax' + data.overview.FPETFM_STATUS);

                if (data.overview.FPETFM_STATUS == 3) {
                    var elements = document.getElementsByClassName('d-flex justify-content-between mb-1');
                    var firstElement = elements[0];
                    firstElement.classList.replace('mb-1', 'mb-2');
                    document.getElementById('FPETFM_EVAL').setAttribute('readonly', 'readonly');

                    var container = document.getElementById('rangeContainer');
                    container.classList.add('progress', 'mb-2');
                    container.style.height = '7px';

                    var evaBar = document.createElement('div');
                    evaBar.setAttribute('class', 'progress-bar bg-primary');
                    evaBar.setAttribute('role', 'progressbar');
                    evaBar.setAttribute('id', 'FPETFM_PEVAL_RANGE');
                    evaBar.setAttribute('aria-valuemin', '0');
                    evaBar.setAttribute('aria-valuemax', '100');
                    evaBar.setAttribute('data-toggle', 'tooltip');
                    evaBar.setAttribute('data-placement', 'top');
                    evaBar.setAttribute('data-original-title', data.overview.FPETFM_PEVAL);
                    evaBar.setAttribute('aria-valuenow', data.overview.FPETFM_PEVAL);
                    evaBar.style.width = data.overview.FPETFM_PEVAL + '%';

                    container.appendChild(evaBar);
                    document.getElementById('FPETFM_PEVAL').remove();
                }

                Circles.create({
                    id: 'preScore',
                    radius: 50,
                    value: data.overview.TRNACC_PRESCORE,
                    maxValue: 100,
                    width: 8,
                    text: function(value) {
                        return value + '%';
                    },
                    colors: ['#eee', '#1D62F0'],
                    duration: 400,
                    wrpClass: 'circles-wrp',
                    textClass: 'circles-text',
                    styleWrapper: true,
                    styleText: true
                });

                Circles.create({
                    id: 'postScore',
                    radius: 50,
                    value: data.overview.TRNACC_POSTSCORE,
                    maxValue: 100,
                    width: 8,
                    text: function(value) {
                        return value + '%';
                    },
                    colors: ['#eee', '#1D62F0'],
                    duration: 400,
                    wrpClass: 'circles-wrp',
                    textClass: 'circles-text',
                    styleWrapper: true,
                    styleText: true
                });
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        var rangeInput = document.getElementById("FPETFM_PEVAL");
        var output = document.getElementById("percentageValue");

        rangeInput.addEventListener("input", function() {
            output.textContent = this.value + "%";
        });


    });

    function sendEvaluation() {
        var form = document.getElementById('formEvaluasi');
        var textArea = document.getElementById('FPETFM_EVAL');
        var range = document.getElementById('FPETFM_PEVAL');

        if (textArea.value == "") {
            textArea.style.borderColor = 'red';
            document.body.scrollTop = 400;
        } else if (range.value == 0) {
            Swal.fire({
                title: 'Evaluation Submit',
                text: 'Are you sure you want to submit with 0 results?',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Evaluation Submit',
                text: 'Are you sure you want to submit this data?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes',
                cancelButtonText: 'No'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    }

    function sortResume(column) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("resumeTable");
        switching = true;
        dir = "asc"; // Set the initial sorting direction to ascending

        while (switching) {
            switching = false;
            rows = table.rows;

            for (i = 1; i < rows.length - 1; i++) {
                shouldSwitch = false;
                var xNo = rows[i].getElementsByTagName("td")[0].innerHTML.toLowerCase();
                var yNo = rows[i + 1].getElementsByTagName("td")[0].innerHTML.toLowerCase();
                x = rows[i].getElementsByTagName("td")[column].innerHTML.toLowerCase();
                y = rows[i + 1].getElementsByTagName("td")[column].innerHTML.toLowerCase();

                // Check if the two rows should switch places based on the sorting direction and column
                if (dir === "asc") {
                    shouldSwitch = x.localeCompare(y) > 0;
                } else if (dir === "desc") {
                    shouldSwitch = x.localeCompare(y) < 0;
                }

                if (shouldSwitch) {
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                    switchcount++;
                }

                // Update the No. column to maintain its original order
                rows[i].getElementsByTagName("td")[0].innerHTML = i;
                rows[i + 1].getElementsByTagName("td")[0].innerHTML = i + 1;
            }

            // Toggle the sorting direction if no switching occurred in the loop
            if (switchcount === 0 && dir === "asc") {
                dir = "desc";
                switching = true;
            }
        }

        // Update the sorting icon in the table header
        updateSortingIcona(column, dir, "detailOnlyEmpTable");
    }

    function updateSortingIcons(column, dir, tableName) {
        var headerRow = document.getElementById(tableName).getElementsByTagName("thead")[0].getElementsByTagName("tr")[0];
        var columns = headerRow.getElementsByTagName("th");

        // Remove existing sorting icons
        for (var i = 0; i < columns.length; i++) {
            var iconUp = columns[i].querySelector(".la-chevron-circle-up");
            if (iconUp) {
                columns[i].removeChild(iconUp);
            }
            var iconDown = columns[i].querySelector(".la-chevron-circle-down");
            if (iconDown) {
                columns[i].removeChild(iconDown);
            }
        }

        // Add new sorting icon to the clicked column
        var icon = document.createElement("i");
        icon.className = dir === "asc" ? "la la-chevron-circle-up" : "la la-chevron-circle-down";
        columns[column].appendChild(icon);
    }

    function searchTableResume() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInputResume");
        filter = input.value.toUpperCase();
        table = document.getElementById("resumeTable");
        tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
        var count = 0;
        // Loop through all table rows, and hide those that don't match the search query
        for (i = 0; i < tr.length; i++) {
            var found = false;
            for (var j = 1; j < tr[i].getElementsByTagName("td").length; j++) {
                td = tr[i].getElementsByTagName("td")[j];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            if (found) {
                tr[i].style.display = "";
                count++;
                tr[i].getElementsByTagName("td")[0].innerText = count;
            } else {
                tr[i].style.display = "none";
            }

        }
    }
</script>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
include __DIR__ . "/script2.php";
?>