var root = document.querySelector(':root');
var rootStyles = getComputedStyle(root);

let baseScale = 0;

var canvas = document.getElementById("pixel_war");
const ctx = canvas.getContext("2d", { willReadFrequently: true });

fetch('readTaille.php')
    .then(response => response.text())
    .then(data => {
        [width, height] = data.split(',');
        canvas.width = parseInt(width);
        canvas.height = parseInt(height);

        root.style.setProperty('--width_pixel_war', width + 'px');
        root.style.setProperty('--height_pixel_war', height + 'px');
        canvas.style.height = height + 'px';
        canvas.style.width = width + 'px';
        var width_pixel_war = parseInt(rootStyles.getPropertyValue('--width_pixel_war'));
        var height_pixel_war = parseInt(rootStyles.getPropertyValue('--height_pixel_war'));
        updateBaseScale(width_pixel_war, height_pixel_war);
        canvas.style.transform = 'scale(' + baseScale + ',' + baseScale + ')';
    })
    .catch(error => {
        console.error('Error reading file:', error);
});

// Code for the button scaling the canvas
var isScaled = false;
canvas.style.transformOrigin = 'top center';

document.getElementById('scaleButton').addEventListener('click', function() {

    // Toggle between scales
    if (isScaled) {
        canvas.style.transform = 'scale(' + baseScale+ ',' + baseScale + ')';
    } else {
        canvas.style.transform = 'scale(' + 3*baseScale + ',' + 3*baseScale + ')';
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
            canvas.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(' + 3*baseScale + ',' + 3*baseScale + ')';
        } 
        else {
            canvas.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(' + baseScale + ',' + baseScale + ')';
        }
    }

});

document.addEventListener('mouseup', function (e) {
    isDragging = false;
    finishMouseX += e.clientX - startMouseX;
    finishMouseY += e.clientY - startMouseY;
});

function updateBaseScale(width_pixel_war, height_pixel_war){
    let screenWidth = window.innerWidth;
    let screenHeight = window.innerHeight;
    
    if (0.9*screenWidth/width_pixel_war > 0.75*screenHeight/height_pixel_war){ //estimated free space occupied by the pixel war after transform
        baseScale = 0.75*screenHeight/height_pixel_war;
    }
    else {
        baseScale = 0.9*screenWidth/width_pixel_war;
    }
}