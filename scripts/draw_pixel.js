// Event listener for uploading color to selected pixel
document.getElementById('uploadColorButton').addEventListener('click', function () {
    // Get the selected color from the canvas
    var selectedColor = getSelectedColor();
    
    // Update the color of the selected pixel
    updatePixelColor(selectedColor);
});

// Function to get the color of the selected pixel
function getSelectedColor() {
    var imageData = ctx.getImageData(selectedPixelX, selectedPixelY, 1, 1);
    var pixelColor = '#' + ('000000' + rgbToHex(imageData.data[0], imageData.data[1], imageData.data[2])).slice(-6);
    return pixelColor;
}

// Function to convert RGB values to hex
function rgbToHex(r, g, b) {
    return ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1);
}

// Variables to track selected pixel
var selectedPixelX = -1;
var selectedPixelY = -1;

// Event listener for canvas clicks
canvas.addEventListener('click', function (e) {
    // Calculate the pixel coordinates based on the click position
    var rect = canvas.getBoundingClientRect();

    if (isScaled){
        var x = Math.floor((e.clientX - rect.left) / upScale);
        var y = Math.floor((e.clientY - rect.top) / upScale);
    }
    else {
        var x = Math.floor((e.clientX - rect.left) / baseScale);
        var y = Math.floor((e.clientY - rect.top) / baseScale);
    }

    console.log(x, y);

    // Update the selected pixel coordinates
    selectedPixelX = x;
    selectedPixelY = y;

    // Redraw the canvas with the updated border
    updatePixelColor(getSelectedColor());
});

// Function to update the color of the selected pixel
function updatePixelColor(color) {
    // Check if a pixel is selected
    if (selectedPixelX !== null && selectedPixelY !== null) {
        // Set the color of the selected pixel
        ctx.fillStyle = color;
        ctx.fillRect(selectedPixelX, selectedPixelY, 1, 1);

        // Clear the selected pixel coordinates after updating color
        selectedPixelX = null;
        selectedPixelY = null;
    }
}