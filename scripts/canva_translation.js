const baseScale = 5;
const upScale = 25;

var canvas = document.getElementById("pixel_war");
const ctx = canvas.getContext("2d", { willReadFrequently: true });

var root = document.querySelector(':root') ;
var rootStyles = getComputedStyle(root) ;

fetch('readTaille.php')
    .then(response => response.text())
    .then(data => {
        [width, height] = data.split(',');
        canvas.width = parseInt(width);
        canvas.height = parseInt(height);
        
        root.style.setProperty('--width', width + 'px');
        root.style.setProperty('--height', height + 'px');
    })
    .catch(error => {
        console.error('Error reading file:', error);
});

// Code for the button scaling the canvas
var isScaled = false;
canvas.style.transform = 'scale(' + baseScale + ',' + baseScale + ')';
canvas.style.transformOrigin = 'top center';

document.getElementById('scaleButton').addEventListener('click', function() {

    // Toggle between scales
    if (isScaled) {
        canvas.style.transform = 'scale(' + baseScale+ ',' + baseScale + ')';
    } else {
        canvas.style.transform = 'scale(' + upScale + ',' + upScale + ')';
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
            canvas.style.transform = 'translate(' + translateX + 'px, ' + translateY + 'px) scale(' + upScale + ',' + upScale + ')';
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