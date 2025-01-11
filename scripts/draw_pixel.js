document.getElementById('uploadColorButton').addEventListener('click', function () {
   
    var data = {
        x: x,
        y: y,
        color: ctx.fillStyle
    };

    if (x != -1 || y != -1) {
        // Utilisation de la fonction fetch pour envoyer une requête POST
        fetch('write_pixel.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(data => {
            if (data == "success") {
                refreshCanva();
                const timerElement = document.getElementById("timer");
                fetch('get_timer.php')
                .then(response => response.text())
                .then(data => {
                    timer(timerElement, data);
                });
            }
            else if (data == "Too many requests") {
                alert("Trop de requêtes, veuillez patienter");
            }
            else if (data == "Pixel war is not active") {
                alert("La pixel war n'est pas active");
            }
            else {
                alert("Erreur lors de l'écriture du pixel");
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête :', error);
        });
    }
    else {
        alert("Veuillez sélectionner un pixel");
    }
});


// Function to get the color of the selected pixel
function getSelectedColor(x,y) {
    let pixelColor;
    let imageData;

    if ((x !== -1) && (y !== -1)) {
        imageData = ctx.getImageData(x, y, 1, 1);
        pixelColor = '#' + ('000000' + rgbToHex(imageData.data[0], imageData.data[1], imageData.data[2])).slice(-6);
    }
    else {
        pixelColor = '#FFFFFF';
    }
    return pixelColor;
}

// Function to convert RGB values to hex
function rgbToHex(r, g, b) {
    return ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}

// Variables to track selected pixel
let previousPixelX = -1;
let previousPixelY = -1;
let x = -1;
let y = -1;
let colorPreviousPixel = '#FFFFFF';
let position = document.getElementById("position");

// Event listener for canvas clicks
canvas.addEventListener('click', function (e) {
    // Calculate the pixel coordinates based on the click position
    let rect = canvas.getBoundingClientRect();

    if (isScaled){
        x = Math.floor((e.clientX - rect.left) / (3*baseScale));
        y = Math.floor((e.clientY - rect.top) / (3*baseScale));
        x = Math.max(0,x);
        y = Math.max(0,y);
    }
    else {
        x = Math.floor((e.clientX - rect.left) / baseScale);
        y = Math.floor((e.clientY - rect.top) / baseScale);
        x = Math.max(0,x);
        y = Math.max(0,y);
    }
    
    position.innerHTML = "(" + y + "," + x + ")"; //y est l'ordonnée et x l'abscisse donc y est le nombre de lignes et x le nombre de colonnes (affichage selon ligne, colonne)

    //si on ne clique pas au même endroit
    if (((x != previousPixelX) || (y != previousPixelY))){
        updatePixelColor();
        previousPixelX = x;
        previousPixelY = y;
    }
});

canvas.addEventListener('touchstart', function (e) {
    // Calculate the pixel coordinates based on the click position
    let rect = canvas.getBoundingClientRect();

    if (isScaled){
        x = Math.floor((e.touches[0].clientX - rect.left) / (3*baseScale));
        y = Math.floor((e.touches[0].clientY - rect.top) / (3*baseScale));
        x = Math.max(0,x);
        y = Math.max(0,y);
    }
    else {
        x = Math.floor((e.touches[0].clientX - rect.left) / baseScale);
        y = Math.floor((e.touches[0].clientY - rect.top) / baseScale);
        x = Math.max(0,x);
        y = Math.max(0,y);
    }
    
    position.innerHTML = "(" + y + "," + x + ")"; //y est l'ordonnée et x l'abscisse donc y est le nombre de lignes et x le nombre de colonnes (affichage selon ligne, colonne)

    //si on ne clique pas au même endroit
    if (((x != previousPixelX) || (y != previousPixelY))){
        updatePixelColor();
        previousPixelX = x;
        previousPixelY = y;
    }
});

// Function to update the color of the selected pixel in front
function updatePixelColor() {
    let currentColor = ctx.fillStyle;

    if ((previousPixelX !== -1) && (previousPixelY !== -1)) {
        ctx.fillStyle = colorPreviousPixel;
        ctx.fillRect(previousPixelX, previousPixelY, 1, 1);
    }

    colorPreviousPixel = getSelectedColor(x, y);

    if ((x !== -1) && (y !== -1)) {
        ctx.fillStyle = currentColor;
        ctx.fillRect(x, y, 1, 1);
    }
}

function timer(timerElement, seconds) {
    let timer = setInterval(function () {
        seconds--;
        timerElement.innerHTML = seconds;
        timerElement.style.color = "red";
        if (seconds < 0) {
            clearInterval(timer);
            timerElement.innerHTML = "Ready";
            timerElement.style.color = "green";
        }
    }, 1000);
}