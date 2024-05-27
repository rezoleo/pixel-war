function disableButton() {
    const button = document.getElementById('uploadColorButton');
    if (button) {
        button.disabled = true;
        button.classList.add('disabled-button');
    }
}

function displayFinishedMessage() {
    const timerDiv = document.querySelector('.timer-div');
    if (timerDiv) {
        timerDiv.innerHTML = '<p id="timer" class="finished-message">Pixel War finished</p>';
    }
}

fetch('is_pixel_war_active.php')
    .then(response => response.text())
    .then(data => {
        if (data === 'false') {
            displayFinishedMessage();
            if (document.getElementById('uploadColorButton')) {
                disableButton();
            }
        }
    })
    .catch(error => {
        console.error('Error reading file:', error);
    });