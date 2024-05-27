// Fonction pour désactiver le lien Pixel War
function changePixelWarStateButton(isActive) {
    const button = document.getElementById('changePixelWarStateButton');
    if (button) {
        button.textContent = isActive ? 'Deactivate Pixel War' : 'Activate Pixel War';
    }
}

fetch('is_pixel_war_active.php')
    .then(response => response.text())
    .then(data => {
        const isActive = data === 'true';
        changePixelWarStateButton(isActive);
    })
    .catch(error => {
        console.error('Error reading file:', error);
    });