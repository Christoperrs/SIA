<?php
ob_start();
?>
<div class="container-fluid">
	<div id="listCardDiv">
		<div class="row">
			<div class="col-md-12">
				<div class="card p-2 mb-3">
					<div class="card-header">
						<div class="row">
							<div class="col">
								<h4 class="card-title">Overview of <?php echo $employee->NAMA ?> in <?php echo $overview->TRNHDR_TITLE ?></h4>
								<p class="card-category">Overview Training Result</p>
							</div>
						</div>
					</div>
                    <form id="formEvaluasi" action="<?php echo base_url('Personal/evaluate')?>" method="post">
                        <input type="hidden" id="FPETFM_ID" name="FPETFM_ID" value="<?php echo $overview->FPETFM_ID ?>">
					    <div class="card-body pt-0">
                            <p class="card-category">Biodata</p>
                            <div class="row form-group px-0 pb-0">
                                <div class="col-md-5">
                                    <label for="NPK" class="pt-2">NPK</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="NPK" name="NPK" readonly value="<?php echo $employee->NPK ?>">
                                </div>
                            </div>
                            <div class="row form-group px-0 pb-0">
                                <div class="col-md-5">
                                    <label for="NAMA" class="pt-2">NAMA</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="NAMA" name="NAMA" readonly value="<?php echo $employee->NAMA ?>">
                                </div>
                            </div>
                            <div class="row form-group px-0 pb-0">
                                <div class="col-md-5">
                                    <label for="DEPARTEMEN" class="pt-2">DEPARTEMEN</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="DEPARTEMEN" name="DEPARTEMEN" readonly value="<?php echo $employee->DEPARTEMEN ?>">
                                </div>
                            </div>
                            <div class="row form-group px-0 pb-0">
                                <div class="col-md-5">
                                    <label for="FPETFM_TRAINSUGGEST" class="pt-2">TRAINING SUGGESTION</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="FPETFM_TRAINSUGGEST" name="FPETFM_TRAINSUGGEST" readonly value="<?php echo $overview->FPETFM_TRAINSUGGEST ?>">
                                </div>
                            </div>
                            <div class="row form-group px-0">
                                <div class="col-md-5">
                                    <label for="TRAINED_IN" class="pt-2">TRAINED IN</label>
                                </div>
                                <div class="col-md-7">
                                    <input class="form-control input-pill" id="TRAINED_IN" name="TRAINED_IN" readonly value="<?php echo $overview->TRNHDR_TITLE ?>">
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
                                            <textarea class="form-control" id="TRNACC_RESUME" name="TRNACC_RESUME" rows="4" spellcheck="false" style="resize: none;" readonly><?php echo $overview->TRNACC_RESUME ?></textarea>
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
                                            <textarea class="form-control mb-4" id="FPETFM_ACTUAL" name="FPETFM_ACTUAL" rows="5" spellcheck="false" style="resize: none;" readonly><?php echo $overview->FPETFM_ACTUAL ?></textarea>
                                            <div class="progress-card">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Actual Percentage</span>
                                                    <span class="text-muted fw-bold"><?php echo $overview->FPETFM_PACTUAL ?>%</span>
                                                </div>
                                                <div class="progress mb-2" style="height: 7px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $overview->FPETFM_PACTUAL ?>%" aria-valuenow="<?php echo $overview->FPETFM_PACTUAL ?>" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $overview->FPETFM_PACTUAL ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <label class="pt-2">Target Condition</label>
                                            <textarea class="form-control mb-4" id="FPETFM_TARGET" name="FPETFM_TARGET" rows="5" spellcheck="false" style="resize: none;" readonly><?php echo $overview->FPETFM_TARGET ?></textarea>
                                            <div class="progress-card">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <span class="text-muted">Target Percentage</span>
                                                    <span class="text-muted fw-bold"><?php echo $overview->FPETFM_PTARGET ?>%</span>
                                                </div>
                                                <div class="progress mb-2" style="height: 7px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $overview->FPETFM_PTARGET ?>%" aria-valuenow="<?php echo $overview->FPETFM_PTARGET ?>" aria-valuemin="0" aria-valuemax="100" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo $overview->FPETFM_PTARGET ?>%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <label class="pt-2">Result Condition <span style="color: red;">*</span></label>
                                            <textarea class="form-control mb-4" id="FPETFM_EVAL" name="FPETFM_EVAL" rows="5" spellcheck="false" style="resize: none;" placeholder="Evaluate..." required><?php echo $overview->FPETFM_EVAL == NULL ? NULL : $overview->FPETFM_EVAL ?></textarea>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted">Result Percentage <span style="color: red;">*</span></span>
                                                <span id="percentageValue" class="text-muted fw-bold"><?php echo $overview->FPETFM_PEVAL == NULL ? 0 : $overview->FPETFM_PEVAL ?>%</span>
                                            </div>
                                            <input type="range" id="FPETFM_PEVAL" name="FPETFM_PEVAL" class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="<?php echo $overview->FPETFM_PEVAL == NULL ? 0 : $overview->FPETFM_PEVAL ?>" aria-valuemin="0" aria-valuemax="100" data-original-title="0%" value="<?php echo $overview->FPETFM_PEVAL == NULL ? 0 : $overview->FPETFM_PEVAL ?>">
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
                                            <h6 class="m-0"><b><?php echo $approver->NAMA ?></b></h6>
                                            <label class="mb-2"><?php echo $approver->DEPARTEMEN ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body py-0">
                                            <label class="pt-2 mb-2">Request Approved By HR</label>
                                            <h6 class="m-0"><b><?php echo $HRapprover->NAMA ?></b></h6>
                                            <label class="mb-2"><?php echo $HRapprover->DEPARTEMEN ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 px-0">
                                    <div class="card">
                                        <div class="card-body py-0">
                                            <label class="pt-2 mb-2">This Form is Evaluated by</label>
                                            <h6 class="m-0"><b><?php echo $evaluator->NAMA ?></b></h6>
                                            <label class="mb-2"><?php echo $evaluator->DEPARTEMEN ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" onclick="sendEvaluation()" id="btnSimpan" class="btn btn-success float-right">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var rangeInput = document.getElementById("FPETFM_PEVAL");
        var output = document.getElementById("percentageValue");

        rangeInput.addEventListener("input", function() {
            output.textContent = this.value + "%";
        });

        Circles.create({
            id:           'preScore',
            radius:       50,
            value:        <?php echo $overview->TRNACC_PRESCORE ?>,
            maxValue:     100,
            width:        8,
            text:         function(value){return value + '%';},
            colors:       ['#eee', '#1D62F0'],
            duration:     400,
            wrpClass:     'circles-wrp',
            textClass:    'circles-text',
            styleWrapper: true,
            styleText:    true
        });

        Circles.create({
            id:           'postScore',
            radius:       50,
            value:        <?php echo $overview->TRNACC_POSTSCORE ?>,
            maxValue:     100,
            width:        8,
            text:         function(value){return value + '%';},
            colors:       ['#eee', '#1D62F0'],
            duration:     400,
            wrpClass:     'circles-wrp',
            textClass:    'circles-text',
            styleWrapper: true,
            styleText:    true
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
</script>
<?php include __DIR__ . '/script2.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
?>