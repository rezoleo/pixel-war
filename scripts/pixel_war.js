const canvas = document.getElementById("pixel_war");
const ctx = canvas.getContext("2d");

ctx.fillStyle = "green";
ctx.fillRect(0, 0, 100, 100);


// Code for the button scaling the canvas

var isScaled = false;

document.getElementById('scaleButton').addEventListener('click', function() {
    // Get the canvas element
    var canvas = document.getElementById('pixel_war');

    // Toggle between scales
    if (isScaled) {
        canvas.style.transform = 'scale(4, 4)';
    } else {
        canvas.style.transform = 'scale(20, 20)';
    }

    // Set the transform origin to "top center"
    canvas.style.transformOrigin = 'top center';

    // Update the scale state
    isScaled = !isScaled;
});