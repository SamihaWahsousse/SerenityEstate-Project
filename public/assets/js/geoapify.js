// The API Key provided is restricted to JSFiddle website
// Get your own API Key on https://myprojects.geoapify.com
// import { autocomplete } from 'https://unpkg.com/@geoapify/geocoder-autocomplete@^1/dist/index.min.js';
const myAPIKey = "229523e8e43741739f73b005a4d05482";


// const autocompleteInput = new autocomplete.GeocoderAutocomplete(
//   document.getElementById("autocomplete"), 
//   'YOUR_API_KEY', 
//   { /* Geocoder options */ });


const streetAddressInput = document.getElementById("property_address_street");

const cityAddressInput = document.getElementById("property_address_cityRegion_city");

const regionAddressInput = document.getElementById("property_address_cityRegion_region");

const streetInput = new autocomplete.GeocoderAutocomplete(
  document.getElementById("street"),
  myAPIKey, {
    allowNonVerifiedHouseNumber: true,
    allowNonVerifiedStreet: true,
    skipDetails: true,
    skipIcons: true,
    placeholder: " "
  });
/*
const stateInput = new autocomplete.GeocoderAutocomplete(
  document.getElementById("state"),
  myAPIKey, {
    type: "state",
    skipDetails: true,
    placeholder: " ",
    skipIcons: true
  });


  const cityInput = new autocomplete.GeocoderAutocomplete(
  document.getElementById("city"),
  myAPIKey, {
    type: "city",
    skipDetails: true,
    skipIcons: true,
    placeholder: " "
  });


const countryInput = new autocomplete.GeocoderAutocomplete(
  document.getElementById("country"),
  myAPIKey, {
    type: "country",
    skipDetails: true,
    placeholder: " ",
    skipIcons: true
  });

const postcodeElement = document.getElementById("postcode");
const housenumberElement = document.getElementById("housenumber");*/

streetInput.on('select', (street) => {
  if (street) {
    // alert('here');
    streetAddressInput.value = street.properties.street;
    if(street.properties.housenumber) {
      streetInput.setValue('');
      streetAddressInput.value = street.properties.housenumber + ' ' + streetAddressInput.value;
    }    

    /*
  if (street && street.properties.postcode) {
     postcodeElement.value = street.properties.postcode;
  } */

  if (street && street.properties.city) {
    cityAddressInput.value = street.properties.city;
  }

  if (street && street.properties.state) {
    regionAddressInput.value = street.properties.state;
  }
}
});

/*
cityInput.on('select', (city) => {

  if (city) {
    cityInput.setValue(city.properties.city || '');
  }

  if (city && city.properties.postcode) {
    postcodeElement.value = city.properties.postcode;
  }

  if (city && city.properties.state) {
    stateInput.setValue(city.properties.state);
  }

  if (city && city.properties.country) {
    countryInput.setValue(city.properties.country);
  }
});

stateInput.on('select', (state) => {

  if (state) {
    stateInput.setValue(state.properties.state || '');
  }

  if (state && state.properties.country) {
    countryInput.setValue(state.properties.country);
  }
});*/
/*
function checkAddress() {
  // const postcode = document.getElementById("postcode").value;;
  const city = cityAddressInput.value;
  const street = streetAddressInput.value;
  const state = regionAddressInput.value;
  const country = 'France';
  const postcode = '38100';
  // const housenumber = document.getElementById("housenumber").value;

  const message = document.getElementById("message");
  message.textContent = "";

  if (!street || !city || !state || !country) {
    highlightEmpty();
    message.textContent = "Please fill in the required fields and check your address again.";
    return;
  }

  // Check the address with Geoapify Geocoding API
  // You may use it for internal information only. Please note that house numbers might be missing for new buildings and non-mapped buildings. So consider that most addresses with verified streets and cities are correct.
  fetch(`https://api.geoapify.com/v1/geocode/search?street=${encodeURIComponent(street)}&city=${encodeURIComponent(city)}&state=${encodeURIComponent(state)}&country=${encodeURIComponent(country)}&apiKey=${myAPIKey}`).then(result => result.json()).then((result) => {
    let features = result.features || [];

    // To find a confidence level that works for you, try experimenting with different levels
    const confidenceLevelToAccept = 0.25;
    features = features.filter(feature => feature.properties.rank.confidence >= confidenceLevelToAccept);

    if (features.length) {
      const foundAddress = features[0];
      if (foundAddress.properties.rank.confidence === 1) {
        message.textContent = `We verified the address you entered. The formatted address is: ${foundAddress.properties.formatted}`;
      } else if (foundAddress.properties.rank.confidence > 0.5 && foundAddress.properties.rank.confidence_street_level === 1) {
        message.textContent = `We have some doubts about the accuracy of the address: ${foundAddress.properties.formatted}`
      } else if (foundAddress.properties.rank.confidence_street_level === 1) {
        message.textContent = `We can confirm the address up to street level: ${foundAddress.properties.formatted}`
      } else {
        message.textContent = `We can only verify your address partially. The address we found is ${foundAddress.properties.formatted}`
      }
    } else {
      message.textContent = "We cannot find your address. Please check if you provided the correct address."
    }
  });
}*/


function highlightEmpty() {
  const toHightlight = [];

  if (!document.getElementById("postcode").value) {
    toHightlight.push(document.getElementById("postcode"));
  }

  if (!streetInput.getValue()) {
    toHightlight.push(streetInput.inputElement);
  }

  if (!countryInput.getValue()) {
    toHightlight.push(countryInput.inputElement);
  }

  toHightlight.forEach(element => element.classList.add("warning-input"));

  setTimeout(() => {
    toHightlight.forEach(element => element.classList.remove("warning-input"));
  }, 3000);
}