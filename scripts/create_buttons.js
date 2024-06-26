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
        colorButton.style.width = 'clamp(15px, 5vw, 40px)';
        colorButton.style.height = 'clamp(15px, 5vw, 40px)';
        colorButton.style.margin = 'clamp(1px, 0.2vw, 5px)';
        colorButton.addEventListener('click', function () {
            // Handle color selection
            ctx.fillStyle = color;
            
            // Remove 'selected' class from all buttons
            var allButtons = document.querySelectorAll('.colorButton');
            allButtons.forEach(function (button) {
                button.classList.remove('selected');
            });
    
            // Add 'selected' class to the clicked button
            colorButton.classList.add('selected');
        });
    
        // Add a class to the button
        colorButton.classList.add('colorButton');
        colorContainer.appendChild(colorButton);

    });
    let firstButton = document.querySelector('.colorButton');
    firstButton.classList.add('selected');
}

// Call the function to create color buttons
createColorButtons();
