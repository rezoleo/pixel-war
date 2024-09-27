var canvas = document.getElementById("pixel_war");
const ctx = canvas.getContext("2d", { willReadFrequently: true });
var uploadModificationButton = document.getElementById('uploadModificationButton');

var root = document.querySelector(':root') ;
var rootStyles = getComputedStyle(root) ;

var refresh_bolean = true;
let scale = 3;

fetch('readTaille.php')
    .then(response => response.text())
    .then(data => {
        [width, height] = data.split(',');
        canvas.width = parseInt(width);
        canvas.height = parseInt(height);

        root.style.setProperty('--width', width + 'px');
        root.style.setProperty('--height', height + 'px');
        canvas.style.transform = 'scale(' + scale + ',' + scale + ')';
        canvas.style.transformOrigin = 'top center';
        refreshCanva();
        scale = 300 / width;
        document.getElementById('refreshButton').addEventListener('click', function () {
            refreshCanva();
            xStart = -1;
            yStart = -1;
            xEnd = -1;
            yEnd = -1;
        });
    })
    .catch(error => {
        console.error('Error reading file:', error);
    });

function refreshCanva() {
    fetch('readPixels.php')
                .then(response => response.text())
                .then(data => {
                    // Convert base64-encoded data to binary
                    let binaryData = atob(data);
        
                    // Process the binary data
                    drawOnCanva(binaryData, width);
                })
                .catch(error => {
                    console.error('Error reading pixels:', error);
                });
}

function drawOnCanva(binaryData, width) {
    
    let colonne = 0;
    let ligne = 0;

    let colors = [
        "#FFFFFF", "#E4E4E4", "#888888", "#222222",
        "#FFA7D1", "#E50000", "#E59500", "#A06A42",
        "#E5D900", "#94E044", "#02BE01", "#00D3DD",
        "#0083C7", "#0000EA", "#CD6EEA", "#820080"
    ];

    for (let binary of binaryData) {
        binary_as_int = binary.charCodeAt(0);

        // Masque pour les 4 bits de poids fort
        let masque_poids_fort = 0b11110000;
        let poids_fort_as_int = (binary_as_int & masque_poids_fort) >> 4;

        ctx.fillStyle = colors[poids_fort_as_int];
        ctx.fillRect(colonne, ligne, 1, 1);

        colonne += 1;
        if (colonne == width) {
            colonne = 0;
            ligne++;
        }

        // Masque pour les 4 bits de poids faible
        let masque_poids_faible = 0b00001111;
        let poids_faible_as_int = binary_as_int & masque_poids_faible;

        ctx.fillStyle = colors[poids_faible_as_int];
        ctx.fillRect(colonne, ligne, 1, 1);

        colonne += 1;
        if (colonne == width) {
            colonne = 0;
            ligne++;
        }
    }
}

let xStart = -1;
let yStart = -1;
let xEnd = -1;
let yEnd = -1;

canvas.addEventListener('mousedown', function (e) {
    refreshCanva();
    // Calculate the pixel coordinates based on the click position
    let rect = canvas.getBoundingClientRect();
    
    xStart = Math.floor((e.clientX - rect.left) / scale);
    yStart = Math.floor((e.clientY - rect.top) / scale);
    xStart = Math.max(0,xStart);
    yStart = Math.max(0,yStart);
   
});

canvas.addEventListener('mouseup', function (e) {
    // Calculate the pixel coordinates based on the click position
    let rect = canvas.getBoundingClientRect();

    xEnd = Math.floor((e.clientX - rect.left) / scale);
    yEnd = Math.floor((e.clientY - rect.top) / scale);
    xEnd = Math.max(0,xEnd);
    yEnd = Math.max(0,yEnd);
    let xMin = Math.min(xStart, xEnd);
    let yMin = Math.min(yStart, yEnd);
    let xMax = Math.max(xStart, xEnd);
    let yMax = Math.max(yStart, yEnd);

    ctx.fillStyle = "white";

    /* The if statement is there because it is only possible to fill a pair of 2 pixels in white
    Thus the rectangle drawn is not correct but it will correspond to what will be whitened */

    // Formulas by trial and error it works with the backend but may not result in the best preview
    if (xMin % 2 == 0 && xMax % 2 == 0){ // case where the last column does not end a byte
        ctx.fillRect(xMin, yMin, xMax + 2 - xMin, yMax + 1 - yMin);
    }
    else if (xMin % 2 == 1 && xMax % 2 == 1){ // case where the first column does not start a byte
        ctx.fillRect(xMin - 1, yMin, xMax + 2 - xMin, yMax + 1 - yMin);
    }
    else if (xMin % 2 == 1 && xMax % 2 == 0){ // both the previous cases
        ctx.fillRect(xMin -1, yMin, xMax + 1 - xMin, yMax + 1 - yMin);
    }
    else{  // basic case everything's good
        ctx.fillRect(xMin, yMin, xMax + 1 - xMin, yMax + 1 - yMin);
    }
});

uploadModificationButton.addEventListener('click', function () {
    if (xStart != -1 && yStart != -1 && xEnd != -1 && yEnd != -1) {
        let xMin = Math.min(xStart, xEnd);
        let yMin = Math.min(yStart, yEnd);
        let xMax = Math.max(xStart, xEnd);
        let yMax = Math.max(yStart, yEnd);

        let data = {
            xMin: xMin,
            yMin: yMin,
            xMax: xMax,
            yMax: yMax
        };        

        fetch('admin_pixel_whitening.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.text())
        .then(data => {
            if (data == "failed") {
                alert("Failed to update the pixel");
            }
            else {
                refreshCanva();
            }
        })
        .catch(error => {
            console.error('Erreur lors de la requête :', error);
        });
    }
    else {
        alert("Please select a zone to modify");
    }
});