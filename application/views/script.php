<!-- Starter Function -->
<script>
	// Global Variables
	var empArrAdmin = [];
	var empArrNon = [];
	var tags = [];
	var empSelected = [];
	var admins;
	var rowCountMateriForm = 0;
	var isAdmin = '<?php echo $this->session->userdata['role']; ?>' == 'admin';
	var trStat = 0;

	truncateTextIfNeeded();
	window.addEventListener('resize', function() {
		truncateTextIfNeeded();
		// Calculate the aspect ratio of the screen
		var screenWidth = window.innerWidth;
		var screenHeight = window.innerHeight;
		var aspectRatio = screenWidth / screenHeight;
		var maxHeight = 0;
		var cond = 0;

		if (screenWidth < 400 && screenHeight < 700) {
			// Mobile devices in portrait mode
			maxHeight = screenHeight * 0.356; // Adjust the factor as needed
			cond = 0;
		} else if (screenWidth < 600 && screenHeight < 700) {
			// Mobile devices in portrait mode
			maxHeight = screenHeight * 0.356; // Adjust the factor as needed
			cond = 8;
		} else if (screenWidth < 576 && screenHeight < 992) {
			// Mobile devices in portrait mode
			maxHeight = screenHeight * 0.5; // Adjust the factor as needed
			cond = 1;
		} else if (screenWidth < 992 && screenHeight < 992 && screenWidth > screenHeight) {
			// Mobile devices in landscape mode
			maxHeight = screenHeight * 0.5;
			cond = 2;
		} else if (screenWidth > screenHeight) {
			// Web landscape mode
			maxHeight = screenHeight * 0.35;
			cond = 3;
		} else {
			// Default scenario
			maxHeight = screenHeight * 0.6;
			cond = 4;
		}

		// Set the max-height of the element
		document.getElementById('dyDiv').style.maxHeight = maxHeight + 'px';
		console.log(screenWidth, screenHeight, aspectRatio, maxHeight, cond);
	});

	function truncateTextIfNeeded() {
		const pElement = document.getElementById('username');
		const screenWidth = window.innerWidth;
		const pElementWidth = pElement.clientWidth;

		// Check if the text overflows the p element
		if (pElementWidth < screenWidth) {
			pElement.classList.add('truncate');
		} else {
			pElement.classList.remove('truncate');
		}
	}

	function confirmDeleteTraining(id) {
		Swal.fire({
			title: 'Konfirmasi Hapus Training',
			text: 'Apakah Anda yakin ingin menghapus data ini?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya',
			cancelButtonText: 'Tidak'
		}).then((result) => {
			if (result.isConfirmed) {
				window.location.href = '<?= base_url('Training/modifyTraining/') ?>' + id;
			}
		});
	}

	function changeForm(kode, status) {
		var listCardDiv = document.getElementById('listCardDiv');
		var detailFormDiv = document.getElementById('detailFormDiv');
		var resumeDiv = document.getElementById('resumeDiv');
		changeDisplayOfElements('block', ['temaDiv', 'substanceDiv']);

		if (kode.includes('edit')) {

			changeDisplayOfElements('block', ['allEmpDiv', 'submitBtn', 'substanceTableEdit', 'addFileBtn']);
			changeDisplayOfElements('none', [isAdmin ? 'detailEmpDiv' : 'detailOnlyDiv', 'substanceTableDetail', 'editBtn', 'deleteBtn', 'publishBtn']);
			resumeDiv.style.display = 'none'
			changeTitle('Ubah Training', true, status);
			var badgeElements = document.querySelectorAll('.badge tags');
			badgeElements.forEach(function(element) {
				element.style.pointerEvents = 'pointer';
			});

		} else if (kode === 'main') {
			listCardDiv.style.display = 'block';
			detailFormDiv.style.display = 'none';
			clearForm();
		} else if (kode === 'tambah') {
			listCardDiv.style.display = 'none';
			detailFormDiv.style.display = 'block';
			isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableEdit2');
			isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableEdit');
			document.getElementById('formTraining').setAttribute('action', '<?php echo base_url('Training/saveTraining') ?>');
			changeDisplayOfElements('block', ['allEmpDiv', 'submitBtn', 'substanceTableEdit', 'addFileBtn']);
			changeDisplayOfElements('none', [isAdmin ? 'detailEmpDiv' : 'detailOnlyDiv', 'substanceTableDetail']);
			var badgeElements = document.querySelectorAll('.badge tags');
			badgeElements.forEach(function(element) {
				element.style.pointerEvents = 'pointer';
			});
			const firstToggleElement = document.querySelector('input[onchange="toggleAll(this.checked);"]');
			if (firstToggleElement) {
				firstToggleElement.parentNode.classList.remove('btn-info');
				firstToggleElement.parentNode.classList.add('btn-default', 'off');
			}
			changeTitle('Tambah Training', true, '');
		} else if (kode === 'detail') {
			listCardDiv.style.display = 'none';
			detailFormDiv.style.display = 'block';
			// resumeDiv.style.display = 'block';
			changeDisplayOfElements('none', ['allEmpDiv', 'submitBtn', 'substanceTableEdit', 'addFileBtn']);
			changeDisplayOfElements('block', [isAdmin ? 'detailEmpDiv' : 'detailOnlyDiv', 'substanceTableDetail']);
			changeTitle('Detail Training', true, '');
			var badgeElements = document.querySelectorAll('.badge tags');
			badgeElements.forEach(function(element) {
				element.style.pointerEvents = 'none';
				element.removeAttribute('onclick');
			});
		}
	}

	function clearTema(judul) {
		document.getElementById('errorMessages').textContent = '';
		if (!judul.classList.contains('mb-3')) {
			judul.classList.add('mb-3');
		}
		judul.removeAttribute('style');
	}

	function clearForm() {
		const judul = document.getElementById('temaTraining');
		document.getElementById('tagsPre').classList.replace('badge-success', 'badge-danger');
		document.getElementById('tagsRes').classList.replace('badge-success', 'badge-danger');
		document.getElementById('tagsPost').classList.replace('badge-success', 'badge-danger');
		document.getElementById('pemateri').value = '';
		document.getElementById('search_keyword').value = '';
		judul.value = '';
		clearTema(judul);
		empArrAdmin = [];
		empArrNon = [];
		searchKeyword('', '', 'allEmpTable');
		rowCountMateriForm = 0;
		document.getElementById('allEmpTableDiv').scrollTop = 0;
		populateTagsSection(<?php echo json_encode($tags) ?>, 'clear');
		var checkboxes = document.querySelectorAll('.form-check-input');
		checkboxes.forEach(checkbox => {
			checkbox.checked = false;
		});
		toggleTab('all');
		const firstToggleElement = document.querySelector('input[onchange="toggleMine(this.checked);"]');
		if (firstToggleElement) {
			firstToggleElement.parentNode.classList.remove('btn-info');
			firstToggleElement.parentNode.classList.add('btn-default', 'off');
		}
		document.getElementById('dropdownMenu1').textContent = 'ALL';
	}

	function changeDisplayOfElements(displayValue, elementsArray) {
		elementsArray.forEach((elementId) => {
			document.getElementById(elementId).style.display = displayValue;
		});
	}

	function changeTitle(title, call, status) {
		document.getElementById('cardTitle').textContent = title;
		document.getElementById('cardCategory').textContent = 'Training / ' + title;
		const isEditable = title.includes('Tambah') || (title.includes('Ubah') && trStat != 2);
		document.getElementById('temaTraining').readOnly = !isEditable;
		document.getElementById('pemateri').readOnly = !isEditable;
		if (call) callLoader();
	}

	function callLoader() {
		var loader = document.getElementById('loaderDiv');
		setTimeout(function() {
			loader.classList.add('fade-out');
			setTimeout(function() {
				loader.style.display = 'none';
			}, 500);
		}, 500);
		loader.style.display = '';
		loader.classList.remove('fade-out');
	}

	function activateClassActive(tabName) {
		var tabs = document.querySelectorAll('#statusTabs .nav-link');
		tabs.forEach(function(tab) {
			tab.classList.remove('active');
		});

		var clickedTab = document.getElementById(tabName + 'Tab');
		clickedTab.classList.add('active');
	}

	function createInputCell(idname, type, placeholder, tr) {
		var cell = document.createElement('td');
		var input = document.createElement('input');
		input.type = type;
		input.id = idname;
		input.name = idname;
		input.classList.add('form-control', 'form-control-sm');
		if (idname.includes('materiTitle')) {
			input.required = true;
		}

		type == 'hidden' ? input.value = placeholder : input.placeholder = placeholder;
		if (type == 'hidden') {
			tr.appendChild(input);
		} else {
			if (type == 'file') {
				input.classList.replace('form-control', 'form-control-file');
				input.accept = '.pdf';
				input.addEventListener('change', function() {
					var file = this.files[0];
					if (file) {
						var fileType = file.type.toLowerCase();
						if (fileType !== 'application/pdf' || (file && file.size > 10 * 1024 * 1024)) {
							let errorMessage = '';
							if (fileType !== 'application/pdf') {
								errorMessage = 'Upload File harus berjenis PDF!';
							} else if (file && file.size > 10 * 1024 * 1024) {
								errorMessage = 'Ukuran file maksimal 10MB!';
							}
							Swal.fire({
								icon: 'error',
								title: 'Error Upload File!',
								text: errorMessage,
								confirmButtonColor: '#3085d6',
								confirmButtonText: 'OK'
							}).then((result) => {
								if (result.isConfirmed) {
									this.value = '';
								}
							});
						}
					}
				});
			}
			cell.appendChild(input);
			tr.appendChild(cell);
		}
	}

	function createFileCell(id1, id, path, code, tr) {
		var cell = document.createElement('td');
		cell.classList.add('text-center');

		var a = document.createElement('a');
		fetch('<?= base_url('Plus/hasRead/') ?>' + id)
			.then(response => response.json())
			.then(dataExists => {
				var fileText = 'View PDF';
				console.log('Data exists:', dataExists);
				if (dataExists) {
					if (code == 'detail') fileText += ' \u00A0\uD83D\uDC41';
				}
				a.textContent = fileText;
				// if (fileText.includes('\u00A0\uD83D\uDC41')) {
				// 	checkPreTest('x', 1);
				// }
			})
			.catch(error => {
				console.error('Error fetching data:', error);
			});

		a.href = 'javascript:void(0)';
		a.addEventListener('click', function(event) {
			if (code == 'detail') {
				fetch('<?= base_url('Training/addProgress/') ?>' + id)
					.then(response => {
						return response.text();
					})
					.catch(error => {
						console.error('Error fetching data:', error);
					});
			}

			var modal = document.getElementById('pdfModal');
			modal.style.display = 'block';

			var pdfViewer = document.getElementById('pdfViewer');
			// Handle modal close event

			pdfViewer.src = `${path}#toolbar=0&zoom=100&view=FitH`;

			pdfViewer.onload = function() {

			};

			var closeButton = document.getElementsByClassName('close')[0];
			closeButton.onclick = function() {
				modal.style.display = 'none';
				pdfViewer.src = '';
			};

			window.onclick = function(event) {
				if (event.target === modal) {
					modal.style.display = 'none';
					pdfViewer.src = '';
					pdfViewer.contentWindow.document.body.oncontextmenu = function() {
						return false;
					};
				}
			};
			if (!a.textContent.includes('\u00A0\uD83D\uDC41')) {
				a.textContent += ' \u00A0\uD83D\uDC41';
			}
		});

		cell.appendChild(a);
		tr.appendChild(cell);

	}

	function createSelectCell(optionsValue, optionsArray, tr, idName, def) {
		var cell = document.createElement('td');

		var select = document.createElement('select');
		select.classList.add('form-control');
		select.id = select.name = idName;

		var defOption = document.createElement('option');
		defOption.value = "default";
		defOption.textContent = def;
		defOption.selected = defOption.disabled = true;
		select.appendChild(defOption);

		// Check if optionsValue and optionsArray are arrays
		if (Array.isArray(optionsValue) && Array.isArray(optionsArray)) {
			// Iterate through the optionsValue and optionsArray simultaneously
			optionsValue.forEach(function(optionValue, index) {
				var optionName = optionsArray[index];

				var option = document.createElement('option');
				option.value = optionValue;
				option.textContent = optionName;
				select.appendChild(option);
			});
		} else {
			// If optionsValue and optionsArray are not arrays, create a single option
			var option = document.createElement('option');
			option.value = optionsValue;
			option.textContent = optionsArray;
			select.appendChild(option);
		}

		cell.appendChild(select);
		tr.appendChild(cell);
	}


	function createTextCell(text, tr, code, align) {
		var cell = document.createElement('td');
		cell.textContent = (code === 'number') ? text + '.' : text;
		if (align == 'center') cell.classList.add('text-center');
		tr.appendChild(cell);
	}

	function createBadgeApproval(idDetail, npk, id, tr) {
		if (isAdmin) {
			var cell = document.createElement('td');
			cell.classList.add('text-center');

			var approvalContainer = document.createElement('div');
			approvalContainer.classList.add('d-inline-flex', 'align-items-center');

			var spanA = document.createElement("span");
			spanA.className = "badge badge-success mr-1 ml-1";
			spanA.textContent = "Approve";
			spanA.id = "sAcc" + tr.id;
			spanA.style.cursor = "pointer";
			spanA.onclick = function() {
				if (!spanA.disabled) {
					modifyApproval(idDetail, npk, id, 1);
					spanA.removeAttribute('id');
					approvalContainer.removeChild(spanR);
					spanA.disabled = true;
				}
			};
			approvalContainer.appendChild(spanA);

			var spanR = document.createElement("span");
			spanR.className = "badge badge-danger  mr-1 ml-1";
			spanR.textContent = "Reject";
			spanR.id = "sRej" + tr.id;
			spanR.style.cursor = "pointer";
			spanR.onclick = function() {
				if (!spanR.disabled) {
					modifyApproval(idDetail, npk, id, 3);
					spanR.removeAttribute('id');
					approvalContainer.removeChild(spanA);
					spanR.disabled = true;
				}
			};
			approvalContainer.appendChild(spanR);

			cell.appendChild(approvalContainer);

			tr.appendChild(cell);
		}
	}


	async function createCheckboxCell(name, value, tr, id, code, stat) {
		try {
			const regex = /^(.*?)(\d+)$/;
			const match = value.match(regex);
			var tema = match[1];
			var npk = match[2];

			var cell = document.createElement('td');
			cell.classList.add('text-center');

			var label = document.createElement('label');
			label.classList.add('form-check-label');

			var input = document.createElement('input');
			input.type = 'checkbox';
			input.name = name;
			input.value = value;
			input.classList.add('form-check-input');
			input.style.position = 'fixed';

			// A condition to disable checking in training detail, specific for admin
			if (code != 'edit' && admins.includes(npk)) input.checked = input.disabled = true;

			// A condition to check whether the participant is allowed to edit or not in training detail
			if (code == 1) {
				input.checked = true;
			}

			// A condition when editing training
			else if (code == 'edit' && id != null && stat != 'admin') {
				// Condition if the user is not an admin
				if (id == 'non') {
					// Condition if the data choosen by non-admin has the value checked
					if (empArrNon != null && empArrNon.includes(value)) {
						input.checked = true;
					}
				}
				// Condition if the data choosen is an admin
				if (empArrAdmin != null && empArrAdmin.includes(value)) {
					input.checked = true;
					if (!isAdmin) input.disabled = true;
				}
			}

			// A condition when adding new admin
			else if (stat == 'admin') {
				if (empArrAdmin.includes(value)) input.checked = true;
				if (admins.includes(value)) input.checked = input.disabled = true;
			}

			label.appendChild(input);

			var span = document.createElement('span');
			span.classList.add('form-check-sign');
			span.id = value;
			span.onclick = function() {
				if (code == 'edit') addEmp(value);
				else if (stat != 2) {
					const val = document.querySelector('input[name="chkBoxAcc"][value="' + value + '"]').checked ? 0 : 1;
					modifyAccess(tema, val, npk, id);
				}
			}
			label.appendChild(span);

			cell.appendChild(label);
			tr.appendChild(cell);
		} catch (error) {
			console.error('Error:', error);
		}
	}

	function createDeleteActionCell(idname, tr) {
		var icon = document.createElement('i');
		icon.classList.add('la', 'la-trash-o');

		var a = document.createElement('a');
		a.href = 'javascript:void(0)';
		a.id = a.name = idname;
		a.classList.add('btn', 'btn-danger');
		a.appendChild(icon);

		var cell = document.createElement('td');
		cell.style.textAlign = 'center';
		cell.appendChild(a);
		tr.appendChild(cell);
	}

	async function createMultipleCells(emp, tr, id, file, part, stat) {
		await createCheckboxCell('chkBoxAcc', 'TRNACC_PART' + emp, tr, id, part, stat);
		await createCheckboxCell('chkBoxAcc', 'TRNACC_FILE' + emp, tr, id, file, stat);
		if (stat == 2) {
			createBadgeApproval('', emp, id, tr);
			document.querySelector('input[value="part' + emp + '"]').disabled = true;
			document.querySelector('input[value="file' + emp + '"]').disabled = true;
		}
	}

	function getMaxRow(tBody, rowName) {
		var rows = tBody.getElementsByTagName('tr');

		var maxId = 0;
		for (var i = 0; i < rows.length; i++) {
			var currentId = parseInt(rows[i].id.replace(rowName, ''));
			if (currentId > maxId) {
				maxId = currentId;
			}
		}
		return maxId + 1;
	}

	function validateNumericInput(input) {
		const numericRegex = /^[0-9]*$/;

		if (!numericRegex.test(input.value)) {
			document.getElementById('numericError').innerText = 'Hanya menerima angka';
		} else {
			document.getElementById('numericError').innerText = '';
		}
	}

	function validateTextOnly(input) {
		input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
	}

	function addSeparator(input) {
		let value = input.value.replace(/\D/g, '');
		value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
		input.value = value;
	}

	function restrictInput(event) {
		var pressedKey = event.key;
		var allowedCharacters = /^[a-zA-Z0-9\b\s\-\;\/\,]*$/;
		if (!allowedCharacters.test(pressedKey)) {
			event.preventDefault();
			return false;
		}
	}

	function isDataTableExist(counter, kode, colspan, idname, tbodyName) {
		const tableBody = document.getElementById(tbodyName);
		if (kode != 1) {
			for (var i = tableBody.rows.length - 1; i >= 0; i--) {
				tableBody.deleteRow(i);
			}
		}

		const existingCell = document.getElementById(idname);
		if (counter == 0) {
			if (existingCell) document.getElementById(idname).remove();
			const row = document.createElement('tr');
			const cell = document.createElement('td');
			cell.colSpan = colspan;
			cell.id = idname;
			cell.textContent = 'No data to be displayed.';
			cell.classList.add('text-center');
			row.appendChild(cell);
			tableBody.appendChild(row);
		} else if (existingCell) document.getElementById(idname).remove();
	}
</script>

<!-- Back End Connector -->
<script>
	function submitEdit(code) {
		if (code == 'training') searchKeyword('', '', 'allEmpTable');
		var form = document.getElementById('formTraining');
		var input = document.createElement('input');
		input.type = 'hidden';
		input.name = 'empSelected';
		input.value = JSON.stringify(isAdmin ? empArrAdmin : empArrNon);
		var span = document.createElement('input');
		span.type = 'hidden';
		span.name = 'tags';
		span.value = JSON.stringify(tags);
		form.appendChild(input);
		form.appendChild(span);
		form.submit();
	}

	function modifyAccess(code, value, npk, header) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status === 200) {

				} else {
					console.error('Error fetching data');
				}
			}
		};

		var params = 'code=' + encodeURIComponent(code) +
			'&value=' + encodeURIComponent(value) +
			'&npk=' + encodeURIComponent(npk) +
			'&header=' + encodeURIComponent(header);

		xhr.open('POST', '<?php echo base_url('Plus/modifyAccess/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send(params);
	}

	function modifyApproval(idDetail, npk, id, status) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				if (xhr.status === 200) {

				} else {
					console.error('Error fetching data');
				}
			}
		};
		var params = 'idDetail=' + encodeURIComponent(idDetail) +
			'&npk=' + encodeURIComponent(npk) +
			'&id=' + encodeURIComponent(id) +
			'&status=' + encodeURIComponent(status);

		xhr.open('POST', '<?php echo base_url('Training/modifyApproval/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send(params);
	}

	async function getAdmins() {
		try {
			const fetchedAdmins = await new Promise((resolve, reject) => {
				var xhr = new XMLHttpRequest();
				xhr.onreadystatechange = function() {
					if (xhr.readyState === XMLHttpRequest.DONE) {
						if (xhr.status === 200) {
							const admins = JSON.parse(xhr.responseText).map(obj => obj.AWIEMP_NPK);
							resolve(admins);
						} else {
							console.error('Error fetching data');
							reject(new Error('Error fetching data'));
						}
					}
				};

				xhr.open('GET', '<?= base_url('Plus/getAdmins/') ?>', true);
				xhr.send();
			});
			admins = fetchedAdmins;
		} catch (error) {
			console.error('Error:', error.message);
		}
	}

	async function getAccessData(npk, id) {
		try {
			const response = await fetch('<?php echo base_url('Plus/getAccessData?') ?>npk=' + npk + '&id=' + id);
			if (response.ok) {
				const access = await response.json();
				return access[0]; // Return the access data
			} else {
				console.error('Error fetching data response NOT OK');
				return null;
			}
		} catch (error) {
			console.error('Error fetching data catch:', error);
			return null;
		}
	}

	async function searchTraining(isAll, keyword, tagID) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					const data = JSON.parse(xhr.responseText);
					modifyTrainingTable(data);
				} else {
					console.error('Error fetching data');
				}
			}
		};

		xhr.open('POST', '<?php echo base_url('Plus/searchTraining/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('isAll=' + encodeURIComponent(isAll) + '&keyword=' + encodeURIComponent(keyword) + '&tag=' + encodeURIComponent(tagID));
	}

	async function filterTraining(status) {
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					const data = JSON.parse(xhr.responseText);
					modifyTrainingTable(data);
				} else {
					console.error('Error fetching data');
				}
			}
		};

		xhr.open('POST', '<?php echo base_url('Plus/filterTraining/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('status=' + status);
	}
</script>

<!-- Training Detail Section -->
<script>
	showPage(1);

	function showPage(pageNumber) {
		var cards = document.getElementById('listCardDiv').getElementsByClassName('card-item');
		var pageItems = document.getElementsByClassName('page-item');
		for (var i = 0; i < pageItems.length; i++) {
			pageItems[i].classList.remove('active');
		}
		document.querySelector('.pagination li[data-page="' + pageNumber + '"]').classList.add('active');

		for (var i = 0; i < cards.length; i++) {
			if (i >= (pageNumber - 1) * 4 && i < pageNumber * 4) {
				cards[i].classList.remove('fade-out');
				cards[i].classList.remove('hide-after-fade-out');
				cards[i].classList.add('fade-in');
			} else {
				cards[i].classList.remove('fade-in');
				cards[i].classList.add('fade-out');
				cards[i].classList.add('hide-after-fade-out');
			}
		}
	}
	async function showDetail(id) {
		rowCountMateriForm = 1;
		await getAdmins();
		var tableBody = document.getElementById('tBodyDetailEmp');
		var tableBodyDetOnly = document.getElementById('tBodyDetailOnlyEmp');
		var t = isAdmin ? document.getElementById('tBodyDetailEmp') : document.getElementById('tBodyDetailOnlyEmp');
		for (var i = t.rows.length - 1; i >= 0; i--) {
			t.deleteRow(i);
		}
		document.getElementById('readResume').value = '';
		changeForm('detail', '');

		empArrAdmin = [];
		const promises = [];
		const checked = [];
		var counterEmp = 0;
		var counterSub = 1;

		//
		var progress = 0;
		if (id != '0') {
			var formElement = document.getElementById('formTraining');
			formElement.setAttribute('action', '<?php echo base_url('Training/editTraining/') ?>' + id);

			fetch('<?= base_url('Training/showDetail/') ?>' + id)
				.then(response => {
					return response.text();
				})
				.then(response => {
					var data = JSON.parse(response);
					console.log(data);
					trStat = data.header[0].TRNHDR_STATUS;
					var packageArray = Object.values(data.package);

					// Use map() to extract the package_id attribute from each package object
					var packageIds = packageArray.map(function(package) {
						return package.TRNPCK_ID;
					});

					var packageNames = packageArray.map(function(package) {
						return package.TRNPCK_NAME;
					});

					data.employee.forEach(async function(emp) {
						console.log(resumeText + "detail");
						if (emp.STATUS != 3) {
							empArrAdmin.push(emp.NPK);
							// Data row
							var tr = document.createElement('tr');
							var idRow = document.getElementById('tBodyDetailEmp') ? 'rowForm' : 'rowFormDet';
							tr.id = idRow + emp.NPK;
							createTextCell(counterEmp + 1, tr, 'number', 'center');
							createTextCell(emp.NAMA, tr, 'text', 'left');
							createTextCell(emp.DEPARTEMEN, tr, 'text', 'left');
							createTextCell(emp.PROGRESS, tr, 'text', 'center');
							if (<?php echo $this->session->userdata['npk']; ?> == emp.NPK) {

								progress = emp.PROGRESS;
								checkPreTest(id, progress, data.header[0].TRNHDR_CATEGORY, data.resume.TRNACC_RESUME);

							} else {
								document.getElementById('examDiv').style.display = 'none';
							}

							// createTextCell(Math.round(emp.PERCENT) + '%', tr, 'text', 'center');
							const accessPromise = getAccessData(emp.NPK, id).then(acc => {
								if (isAdmin) {
									createSelectCell(packageIds, packageNames, tr, 'soalSelect' + emp.NPK, '-- Pilih Paket --');
									createMultipleCells(emp.NPK, tr, id, acc.TRNACC_FILE, acc.TRNACC_PART, emp.STATUS);
								}
							});
							t.appendChild(tr);
							counterEmp++;
							promises.push(accessPromise);
						}
					});

					Promise.all(promises).then(() => {
						if (tableBodyDetOnly) isDataTableExist(counterEmp, 1, 5, 'emptyParticipantDet', 'tBodyDetailOnlyEmp');
						if (tableBody) isDataTableExist(counterEmp, 1, 8, 'emptyParticipant', 'tBodyDetailEmp');

						$("[name^='soalSelect']").each(function() {
							$(this).on('change', function() {
								var changedValue = $(this).val(); // Retrieve the changed value
								var elementId = $(this).attr('id').substring('soalSelect'.length); // Retrieve the id of the element
								assignPackage(changedValue, elementId, data.header[0].TRNHDR_ID); // Call the 'assignPackage' function
							});
						});

					});
					document.getElementById('idTraining').value = data.header[0].TRNHDR_ID;
					// Set the value of the TRNHDR_ID as the href attribute of the anchor element
					if (data.header[0].TRNHDR_STATUS == 2) {
						var dataPreTest = null;
						if (data.pretest && data.pretest.length > 0) {
							dataPreTest = data.pretest[0].preScore;
							if (data.pretest[0].preScore != null) {
								document.getElementById('tagsPre').classList.replace('badge-danger', 'badge-success');
							}
							if (data.pretest[0].postScore != null) {
								document.getElementById('tagsPost').classList.replace('badge-danger', 'badge-success');
							}
						}

						if (dataPreTest === null || dataPreTest === undefined) {
							//	var examPrePostUrl = "<?php echo base_url('Question/getQuestExam/') ?>" + id + "/" + 1;
							document.getElementById('examPrePost').setAttribute('onclick', 'getQuestExam(' + id + ', "1")');

						} else {
							//	var examPrePostUrl = "<?php echo base_url('Question/getQuestExam/') ?>" + id + "/" + 2;
							document.getElementById('examPrePost').setAttribute('onclick', 'getQuestExam(' + id + ', "2")');

						}
					} else {
						document.getElementById('examDiv').style.display = 'none';
					}
					// document.getElementById('examPrePost').setAttribute('href', examPrePostUrl);
					document.getElementById('temaTraining').value = data.header[0].TRNHDR_TITLE;
					document.getElementById('jenis').value = data.header[0].TRNHDR_CATEGORY;
					document.getElementById('pemateri').value = data.header[0].TRNHDR_INSTRUCTOR;
					document.getElementById('editBtn').onclick = function() {
						doEdit(id, status);
					};

					var base_url = "<?= base_url('Training/modifyTraining/') ?>";
					var judul_training_header = data.header[0].TRNHDR_ID;
					if (document.getElementById('deleteBtn')) document.getElementById('deleteBtn').onclick = function() {
						confirmDeleteTraining(judul_training_header + '0');
					};
					if (document.getElementById('publishBtn')) document.getElementById('publishBtn').href = (base_url + judul_training_header) + 2;

					rowCountMateriForm = data.substance.length;
					if (isAdmin) {
						isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableDetail');

					} else {
						isDataTableExist(rowCountMateriForm, 'x', 3, 'emptyData', 'tBodySubstanceTableDetail');
					}
					if (rowCountMateriForm != 0) {
						data.substance.forEach(function(substance) {
							var tableBody = document.getElementById('tBodySubstanceTableDetail');
							var row = document.createElement('tr');
							createTextCell(counterSub, row, 'number', 'center');
							createTextCell(substance.TRNSUB_TITLE, row, 'text', 'left');
							createFileCell(id, substance.TRNSUB_ID, substance.TRNSUB_PATH, substance.TRNSUB_STATUS == 2 ? '' : 'detail', row);
							if (substance.TRNSUB_STATUS == 2) {
								createBadgeApproval(substance.TRNSUB_ID, '', '', row);
							}
							counterSub++;
							tableBody.appendChild(row);
						});
					}
					if (data.header[0].TRNHDR_CATEGORY == "Elearning") {
						document.getElementById('tagsPre').style.display = 'inline';
						document.getElementById('tagsPost').style.display = 'inline';
					} else {
						// document.getElementById('substanceDiv').style.display = 'none';
						document.getElementById('examDiv').style.display = 'none';
						document.getElementById('tagsPre').style.display = 'none';
						document.getElementById('tagsPost').style.display = 'none';
					}
					populateTagsSection(data.tags, 'detail');

					const accessData = getAccessData(<?php echo $this->session->userdata['npk']; ?>, id).then(access => {
						if (access.TRNACC_PART == 1 || access.TRNACC_FILE == 1 || isAdmin) {
							arr = ['editBtn'];
							if ((data.header[0].TRNHDR_STATUS == 1)) {
								arr.push('deleteBtn');
								arr.push('publishBtn');
							} else {
								changeDisplayOfElements('none', ['deleteBtn', 'publishBtn']);
							}
							changeDisplayOfElements('block', arr);
						}
					});
					console.log("resum" + data.resume.TRNACC_RESUME);

					if (data.resume.TRNACC_RESUME) {
						var resumeText = data.resume.TRNACC_RESUME;
						document.getElementById('readResume').value = resumeText;
						document.getElementById('tagsRes').classList.replace('badge-danger', 'badge-success');
						document.getElementById("resumeLink").innerText = "Detail Resume";
					} else {
						document.getElementById("resumeLink").innerText = "Buat Resume";
					}


				})
				.catch(error => {
					console.error('Error fetching data showdetail:', error);
				});

			console.log("sii2" + progress);
		}
	}

	async function checkPreTest(id, doneModul, category, resume) {
		if (category == "Elearning") {
			fetch('<?= base_url('Training/checkPreTest/') ?>' + id)
				.then(response => {
					return response.text();
				})
				.then(response => {
					var data = JSON.parse(response);
					console.log("ambil id" + data.pretest[0].preScore);
					var dataPreTest = null;
					if (data.pretest && data.pretest.length > 0) {
						dataPreTest = data.pretest[0].preScore;
					}
					console.log("data2" + dataPreTest);
					if (dataPreTest === null || dataPreTest === undefined) {
						document.getElementById('substanceDiv').style.display = 'none';
						document.getElementById('resumeDiv').style.display = 'none';
						document.getElementById('examDiv').style.display = 'block';
					} else {
						document.getElementById('substanceDiv').style.display = 'block';
						document.getElementById('resumeDiv').style.display = 'block';
						document.getElementById('examDiv').style.display = 'none';

						var input = doneModul;
						console.log("data" + doneModul);
						var slashIndex = input.indexOf('/');
						var a = input.substring(0, slashIndex);
						var b = input.substring(slashIndex + 1);
						console.log("var a is =", a);
						console.log("resume b is =", resume);

						if (a == b && a != 0 && resume != null) {
							document.getElementById('examDiv').style.display = 'block';
						}
					}
				})
				.catch(error => {
					console.error('Error fetching data showdetail:', error);
				});
		} else {
			document.getElementById('examDiv').style.display = 'none';
		}
	}


	function getQuestExam(id, PreOrPost) {
		Swal.fire({
			title: 'Konfirmasi Pelaksanaan Tes',
			text: 'Anda tidak dapat mengulangi tes!',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya',
			cancelButtonText: 'Tidak'
		}).then((result) => {
			if (result.isConfirmed) {
				//	window.location.href = '<?= base_url('Question/getQuestExam/') ?>' + id + "/" + PreOrPost;
				if (result.isConfirmed) {
					var hashedId = CryptoJS.MD5(id.toString()).toString();
					var hashedPreOrPost = CryptoJS.MD5(PreOrPost.toString()).toString();
					window.location.href = '<?= base_url('Question/getQuestExam/') ?>' + hashedId + "/" + hashedPreOrPost;
				}

			}
		});
	}

	async function toggleTab(tabName) {
		document.getElementById('search_training').value = '';
		document.getElementById('ddTags').textContent = 'ALL';
		document.getElementById('ddTags').name = '';
		const firstToggleElement = document.querySelector('input[onchange="toggleMine(this.checked);"]');
		if (firstToggleElement) {
			firstToggleElement.parentNode.classList.remove('btn-info');
			firstToggleElement.parentNode.classList.add('btn-default', 'off');
		}
		document.getElementById('myTraining').checked = false;
		activateClassActive(tabName);
		status = '';
		if (tabName == 'all') status = '> 0';
		else if (tabName == 'published') status = '= 2';
		else if (tabName == 'draft') status = '= 1';
		else if (tabName == 'allWithRequest') status = '> x';
		await filterTraining(status);
	}

	function modifyTrainingTable(trainings) {
		const container = document.getElementById('trainingContainer');
		const paging = document.getElementById('pagingContainer');
		container.innerHTML = '';
		paging.innerHTML = '';
		var counter = 1;
		trainings.forEach((t, index) => {
			const cardHTML = `
				<div class="col-sm-3 card-item ${counter <= 4 ? 'fade-in' : 'fade-out hide-after-fade-out'}">
					<div class="card card-flip" onclick="showDetail(${t.TRNHDR_ID})" style="border-radius: 20px;">
						<div class="card-header remove-border-bottom">
							<img src="assets/img/picLog.png" class="img-fluid mobile-image" style="width: 100%">
							<div class="row overlay-content" style="width: 100%">
								<div class="col-sm-6">
								${t.TRNHDR_STATUS === 2 ? `
									<span class="badge badge-success">Published</span>`
								: '<span class="badge badge-warning">Draft</span>'}
								</div>
								<div class="col-sm-6 justify-content-end d-flex">
								${t.detail_request == "true" || t.participant_request == "true" ? 
									'<?php if ($this->session->userdata("role") == "admin") { ?>'+	
									'<span class="badge badge-warning">!</span>'+
									'<?php } ?>' : ''}
								</div>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col">
									<h4 class="card-title forum-class">${t.TRNHDR_TITLE}</h4>
									<p class="card-category">${t.detail_count} materi</p>
									<p class="card-category">${t.participant_count} partisipan</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			`;

			container.innerHTML += cardHTML;
			counter++;
		});

		if (trainings.length == 0) {
			const cardHTML = `
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
											<h4 class="card-title">Tidak ada data training!</h4>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			`;

			container.innerHTML = cardHTML;
		}

		const paginationHTML = `
			<div class="col align-items-center justify-content-center d-flex">
				<ul class="pagination pg-primary">
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Previous">
							<span aria-hidden="true">«</span>
							<span class="sr-only">Previous</span>
						</a>
					</li>
					${trainings.map((t, index) => {
						if (index % 4 == 0) {
							return `
								<li class="page-item ${Math.ceil(index / 4) + 1 == 1 ? 'active' : ''}" data-page="${Math.ceil(index / 4) + 1}">
									<a class="page-link" href="javascript:void(0)" onclick="showPage(${Math.ceil(index / 4) + 1})">${Math.ceil(index / 4) + 1}</a>
								</li>
							`;
						}
					}).join('')}
					<li class="page-item">
						<a class="page-link" href="#" aria-label="Next">
							<span aria-hidden="true">»</span>
							<span class="sr-only">Next</span>
						</a>
					</li>
				</ul>
			</div>
		`;

		document.getElementById('pagingContainer').innerHTML += paginationHTML;
	}

	async function tagFilter(id, name) {
		document.getElementById('ddTags').textContent = name;
		document.getElementById('ddTags').name = id;
		if (isAdmin) activateClassActive('all');
		await searchTraining(!document.getElementById('myTraining').checked, document.getElementById('search_training').value.trim(), id);
	}

	async function toggleMine(select) {
		if (isAdmin) activateClassActive('all');
		await searchTraining(!select, document.getElementById('search_training').value.trim(), document.getElementById('ddTags').name.trim());
	}

	async function searchByKey(element) {
		if (isAdmin) activateClassActive('all');
		await searchTraining(!document.getElementById('myTraining').checked, element.value.trim(), document.getElementById('ddTags').name.trim());
	}
</script>

<!-- Participant Section -->
<script>
	function toggleAll(select) {
		var checkboxes = document.querySelectorAll('.form-check-input');
		checkboxes.forEach(checkbox => {
			if (!checkbox.disabled) {
				var index = empArrAdmin.indexOf(checkbox.value);
				checkbox.checked = select;
				if (!select) {
					empArrAdmin.splice(index, 1);
				} else if (!empArrAdmin.includes(checkbox.value)) {
					empArrAdmin.push(checkbox.value);
				}
			}
		});
	}

	document.getElementById('search_keyword').addEventListener('keyup', function() {
		var keyword = this.value.trim();
		searchKeyword(keyword, '', 'allEmpTable');
	});

	function searchKeyword(name, dept, tableName) {
		if (dept != '') {
			document.getElementById('dropdownMenu1').textContent = dept;
		}
		if (dept == '') dept = document.getElementById('dropdownMenu1').textContent.trim();
		if (name == '') name = document.getElementById('search_keyword') ? document.getElementById('search_keyword').value.trim() :
			document.getElementById('search_employee').value.trim();
		var xhr = new XMLHttpRequest();
		xhr.onreadystatechange = function() {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					var filteredData = JSON.parse(xhr.responseText);
					populateTable(filteredData.employees, tableName);
				} else {
					console.error('Error fetching data');
				}
			}
		};

		xhr.open('POST', '<?php echo base_url('Plus/searchEmployee/') ?>', true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.send('search_employee=' + encodeURIComponent(name) + '&code=' + encodeURIComponent(dept));
	}

	function populateTable(filteredData, tableName) {
		var tableBody = document.getElementById(tableName).querySelector('tbody');
		tableBody.innerHTML = ''; // Clear existing table content
		var i = 1;
		filteredData.forEach(function(row) {
			var tr = document.createElement('tr');
			Object.keys(row).forEach(function(key) {
				if (key === 'NPK' || key === 'NAMA' || key === 'DEPARTEMEN') {
					var td = document.createElement('td');
					if (key === 'NPK') td.textContent = i;
					else td.textContent = row[key];
					tr.appendChild(td);
					if (key === 'DEPARTEMEN') {
						createCheckboxCell('chkBoxemp[]', row['NPK'], tr, isAdmin ? '' : 'non', 'edit', tableName.includes('Admin') ? 'admin' : '');
					}
				}
			});
			tableBody.appendChild(tr);
			i++;
		});
		isDataTableExist(filteredData.length, 1, 4, 'emptyParticipant', tableName);
	}

	async function addEmp(id) {
		if (isAdmin) {
			var index = empArrAdmin.indexOf(id);
			if (index !== -1) {
				empArrAdmin.splice(index, 1);
			} else {
				empArrAdmin.push(id);
			}
		} else {
			var index = empArrNon.indexOf(id);
			if (index !== -1) {
				empArrNon.splice(index, 1);
			} else {
				empArrNon.push(id);
			}
		}
	}
</script>

<!-- Substance Section -->
<script>
	var no = 0;

	function addRow() {
		var tableBody = document.getElementById('tBodySubstanceTableEdit');
		var tableBody2 = document.getElementById('tBodySubstanceTableEdit2');

		var materiRow = document.createElement('tr');
		var idNow = getMaxRow(tableBody, 'rowFormMateri');
		var idNow2 = getMaxRow(tableBody2, 'rowFormMateri');
		console.log(idNow2);
		var no1 = 0;
		idNow3 = idNow2 + no1;

		if (idNow2 < 2) {
			console.log("sdf");
		} else {
			if (no == 0 || idNow < idNow2) {
				no = no + 1;
				var idNow4 = idNow3;
				idNow = idNow4;
			}
		}

		materiRow.id = 'rowFormMateri' + idNow;
		createInputCell('materiTitle' + idNow, 'text', 'Masukkan judul materi...', materiRow);
		createInputCell('materiFile' + idNow, 'file', '', materiRow);
		createDeleteActionCell('deleteRowSubstance' + idNow, materiRow);

		rowCountMateriForm++;
		isDataTableExist(rowCountMateriForm, 1, 4, 'emptyData', 'tBodySubstanceTableEdit');
		tableBody.appendChild(materiRow);

		document.getElementById('deleteRowSubstance' + idNow).onclick = function() {
			removeRow(idNow);
		}
	}

	function removeRow(id) {
		document.getElementById('rowFormMateri' + id).remove();
		rowCountMateriForm--;
		isDataTableExist(rowCountMateriForm, 1, 4, 'emptyData', 'tBodySubstanceTableEdit');
	}
</script>

<!-- Tags Section -->
<script>
	function mouseIn(id, color) {
		var hoverDiv = document.getElementById(id);
		var rgb = parseInt(color.slice(1), 16);
		var r = (rgb >> 16) & 0xff;
		var g = (rgb >> 8) & 0xff;
		var b = (rgb >> 0) & 0xff;

		r = Math.max(0, r - 20);
		g = Math.max(0, g - 20);
		b = Math.max(0, b - 20);

		hoverDiv.style.backgroundColor = 'rgba(' + r + ',' + g + ',' + b + ', 0.7)';
	}

	function mouseOut(id, color) {
		var hoverDiv = document.getElementById(id);
		hoverDiv.style.backgroundColor = color;
	}

	function isColorLight(hexColor) {
		const r = parseInt(hexColor.substr(1, 2), 16);
		const g = parseInt(hexColor.substr(3, 2), 16);
		const b = parseInt(hexColor.substr(5, 2), 16);
		const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;

		if (luminance > 0.7) {
			return '#000000';
		} else {
			return '#ffffff';
		}
	}

	function populateTagsSection(data, code) {
		const container = document.getElementById('tagsContainer');
		container.innerHTML = '';
		if (code == 'clear') tags = [];
		data.forEach(function(tag) {
			var col = isColorLight(tag.TRNLBL_COLOR);
			// trStat == 2 ? '' :
			const quer = `onclick="addTags('tags${tag.TRNLBL_ID}')"`;
			const cardHTML = `
				<span class="badge tags" id="tags${tag.TRNLBL_ID}" style="background-color: ${tag.TRNLBL_COLOR}; color: ${col}; border-color: white;" ` + quer + `
				onmouseover="mouseIn('tags${tag.TRNLBL_ID}', '${tag.TRNLBL_COLOR}')" onmouseout="mouseOut('tags${tag.TRNLBL_ID}', '${tag.TRNLBL_COLOR}')">${tag.TRNLBL_NAME}</span>
			`;
			if (code == 'detail') tags.push(tag.TRNLBL_ID);
			container.innerHTML += cardHTML;
		});
	}

	function addTags(id) {
		if (!document.getElementById('cardTitle').textContent.toLowerCase().includes('detail')) {
			var span = document.getElementById(id);
			var index = tags.indexOf(parseInt(id.substr("tags".length)));
			if (index !== -1) {
				tags.splice(index, 1);
				span.style.borderColor = 'white';
			} else {
				tags.push(parseInt(id.substr("tags".length)));
				span.style.borderColor = 'blue';
			}
		}
	}
</script>

<!-- Training Edit -->
<script>
	document.getElementById('temaTraining').addEventListener('keyup', function() {
		if (this.value.trim() != '') clearTema(document.getElementById('temaTraining'));
	});

	async function doEdit(id) {
		let npk = '<?php echo $this->session->userdata('npk'); ?>';

		const sAccElements = document.querySelectorAll('[id^="sAcc"]');
		const sRejElements = document.querySelectorAll('[id^="sRej"]');

		if (sAccElements.length > 0 || sRejElements.length > 0) {
			Swal.fire({
				title: 'ERROR',
				text: 'Masih ada permintaan modifikasi. Mohon cek semua modifikasi!',
				icon: 'error',
				confirmButtonColor: '#d33',
				confirmButtonText: 'OK'
			});
			return;
		}

		const accessData = getAccessData(npk, id).then(async access => {
			if (!(access.TRNACC_PART == 1 || access.TRNACC_FILE == 1 || isAdmin)) {
				Swal.fire({
					title: 'ERROR',
					text: 'Anda mengakses menu terlarang. Silakan refresh halaman!',
					icon: 'error',
					confirmButtonColor: '#d33',
					confirmButtonText: 'OK'
				});
				return;
			} else {
				await checkAccess(access.TRNACC_PART, access.TRNACC_FILE);
			}
		});

		changeForm('edit');
		rowCountMateriForm = 0;
		var counterSub = 1;
		var idHeader = document.getElementById('idTraining').value;

		empArrAdmin.forEach(function(emp) {
			const chb = document.querySelector('input[value="' + emp + '"]');
			chb.checked = true;
			if (!isAdmin) chb.disabled = true;
		});

		empArrNon.forEach(function(emp) {
			const chb = document.querySelector('input[value="' + emp + '"]');
			chb.checked = true;
		});

		isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableEdit2');
		isDataTableExist(rowCountMateriForm, 'x', 4, 'emptyData', 'tBodySubstanceTableEdit');
		var tableBody = document.getElementById('tBodySubstanceTableEdit2');
		<?php echo $combinedDataJSON ?>.forEach(function(substance) {
			if (substance.id_header == idHeader && substance.status == 1) {
				var materiRow = document.createElement('tr');
				var idNow = rowCountMateriForm + 1;
				materiRow.id = 'rowFormMateri' + idNow;

				// createTextCell(idNow, materiRow, 'number', 'center');
				createInputCell('materiId' + idNow, 'hidden', substance.id_detail, materiRow);
				createTextCell(substance.title, materiRow, 'text', 'left')
				createFileCell(id, substance.id_detail, substance.path, '', materiRow);
				createDeleteActionCell('deleteRowSubstanceCommit' + idNow, materiRow);

				rowCountMateriForm++;
				isDataTableExist(rowCountMateriForm, 1, 4, 'emptyData', 'tBodySubstanceTableEdit');
				tableBody.appendChild(materiRow);

				document.getElementById('deleteRowSubstanceCommit' + idNow).onclick = function() {
					removeRow(idNow);
				}
			}
		});


		populateTagsSection(<?php echo json_encode($tags) ?>, 'edit');
		tags.forEach(function(tag) {
			document.getElementById('tags' + tag).style.borderColor = 'blue';
		});
	}

	async function checkAccess(part, file) {
		if (isAdmin) return;
		if (part == 0) {
			changeDisplayOfElements('none', ['allEmpDiv']);
		}
		if (file == 0) {
			changeDisplayOfElements('none', ['substanceDiv']);
		}
	}

	function validateForm() {
		var inputFields = [
			'temaTraining'
		];
		var errorMessages = document.getElementById('errorMessages');

		if (!errorMessages) {
			console.error("Error: 'errorMessages' element not found.");
			return;
		}

		var errors = [];

		inputFields.forEach(function(fieldId) {
			var fieldValue = document.getElementById(fieldId).value.trim();
			var fieldElement = document.getElementById(fieldId);

			if (fieldValue === '') {

				fieldElement.style.borderColor = 'red';
				var label = document.querySelector('label[for="' + fieldId + '"]');
				var labelText = label ? label.textContent.trim() : '' + fieldId;
				errors.push(labelText);
				errorMessages.textContent = '* Tema training wajib diisi!';
				document.getElementById('temaTraining').classList.remove('mb-3');
			} else {
				fieldElement.style.borderColor = ''; // Reset border
			}
		});

		if (errors.length > 0) {
			// Handle error messages as needed
			document.body.scrollIntoView({
				behavior: 'smooth',
				block: 'start'
			});
		} else {
			errorMessages.textContent = ''; // Clear error messages
			submitEdit('training');
		}
	}
</script>

<script>
	function showPForm(id) {
		document.getElementById('formPackage').reset();
		createTableQuestion(0);
		document.getElementById('titlePackage').textContent = id == 'x' ? 'Tambah Paket' : 'Edit Paket';
		document.getElementById('navPackage').textContent = 'Soal / ' + (id == 'x' ? 'Tambah Paket Soal' : 'Edit Paket Soal');
		if (id != 'x') {
			fetch('<?= base_url('Question/getPackage/') ?>' + id)
				.then(response => response.json())
				.then(data => {
					var package = data['package'];
					var questions = data['questions'];
					console.log(questions);
					document.getElementById('idUniqPaket').value = package['TRNPCK_UNIQUEID'];
					document.getElementById('namePaket').value = package['TRNPCK_NAME'];
					document.getElementById('chooseTrain').value = package['TRNHDR_ID'];
					document.getElementById('package_id').value = package['TRNPCK_ID'];
					document.getElementById('decider').value = questions.length;
					valuesArray = [];
					createTableQuestion(questions.length);
					for (var i = 0; i < questions.length; i++) {
						fields.forEach(function(field) {
							var elementId = field.id + (i + 1);
							var element = document.getElementById(elementId);

							if (element && questions.length > 0) {
								var propertyName = Object.keys(questions[0]).find(function(key) {
									return key === field.id;
								});

								if (propertyName) {
									element.value = questions[i][propertyName];
									element.setAttribute('readonly', true);
									(function() {
										if (element) {
											element.oninput = function() {
												removeStyle(element);
											};
										}
									})();
								}
							}
						});
						document.getElementById('TRNQUE_ID' + (i + 1)).value = questions[i]['TRNQUE_ID'];
					}
					editBtnPackage.onclick = function() {
						doEditPackage(questions.length);
					};
				})
				.catch(error => {
					console.error('Error:', error);
				});
		}

		header.forEach(function(field) {
			var element = document.getElementById(field);
			element.oninput = function() {
				removeStyle(element);
			};
		});
		changePForm('modify');
		document.getElementById('scrollableDiv').scrollTop = 0;
	}

	var fields = [{
			id: 'TRNQUE_ID',
			label: 'ID Pertanyaan'
		},
		{
			id: 'TRNQUE_LEVEL',
			label: 'Level Pertanyaan'
		},
		{
			id: 'TRNQUE_ANSWER',
			label: 'Jawaban Benar'
		},
		{
			id: 'TRNQUE_QUESTION',
			label: 'Pertanyaan'
		},
		{
			id: 'TRNQUE_AOPT',
			label: 'Pilihan A'
		},
		{
			id: 'TRNQUE_BOPT',
			label: 'Pilihan B'
		},
		{
			id: 'TRNQUE_COPT',
			label: 'Pilihan C'
		},
		{
			id: 'TRNQUE_DOPT',
			label: 'Pilihan D'
		}
	];

	var header = ['idUniqPaket', 'namePaket', 'chooseTrain', 'decider'];

	function changePForm(code) {
		header.forEach(function(field) {
			var element = document.getElementById(field);
			removeStyle(element);
		});
		document.getElementById('packagePage').style.display = (code == 'main') ? 'block' : 'none';
		document.getElementById('modifyPackagePage').style.display = (code == 'modify') ? 'block' : 'none';
		callLoader();
	}

	function removeStyle(inputElement) {
		inputElement.removeAttribute("style");
	}

	function validatePForm() {
		var max = document.getElementById('decider').value;
		var next = true;
		fields.forEach(function(field) {
			for (var i = 1; i <= max; i++) {
				var elementId = field.id + i;
				var element = document.getElementById(elementId);

				if (!elementId.includes('TRNQUE_ID') && (element.value == '' || element.value == 'default')) {
					element.style.borderColor = 'red';
					next = false;
				}
			}
		});

		header.forEach(function(field) {
			var element = document.getElementById(field);
			if (element.value == '' || element.value == 'default') {
				element.style.borderColor = 'red';
				next = false;
			}
		});

		if (next) {
			var form = document.getElementById('formPackage');
			var method = document.getElementById('package_id').value == '' ? 'savePackage' : 'editPackage';
			var newActionURL = '<?php echo base_url('Question/') ?>' + method;
			form.setAttribute('action', newActionURL);
			form.submit();
		} else {
			Swal.fire({
				title: 'Error',
				text: 'Silakan lengkapi data!',
				icon: 'error',
				confirmButtonColor: '#3085d6',
				confirmButtonText: 'OK'
			});
		}
	}

	function deletePackage(id) {
		Swal.fire({
				title: 'Apakah Anda Yakin?',
				text: 'Anda akan menghapus data permanen!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya!'
			})
			.then((result) => {
				if (result.isConfirmed) {
					var url = '<?php echo base_url("Question/deletePackage/"); ?>' + id;

					fetch(url, {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json',
							},
						})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								Swal.fire({
										title: 'Dihapus',
										text: 'Paket Soal berhasil dihapus',
										icon: 'success',
										confirmButtonColor: '#3085d6',
										confirmButtonText: 'OK'
									})
									.then((result) => {
										if (result.isConfirmed) {
											window.location.reload();
										}
									});
							} else {
								console.log('Deletion failed:', data.error);
							}
						})
						.catch(error => {
							console.error('Error:', error);
						});
				}
			});
	}

	function doEditPackage(max) {
		header.forEach(function(field) {
			var element = document.getElementById(field);
			element.removeAttribute('readonly');
			element.oninput = function() {
				removeStyle(element);
			};
		});

		document.getElementById("generatePackage").disabled = false;
		document.getElementById("generatePackage").disabled = false;
		document.getElementById("submitPackage").style.display = "block";
		document.getElementById("btnDetailPackage").style.display = "none";
		generateQuestionRows();
		var max = document.getElementById('decider').value;

		fields.forEach(function(field) {
			for (var i = 1; i <= max; i++) {
				var elementId = field.id + i;
				var element = document.getElementById(elementId);

				if (element && !valuesArray.some(entry => entry.id === elementId)) {
					valuesArray.push({
						id: elementId,
						value: element.value
					});
				}
			}
		});

	}

	function callLoader() {
		var loader = document.getElementById('loaderDiv');
		setTimeout(function() {
			loader.classList.add('fade-out');
			setTimeout(function() {
				loader.style.display = 'none';
			}, 500);
		}, 500);
		loader.style.display = '';
		loader.classList.remove('fade-out');
	}

	function saveResume() {
		var formElements = document.getElementById("formResume");
		formElements.submit();
	}

	function doUpdateResume() {
		document.getElementById("textResume").readOnly = false;
		document.getElementById("submitBtnResume").style.display = 'block';
	}


	function makeFormResume() {
		document.getElementById("secondaryBtn").style.display = 'block';
		document.getElementById("resume").style.display = 'block';

		document.getElementById("formTraining").style.display = 'none';
		var formElement = document.getElementById('formResume');
		var idHeader = document.getElementById('idTraining').value;
		var resumeValue = document.getElementById('readResume').value;
		console.log(resumeValue + "isi");
		// if (resumeValue == null || resumeValue.trim() === '') {
		// 	console.log("el");
		// } else {

		console.log("resumeText");
		document.getElementById("divResumeBtn").style.display = 'block';
		document.getElementById('textResume').value = resumeValue;
		// var formElement = document.getElementById('formResume');
		// formElement.setAttribute('action', '<?php echo base_url('Training/modifyResume/') ?>' + idHeader);

		// }
		formElement.setAttribute('action', '<?php echo base_url('Training/modifyResume/') ?>' + idHeader);
	}

	function back2Form() {
		document.getElementById("resume").style.display = 'none';
		document.getElementById("formTraining").style.display = 'block';
		var formElement = document.getElementById('formResume');
		formElement.removeAttribute('action');

	}

	function delResume() {
		Swal.fire({
				title: 'Apakah Anda ingin menghapus Resume?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Ya'
			})
			.then((result) => {
				if (result.isConfirmed) {
					document.getElementById('textResume').value = null;
					var url = '<?php echo base_url("Training/modifyResume/"); ?>' + id;

					fetch(url, {
							method: 'POST',
							headers: {
								'Content-Type': 'application/json',
							},
						})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								Swal.fire({
										title: 'Resume berhasil dihapus',
										icon: 'success',
										confirmButtonColor: '#3085d6',
										confirmButtonText: 'OK'
									})
									.then((result) => {
										if (result.isConfirmed) {
											window.location.reload();
										}
									});
							} else {
								console.log('Deletion failed:', data.error);
							}
						})
						.catch(error => {
							console.error('Error:', error);
						});
				}
			});
	}

	var valuesArray = [];

	function createTableQuestion(max) {
		var tableBody = document.getElementById('tBodyAllSoal');
		tableBody.innerHTML = '';
		for (var i = 1; i <= max; i++) {
			var tr = document.createElement('tr');
			tr.id = 'rowSoal' + i;

			createTextCell(i, tr, 'number', 'center');
			createInputCell('TRNQUE_QUESTION' + i, 'textarea', '', tr);
			createInputCell('TRNQUE_ID' + i, 'hidden', '', tr);
			createSelectCell(['Low', 'Medium', 'High'], ['Low', 'Medium', 'High'], tr, "TRNQUE_LEVEL" + i, "--");
			createSelectCell(['A', 'B', 'C', 'D'], ['A', 'B', 'C', 'D'], tr, "TRNQUE_ANSWER" + i, "--");
			createInputCell('TRNQUE_AOPT' + i, 'textarea', '', tr);
			createInputCell('TRNQUE_BOPT' + i, 'textarea', '', tr);
			createInputCell('TRNQUE_COPT' + i, 'textarea', '', tr);
			createInputCell('TRNQUE_DOPT' + i, 'textarea', '', tr);
			tableBody.appendChild(tr);
		}

		isDataTableExist(max, 1, 8, 'emptyData', 'tBodyAllSoal');
	}

	function assignPackage(value, npk, id) {
		$.ajax({
			url: 'Training/updateTRNPCK', // Replace ControllerName with your actual controller name
			method: 'POST',
			data: {
				AWIEMP_NPK: npk,
				TRNHDR_ID: id,
				TRNPCK_ID: value
			},
			success: function(response) {
				console.log(response);
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
			}
		});
	}

	function generateQuestionRows() {
		var max = document.getElementById('decider').value;

		fields.forEach(function(field) {
			for (var i = 1; i <= max; i++) {
				var elementId = field.id + i;
				var element = document.getElementById(elementId);

				if (element && !valuesArray.some(entry => entry.id === elementId)) {
					valuesArray.push({
						id: elementId,
						value: element.value
					});
				}
			}
		});

		createTableQuestion(max);

		fields.forEach(function(field) {
			for (var i = 0; i <= max; i++) {
				(function() {
					var elementId = field.id + i;
					var element = document.getElementById(elementId);

					if (element) {
						element.oninput = function() {
							removeStyle(element);
						};
					}
				})();
			}
		});

		valuesArray.forEach(function(entry) {
			var element = document.getElementById(entry.id);
			if (element) {
				element.value = entry.value;
			}
		});

		console.log(valuesArray);
	}

	document.getElementById('decider').addEventListener('input', function(event) {
		var value = parseInt(this.value);
		if (value < 0) {
			this.value = 0;
		} else if (value > 50) {
			this.value = 50;
		} else if (isNaN(value)) {
			this.value = '';
		}
	});
</script>