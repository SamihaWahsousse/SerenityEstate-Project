async function Init(idProperty) {
	// handle select, drag and drop events
	let fileSelect = document.getElementById("file-upload");
	let fileDrag = document.getElementById("file-drag");
	fileSelect.addEventListener("change", fileSelectHandler);
	fileDrag.addEventListener("dragover", fileDragHover);
	fileDrag.addEventListener("dragleave", fileDragHover);
	fileDrag.addEventListener("drop", fileSelectHandler);

	document.getElementById("messages").classList.add("hidden");
	document.getElementById("loaded-images").innerHTML = null;

	// get a valid token for the user
	await refreshToken();

	// load property pictures
	displayPictures(myToken, idProperty);
}

function fileSelectHandler(e) {
	// Fetch FileList object
	var files = e.target.files || e.dataTransfer.files;

	// Cancel event and hover styling
	fileDragHover(e);

	// Process all File objects
	for (var i = 0, f; (f = files[i]); i++) {
		var isFileParsed = parseFile(f);
		if (!isFileParsed) {
			break;
		}
	}
}

function fileDragHover(e) {
	var fileDrag = document.getElementById("file-drag");
	e.stopPropagation();
	e.preventDefault();
	fileDrag.className =
		e.type === "dragover" ? "hover" : "modal-body file-upload";
}

function output(msg, successOrErrorClass) {
	var messagesElement = document.getElementById("messages");
	messagesElement.classList.remove("hidden");
	messagesElement.className = "";
	messagesElement.classList.add(successOrErrorClass);
	messagesElement.innerHTML = msg;
}

function parseFile(file) {
	let imageName = file.name;
	let isImage = /\.(?=jpg|png|jpeg)/gi.test(imageName); //

	if (isImage) {
		let fileSizeLimit = 2 * 1024 * 1024; // In MB

		// Check if file is less than x MB
		if (file.size <= fileSizeLimit) {
			addNewImage(file);
			return true;
		} else {
			output(
				"Please upload a smaller file (< " + fileSizeLimit + " MB).",
				"text-danger"
			);
			return false;
		}
	} else {
		output(
			"The selected file is not an image! Please select an image.",
			"text-danger"
		);
		return false;
	}
}

// add the picture to the section new image to upload
function addNewImage(file) {
	let imageContainer = document.createElement("div");
	imageContainer.classList.add("new-image-container");

	let img = document.createElement("img");
	img.src = URL.createObjectURL(file);
	img.setAttribute("name", file.name);
	img.classList.add("new-image");

	let iconContainer = document.createElement("div");
	iconContainer.classList.add("icon-container");

	let removeIcon = document.createElement("a");
	removeIcon.classList.add("remove-image");
	removeIcon.setAttribute("href", "#");
	removeIcon.innerHTML = "&#215;";

	iconContainer.appendChild(removeIcon);
	imageContainer.appendChild(iconContainer);
	imageContainer.appendChild(img);

	let previewImages = document.getElementById("new-images");
	previewImages.appendChild(imageContainer);

	document
		.getElementById("preview-section")
		.classList.remove("hidden");
	document
		.getElementById("upload-button")
		.classList.remove("disabled");
	document.getElementById("messages").classList.add("hidden");

	iconContainer.addEventListener("click", removeImage);
}

function removeImage(event) {
	event.target.closest(".new-image-container").remove();
	let imagesLength = document.getElementsByClassName("new-image");

	if (imagesLength.length == 0) {
		document
			.getElementById("preview-section")
			.classList.add("hidden");
		document
			.getElementById("upload-button")
			.classList.add("disabled");
	}
}

// UPLOAD TO SERENITY API FILES
let myToken = "";

async function refreshToken() {
	const response = await fetch(
		"https://apistorefile.devwebpro.tech/public/api/login_check",
		{
			method: "POST",
			headers: {
				Accept: "*/*",
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				username: "user3@email.com", // username of api user
				password: "password3", // password of api user
			}),
		}
	);

	let jsonResponse = await response.json();
	myToken = jsonResponse.token;
	// return myToken;
}

async function uploadAll(idProperty) {
	let images = document.getElementsByClassName("new-image");
	for (let i = 0; i < images.length; i++) {
		await uploadImage(
			images[i].getAttribute("src"),
			images[i].getAttribute("name"),
			idProperty
		);
	}
}

async function uploadImage(url, name, idProperty) {
	let blob = await fetch(url, {
		mode: "no-cors",
	}).then((result) => result.blob());
	const file = new File([blob], name);
	const formData = new FormData();
	formData.append("file", file);
	formData.append("fileType", "image");
	formData.append("idProperty", idProperty);
	sendImageToSerenityApiFiles(myToken, formData);
}

async function sendImageToSerenityApiFiles(token, formData) {
	const response = await fetch(
		"https://apistorefile.devwebpro.tech/public/api/media_files",
		{
			method: "POST",
			headers: {
				Authorization: "Bearer " + token,
			},
			body: formData,
		}
	);

	if (response.status >= 400 && response.status < 600) {
		output("An Error has been occured during upload", "text-danger");
	} else {
		let previewSectionElt =
			document.getElementById("preview-section");
		previewSectionElt.classList.add("hidden");

		let previewImages = document.getElementsByClassName(
			"new-image-container"
		);
		for (let i = 0; i < previewImages.length; i++) {
			previewImages[i].remove();
		}
		document
			.getElementById("upload-button")
			.classList.add("disabled");
		output(
			"Pictures has been uploaded successfully !",
			"text-success"
		);

		let jsonResponse = await response.json();
		addUploadedImage(jsonResponse);
	}
}

async function displayPictures(token, idProperty) {
	const response = await fetch(
		"https://apistorefile.devwebpro.tech/public/api/media_files?fileType=image&idProperty=" +
			idProperty,
		{
			method: "GET",
			headers: {
				Authorization: "Bearer " + token,
			},
		}
	);

	if (response.status >= 400 && response.status < 600) {
		// call the function output to display an error message
		output("An Error has been occured during upload", "text-danger");
	} else {
		let result = await response.json();
		let imagesArray = result["hydra:member"];

		imagesArray.forEach((element) => {
			addUploadedImage(element);
		});
	}
}

// Add an image to the section already uploaded image
function addUploadedImage(imageJson) {
	let imageContainer = document.createElement("div");
	imageContainer.classList.add("loaded-image-container");

	let img = document.createElement("img");
	img.src = "https://apistorefile.devwebpro.tech" + imageJson.fileUrl; // path to the picture
	img.classList.add("loaded-image");

	let iconContainer = document.createElement("div");
	iconContainer.classList.add("icon-container");
	iconContainer.id = imageJson.id;

	let removeIcon = document.createElement("i");
	removeIcon.classList.add("fa", "fa-trash", "delete-image");

	iconContainer.appendChild(removeIcon);
	imageContainer.appendChild(img);
	imageContainer.appendChild(iconContainer);

	let previewImages = document.getElementById("loaded-images");
	previewImages.appendChild(imageContainer);
	iconContainer.addEventListener("click", deleteImage);
	document
		.getElementById("loaded-section")
		.classList.remove("hidden");
}

async function deleteImage(event) {
	const response = await fetch(
		"https://apistorefile.devwebpro.tech/public/api/media_files/" +
			event.currentTarget.id,
		{
			method: "DELETE",
			headers: {
				Authorization: "Bearer " + myToken,
			},
		}
	);

	if (response.status >= 400 && response.status < 600) {
		output("An Error has been occured during upload", "text-danger");
	} else {
		event.target.closest(".loaded-image-container").remove();
		let images = document.getElementsByClassName("loaded-image");
		if (images.length == 0) {
			document
				.getElementById("loaded-section")
				.classList.add("hidden");
		}
		output("Picture has been deleted successfully !", "text-success");
	}
}
