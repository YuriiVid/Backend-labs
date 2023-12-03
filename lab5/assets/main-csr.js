const dataTable = document.getElementById('dataTable');
const pageHeader = document.getElementById('pageHeader');
const manufacturerForm = document.getElementById('manufacturerForm');
const propertyForm = document.getElementById('propertyForm');
const waterMeterForm = document.getElementById('waterMeterForm');
const waterMeterLink = document.getElementById('waterMeterLink');
const manufacturerLink = document.getElementById('manufacturerLink');
const propertyLink = document.getElementById('propertyLink');
const logoutLink = document.getElementById('logoutLink');
const manufacturerSelect = document.getElementById('waterMeterManufacturerInput');
const contentContainer = document.getElementById('contentContainer');
const loginContainer = document.getElementById('loginContainer');
const loginForm = document.getElementById('loginForm');
const loginErrorText = document.getElementById('loginErrorText');
const propertyInputContainer = document.getElementById('propertyInputContainer');
function checkLogin() {
	fetch('http://localhost/labs/lab5/app/api/loginController.php')
		.then((response) => {
			return response.json();
		})
		.then((data) => {
			console.log(data);
			if (data.userlogin == '') {
				loginContainer.style.display = 'flex';
			} else {
				getManufacturers();
				getProperties();
				getWaterMeters();
				contentContainer.style.display = 'flex';
			}
		});
}
function getManufacturers() {
	fetch('http://localhost/labs/lab5/app/api/manufacturerController.php')
		.then((response) => {
			return response.json();
		})
		.then((data) => {
			[].forEach.call(document.querySelectorAll('.app-form'), function (el) {
				el.style.display = 'none';
			});
			manufacturerForm.style.display = 'block';
			pageHeader.innerText = 'Виробники';
			let content = ``;
			let selectContent = `<option selected disabled hidden>Виберіть виробника</option>`;
			for (let i = 0; i < data.length; i++) {
				selectContent += `<option value="` + data[i].name + `">` + data[i].name + `</option>`
				content += `<tr>
                        <td>`+ data[i].id + `</td>
                        <td>`+ data[i].name + `</td>
            </tr>`;
			}
			dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Назва</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
			manufacturerSelect.innerHTML = selectContent;
		});
}
function getProperties() {
	fetch('http://localhost/labs/lab5/app/api/propertyController.php')
		.then((response) => {
			return response.json();
		})
		.then((data) => {
			[].forEach.call(document.querySelectorAll('.app-form'), function (el) {
				el.style.display = 'none';
			});
			propertyForm.style.display = 'block';
			pageHeader.innerText = 'Властивості';
			let content = ``;
			let selectFieldsContent = `<h3>Характеристики </h3>`;
			for (let i = 0; i < data.length; i++) {
				let valueContent = ``;
				selectFieldsContent += `<p><select class="prop-select" name="property_` + data[i].id + `">` + `
				<option selected disabled hidden>`+ data[i].name + ` </option>`;
				for (const [key, value] of Object.entries(data[i].values)) {
					valueContent += value + `</br>`;
					selectFieldsContent += `<option value="` + value + `">` + value + `</option>`
				}
				selectFieldsContent += `</select></p>`
				propertyInputContainer.innerHTML = selectFieldsContent;
				content += `<tr>
                        <td>`+ data[i].id + `</td>
                        <td>`+ data[i].name + `</td>
                        <td>`+ valueContent + `</td>
            </tr>`;

			}
			dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Значення</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
		});
}
function getWaterMeters() {
	fetch('http://localhost/labs/lab5/app/api/waterMeterController.php')
		.then((response) => {
			return response.json();
		})
		.then((data) => {
			//console.log(data);
			[].forEach.call(document.querySelectorAll('.app-form'), function (el) {
				el.style.display = 'none';
			});
			waterMeterForm.style.display = 'block';
			pageHeader.innerText = 'Лічильники води';
			let content = ``;
			for (let i = 0; i < data.length; i++) {
				let propertyContent = ``;
				for (const [key, value] of Object.entries(data[i].properties)) {
					propertyContent += key + `: ` + value + `</br>`;
				}
				content += `<tr>
                        <td>`+ data[i].id + `</td>
                        <td>`+ data[i].name + `</td>
                        <td>`+ data[i].manufacturer + `</td>
                        <td>`+ data[i].price + `</td>
                        <td>`+ propertyContent + `</td>
            </tr>`;
			}
			dataTable.innerHTML = `<thead>
                                <th>ID</th>
                                <th>Назва</th>
                                <th>Виробник</th>
                                <th>Ціна</th>
                                <th>Характеристики</th>
                            </thead>
                            <tbody>
                                `+ content + `
                            </tbody>`;
		});
}
checkLogin();

manufacturerForm.addEventListener("submit", (event) => {
	event.preventDefault();
	let manufacturerName = document.getElementById('manufacturerNameInput').value;
	let formData = new FormData();
	formData.append('name', manufacturerName);
	fetch("http://localhost/labs/lab5/app/api/manufacturerController.php",
		{
			body: formData,
			method: "POST"
		}).then(() => {
			manufacturerForm.reset();
			getManufacturers();
		});
});
propertyForm.addEventListener("submit", (event) => {
	event.preventDefault();
	let propertyName = document.getElementById('propertyNameInput').value;
	let propertyValues = document.getElementById('propertyValuesInput').value;
	let formData = new FormData();
	formData.append('name', propertyName);
	formData.append('values', propertyValues);
	fetch("http://localhost/labs/lab5/app/api/propertyController.php",
		{
			body: formData,
			method: "POST"
		}).then(() => {
			propertyForm.reset();
			getProperties();
		});
});
waterMeterForm.addEventListener("submit", (event) => {
	event.preventDefault();
	let waterMeterName = document.getElementById('waterMeterNameInput').value;
	let waterMeterManufacturer = document.getElementById('waterMeterManufacturerInput').value;
	let waterMeterPrice = document.getElementById('waterMeterPriceInput').value;
	let formData = new FormData();
	formData.append('name', waterMeterName);
	formData.append('manufacturer', waterMeterManufacturer);
	formData.append('price', waterMeterPrice);
	var propSelects = document.getElementsByClassName('prop-select');
	for (var i = 0; i < propSelects.length; i++) {
		formData.append(propSelects[i].getAttribute('name'), propSelects[i].value);
	}
	fetch("http://localhost/labs/lab5/app/api/waterMeterController.php",
		{
			body: formData,
			method: "POST"
		}).then(() => {
			waterMeterForm.reset();
			getWaterMeters();
		});
});
loginForm.addEventListener("submit", (event) => {
	event.preventDefault();
	let login = document.getElementById('loginInput').value;
	let password = document.getElementById('passwordInput').value;
	let formData = new FormData();
	formData.append('login', login);
	formData.append('password', password);
	fetch("http://localhost/labs/lab5/app/api/loginController.php",
		{
			body: formData,
			method: "POST"
		}).then((response) => {
			return response.json();
		})
		.then((data) => {
			loginForm.reset();
			if (data.error == '') {
				loginContainer.style.display = 'none';
				contentContainer.style.display = 'flex';
				getManufacturers();
				getProperties();
				getWaterMeters();
			} else {
				loginErrorText.innerText = data.error;
			}
		});
});
waterMeterLink.addEventListener("click", (event) => {
	event.preventDefault();
	getWaterMeters();
});
manufacturerLink.addEventListener("click", (event) => {
	event.preventDefault();
	getManufacturers();
});
propertyLink.addEventListener("click", (event) => {
	event.preventDefault();
	getProperties();
});
logoutLink.addEventListener("click", (event) => {
	event.preventDefault();
	fetch('http://localhost/labs/lab5/app/api/loginController.php?action=logout')
		.then((response) => {
			return response.json();
		})
		.then((data) => {
			loginContainer.style.display = 'flex';
			contentContainer.style.display = 'none';
			loginErrorText.innerText = '';
		});
});