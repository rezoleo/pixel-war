var canvas = document.getElementById("pixel_war");
const ctx = canvas.getContext("2d");

ctx.fillStyle = "green";
ctx.fillRect(0, 0, 100, 100);


// Code for the button scaling the canvas

var isScaled = false;
canvas.style.transform = 'scale(4, 4)'
canvas.style.transformOrigin = 'top center';

document.getElementById('scaleButton').addEventListener('click', function() {

    // Toggle between scales
    if (isScaled) {
        canvas.style.transform = 'scale(4, 4)';
    } else {
        canvas.style.transform = 'scale(20, 20)';
    }

    // Update the scale state
    isScaled = !isScaled;
    finishMouseX = 0;
    finishMouseY = 0;
    translateX = 0;
    translateY = 0;
    finishTouchX = 0;
    finishTouchY = 0;
});

// Event listeners for dragging
var translateX = 0;
var translateY = 0;
var finishMouseX = 0;
var finishMouseY = 0;
var startMouseX = 0;
var startMouseY = 0;
var startTouchX = 0;
var startTouchY = 0;
var finishTouchX = 0;
var finishTouchY = 0;
var isDragging = false;

document.addEventListener('mousedown', function (e) {
    isDragging = true;
    startMouseX = e.clientX;
    startMouseY = e.clientY;
});

document.addEventListener('mousemove', function (e) {
    if (isDragging) {
        // Calculate the distance moved
        var deltaX = e.clientX - startMouseX;
        var deltaY = e.clientY - startMouseY;

        translateX = deltaX + finishMouseX;
        translateY = deltaY + finishMouseY;

        // Update the canvas position
        if (isScaled){
            canvas.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(20, 20)';
        } 
        else {
            canvas.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(4, 4)';
        }
    }

});

document.addEventListener('mouseup', function (e) {
    isDragging = false;
    finishMouseX += e.clientX - startMouseX;
    finishMouseY += e.clientY - startMouseY;
});

document.addEventListener('touchstart', function (e) {
    isDragging = true;
    startTouchX = e.touches[0].clientX;
    startTouchY = e.touches[0].clientY;
});

document.addEventListener('touchmove', function (e) {
    if (isDragging) {
        // Calculate the distance moved
        var deltaX = e.touches[0].clientX - startTouchX;
        var deltaY = e.touches[0].clientY - startTouchY;

        translateX = deltaX + finishTouchX;
        translateY = deltaY + finishTouchY;

        // Update the canvas position
        if (isScaled) {
            canvas.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(20, 20)';
        } else {
            canvas.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(4, 4)';
        }
    }
});

document.addEventListener('touchend', function (e) {
    isDragging = false;
    finishTouchX += e.changedTouches[0].clientX - startTouchX;
    finishTouchY += e.changedTouches[0].clientY - startTouchY;
});

// Function to create color buttons
function createColorButtons() {
    var colorContainer = document.getElementById('colorButtons');

    // Array of color values
    var colors = [
        "#FFFFFF", "#E4E4E4", "#888888", "#222222",
        "#FFA7D1", "#E50000", "#E59500", "#A06A42",
        "#E5D900", "#94E044", "#02BE01", "#00D3DD",
        "#0083C7", "#0000EA", "#CD6EEA", "#820080"
    ];

    // Create buttons for each color
    colors.forEach(function (color) {
        var colorButton = document.createElement('button');
        colorButton.style.backgroundColor = color;
        colorButton.style.width = '40px';
        colorButton.style.height = '40px';
        colorButton.style.margin = '3px';
        colorButton.addEventListener('click', function () {
            // Handle color selection
            ctx.fillStyle = color;
            ctx.fillRect(0, 0, 100, 100);
        });
        colorContainer.appendChild(colorButton);
    });
}

// Call the function to create color buttons
createColorButtons();