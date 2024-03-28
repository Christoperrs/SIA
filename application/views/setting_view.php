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
								<h4 class="card-title">Pengaturan</h4>
								<p class="card-category">Pengaturan AWI Training System</p>
							</div>
						</div>
					</div>
					<form action="Setting/editSettings" method="post">
                        <div class="card-body pt-0">
                            <p class="card-category">Formulir Pengajuan dan Evaluasi Training (FPET)</p>
                            <div class="row form-group px-0" style="border-bottom: 1px solid #ebedf2 !important;">
                                <div class="col-md-5">
                                    <label for="FPETFM_DEFAULTHR" class="pt-2">Default HR</label>
                                </div>
                                <div class="col-md-7">
                                    <select class="form-control input-pill" id="FPETFM_DEFAULTHR" name="FPETFM_DEFAULTHR">
                                        <option value="">-- Tidak Ada Default HR --</option>
                                        <?php foreach ($employee as $e) : ?>
                                            <option value="<?php echo $e->NPK; ?>" <?php echo $settings[1]->SETTING_VALUE == $e->NPK ? 'selected' : ''; ?>><?php echo $e->NAMA; ?> (<?php echo $e->DEPARTEMEN; ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <p class="card-category">Pre-test dan Post-test</p>
                            <div class="row form-group px-0" >
                                <div class="col-md-5">
                                    <label for="TRNQUE_MAX" class="pt-2">Default Jumlah Soal dalam 1 Halaman</label>
                                </div>
                                <?php $selectedValue = $settings[0]->SETTING_VALUE; ?>
                                <div class="col-md-7">
                                    <select class="form-control input-pill" id="TRNQUE_MAX" name="TRNQUE_MAX">
                                        <option value="1" <?php echo $selectedValue == '1' ? 'selected' : ''; ?>>1</option>
                                        <option value="2" <?php echo $selectedValue == '2' ? 'selected' : ''; ?><?php echo $selectedValue == '1' ? 'selected' : ''; ?>>2</option>
                                        <option value="3" <?php echo $selectedValue == '3' ? 'selected' : ''; ?>>3</option>
                                        <option value="4" <?php echo $selectedValue == '4' ? 'selected' : ''; ?>>4</option>
                                        <option value="5" <?php echo $selectedValue == '5' ? 'selected' : ''; ?>>5</option>
                                        <option value="10" <?php echo $selectedValue == '10' ? 'selected' : ''; ?>>10</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <button type="submit" id="btnSimpan" class="btn btn-success float-right">Simpan</button>
                        </div>
                    </form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include __DIR__ . '/script2.php'; ?>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
?>