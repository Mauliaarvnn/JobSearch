const dropdown = document.querySelector('.dropdownProfile');
const btn = document.querySelector('.dropdownBtn');

btn.addEventListener('click', () => {
    dropdown.classList.toggle('active');
});

// Klik di luar: tutup dropdown
document.addEventListener('click', (e) => {
    if (!dropdown.contains(e.target)) {
        dropdown.classList.remove('active');
    }
});


// ===== TEXT EDITOR TOOLBAR =====
const editor = document.getElementById('editor');
const buttons = document.querySelectorAll('.textEditorToolbar button');

buttons.forEach(button => {
    button.addEventListener('click', () => {
        let command = button.getAttribute('data-command');

        if (command) {
            document.execCommand(command, false, null);
            editor.focus();
        }
    });
});


// ===== FULLSCREEN MODE =====
const fullscreenBtn = document.getElementById('fullscreenBtn');
const jobDescContainer = document.querySelector('.jobDescContainer');

fullscreenBtn.addEventListener('click', () => {
    jobDescContainer.classList.toggle('fullscreenMode');
});
