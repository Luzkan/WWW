function shuffleArray(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;
  
    // While there remain elements to shuffle...
    while (0 !== currentIndex) {
  
      // Pick a remaining element...
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex -= 1;
  
      // And swap it with the current element.
      temporaryValue = array[currentIndex];
      array[currentIndex] = array[randomIndex];
      array[randomIndex] = temporaryValue;
    }
  
    return array;
}

let previousImage;
let newLoadedImage;
function showImage(imgs) {
    
    // Create border on pressed Image
    if(previousImage != null) previousImage.style.border = "0px";
    imgs.style.border = "2px solid white";
    previousImage = imgs;

    // Preview full quality image below (for TaskList purposes, not a cool freature in practice)
    let newUrl = imgs.src.replace("-min", "");
    loadFull(newUrl);
}

function getImage(url){
    return new Promise(
        function(resolve, reject){
            console.log("Test 2.");
            var img = new Image();
            img.onload = function(){ resolve(url); };
            img.onerror = function(){ reject(url); };
            img.src = url;
            newLoadedImage = url;
        }
    );
}

function onSuccess(url){
    var previewText = document.getElementById("imgtext");
    previewText.innerHTML = "Full Quality Preview";
    var previewImage = document.getElementById("expandedImg");
    previewImage.src = url;
    previewImage.parentElement.style.display = "block";
    console.log("Loaded: " + url);
}
function onFailure(url){
    console.log("Error loading: " + url);
}

function loadFull(url){
    var obietnica = getImage(url);
    obietnica.then(onSuccess).catch(onFailure);
}
    