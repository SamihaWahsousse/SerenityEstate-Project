// ********** Test strenght Password Registration using REGEX ************//
var resetNewPasswordField = document.getElementById(
	"change_password_form_plainPassword_first"
);

// Call of the password validation function for the user input password
const inputResetPassword = resetNewPasswordField.addEventListener(
	"input",
	() => {
		validResetPassword(resetNewPasswordField.value);
	}
);

// const displayErrors = function (inputPasswordField) {};

// Function validation password
const validResetPassword = function (inputResetPassword) {
	console.log(inputResetPassword);

	// Get html/twig fields - Registration form
	let listeMinimumCharacters = document.querySelector(
		"#minimumCharacters"
	);
	let lowerCaseValid = document.getElementById("lowerCase");
	let upperCaseValid = document.getElementById("upperCase");
	let specialCharacterValid = document.getElementById(
		"specialCharacter"
	);
	let numberValid = document.getElementById("numbers");

	// 8 characters minimum
	if (inputResetPassword.length > 8) {
		listeMinimumCharacters.style.color = "green";
	} else {
		listeMinimumCharacters.style.color = "red";
	}

	// Uppercase letters
	if (/[A-Z]/.test(inputResetPassword)) {
		upperCaseValid.style.color = "green";
	} else {
		upperCaseValid.style.color = "red";
	}

	// LowerCase letters
	if (/[a-z]/.test(inputResetPassword)) {
		lowerCaseValid.style.color = "green";
	} else {
		lowerCaseValid.style.color = "red";
	}

	//Numbers
	if (/[0-9]/.test(inputResetPassword)) {
		numberValid.style.color = "green";
	} else {
		numberValid.style.color = "red";
	}

	// Special Characters
	if (/[#?!@$%^&*-]/.test(inputResetPassword)) {
		specialCharacterValid.style.color = "green";
	} else {
		specialCharacterValid.style.color = "red";
	}
};
