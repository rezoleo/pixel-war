function closeNav() {
    document.getElementById('responsive-navbar').style.visibility = 'hidden';
    document.getElementById('responsive-navbar').style.width = '0';
    document.getElementById('responsive-navbar').style.height = '0';
    document.getElementById('open-nav').style.margin = 'auto';
    document.getElementById('open-nav').style.visibility = 'visible';
    document.getElementById('open-nav').style.width = 'auto';
    document.getElementById('close-nav').style.visibility = 'hidden';
    document.getElementById('close-nav').style.width = '0';
    document.getElementById('close-nav').style.margin = '0';
}

function openNav() {
    document.getElementById('responsive-navbar').style.visibility = 'visible';
    document.getElementById('responsive-navbar').style.width = 'auto';
    document.getElementById('responsive-navbar').style.height = '100vh';
    document.getElementById('open-nav').style.visibility = 'hidden';
    document.getElementById('open-nav').style.margin = '0';
    document.getElementById('open-nav').style.width = '0';
    document.getElementById('close-nav').style.visibility = 'visible';
    document.getElementById('close-nav').style.width = 'auto';
    document.getElementById('close-nav').style.margin = 'auto';
}