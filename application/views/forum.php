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
                            <input type="text" id="searchInput" placeholder="Search..." oninput="searchArticles(this.value)">
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
                $z = 0;
                foreach ($forum as $t) {
                    if ($t->FRM_STATUS >= 1) {
                        $z++;
                    }
                }
                if ($z == 0) { ?>
                    <div class="col card-item fade-in">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="card" style="border-radius: 20px;">
                                    <div class="card-header d-flex justify-content-center">
                                        <img src="assets/img/dataEmpty1.jpg" style="max-height: 163px">
                                    </div>
                                    <div class="card-body d-flex justify-content-center">
                                        <div class="row">
                                            <div class="col">
                                                <h4 class="card-title">Tidak ada artikel tersedia!</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php    }
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
                                            <p class="card-category forum-class long-description"><?php echo strip_tags($t->FRM_DESC); ?></p>
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
    <div class="row" id="xyz" style="display: none;">
        <div class="col-md-12">
            <div class="card p-2 mb-3">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="article-cover" id="articleCover" style="background-image: url('<?php echo base_url('assets/img/picLog.png') ?>');"></div>
                        </div>
                        <div class="col d-flex align-items-center">
                            <span class="h3" id="articleTitle">Artikel </span>
                        </div>
                        <div class="col-md-2" id="modifyButtonDiv" style="display: none;">
                            <div class="d-flex justify-content-end">
                                <a href="javascript:void(0)" id="editBtnFRMPublished" onclick="doUpdateFRM()" class="btn btn-warning" style="margin-right: 9px;"></i> Edit</a>
                                <a id="deleteBtnFRMPublished" class="btn btn-danger "></i> Hapus</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p id="articleDesc"></p>
                </div>
                <div class="card-body" id="divBackFRM">
                    <a href="javascript:void(0)" onclick="changeFormFRM()" class="btn btn-danger"></i> Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div>
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
                                <div class="col-md-6">
                                    <div class="form-group p-0">
                                        <label for="trainer">Judul<span style="color: red;"> *</span></label>
                                        <input type="text" maxlength="100" class="form-control input-pill mb-3" name="titleFRM" id="titleFRM" placeholder="Masukkan Judul">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group p-0">
                                        <label for="imgFRM">Tambahkan Cover</label>
                                        <input type="file" class="form-control-file" name="imgFRM" id="imgFRM" placeholder="" accept="image/*" onchange="updateSpanText(this)">
                                        <span class="file-label" name="imgTXT" id="imgTXT">Choose a file</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="my-2">Deskripsi<span style="color: red;"> *</span></label>
                                    <div id="editor-container" style="height: 300px;"></div>
                                    <!-- <textarea class="form-control" id="descFRM" name="descFRM" rows="5" maxlength="1000" placeholder="Masukkan deskripsi"></textarea> -->
                                </div>
                            </div>
                        </div>
                        <div class="card-body" id="divBackFRM">
                            <button type="button" id="btnSubFRM" class="btn btn-success float-right">Simpan</button>
                            <a href="javascript:void(0)" onclick="changeFormFRM()" class="btn btn-danger"></i> Kembali</a>
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
            document.getElementById("addFRM").style.display = 'block';
            document.getElementById("xyz").style.display = 'none';
            enableFormElements();
            var formElement = document.getElementById('btnSubFRM');
            formElement.setAttribute('onclick', 'update()');
            console.log(document.getElementById('btnSubFRM'));
            var editorContainer = document.getElementById('editor-container');
            editorContainer.style.backgroundColor = '#fff';

            var quillContent = editorContainer.querySelector('.ql-editor');
            quillContent.setAttribute('contenteditable', 'true');
        }

        function clearFormFRM() {
            ['idFRM', 'titleFRM'].forEach(id => {
                document.getElementById(id).value = '';
            });
            document.getElementById('imgTXT').textContent = 'Choose a file';
            var editorContainer = document.getElementById('editor-container');
            editorContainer.style.backgroundColor = '#fff';

            var quillContent = editorContainer.querySelector('.ql-editor');
            quillContent.setAttribute('contenteditable', 'true');
        }

        function createDeleteTimes(input) {
            if (!document.getElementById('deleteIMG')) {
                var deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.id = 'deleteButton';
                deleteButton.dataset.toggle = 'tooltip';
                deleteButton.title = 'Remove';
                deleteButton.className = 'btn btn-link btn-simple-danger';
                deleteButton.style.position = 'absolute';
                deleteButton.style.top = '24px';
                deleteButton.style.left = '89%';
                deleteButton.innerHTML = '<i class="la la-times"></i>'; // You can replace "la la-times" with your desired Font Awesome icon classes
                deleteButton.onclick = function() {
                    clearFileInput(input);
                };

                var parentElement = input.parentElement;
                parentElement.appendChild(deleteButton);
            }
        }

        function clearFileInput(input) {
            input.value = '';
            var span = document.getElementById('imgTXT');
            span.textContent = 'Choose a file';
            document.getElementById('deleteButton').remove();
        }

        function updateSpanText(input) {
            var span = document.getElementById('imgTXT');
            if (input.files && input.files.length > 0) {
                var fileName = input.files[0].name;
                span.textContent = fileName;
                createDeleteTimes(input);
            } else {
                span.textContent = 'Choose a file';
            }
        }

        async function searchArticles(keyword) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        const data = JSON.parse(xhr.responseText);
                        const container = document.getElementById('articlesContainer');
                        container.innerHTML = '';
                        data.forEach(function(item) {
                            var imageUrl = item.FRM_IMAGE ? `https://localhost/prior/SIA/uploads/${item.FRM_IMAGE}` : 'https://localhost/prior/SIA/assets/img/picLog.png';

                            // Create the card structure
                            const cardHTML = `
                                <div class="col-sm-3 card-item fade-in">
                                    <div class="card" style="border-radius: 20px;">
                                        <div class="card-header">
                                            <div class="image-article">
                                                <img src="${imageUrl}" alt="Default Image">
                                            </div>
                                            <div class="row overlay-content" style="width: 100%">
                                                <div class="col-sm-6">
                                                    <span class="badge ${item.FRM_STATUS == 2 ? 'badge-success' : 'badge-warning'}">${item.FRM_STATUS == 2 ? 'Published' : 'Draft'}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-8 pr-0">
                                                    <h4 class="card-title forum-class">${item.FRM_TITLE || 'Default Title'}</h4>
                                                    <p class="card-category forum-class long-description">${stripTags(item.FRM_DESC) || 'Default Description'}</p>
                                                </div>
                                                <div class="col d-flex align-items-center justify-content-end p-0 pr-3">
                                                    <a href="javascript:void(0)" onclick="showDetailFRM(${item.FRM_ID})" class="btn btn-primary px-2"><i class="la la-bars" style="font-size: 16px;"></i> Detail</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            container.innerHTML += cardHTML;
                        });
                    } else {
                        console.error('Error fetching data');
                    }
                }
            };

            xhr.open('POST', '<?php echo base_url('Article/searchArticles/') ?>', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.send('keyword=' + encodeURIComponent(keyword));
        }

        function stripTags(html) {
            return html.replace(/<[^>]*>/g, '');
        }

        function save() {
            var requiredFields = ['titleFRM'];
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
                var quillContent = document.getElementById('editor-container').querySelector('.ql-editor').innerHTML;
                var descFRM = document.createElement('input');
                descFRM.type = 'hidden';
                descFRM.name = 'descFRM';
                descFRM.id = 'descFRM';
                descFRM.value = quillContent;
                document.getElementById("formFRM").appendChild(descFRM);
                document.getElementById("formFRM").submit();
            }
        }

        function update() {
            var requiredFields = ['idFRM', 'titleFRM'];
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
                var imgTXTValue = document.getElementById('imgTXT').textContent;
                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'imgTXTInput';
                hiddenInput.id = 'imgTXTInput';
                hiddenInput.value = imgTXTValue;
                var descFRM = document.createElement('input');
                descFRM.type = 'hidden';
                descFRM.name = 'descFRM';
                descFRM.id = 'descFRM';
                descFRM.value = document.getElementById('editor-container').querySelector('.ql-editor').innerHTML;
                document.getElementById("formFRM").appendChild(hiddenInput);
                document.getElementById("formFRM").appendChild(descFRM);
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
            document.getElementById("xyz").style.display = 'none';

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
            document.getElementById("xyz").style.display = 'none';
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
                            document.getElementById('titleFRM').value = document.getElementById('articleTitle').textContent = dataFRM.FRM_TITLE || '';
                            document.getElementById('imgTXT').textContent = dataFRM.FRM_IMAGE || 'Choose a file';
                            if (dataFRM.FRM_IMAGE != null) createDeleteTimes(document.getElementById('imgFRM'));

                            var editorContainer = document.getElementById('editor-container');
                            editorContainer.style.backgroundColor = '#222';

                            var quillContent = editorContainer.querySelector('.ql-editor');
                            quillContent.setAttribute('contenteditable', 'false');
                            quillContent.innerHTML = dataFRM.FRM_DESC || '';
                            document.getElementById('articleDesc').innerHTML = dataFRM.FRM_DESC.replace(/<img/g, '<img class="align-items-center" style="display: block; margin: 0 auto; width: 50%; height: auto;"') || '';
                            // .replace(/<[^>]+>/g, '')

                            document.getElementById('btnSubFRM').style.display = 'none';

                            document.getElementById('btnDetailFRM').style.display = 'block';
                            if (dataFRM.FRM_STATUS == '1') {
                                document.getElementById('editBtnFRM').style.display = 'block'; // Adjust as per your requirement
                                document.getElementById('deleteBtnFRM').style.display = 'block';
                                document.getElementById('publishBtnFRM').style.display = 'block';
                                document.getElementById("addFRM").style.display = 'block';
                            } else {
                                document.getElementById("xyz").style.display = 'block';
                                if ('<?php echo $this->session->userdata['role']; ?>' == 'admin') {
                                    document.getElementById('modifyButtonDiv').style.display = 'block';
                                }
                                var imageUrl = dataFRM.FRM_IMAGE ? '<?php echo base_url('uploads/'); ?>' + dataFRM.FRM_IMAGE : '<?php echo base_url("assets/img/picLog.png") ?>';
                                var articleCover = document.getElementById('articleCover');
                                articleCover.style.backgroundImage = `url('${imageUrl}')`;
                            }
                            document.querySelectorAll('#deleteBtnFRMPublished, #deleteBtnFRM').forEach(function(element) {
                                element.setAttribute('href', '<?= base_url('Article/deleteFRM/') ?>' + id);
                            });
                            var publishBtnFRM = document.getElementById('publishBtnFRM');
                            publishBtnFRM.setAttribute('href', '<?= base_url('Article/publishFRM/') ?>' + id);

                            document.getElementById("showListFRM").style.display = 'none';
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

        var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'], // toggled buttons
            ['blockquote', 'code-block'],
            [{
                'header': 1
            }, {
                'header': 2
            }], // custom button values
            [{
                'list': 'ordered'
            }, {
                'list': 'bullet'
            }],
            [{
                'script': 'sub'
            }, {
                'script': 'super'
            }], // superscript/subscript
            [{
                'indent': '-1'
            }, {
                'indent': '+1'
            }], // outdent/indent
            [{
                'direction': 'rtl'
            }], // text direction
            [{
                'size': ['small', false, 'large', 'huge']
            }], // custom dropdown
            [{
                'header': [1, 2, 3, 4, 5, 6, false]
            }],
            [{
                'color': []
            }, {
                'background': []
            }], // dropdown with defaults from theme
            [{
                'font': []
            }],
            [{
                'align': []
            }],
            ['clean'], // remove formatting button
            ['link', 'image', 'video'] // link and image, video
        ];

        function changeFormFRM() {
            var formElement = document.getElementById('formFRM');
            formElement.removeAttribute('action');
            document.getElementById("showListFRM").style.display = 'block';
            document.getElementById("addFRM").style.display = 'none';
            document.getElementById("xyz").style.display = 'none';

            var formElement2 = document.getElementById('btnSubFRM');
            formElement2.removeAttribute('onclick');



            document.getElementById('editBtnFRM').style.display = 'none';
            document.getElementById('deleteBtnFRM').style.display = 'none';
            document.getElementById('publishBtnFRM').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            var requiredFields = ['titleFRM'];

            requiredFields.forEach(function(fieldId) {
                var inputField = document.getElementById(fieldId);

                inputField.addEventListener('input', function() {
                    inputField.style.borderColor = ''; // Reset to default
                });
            });

            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'], // toggled buttons
                ['blockquote', 'code-block'],
                [{
                    'header': 1
                }, {
                    'header': 2
                }], // custom button values
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }], // superscript/subscript
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }], // outdent/indent
                [{
                    'direction': 'rtl'
                }], // text direction
                [{
                    'size': ['small', false, 'large', 'huge']
                }], // custom dropdown
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'color': []
                }, {
                    'background': []
                }], // dropdown with defaults from theme
                [{
                    'font': []
                }],
                [{
                    'align': []
                }],
                ['clean'], // remove formatting button
                ['link', 'image', 'video'] // link and image, video
            ];

            var quill = new Quill('#editor-container', {
                modules: {
                    toolbar: toolbarOptions
                },
                theme: 'snow'
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