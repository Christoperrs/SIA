<?php
ob_start();
?>
<div class="container-fluid">
	<div id="showListFpet">
		<div class="row">
			<div class="col-md-12">
				<div class="card p-2 mb-3">
					<div class="card-header">
						<div class="row">
							<div class="col">
								<h4 class="card-title">Daftar Nilai Karyawan</h4>
								<p class="card-category">Daftar Nilai Pre Test dan Post Test</p>
							</div>


						</div>
					</div>
					<div class="card-body">
						<table name="table" class="table table-hover table-head-bg-info my-2">
							<thead>
								<tr>
									<th scope="col" class="text-center" style="width: 50px;">No.</th>
									<th scope="col" class="text-center" style="width: 500px;">Nama Karyawan</th>
									<th scope="col" class="text-center" style="width: 700px;">Paket Soal</th>
									<th scope="col" class="text-center" style="width: 500px;">Nama Training</th>
									<th scope="col" class="text-center" style="width: 500px;">Pre Test</th>
									<th scope="col" class="text-center" style="width: 500px;">Post Test</th>
									<!-- <th scope="col" class="text-center">Aksi</th> -->
								</tr>
							</thead>
							<tbody id="tBodymainTable">
								<?php
								$i = 1;
								if (empty($score)) {
									echo '<tr><td colspan="6" class="text-center">Belum ada data</td></tr>';
								} else {
									foreach ($score as $t) {

								?>
										<tr>
											<td><?php echo $i ?></td>
											<td><?php echo isset($t['nama']) ? $t['nama'] : ''; ?></td>
											<td><?php echo isset($t['package_name']) ? $t['package_name'] : ''; ?></td>
											<td><?php echo isset($t['training_id']) ? $t['training_id'] : ''; ?></td>
											<td><?php echo isset($t['scorePre']) ? $t['scorePre'] : ''; ?></td>
											<td><?php echo isset($t['scorePost']) ? $t['scorePost'] : ''; ?></td>

											<!-- <th class="text-center"><a href="javascript:void(0)" onclick="showDetailFpet(<?php echo isset($t['idFpet']) ? $t['idFpet'] : ''; ?>)" class="btn btn-primary"></i>Detail</a></th> -->
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
						<div class="row" style="display: none;">
							<div class="col-md-6">
								<div class="form-check" id="questionTrain">
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
							<!-- <div class="row">
								<div class="form-group">
									<label for="approved">Pilih Calon Trainer <span style="color: red;">*</span></label>
									<select class="form-control input-pill mb-3" id="trainer" name="trainer">
										<option disabled selected>Pilih </option>
										<?php foreach ($employee as $e) : ?>
											<option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
										<?php endforeach; ?>
									</select>
								</div>
							</div> -->
						</div>
						<div class="col-md-6" style="display: none;">
							<div class="form-check">
								<label>Pilih Jenis Training <span style="color: red;">*</span></label><br />
								<label class="form-radio-label">
									<input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrainInhouse" value="Inhouse" required>
									<span class="form-radio-sign">In-House</span>
								</label>
								<label class="form-radio-label ml-3">
									<input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrainOuthouse" value="Outhouse">
									<span class="form-radio-sign">Out-House</span>
								</label>
								<label class="form-radio-label ml-3">
									<input class="form-radio-input" type="radio" name="categoryTrain" id="categoryTrainElearning" value="Elearning">
									<span class="form-radio-sign">E-learning</span>
								</label>
							</div>

						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="trainer">Pilih Calon Trainer <span style="color: red;">*</span></label>
									<select class="form-control input-pill mb-3" id="trainer" name="trainer">
										<option disabled selected>Pilih</option>
										<?php foreach ($employee as $e) : ?>
											<option value="<?php echo $e->NPK; ?>"><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
										<?php endforeach; ?>
									</select>
								</div>

							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="trainer">Saran training <span style="color: red;">*</span></label>
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
									<label for="chooseTrain">Kemampuan Yang diinginkan<span style="color: red;">*</span></label> <br />
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

<?php include __DIR__ . '/../script2.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/../layout.php";
?>