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
	<div class="row" id="adminPage">
		<div class="col-md-6">
			<div class="card p-2 m-0">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title">Kelola Admin</h4>
							<p class="card-category">Admin</p>
						</div>
						<div class="col d-flex align-items-center justify-content-end">
							<button onclick="showAdminModal()" class="btn btn-primary" id="tambahButton">Tambah</button>
						</div>
					</div>
				</div>
				<div class="card-body" style="max-height: 480px; overflow-y: scroll; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px;">
					<?php
					$i = 1;
					if (empty($admin)) {
					?>
					<div class="avatar flex-shrink-0 me-3 justify-content-center d-flex">

						<img src="<?= base_url('assets/img/dataEmpty1.jpg') ?>" alt="User" class="img-fluid" style="max-width: 310px; max-height: 190px;" />

					</div>
					<hr>
					<h5 style="text-align: center;">Data Tidak Ada</h5>
					<?php
					} else {
						foreach ($admin as $t) {
					?>
						<li class="d-flex py-1 card-admin">
					<?php
						$imageUrl = "https://aas.awi.co.id/ehrd/foto/$t->NPK.jpg";
						$headers = get_headers($imageUrl);

						if (strpos($headers[0], '200')) { ?>
							<div class="avatar flex-shrink-0 me-3">
								<img src="<?php echo $imageUrl; ?>" alt="User" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; object-position: top center;" />
							</div>
						<?php } else { ?>
							<div class="avatar flex-shrink-0 me-3">
								<img src="<?php echo base_url('assets/img/user2.jpg'); ?>" alt="Default" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; object-position: top center;" />
							</div>
						<?php } ?>
							<!-- <div class="avatar flex-shrink-0 me-3">
								<img src="https://aas.awi.co.id/ehrd/foto/<?php echo $t->NPK ?>.jpg" alt="User" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover; object-position: top left;" />
							</div> -->
							<div class="d-flex flex-column flex-md-row w-100 align-items-md-center justify-content-between gap-3">
								<div class="me-md-2" style="flex: 1;">
									<div style="padding-right: 10px; padding-left: 10px">
										<span class="user-level" style="font-size: larger;">
											<h6> <?php echo $t->NAMA ?></h6>
										</span>
										<small class="text-muted d-block mb-1">
											<h8><?php echo $t->DEPARTEMEN ?></h8>
										</small>
									</div>
								</div>

								<?php if ($countAdmin > 1) { ?>
								<!-- <div class="user-progress d-flex align-items-center gap-1"> -->
									<div class="close-icon" onclick="confirmDeleteAdmin('<?php echo $t->NPK ?>')"><i style="font-size: 1.8rem;" class="la la-trash"></i></div>
									<!-- <a onclick="confirmDeleteAdmin('<?php echo $t->NPK ?>')"><i style="font-size: 1.8rem;" class="la la-trash"></i></a> -->
								<!-- </div> -->
								<?php } ?>
							</div>
						</li>
						<hr>
				<?php
							$i++;
						}
					} ?>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="card p-2">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title">Kelola Tagar</h4>
							<p class="card-category">Tagar</p>
						</div>
						<div class="col d-flex align-items-center justify-content-end">
							<button onclick="showTagModal()" class="btn btn-primary" id="tambahButton">Tambah</button>
						</div>
					</div>
				</div>
				<div class="card-body" style="max-height: 480px; overflow-y: scroll;">
					<div class="row">
						<?php $i = 1;
						function isColorLight($hexColor)
						{
							$r = hexdec(substr($hexColor, 1, 2));
							$g = hexdec(substr($hexColor, 3, 2));
							$b = hexdec(substr($hexColor, 5, 2));
							$luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;
							if ($luminance > 0.7) {
								return '#000000';
							} else {
								return '#ffffff';
							}
						}
						foreach ($tags as $t) {
							$textColor = isColorLight($t->TRNLBL_COLOR); ?>
							<div class="col-sm-6 my-2">
								<div class="card-rounded card-color" id="<?php echo $t->TRNLBL_NAME ?>" onmouseover="mouseIn('<?php echo $t->TRNLBL_NAME ?>', '<?php echo $t->TRNLBL_COLOR ?>')" onmouseout="mouseOut('<?php echo $t->TRNLBL_NAME ?>', '<?php echo $t->TRNLBL_COLOR ?>')" style="background-color: <?php echo $t->TRNLBL_COLOR ?>;">
									<!-- <p><?php echo $i ?></p> -->
									<b>
										<h5 class="mb-0 ml-3 my-3" style="color: <?php echo $textColor ?>"><?php echo $t->TRNLBL_NAME ?></h5>
									</b>
									<div class="close-icon" onclick="confirmDeleteTag(<?php echo $t->TRNLBL_ID ?>, <?php echo $t->TRNLBL_TOTAL ?>)"><i style="font-size: 1.8rem;" class="la la-trash"></i></div>
								</div>
							</div>
						<?php $i++;
						} ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="colorModalLabel">Edit Color</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<label for="colorInputEdit">Choose a color:</label>
				<input type="color" id="colorInputEdit" class="form-control">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" onclick="updateColor()">Save changes</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-labelledby="addAdminModalLabel" aria-hidden="true" style="overflow: visible;">
	<div class="modal-dialog" style="max-width: 950px">
		<div class="modal-content" style="width: 950px;">
			<div class="card p-2 mb-0">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title" id="addAdminModalLabel">Tambah Admin</h4>
							<p class="card-category">Admin / Tambah Admin</p>
						</div>
						<div class="col d-flex justify-content-end">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<form role="form" id="formTraining" action="<?php echo base_url('Admin/saveAdmin') ?>" method="post" enctype="multipart/form-data">
						<div class="card-body p-0">
							<div class="row py-2">
								<div class="dropdown col-md-4">
									<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="false">
										ALL
									</button>
									<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" x-placement="top-start" style="position: absolute; transform: translate3d(0px, 0px, 0px); top: 0px; left: 0px; will-change: transform; max-height: 200px; overflow-y: auto;">
										<a class="dropdown-item" id="all" href="javascript:void(0)" onclick="searchKeyword('', 'ALL', 'allEmpTableAdmin');">ALL</a>
										<?php foreach ($dept as $d) { ?>
											<a class="dropdown-item" id="<?php echo $d->DEPARTEMEN ?>" href="javascript:void(0)" onclick="searchKeyword('', '<?php echo $d->DEPARTEMEN ?>', 'allEmpTableAdmin')"><?php echo $d->DEPARTEMEN ?></a>
										<?php } ?>
									</ul>
								</div>
								<div class="col-md-1" style="align-items: center;">
									<label for="search_employee">Search:&nbsp;&nbsp;</label>
								</div>
								<div class="col-md-3">
									<input type="text" class="form-control input-full" id="search_employee" name="search_employee" style="width: 100%;">
								</div>
								<div class="col-md-4">
									<label class="form-radio-label mb-3 mr-3 float-right">
										<span class="form-radio-sign">Select All: &nbsp;&nbsp;</span>
										<input type="checkbox" data-toggle="toggle" data-onstyle="info" data-style="btn-round" name="optionsRadiosA" value="" onchange="toggleAll(this.checked);">
									</label>
								</div>
							</div>
							<div style="max-height: 300px; overflow-y: scroll;" id="allEmpTableAdminDiv">
								<table id="allEmpTableAdmin" name="table" class="table table-hover table-head-bg-info my-2">
									<thead>
										<tr>
											<th scope="col" class="text-center" style="width: 60px;">No.</th>
											<th scope="col" class="text-center" style="width: 350px;">Nama Karyawan</th>
											<th scope="col" class="text-center" style="width: 350px;">Departemen</th>
											<th scope="col" class="text-center">Aksi</th>
										</tr>
									</thead>
									<tbody id="tBodyAllEmpA">
										<?php $i = 1;
										foreach ($employee as $e) { ?>
											<tr>
												<td><?php echo $i; ?></td>
												<td><?php echo $e->NAMA; ?></td>
												<td><?php echo $e->DEPARTEMEN; ?></td>
												<td class="text-center">
													<label class="form-check-label">
														<input class="form-check-input" type="checkbox" value="<?php echo $e->NPK; ?>" name="chkBoxemp" <?php if ($e->isAdmin) echo 'disabled checked' ?>>
														<span class="form-check-sign" onclick="addEmp('<?php echo $e->NPK; ?>')"></span>
													</label>
												</td>
											</tr>
										<?php $i++;
										} ?>
									</tbody>
								</table>
							</div>
						</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="submitEdit('admin')" class="btn btn-primary">Submit</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="addTagModal" tabindex="-1" aria-labelledby="addTagModalLabel" aria-hidden="true" style="overflow: visible;">
	<div class="modal-dialog" style="max-width: 950px">
		<div class="modal-content" style="width: 950px;">
			<div class="card p-2 mb-0">
				<div class="card-header">
					<div class="row">
						<div class="col">
							<h4 class="card-title" id="addTagModalLabel">Tambah Tag</h4>
							<p class="card-category">Tag / Tambah Tag</p>
						</div>
						<div class="col d-flex justify-content-end">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
					</div>
				</div>
				<div class="modal-body">
					<form role="form" action="<?php echo base_url('Admin/saveTag') ?>" method="post" enctype="multipart/form-data">
						<div class="card-body p-0">
							<div class="row">
								<div class="col">
									<label for="tagTraining" class="my-2">Nama Taggar <span style="color: red;">*</span></label>
									<input type="text" class="form-control input-pill mb-3" name="nameTag" id="nameTag" placeholder="Masukkan Nama Tag Baru" required="">
								</div>
								<div class="col">
									<label for="tagTraining" class="my-2">Pilih Warna </label>
									<input type="color" id="colorTag" name="colorTag" value="#ffffff" class="form-control mb-3;" style="height: 42%;">
								</div>
							</div>
						</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	async function showAdminModal() {
		await getAdmins();
		new bootstrap.Modal(document.getElementById('addAdminModal')).show();
		document.getElementById('search_employee').value = '';
		empArrAdmin = [];
		searchKeyword('', '', 'allEmpTableAdmin');
		toggleAll(false);
		document.getElementById('dropdownMenu1').textContent = 'ALL';
	};

	function showTagModal() {
		new bootstrap.Modal(document.getElementById('addTagModal')).show();
	};

	document.getElementById('search_employee').addEventListener('keyup', function() {
		var keyword = this.value.trim();
		searchKeyword(keyword, '', 'allEmpTableAdmin');
	});
</script>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
include __DIR__ . '/script2.php';
?>