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
								<h4 class="card-title">Daftar FPET</h4>
								<p class="card-category">Form Penilaian dan Evaluasi Training</p>
							</div>

							<div class="col d-flex align-items-center justify-content-end">
								<a href="javascript:void(0)" onclick="showAdd('tambah')" class="btn btn-primary"> Tambah</a>
							</div>

						</div>
					</div>
					<div class="card-body">
						<table name="table" class="table table-hover table-head-bg-info my-2" style="max-width: 100%;">
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
								$i = 1;
								if (empty($fpet)) {
									echo '<tr><td colspan="6" class="text-center">Belum ada data</td></tr>';
								} else {
									function getStatusText($status)
									{
										switch ($status) {
											case 0:
												return 'Ditolak';
											case 1:
												return 'Disetujui';
											default:
												return 'Belum disetujui';
										}
									}

									foreach ($fpet as $t) {
										$statusText = getStatusText(isset($t['statusApproved']) ? $t['statusApproved'] : '');
										$statusTextHr = getStatusText(isset($t['statusApprovedHr']) ? $t['statusApprovedHr'] : '');
								?>
										<tr>
											<td><?php echo $i ?></td>
											<td><?php echo isset($t['nama']) ? $t['nama'] : ''; ?></td>
											<td><?php echo isset($t['target']) ? $t['target'] : ''; ?></td>
											<td><?php echo $statusText ?></td>
											<td><?php echo $statusTextHr ?></td>
											<td class="text-center"><a href="javascript:void(0)" onclick="showDetailFpet(<?php echo isset($t['idFpet']) ? $t['idFpet'] : ''; ?>)" class="btn btn-primary"></i>Detail</a></td>
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
	<div class="row" id="addFpet" style="display: none;">
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
									<a id="publishBtnFpet" class="btn btn-info" style="margin-right: 9px; display: none;"></i> Publish</a>
									<a href="javascript:void(0)" id="editBtnFpet" onclick="doUpdate()" class="btn btn-warning" style="margin-right: 9px; display: none;"></i> Edit</a>
									<a id="deleteBtnFpet" class="btn btn-danger " style="display: none;"></i> Hapus</a>
								</div>
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
							<div class="col-md-6">
								<label class="my-2">Kondisi Aktual</label>
								<textarea class="form-control" id="actual" name="actual" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda" required></textarea>
							</div>
							<div class="col-md-6">
								<div class="form-check">
									<label>Kemampuan saat ini <span style="color: red;">*</span></label><br />
									<label class="form-radio-label">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="0" required>
										<span class="form-radio-sign" name="rActualText">0%</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="25" required>
										<span class="form-radio-sign" name="rActualText">25%</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="50" required>
										<span class="form-radio-sign" name="rActualText">50%</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="75" required>
										<span class="form-radio-sign" name="rActualText">75%</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="rActual" id="rActual" value="100" required>
										<span class="form-radio-sign" name="rActualText">100%</span>
									</label>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<label class="my-2">Target / Standard</label>
								<textarea class="form-control" id="target" name="target" rows="2" maxlength="200" placeholder="Masukkan pendapat Anda" required></textarea>
							</div>
							<div class="col-md-6">
								<div class="form-check">
									<label>Kemampuan Yang diinginkan<span style="color: red;">*</span></label> <br />
									<label class="form-radio-label">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="0" required>
										<span class="form-radio-sign" name="rTargetText">0%</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="25" required>
										<span class="form-radio-sign" name="rTargetText">25%</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="50" required>
										<span class="form-radio-sign" name="rTargetText">50%</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="75" required>
										<span class="form-radio-sign" name="rTargetText">75%</span>
									</label>
									<label class="form-radio-label ml-3">
										<input class="form-radio-input" type="radio" name="rTarget" id="rTarget" value="100" required>
										<span class="form-radio-sign" name="rTargetText">100%</span>
									</label>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-12">
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
						<div class="card-body" id="divBackSub">
							<button type="button" id="btnSub" class="btn btn-success float-right">Simpan</button>
							<a href="javascript:void(0)" onclick="changeFormFpet('main')" class="btn btn-danger"></i> Kembali</a>
						</div>
					</div>
				</div>
			</form>
		</div>

	</div>
</div>

<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true" style="overflow: visible;">
	<div class="modal-dialog" style="max-width: 950px">
		<div class="modal-content" style="width: 950px;">
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
		['idFpet', 'partisipanTraining', 'actual', 'target', 'notes', 'approvedHR', 'approved', 'trainSuggest'].forEach(id => {
			document.getElementById(id).value = '';
		});

		['rActual', 'rTarget', 'rEval'].forEach(name => {
			document.getElementsByName(name).forEach(radio => {
				radio.checked = false;
			});
		});

		document.getElementById('approvedHR').selectedIndex = 0;
		document.getElementById('approved').selectedIndex = 0;
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
		formElement.setAttribute('onclick', 'save()')
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
						document.getElementById('idFpet').value = dataFpet.FPETFM_ID || '';
						document.getElementById('trainSuggest').value = dataFpet.FPETFM_TRAINSUGGEST || '';
						document.getElementById('partisipanTraining').value = dataFpet.AWIEMP_NPK || '';
						document.getElementById('actual').value = dataFpet.FPETFM_ACTUAL || '';
						document.getElementById('target').value = dataFpet.FPETFM_TARGET || '';
						document.getElementById('notes').value = dataFpet.FPETFM_NOTES || '';
						document.getElementById('approvedHR').value = dataFpet.FPETFM_HRAPPROVER || '';
						document.getElementById('approved').value = dataFpet.FPETFM_APPROVER || '';

						var rActualRadios = document.getElementsByName('rActual');
						rActualRadios.forEach(radio => {
							if (radio.value === dataFpet.FPETFM_PACTUAL.toString()) {
								radio.checked = true;
							}
						});
						var rTargetRadios = document.getElementsByName('rTarget');
						rTargetRadios.forEach(radio => {
							if (radio.value === dataFpet.FPETFM_PTARGET.toString()) {
								radio.checked = true;
							}
						});
						document.getElementById('btnSub').style.display = 'none';

						document.getElementById('btnDetailFpet').style.display = 'block';
						if (dataFpet.FPETFM_STATUS == '2') {
							document.getElementById('editBtnFpet').style.display = 'block'; // Adjust as per your requirement
							document.getElementById('deleteBtnFpet').style.display = 'block';
							document.getElementById('publishBtnFpet').style.display = 'block';
						}
						var deleteBtnFpet = document.getElementById('deleteBtnFpet');
						deleteBtnFpet.setAttribute('href', '<?= base_url('FPET/removeFpet/') ?>' + id);
						var publishBtnFpet = document.getElementById('publishBtnFpet');
						publishBtnFpet.setAttribute('href', '<?= base_url('FPET/publishFpet/') ?>' + id);


						document.getElementById("showListFpet").style.display = 'none';
						document.getElementById("addFpet").style.display = 'block';

						disableFormElements();
					} else {
						console.error('Error: No data found for id ' + id);
					}
				})
				.catch(error => {
					console.error('Error fetching data showdetail:', error);
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