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
							<div class="col pr-0">
								<h4 class="card-title">Daftar FPT</h4>
								<p class="card-category">Pengajuan Training</p>
							</div>
							<div class="col-auto d-flex align-items-center justify-content-end">
								<a href="javascript:void(0)" onclick="showAdd('tambah')" class="btn btn-primary"> Tambah</a>
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="form-inline py-2">
							<label class="col-md-1 p-0">Search:&nbsp;&nbsp;</label>
							<div class="col-md-11 p-0">
								<input type="text" class="form-control input-full" onkeyup="searchFPET()" id="searchFPET" name="searchFPET">
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-hover table-head-bg-info mb-0">
								<thead>
									<tr>
										<th scope="col" class="text-center" style="width: 50px;">No.</th>
										<th scope="col" class="text-center">Peserta Pelatihan</th>
										<th scope="col" class="text-center" style="width: 500px;">Saran Training</th>
										<th scope="col" class="text-center" style="width: 250px;">Status</th>
										<!-- <th scope="col" class="text-center" style="width: 500px;">Approval HR</th> -->
										<!-- <th scope="col" class="text-center">Aksi</th> -->
									</tr>
								</thead>
							</table>
							<div id="dyDiv" style="overflow-y: auto; max-height: 50vh;">
								<table name="tableFPET" class="table table-hover table-head-bg-info">
									<tbody id="tBodymainTable">
										<?php
										$i = 1;
										if (empty($fpet)) {
											echo '<tr><td colspan="4" class="text-center">Belum ada data</td></tr>';
										} else {
											function getStatusText($boss, $hr, $status, $role)
											{
												if ($status === 1) {
													return '<span class="badge badge-default">Draft</span>';
												} else if ($status === 3) {
													return '<span class="badge badge-success">Training telah dievaluasi</span>';
												} else if ($boss === 1) {
													return '<span class="badge badge-warning">Menunggu persetujuan ' . ($role == 'BOSS' ? 'Anda' : 'atasan') . '</span>';
												} else if ($boss === 0 || $boss === 3) {
													return '<span class="badge badge-danger">' . ($role == 'BOSS' ? 'Anda menolak permintaan ini' : 'Permintaan ditolak oleh atasan') . '</span>';
												} else if ($hr === 1) {
													return '<span class="badge badge-warning">Menunggu persetujuan ' . ($role == 'HR' ? 'Anda' : 'HR') . '</span>';
												} else if ($hr === 0 || $hr === 3) {
													return '<span class="badge badge-danger">' . ($role == 'HR' ? 'Anda menolak permintaan ini' : 'Permintaan ditolak oleh HR') . '</span>';
												} else if ($hr === 2) {
													return '<span class="badge badge-info">Training telah direncanakan</span>';
												}
											}

											foreach ($fpet as $t) {
												$statusText = getStatusText($t['statusApproved'], $t['statusApprovedHr'], $t['status'], $t['role']);
										?> <tr onclick="showDetailFpet(<?php echo isset($t['idFpet']) ? $t['idFpet'] : ''; ?>)" style="cursor: pointer;">
													<td class="text-right" style="width: 50px;"><?php echo $i ?>.</td>
													<td><?php echo isset($t['nama']) ? $t['nama'] : ''; ?></td>
													<td style="width: 500px;"><?php echo isset($t['trainsuggest']) ? $t['trainsuggest'] : ''; ?></td>
													<td style="width: 250px;" class="text-center"><?php echo $statusText ?></td>
													<!-- <td><?php echo $statusTextHr ?></td> -->
													<!-- <td class="text-center"><a href="javascript:void(0)" class="btn btn-primary"></i>Detail</a></td> -->
												</tr>
										<?php
												$i++;
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
								<div class="card-title" id="cardTitle">Form Pengajuan Training</div>
								<p class="card-category" id="cardCategory">Pengajuan Training / FPT</p>
							</div>
							<div class="col-auto justify-content-end" id="btnDetailFpet" style="display: none;">
								<a href="javascript:void(0)" id="publishBtnFpet" class="btn btn-info" style="margin-right: 9px;"></i> Publish</a>
								<a href="javascript:void(0)" id="editBtnFpet" onclick="doUpdate()" class="btn btn-warning" style="margin-right: 9px;"></i> Edit</a>
								<a href="javascript:void(0)" id="deleteBtnFpet" class="btn btn-danger "></i> Hapus</a>
							</div>
							<div class="col-auto justify-content-end" id="btnApprovalFpet" style="display: none;">
								<a id="approveBtnFpet" class="btn btn-info" href="javascript:void(0)" style="margin-right: 9px; display: none;"></i> Approve</a>
								<a id="rejectBtnFpet" class="btn btn-danger " href="javascript:void(0)" style="margin-right: 9px; display: none;"></i> Reject</a>
								<a id="approveBtnFpetHr" href="javascript:void(0)" onclick="showFormTrainModal()" class="btn btn-info" style="margin-right: 9px; display: none;"></i> Approve HR</a>
								<a id="rejectBtnFpetHr" class="btn btn-danger " href="javascript:void(0)" style="margin-right: 9px; display: none;"></i> Reject HR</a>
							</div>
						</div>
					</div>
					<div class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">
						<input type="text" hidden class="form-control input-pill mb-3" name="idFpet" id="idFpet">
						<div class="row">
							<div class="col-md-6">
								<label class="my-2">Pilih Calon Partisipan <span style="color: red;">*</span></label>
								<select class="form-control input-pill mb-3" id="partisipanTraining" name="partisipanTraining">
									<option disabled selected>Pilih</option>
									<?php foreach ($employee as $e) : ?>
										<option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label class="my-2">Saran training <span style="color: red;">*</span></label>
								<input type="text" maxlength="40" class="form-control input-pill mb-3" name="trainSuggest" id="trainSuggest" placeholder="Masukkan Saran Training" required>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="my-2">Kondisi Aktual</label>
								<textarea class="form-control" id="actual" name="actual" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda" required></textarea>
							</div>
							<div class="col-md-6 mb-3">
								<div class="form-check p-0">
									<label class="my-2">Kemampuan saat ini <span style="color: red;">*</span></label><br />
									<label class="form-radio-label mr-3">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="0" required>
										<span class="form-radio-sign" name="rActualText">0%</span>
									</label>
									<label class="form-radio-label mr-3">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="25" required>
										<span class="form-radio-sign" name="rActualText">25%</span>
									</label>
									<label class="form-radio-label mr-3">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="50" required>
										<span class="form-radio-sign" name="rActualText">50%</span>
									</label>
									<label class="form-radio-label mr-3">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="75" required>
										<span class="form-radio-sign" name="rActualText">75%</span>
									</label>
									<label class="form-radio-label">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="100" required>
										<span class="form-radio-sign" name="rActualText">100%</span>
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6 mb-3">
								<label class="my-2">Target / Standard</label>
								<textarea class="form-control" id="target" name="target" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda" required></textarea>
							</div>
							<div class="col-md-6 mb-3">
								<div class="form-check p-0">
									<label class="my-2">Kemampuan Yang diinginkan<span style="color: red;">*</span></label> <br />
									<label class="form-radio-label mr-3">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="0" required>
										<span class="form-radio-sign" name="rTargetText">0%</span>
									</label>
									<label class="form-radio-label mr-3">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="25" required>
										<span class="form-radio-sign" name="rTargetText">25%</span>
									</label>
									<label class="form-radio-label mr-3">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="50" required>
										<span class="form-radio-sign" name="rTargetText">50%</span>
									</label>
									<label class="form-radio-label mr-3">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="75" required>
										<span class="form-radio-sign" name="rTargetText">75%</span>
									</label>
									<label class="form-radio-label">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="100" required>
										<span class="form-radio-sign" name="rTargetText">100%</span>
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12 mb-3">
								<label class="my-2">Keterangan dan Saran</label>
								<textarea class="form-control" id="notes" name="notes" rows="1" maxlength="200" placeholder="Masukkan pendapat Anda" required></textarea>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="my-2" for="approved">Pilih Pihak yang Menyetujui <span style="color: red;">*</span></label>
								<select class="form-control input-pill mb-3" id="approved" name="approved" required>
									<option disabled selected>Pilih </option>
									<?php foreach ($employee as $e) : ?>
										<option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
									<?php endforeach; ?>
								</select>
							</div>
							<div class="col-md-6">
								<label class="my-2" for="approvedHR">Pilih Pihak HRD yang Menyetujui <span style="color: red;">*</span></label>
								<select class="form-control input-pill mb-3" id="approvedHR" name="approvedHr" required>
									<?php if ($defHR) { ?>
										<option selected value="<?php echo $defHR->NPK; ?>"><?php echo $defHR->NAMA; ?> (<?php echo $defHR->DEPARTEMEN; ?>)</option>
									<?php } else { ?>
										<option disabled selected>Pilih </option>
										<?php foreach ($employee as $e) : ?>
											<option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
										<?php endforeach; ?>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="card-body" id="divBackSub">
						<button type="button" id="btnSub" class="btn btn-success float-right">Simpan</button>
						<a href="javascript:void(0)" onclick="changeFormFpet('main')" class="btn btn-danger"></i> Kembali</a>
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
									<div class="row">
										<div class="col-md-6">
											<div class="form-check">
												<label class="ml-3">Apakah Anda ingin mengambil usulan training bedasar training yang ada? <span style="color: red;">*</span></label><br />
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

										<div class="col-md-5">
											<div class="form-group">

												<label>Saran training <span style="color: red;">*</span></label>
												<div class="row">
													<div class="col-md-8">
														<input type="text" maxlength="40" class="form-control input-pill mb-3" name="trainSuggestModal" id="trainSuggestModal" placeholder="Masukkan Saran Training" readonly>
													</div>
													<div class="col-md-4" id="generator">
														<button type="button" class="btn btn-primary float-right" onclick="generateRecomTrain()">Generate</button>
													</div>
												</div>
											</div>
										</div>
										<!-- <div class="col-md-1" id="generator">
                                            <div class="form-group p-0">
                                                <label><span style="color: white;">*</span></label>
                                                <button type="button" class="btn btn-primary float-right" onclick="generateRecomTrain()">Generate</button>
                                            </div>
                                        </div> -->
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

								<div id="trainSection2" style="display: none;">
									<div class="row">
										<div class="col">
											<label class="my-2">Judul Training<span style="color: red;">*</span></label>
											<input type="text" class="form-control input-pill mb-3" name="title" id="title" placeholder="Masukkan Judul Training">
										</div>
										<div class="col">
											<label class="my-2">Lembaga Pelaksana<span style="color: red;">*</span></label><br />

											<input type="text" class="form-control input-pill mb-3" name="educator" id="educator" placeholder="Masukkan Lembaga Pelaksana">
										</div>
									</div>
									<div class="row">
										<div class="col">
											<label class="my-2">Jadwal training<span style="color: red;">*</span></label>
											<input type="date" min="<?php echo date('Y-m-d') ?>" class="form-control input-pill mb-3" name="schedule" id="schedule" placeholder="Pilih Jadwal">
										</div>
										<div class="col">
											<label class="my-2">Biaya Pelaksanaan</label><br />
											<input type="text" class="form-control input-pill mb-3" name="cost" id="cost" placeholder="Masukkan Biaya " oninput="formatCost(this)">
										</div>
									</div>
									<input type="text" hidden class="form-control input-pill mb-3" name="npk" id="npk">

									<div class="row">
										<div class="col-md-6">
											<div class="form-check">
												<label>Pilih Jenis Training <span style="color: red;">*</span></label><br />
												<label class="form-radio-label">
													<input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="1">
													<span class="form-radio-sign" name="categoryTrainText">In-House</span>
												</label>
												<label class="form-radio-label ml-3">
													<input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="2">
													<span class="form-radio-sign" name="categoryTrainText">Out-House</span>
												</label>
												<label class="form-radio-label ml-3">
													<input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrain" value="3">
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
	function handleRadioChange(event) {
		var radios = event.target.name === 'rActual' ? document.getElementsByName('rTarget') : document.getElementsByName('rActual');
		var checkedValue = parseInt(event.target.value);

		radios.forEach(radio => {
			var radioValue = parseInt(radio.value);
			radio.disabled = (event.target.name === 'rActual' && radioValue < checkedValue) ||
				(event.target.name === 'rTarget' && radioValue > checkedValue);
		});
	}

	document.getElementsByName('rActual').forEach(radio => {
		radio.addEventListener('change', handleRadioChange);
	});

	document.getElementsByName('rTarget').forEach(radio => {
		radio.addEventListener('change', handleRadioChange);
	});

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
		document.getElementById('btnSub').style.display = 'block';
		document.getElementById('editBtnFpet').style.display = 'none'; // Adjust as per your requirement
		document.getElementById('deleteBtnFpet').style.display = 'none';
		document.getElementById('publishBtnFpet').style.display = 'none';
		enableFormElements();
		var formElement = document.getElementById('btnSub');
		formElement.setAttribute('onclick', 'update()')
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

		['idFpet', 'partisipanTraining', 'actual', 'target', 'notes', 'approvedHR', 'approved', 'trainSuggest'].forEach(id => {
			document.getElementById(id).value = '';
		});

		['rActual', 'rTarget', 'rEval'].forEach(name => {
			document.getElementsByName(name).forEach(radio => {
				radio.checked = false;
			});
		});

		['rActualText', 'rTargetText'].forEach(name => {
			var elements = document.getElementsByName(name);
			for (var i = 0; i < elements.length; i++) {
				elements[i].removeAttribute("style");
			}
		});


		["partisipanTraining", "trainSuggest", "rActual", "actual", "rTarget", "target", "notes", "approvedHR", "approved"].forEach(id => {
			document.getElementById(id).style.border = "1px solid #ced4da";
		});

		// Reset select elements
		document.getElementById('partisipanTraining').selectedIndex = 0;
		document.getElementById('chooseTrain').selectedIndex = 0;
		document.getElementById('approvedHR').selectedIndex = 0;
		document.getElementById('approved').selectedIndex = 0;

		// Reset radio buttons for rEstablished
		document.getElementById('rEstablishedY').checked = false;
		document.getElementById('rEstablishedN').checked = false;
	}


	function save() {
		var requiredFields = ["partisipanTraining", "trainSuggest", "rActual", "actual", "rTarget", "target", "notes", "approvedHR", "approved"];
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

		var actualRadioChecked = document.querySelector('input[name="rActual"]:checked');
		if (!actualRadioChecked) {
			document.querySelectorAll('span[name="rActualText"]').forEach(function(span) {
				span.style.color = "red";
			});
			isValid = false;
		} else {
			document.querySelectorAll('span[name="rActualText"] .text-content').forEach(function(span) {
				span.style.color = "";
			});
		}

		var targetRadioChecked = document.querySelector('input[name="rTarget"]:checked');
		if (!targetRadioChecked) {
			console.log("sini")
			document.querySelectorAll('span[name="rTargetText"]').forEach(function(span) {
				span.style.color = "red";
			});
			isValid = false;
		} else {
			document.querySelectorAll('span[name="rTargetText"]').forEach(function(span) {
				span.style.color = "";
			});
		}
		var dropdowns = ["partisipanTraining", "approved", "approvedHR"];
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
			document.getElementById("formFpet").submit();
		}
	}

	function validate() {
		var requiredFields = ["partisipanTraining", "trainSuggest", "rActual", "actual", "rTarget", "target", "notes", "approvedHR", "approved"];
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
		var actualRadioChecked = document.querySelector('input[name="rActual"]:checked');
		if (!actualRadioChecked) {
			document.querySelectorAll('span[name="rActualText"]').forEach(function(span) {
				span.style.color = "red";
			});
			isValid = false;
		} else {
			document.querySelectorAll('span[name="rActualText"] .text-content').forEach(function(span) {
				span.style.color = "";
			});
		}

		var targetRadioChecked = document.querySelector('input[name="rTarget"]:checked');
		if (!targetRadioChecked) {
			console.log("sini")
			document.querySelectorAll('span[name="rTargetText"]').forEach(function(span) {
				span.style.color = "red";
			});
			isValid = false;
		} else {
			document.querySelectorAll('span[name="rTargetText"]').forEach(function(span) {
				span.style.color = "";
			});
		}
		var dropdowns = ["partisipanTraining", "approved", "approvedHR"];
		dropdowns.forEach(dropdownId => {
			var dropdownValue = document.getElementById(dropdownId).value;
			if (!dropdownValue || dropdownValue === "Pilih") {
				document.getElementById(dropdownId).style.border = "1px solid red";
				isValid = false;
			} else {
				document.getElementById(dropdownId).style.border = "1px solid #ced4da";
			}
		});

	}

	function update() {
		var requiredFields = ["partisipanTraining", "trainSuggest", "rActual", "actual", "rTarget", "target", "notes", "approvedHR", "approved"];
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

		var actualRadioChecked = document.querySelector('input[name="rActual"]:checked');
		if (!actualRadioChecked) {
			document.querySelectorAll('span[name="rActualText"]').forEach(function(span) {
				span.style.color = "red";
			});
			isValid = false;
		} else {
			document.querySelectorAll('span[name="rActualText"] .text-content').forEach(function(span) {
				span.style.color = "";
			});
		}

		var targetRadioChecked = document.querySelector('input[name="rTarget"]:checked');
		if (!targetRadioChecked) {
			console.log("sini")
			document.querySelectorAll('span[name="rTargetText"]').forEach(function(span) {
				span.style.color = "red";
			});
			isValid = false;
		} else {
			document.querySelectorAll('span[name="rTargetText"]').forEach(function(span) {
				span.style.color = "";
			});
		}

		var dropdowns = ["partisipanTraining", "approved", "approvedHR"];
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
			document.getElementById("formFpet").submit();
		}
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
				var costInput = document.getElementById('cost');
				costInput.value = costInput.value.replace(/,/g, '');

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

	function validateForm() {
		var isValid = true;
		resetValidationStyles();
		var textInputs = document.querySelectorAll('input[type="text"]');
		textInputs.forEach(function(input) {
			if (!input.value.trim()) {
				input.style.borderColor = "red";
				isValid = false;
			}
		});
		var radioButtons = document.querySelectorAll('input[type="radio"]');
		var radioChecked = false;
		radioButtons.forEach(function(radio) {
			if (radio.checked) {
				radioChecked = true;
			}
		});
		if (!radioChecked) {
			var radioContainer = document.querySelector('.form-check');
			radioContainer.style.color = "red";
			isValid = false;
		}
		var dropdowns = document.querySelectorAll('select');
		dropdowns.forEach(function(dropdown) {
			if (!dropdown.value) {
				dropdown.style.borderColor = "red";
				isValid = false;
			}
		});

		return isValid;
	}

	function resetValidationStyles() {
		var inputs = document.querySelectorAll('input[type="text"]');
		inputs.forEach(function(input) {
			input.style.borderColor = "";
		});

		var radioContainer = document.querySelector('.form-check');
		radioContainer.style.color = "";

		var dropdowns = document.querySelectorAll('select');
		dropdowns.forEach(function(dropdown) {
			dropdown.style.borderColor = "";
		});
	}

	function disableFormElements() {
		var formElements = document.getElementById("formFpet").elements;
		for (var i = 0; i < formElements.length; i++) {
			formElements[i].disabled = true;
		}
	}

	function enableFormElements() {
		var formElements = document.getElementById("formFpet").elements;

		for (var i = 0; i < formElements.length; i++) {
			formElements[i].disabled = false;
		}
	}



	var rowFtpe = 0;

	function changeFormFpet() {
		var formElement = document.getElementById('formFpet');
		formElement.removeAttribute('action');
		document.getElementById("showListFpet").style.display = 'block';
		document.getElementById("addFpet").style.display = 'none';

		var formElement2 = document.getElementById('btnSub');
		formElement2.removeAttribute('onclick');



		document.getElementById('editBtnFpet').style.display = 'none';
		document.getElementById('deleteBtnFpet').style.display = 'none';
		document.getElementById('publishBtnFpet').style.display = 'none';
	}

	function showAdd(kode) {
		enableFormElements();
		clearFormFpet();

		var formElement = document.getElementById('formFpet');
		formElement.setAttribute('action', '<?php echo base_url('FPET/saveFpet/') ?>');
		document.getElementById("showListFpet").style.display = 'none';
		document.getElementById("addFpet").style.display = 'block';
		document.getElementById("btnSub").style.display = 'block';
		var formElement = document.getElementById('btnSub');
		formElement.setAttribute('onclick', 'save()');
		callLoader();
	}

	async function showDetailFpet(id) {
		if (id != '0') {
			var formElement = document.getElementById('formFpet');
			formElement.setAttribute('action', '<?php echo base_url('FPET/modifyFpet/') ?>' + id);

			fetch('<?= base_url('FPET/showDetail/') ?>' + id)
				.then(response => {
					return response.json();
				})
				.then(data => {
					console.log(data);
					var dataFpet = data.dataFpet;

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
						document.getElementById('btnSub').style.display = 'none';
						document.getElementById('btnDetailFpet').style.display = 'block';
						if (dataFpet.FPETFM_STATUS == '2') {
							document.getElementById('btnDetailFpet').style.display = 'none';
							document.getElementById('btnApprovalFpet').style.display = 'block';
							if (dataFpet.FPETFM_APPROVER == <?php echo $this->session->userdata('npk'); ?> && dataFpet.FPETFM_APPROVED === 1) {
								document.getElementById('rejectBtnFpet').style.display = 'inline-block';
								document.getElementById('approveBtnFpet').style.display = 'inline-block';
								var rejectBtnFpet = document.getElementById('rejectBtnFpet');
								//  rejectBtnFpet.setAttribute('href', '<?= base_url('FPET/rejectFpet/') ?>' + id);
								rejectBtnFpet.setAttribute('onclick', "confirmApproval(3, '" + id + "')");
								var approveBtnFpet = document.getElementById('approveBtnFpet');
								// approveBtnFpet.setAttribute('href', '<?= base_url('FPET/approveFpet/') ?>' + id);
								approveBtnFpet.setAttribute('onclick', "confirmApproval(2, '" + id + "')");
							}
							if (dataFpet.FPETFM_HRAPPROVER == <?php echo $this->session->userdata('npk'); ?> && (dataFpet.FPETFM_APPROVED === 2 || dataFpet.FPETFM_APPROVED === 0 || dataFpet.FPETFM_APPROVED === 3) && dataFpet.FPETFM_HRAPPROVED == 1) {
								document.getElementById('rejectBtnFpetHr').style.display = 'inline-block';
								document.getElementById('approveBtnFpetHr').style.display = 'inline-block';
								var rejectBtnFpet = document.getElementById('rejectBtnFpetHr');
								// rejectBtnFpet.setAttribute('href', '<?= base_url('FPET/rejectHrFpet/') ?>' + id);
								rejectBtnFpet.setAttribute('onclick', "confirmApproval(4, '" + id + "')");
								document.getElementById('makeTrain').style.display = 'block';

							}
						}
						var deleteBtnFpet = document.getElementById('deleteBtnFpet');
						deleteBtnFpet.setAttribute('onclick', "confirmBtn(0, '" + id + "')");
						var publishBtnFpet = document.getElementById('publishBtnFpet');
						publishBtnFpet.setAttribute('onclick', "confirmBtn(2, '" + id + "')");
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

	function generateRecomTrain() {
		var train = document.getElementById('trainSuggestModal').value;

		document.getElementById('title').value = train;

	}

	function confirmApproval(code, id) {
		if (code == 2) {
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
		} else if (code == 4 || code == 3) {
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

	function toggleTrainSections() {
		var rEstablishedValue = document.querySelector('input[name="rEstablished"]:checked').value;

		if (rEstablishedValue == "1") {
			document.getElementById('trainSection1').style.display = 'block';
			document.getElementById('generator').style.display = 'none';
			document.getElementById('trainSection2').style.display = 'none';
		} else {
			document.getElementById('trainSection1').style.display = 'none';
			document.getElementById('generator').style.display = 'block';
			document.getElementById('trainSection2').style.display = 'block';
		}
	}


	// Call toggleTrainSections initially to set the initial visibility state
	toggleTrainSections();

	function showFormTrainModal() {
		new bootstrap.Modal(document.getElementById('trainModal')).show();
		document.getElementById('generator').style.display = 'none';
	};

	function searchFPET() {
		var input, filter, table, tr, td, i, txtValue;
		input = document.getElementById("searchFPET");
		filter = input.value.toUpperCase();
		table = document.getElementsByName("tableFPET")[0];
		tr = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
		var count = 0;
		// Loop through all table rows, and hide those who don't match the search query
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
		isDataTableExist(count, 1, 4, 'emptyData', 'tBodymainTable');
	}

	function formatCost(input) {
		// Remove non-numeric characters
		var value = input.value.replace(/\D/g, '');

		// Add separators every three digits
		value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');

		// Update the input value
		input.value = value;
	}

	function confirmBtn(code, id) {
		if (code == 0) {
			Swal.fire({
				title: 'Konfirmasi Penghapusan Data',
				text: 'Apakah Anda yakin ingin menghapus data ini?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then((result) => {
				if (result.isConfirmed) {
					window.location.href = '<?= base_url('FPET/confirmPublishDeleteFPET/') ?>' + code + '/' + id;
				}
			});
		} else if (code == 2) {
			Swal.fire({
				title: 'Konfirmasi Publikasi',
				text: 'Data yang sudah dipublikasi tidak dapat diubah!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya',
				cancelButtonText: 'Tidak'
			}).then((result) => {
				if (result.isConfirmed) {

					window.location.href = '<?= base_url('FPET/confirmPublishDeleteFPET/') ?>' + code + '/' + id;


				}
			});
		}
	}
</script>

<?php include __DIR__ . '/../script.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/../layout.php";
?>