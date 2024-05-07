var canvas = document.getElementById("pixel_war");
const ctx = canvas.getContext("2d", { willReadFrequently: true });

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
        //setInterval(refreshCanva, 3000);
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

    ctx.fillStyle = "white";
    ctx.fillRect(xStart, yStart, xEnd - xStart, yEnd - yStart);
});
