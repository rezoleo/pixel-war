fetch('readTaille.php')
    .then(response => response.text())
    .then(data => {
        [width, height] = data.split(',');
        canvas.width = parseInt(width);
        canvas.height = parseInt(height);

        root.style.setProperty('--width', width + 'px');
        root.style.setProperty('--height', height + 'px');

        refreshCanva();

        document.getElementById('refreshButton').addEventListener('click', function () {
            refreshCanva();
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
    // on the initiation, the color is #000000 (due to asynchronism of fetch) but it is not a color from our color array
    if (ctx.fillStyle == "#000000") {
        ctx.fillStyle = "#FFFFFF"; // this is the first color of our array (and goes with the selected color in create_buttons.js)
    }
    let choosenColor = ctx.fillStyle;
    
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

    ctx.fillStyle = choosenColor;

    x = -1;
    y = -1;
    previousPixelX = -1;
    previousPixelY = -1;
    position.innerHTML = "(?,?)"
}