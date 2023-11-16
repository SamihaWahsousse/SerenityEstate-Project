function Init() {

    let fileSelect = document.getElementById('file-upload');
    let fileDrag = document.getElementById('file-drag');
    fileSelect.addEventListener('change', fileSelectHandler);
    fileDrag.addEventListener('dragover', fileDragHover);
    fileDrag.addEventListener('dragleave', fileDragHover);
    fileDrag.addEventListener('drop', fileSelectHandler);
    document.getElementById('messages').classList.add("hidden");
    refreshToken();
  }
  
  function fileSelectHandler(e) {
    // Fetch FileList object
    var files = e.target.files || e.dataTransfer.files;
    
    // Cancel event and hover styling
    fileDragHover(e);
  
    // Process all File objects
    for (var i = 0, f; f = files[i]; i++) {
      var isFileParsed = parseFile(f);
      if(!isFileParsed) {
        break;
      }
    }
  }
  
  function fileDragHover(e) {
    var fileDrag = document.getElementById('file-drag');
    e.stopPropagation();
    e.preventDefault();
    fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
  }
  
  function output(msg, successOrErrorClass) {
    var messagesElement = document.getElementById('messages');
    messagesElement.classList.remove("hidden");
    messagesElement.className = '';
    messagesElement.classList.add(successOrErrorClass);
    messagesElement.innerHTML = msg;
  }
  
  function parseFile(file) {
  
    let imageName = file.name;
    let isImage = (/\.(?=jpg|png|jpeg)/gi).test(imageName);      
  
    if (isImage) {
      let fileSizeLimit = 1; // In MB
  
      // Check if file is less than x MB
      if (file.size <= fileSizeLimit * 1024 * 1024) { 
        addNewImage(file);
        return true;
      } else {
        output('Please upload a smaller file (< ' + fileSizeLimit + ' MB).', 'text-danger');
        return false;
      }
    }
    else {
      output('The selected file is not an image! Please select an image.', 'text-danger');        
      return false;
    }        
  }
  
  function addNewImage(file) {
    let imageContainer = document.createElement("div");
    imageContainer.classList.add('image-container');      
  
    let img = document.createElement("img");
    img.src = URL.createObjectURL(file);
    img.setAttribute('name', file.name);
    img.classList.add("file-preview-image");
      
    let iconContainer = document.createElement("div");
    iconContainer.classList.add('icon-container');
  
    let removeIcon = document.createElement("a");
    removeIcon.classList.add('remove-image');
    removeIcon.setAttribute('href','#');
    removeIcon.innerHTML = '&#215;';
    
    iconContainer.appendChild(removeIcon);
    imageContainer.appendChild(iconContainer);
    imageContainer.appendChild(img);
  
    let previewImages = document.getElementById('preview-images');
    previewImages.appendChild(imageContainer);
  
    document.getElementById('preview-section').classList.remove('hidden');
    document.getElementById('upload-button').classList.remove('disabled');
    document.getElementById('messages').classList.add("hidden");
  
    iconContainer.addEventListener('click', removeImage);
  }
  
  function removeImage(event) {
    event.target.closest(".image-container").remove();
    let imagesLength = document.getElementsByClassName('file-preview-image');
  
    if(imagesLength.length == 0) {
      document.getElementById('preview-section').classList.add('hidden');
      document.getElementById('upload-button').classList.add('disabled');
    }
  }
  
  // UPLOAD TO SERENITY API FILES
  let myToken = 'token_to_bet_set_with_refresh_token';
  
  async function refreshToken() {
  
      const response = await fetch("https://apistorefile.devwebpro.tech/public/api/login_check", {
          method: 'POST',
          headers: {
          'Accept': '*/*',
          'Content-Type': 'application/json'
          },
          body: JSON.stringify({
          "username":"user3@email.com",
          "password":"password3",
          })
      });
  
      response.json().then(data => {
          myToken = data.token;
          return data.token;
      });
  }
  
  async function uploadAll() {
      let images = document.getElementsByClassName('file-preview-image');
      for(let i=0; i < images.length; i++) 
      {
        await uploadImage(images[i].getAttribute('src'), images[i].getAttribute('name'));     
      }
  }
  
  async function uploadImage(url, name) {
        let blob = await fetch(url).then(result => result.blob());
        const file = new File([blob], name);
        const formData = new FormData();
        formData.append('file', file);
        formData.append('fileType', 'image');
        formData.append('idProperty', '11');
        sendImageToSerenityApiFiles(myToken, formData)   
  }
  
  async function sendImageToSerenityApiFiles(token, formData) {
  
    await fetch("https://apistorefile.devwebpro.tech/public/api/media_files", {
        method: 'POST',
        headers: {
        'Authorization': 'Bearer ' + token
        },
        body: formData                        
    }).then((response) => {
      if (response.status >= 400 && response.status < 600) {
        throw new Error("Bad response from server");
      }
    }).then(() => {
        
        let previewSectionElt = document.getElementById('preview-section');
        previewSectionElt.classList.add('hidden');
  
        let previewImages = document.getElementsByClassName('image-container');
        for(let i = 0; i < previewImages.length; i++) {
          previewImages[i].remove()
        }
        document.getElementById('upload-button').classList.add('disabled');
        output('Pictures has been uploaded successfully !', 'text-success');
    })
    .catch((error) => {
      console.log('response error ' + error);
      output('An Error has been occured during upload', 'text-danger');
    });
  }