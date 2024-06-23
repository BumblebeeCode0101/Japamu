var htmlElement = document.getElementsByTagName('html')[0];

//TODO: Add theme swit icon
function switchTheme() {
    var currentTheme = localStorage.getItem('theme') || 'light';
    var newTheme;

    if (currentTheme === 'dark') {
        newTheme = 'light';
    } else if (currentTheme === 'light') {
        newTheme = 'dark';
    } else {
        newTheme = 'light';
    }

    htmlElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

switchTheme();
switchTheme();
