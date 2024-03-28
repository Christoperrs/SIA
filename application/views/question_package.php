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
							<h4 class="card-title">Kelola Paket Soal</h4>
							<p class="card-category">Paket Soal</p>
						</div>
						<div class="col d-flex align-items-center justify-content-end">
							<button onclick="showPForm('x')" class="btn btn-primary" id="addPackageBtn">Tambah</button>
						</div>
					</div>
				</div>
				<div class="card-body" style="max-height: 480px; overflow-y: scroll; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
					<?php
					$i = 1;
					if (empty($package)) {
					?>
						<div class="avatar flex-shrink-0 me-3 justify-content-center d-flex">

							<img src="<?= base_url('assets/img/dataEmpty1.jpg') ?>" alt="User" class="img-fluid" style="max-width: 310px; max-height: 190px;" />

						</div>
						<hr>
						<h5 style="text-align: center;">Data Tidak Ada</h5>
					<?php
					} else { ?>
						<table id="allPackageTable" name="table" class="table table-hover table-head-bg-info my-2">
							<thead>
								<tr>
									<th scope="col" style="width: 50px;">No.</th>
									<th scope="col">Id Paket Soal</th>
									<th scope="col">Paket Soal</th>
									<th scope="col" style="width: 95px;">Nama Training</th>
									<th scope="col" style="width: 135px;" class="text-center">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<?php $i = 1;
								foreach ($package as $e) { ?>
									<tr>

										<td><?php echo $i; ?></td>
										<td><?php echo $e->TRNPCK_UNIQUEID; ?></td>
										<td><?php echo $e->TRNPCK_NAME; ?></td>
										<td><?php echo $e->TRNHDR_ID; ?></td>
										<td>
											<div class="d-flex justify-content-center">
												<a href="javascript:void(0)" id="editBtn" onclick="showPForm(<?php echo $e->TRNPCK_ID; ?>)" class="btn btn-warning mr-2"><i class="la la-pencil" style="font-size: 16px;"></i></a>
												<a href="javascript:void(0)" id="deleteBtn" onclick="deletePackage(<?php echo $e->TRNPCK_ID; ?>)" class="btn btn-danger"><i class="la la-trash" style="font-size: 16px;"></i></a>
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

	<div class="row" id="modifyPackagePage" style="display: none;">
		<div class="loader-container" id="loaderDiv">
			<div class="loader">
				<div class="loader-reverse"></div>
			</div>
			<p class="m-0">&emsp;Loading data...</p>
		</div>
		<div class="col-md-12">
			<div class="card p-2 m-0">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title" id="titlePackage">Tambah Paket Soal</h4>
							<p class="card-category" id="navPackage">Paket Soal / Tambah Paket Soal</p>
						</div>
					</div>
				</div>
				<form role="form" id="formPackage" method="post" enctype="multipart/form-data">
					<div class="card-body" style="max-height: 480px; overflow-y: auto;" id="scrollableDiv">
						<div class="row py-2">
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="aOption">ID Paket <span style="color: red;">*</span></label>
									<input class="form-control" id="idUniqPaket" name="idUniqPaket" rows="3" spellcheck="false" style="resize: none;" placeholder="Masukkan ID Paket" required></input>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="bOption">Nama Paket <span style="color: red;">*</span></label>
									<input class="form-control" id="namePaket" name="namePaket" rows="3" spellcheck="false" style="resize: none;" placeholder="Masukkan Nama Paket" required></input>
								</div>
							</div>
						</div>
						<div class="row py-2">
							<div class="col-md-6">
								<div class="form-group p-0">
									<label for="chooseTrain">Training <span style="color: red;">*</span></label>
									<select class="form-control form-control" id="chooseTrain" name="chooseTrain" required>
										<option value="default" selected disabled>-- Pilih Training --</option>
										<?php foreach ($train as $t) : ?>
											<option value="<?php echo $t->TRNHDR_ID; ?>"><?php echo $t->TRNHDR_TITLE; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col-md-5">
								<div class="form-group p-0">
									<label for="decider">Jumlah Soal <span style="color: red;">*</span></label>
									<input class="form-control" type="number" id="decider" name="decider" placeholder="Masukkan Jumlah Pertanyaan" max="50"></input>
									<small id="deciderHelp" class="form-text text-muted">Value must be between 1 and 50.</small>
								</div>
							</div>
							<div class="col-md-1">
								<div class="form-group p-0">
									<label for="chooseTrain"><span style="color: white;">*</span></label>
									<button type="button" class="btn btn-primary float-right" onclick="generateQuestionRows()">Generate</button>
								</div>
							</div>
						</div>
						<div class="row py-2">
							<div class="col-md-12">
								<table id="allSoalTable" name="table" class="table table-hover table-head-bg-info my-2">
									<thead>
										<tr>
											<th scope="col" style="width: 50px;">No.</th>
											<th scope="col">Pertanyaan</th>
											<th scope="col" style="width: 130px;">Kesulitan</th>
											<th scope="col" style="width: 85px;">Jawaban</th>
											<th scope="col" style="width: 150px;">Pilihan A</th>
											<th scope="col" style="width: 150px;">Pilihan B</th>
											<th scope="col" style="width: 150px;">Pilihan C</th>
											<th scope="col" style="width: 150px;">Pilihan D</th>
										</tr>
									</thead>
									<tbody id="tBodyAllSoal">
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="card-body">
						<input type="hidden" name="package_id" id="package_id">
						<button type="button" class="btn btn-success float-right" onclick="validatePForm()">Submit</button>
						<a href="javascript:void(0)" onclick="changePForm('main')" class="btn btn-danger"> Kembali</a>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
include __DIR__ . "/script2.php";
?>