<?php
ob_start();
?>
<div class="container-fluid">
    <div id="showListFRM">
        <div class="row">
            <div class="col-md-12">
                <div class="card p-2 mb-3">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h4 class="card-title">Artikel </h4>
                                <p class="card-category">Lihat Artikel</p>
                            </div>
                            <?php if ($this->session->userdata('role') == 'admin') { ?>
                                <div class="col d-flex align-items-center justify-content-end">
                                    <a href="javascript:void(0)" onclick="showAddFRM('tambah')" class="btn btn-primary"> Tambah</a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body px-0">
            <div class="row" id="articlesContainer">
                <?php $i = 1;
                $j = 1;
                foreach ($forum as $t) {
                    if (($this->session->userdata('role') != 'admin' && $t->FRM_STATUS == 2) || $this->session->userdata('role') == 'admin') { ?>
                        <div class="col-sm-3 card-item fade-in">
                            <div class="card" style="border-radius: 20px;">
                                <div class="card-header">
                                    <div class="image-article">
                                        <?php if (!empty($t->FRM_IMAGE)) : ?>
                                            <!-- If $t->FRM_IMAGE is not null, display the image -->
                                            <img src="<?php echo base_url('uploads/' . $t->FRM_IMAGE); ?>" alt="Forum Image">
                                        <?php else : ?>
                                            <!-- If $t->FRM_IMAGE is null, display the default image picLog.png -->
                                            <img src="<?php echo base_url('assets/img/picLog.png'); ?>" alt="Default Image">
                                        <?php endif; ?>
                                    </div>
                                    <div class="row overlay-content" style="width: 100%">
                                        <div class="col-sm-6">
                                            <?php if ($t->FRM_STATUS == 2) { ?>
                                                <span class="badge badge-success">Published</span>
                                            <?php } else { ?>
                                                <span class="badge badge-warning">Draft</span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-8 pr-0">
                                            <h4 class="card-title forum-class"><?php echo $t->FRM_TITLE; ?></h4>
                                            <p class="card-category forum-class long-description"><?php echo $t->FRM_DESC; ?></p>
                                            <!-- <p class="card-category"><i class="la la-users"></i>&ensp;<?php echo $t->participant_count ?> partisipan</p> -->
                                        </div>
                                        <div class="col d-flex align-items-center justify-content-end p-0 pr-3">
                                            <a href="javascript:void(0)" onclick="showDetailFRM(<?php echo $t->FRM_ID ?>)" class="btn btn-primary px-2"><i class="la la-bars" style="font-size: 16px;"></i> Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php $i++;
                    }
                } ?>
            </div>
        </div>
    </div>
    <div class="row" id="addFRM" style="display: none;">
        <div class="col-md-12">
            <form id="formFRM" method="post" enctype="multipart/form-data" role="form">
                <div class="card p-2">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <div class="card-title" id="cardTitle">Tambah Artikel</div>
                                <p class="card-category" id="cardCategory">Lihat Artikel / Tambah Artikel</p>
                            </div>
                            <div class="col">
                                <div class="d-flex justify-content-end" id="btnDetailFRM" style="display: none;">
                                    <a id="publishBtnFRM" class="btn btn-info" style="margin-right: 9px; display: none;"></i> Publish</a>
                                    <a href="javascript:void(0)" id="editBtnFRM" onclick="doUpdateFRM()" class="btn btn-warning" style="margin-right: 9px; display: none;"></i> Edit</a>
                                    <a id="deleteBtnFRM" class="btn btn-danger " style="display: none;"></i> Hapus</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" style="border-bottom: 1px solid #ebedf2 !important;">
                        <div class="row">
                            <input type="text" maxlength="40" class="form-control input-pill mb-3" name="idFRM" hidden id="idFRM" placeholder="Masukkan Judul">
                            <div class="col-md-12">
                                <div class="form-group p-0">
                                    <label for="trainer">Judul<span style="color: red;"> *</span></label>
                                    <input type="text" maxlength="100" class="form-control input-pill mb-3" name="titleFRM" id="titleFRM" placeholder="Masukkan Judul">
                                </div>
                            </div>
                            <!-- <div class="col-md-6">
                                <div class="form-group p-0">
                                    <label for="imgFRM">Tambahkan Gambar</label>
                                    <input type="file" class="form-control file" name="imgFRM" id="imgFRM" accept="image/*">
                                </div>
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <label class="my-2">Deskripsi<span style="color: red;"> *</span></label>
                                <textarea class="form-control" id="descFRM" name="descFRM" rows="5" maxlength="1000" placeholder="Masukkan deskripsi"></textarea>
                            </div>
                        </div>

                        <div class="card-body" id="divBackFRM">
                            <button type="button" id="btnSubFRM" class="btn btn-success float-right">Simpan</button>
                            <a href="javascript:void(0)" onclick="changeFormFRM('main')" class="btn btn-danger"></i> Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>


</div>
<script>
    function doUpdateFRM() {
        document.getElementById('btnSubFRM').style.display = 'block';
        document.getElementById('editBtnFRM').style.display = 'none'; // Adjust as per your requirement
        document.getElementById('deleteBtnFRM').style.display = 'none';
        document.getElementById('publishBtnFRM').style.display = 'none';
        enableFormElements();
        var formElement = document.getElementById('btnSubFRM');
        formElement.setAttribute('onclick', 'update()')
    }

    function clearFormFRM() {
        ['idFRM', 'titleFRM', 'descFRM'].forEach(id => {
            document.getElementById(id).value = '';
        });

    }


    function save() {
        var requiredFields = ['titleFRM', 'descFRM'];
        var isValid = true;

        requiredFields.forEach(function(fieldId) {
            var fieldValue = document.getElementById(fieldId).value.trim();

            if (!fieldValue) {
                document.getElementById(fieldId).style.border = "1px solid red";
                isValid = false;
            } else {
                document.getElementById(fieldId).style.border = "1px solid #ced4da";
            }
        });

        if (isValid) {
            document.getElementById("formFRM").submit();
        }
    }

    function update() {
        var requiredFields = ['idFRM', 'titleFRM', 'descFRM'];
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



        if (isValid) {
            document.getElementById("formFRM").submit();
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

        return isValid;
    }

    function resetValidationStyles() {
        var inputs = document.querySelectorAll('input[type="text"]');
        inputs.forEach(function(input) {
            input.style.borderColor = "";
        });
    }

    function disableFormElements() {
        var formElements = document.getElementById("formFRM").elements;
        for (var i = 0; i < formElements.length; i++) {
            formElements[i].disabled = true;
        }
    }

    function enableFormElements() {
        var formElements = document.getElementById("formFRM").elements;

        for (var i = 0; i < formElements.length; i++) {
            formElements[i].disabled = false;
        }
    }


    var rowFtpe = 0;

    function changeformFRM() {
        var formElement = document.getElementById('formFRM');
        formElement.removeAttribute('action');
        document.getElementById("showListFRM").style.display = 'block';
        document.getElementById("addFRM").style.display = 'none';

        var formElement2 = document.getElementById('btnSubFRM');
        formElement2.removeAttribute('onclick');



        document.getElementById('editBtnFRM').style.display = 'none';
        document.getElementById('deleteBtnFRM').style.display = 'none';
        document.getElementById('publishBtnFRM').style.display = 'none';
    }

    function showAddFRM(kode) {
        enableFormElements();
        clearFormFRM();

        var formElement = document.getElementById('formFRM');
        formElement.setAttribute('action', '<?php echo base_url('Article/saveFRM/') ?>');
        document.getElementById("showListFRM").style.display = 'none';
        document.getElementById("addFRM").style.display = 'block';
        document.getElementById("btnSubFRM").style.display = 'block';
        var formElement = document.getElementById('btnSubFRM');
        formElement.setAttribute('onclick', 'save()')
    }

    async function showDetailFRM(id) {
        if (id != '0') {
            var formElement = document.getElementById('formFRM');
            formElement.setAttribute('action', '<?php echo base_url('Article/modifyFRM/') ?>' + id);

            fetch('<?= base_url('Article/showDetail/') ?>' + id)
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    var dataFRM = data.dataFRM;

                    if (dataFRM) {
                        document.getElementById('idFRM').value = dataFRM.FRM_ID || '';
                        document.getElementById('titleFRM').value = dataFRM.FRM_TITLE || '';
                        document.getElementById('descFRM').value = dataFRM.FRM_DESC || '';


                        document.getElementById('btnSubFRM').style.display = 'none';

                        document.getElementById('btnDetailFRM').style.display = 'block';
                        if (dataFRM.FRM_STATUS == '1') {
                            document.getElementById('editBtnFRM').style.display = 'block'; // Adjust as per your requirement
                            document.getElementById('deleteBtnFRM').style.display = 'block';
                            document.getElementById('publishBtnFRM').style.display = 'block';
                        }
                        var deleteBtnFRM = document.getElementById('deleteBtnFRM');
                        deleteBtnFRM.setAttribute('href', '<?= base_url('Article/deleteFRM/') ?>' + id);
                        var publishBtnFRM = document.getElementById('publishBtnFRM');
                        publishBtnFRM.setAttribute('href', '<?= base_url('Article/publishFRM/') ?>' + id);


                        document.getElementById("showListFRM").style.display = 'none';
                        document.getElementById("addFRM").style.display = 'block';

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

    function changeFormFRM() {
        var formElement = document.getElementById('formFRM');
        formElement.removeAttribute('action');
        document.getElementById("showListFRM").style.display = 'block';
        document.getElementById("addFRM").style.display = 'none';

        var formElement2 = document.getElementById('btnSubFRM');
        formElement2.removeAttribute('onclick');



        document.getElementById('editBtnFRM').style.display = 'none';
        document.getElementById('deleteBtnFRM').style.display = 'none';
        document.getElementById('publishBtnFRM').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        var requiredFields = ['titleFRM', 'descFRM'];

        requiredFields.forEach(function(fieldId) {
            var inputField = document.getElementById(fieldId);

            inputField.addEventListener('input', function() {
                inputField.style.borderColor = ''; // Reset to default
            });
        });
    });
</script>
<?php
/* Store the content of the buffer for later use */
$contentPlaceHolder = ob_get_contents();
/* Clean out the buffer, and destroy the output buffer */
ob_end_clean();
/* Call the master page. It will echo the content of the placeholders in the designated locations */
include __DIR__ . "/layout.php";
?>