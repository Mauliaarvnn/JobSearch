// Calendar functionality
let currentDate = new Date();
let selectedDate = null;

const calendarBtn = document.getElementById('calendarBtn');
const calendarOverlay = document.getElementById('calendarOverlay');
const closeCalendar = document.getElementById('closeCalendar');
const prevMonth = document.getElementById('prevMonth');
const nextMonth = document.getElementById('nextMonth');
const calendarTitle = document.getElementById('calendarTitle');
const calendarDays = document.getElementById('calendarDays');
const tanggalLahirInput = document.getElementById('tanggalLahir');

const months = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

function renderCalendar() {
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    
    calendarTitle.textContent = `${months[month]} ${year}`;
    
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const daysInPrevMonth = new Date(year, month, 0).getDate();
    
    calendarDays.innerHTML = '';
    
    // Previous month days
    for (let i = firstDay - 1; i >= 0; i--) {
        const day = document.createElement('div');
        day.className = 'calendar-day other-month';
        day.textContent = daysInPrevMonth - i;
        calendarDays.appendChild(day);
    }
    
    // Current month days
    for (let i = 1; i <= daysInMonth; i++) {
        const day = document.createElement('div');
        day.className = 'calendar-day';
        day.textContent = i;
        
        const today = new Date();
        if (year === today.getFullYear() && month === today.getMonth() && i === today.getDate()) {
            day.classList.add('today');
        }
        
        if (selectedDate && 
            selectedDate.getFullYear() === year && 
            selectedDate.getMonth() === month && 
            selectedDate.getDate() === i) {
            day.classList.add('selected');
        }
        
        day.addEventListener('click', () => selectDate(year, month, i));
        calendarDays.appendChild(day);
    }
    
    // Next month days
    const totalCells = calendarDays.children.length;
    const remainingCells = 42 - totalCells; // 6 rows * 7 days
    for (let i = 1; i <= remainingCells; i++) {
        const day = document.createElement('div');
        day.className = 'calendar-day other-month';
        day.textContent = i;
        calendarDays.appendChild(day);
    }
}

function selectDate(year, month, day) {
    selectedDate = new Date(year, month, day);
    const formattedDate = `${String(day).padStart(2, '0')}/${String(month + 1).padStart(2, '0')}/${year}`;
    tanggalLahirInput.value = formattedDate;
    calendarOverlay.classList.remove('active');
}

calendarBtn.addEventListener('click', () => {
    calendarOverlay.classList.add('active');
    renderCalendar();
});

closeCalendar.addEventListener('click', () => {
    calendarOverlay.classList.remove('active');
});

calendarOverlay.addEventListener('click', (e) => {
    if (e.target === calendarOverlay) {
        calendarOverlay.classList.remove('active');
    }
});

prevMonth.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

nextMonth.addEventListener('click', () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

// Skills functionality
const skillInput = document.getElementById('skill');
const skillsContainer = document.getElementById('skillsContainer');
const skills = [];

skillInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        const skillText = skillInput.value.trim();
        if (skillText && !skills.includes(skillText)) {
            skills.push(skillText);
            addSkillTag(skillText);
            skillInput.value = '';
        }
    }
});

function addSkillTag(skill) {
    const tag = document.createElement('div');
    tag.className = 'skill-tag';
    tag.innerHTML = `
        ${skill}
        <button type="button" onclick="removeSkill(this, '${skill}')">×</button>
    `;
    skillsContainer.appendChild(tag);
}

function removeSkill(button, skill) {
    const index = skills.indexOf(skill);
    if (index > -1) {
        skills.splice(index, 1);
    }
    button.parentElement.remove();
}

// File input handling
const fotoProfilInput = document.getElementById('fotoProfil');
const cvInput = document.getElementById('cv');

fotoProfilInput.addEventListener('change', (e) => {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
    e.target.nextElementSibling.querySelector('.file-name').textContent = fileName;
});

cvInput.addEventListener('change', (e) => {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
    e.target.nextElementSibling.querySelector('.file-name').textContent = fileName;
});

// Form submission
const profilForm = document.getElementById('profilForm');

profilForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const formData = {
        nama: document.getElementById('nama').value,
        tanggalLahir: document.getElementById('tanggalLahir').value,
        jenisKelamin: document.getElementById('jenisKelamin').value,
        whatsapp: document.getElementById('whatsapp').value,
        email: document.getElementById('email').value,
        pendidikan: document.getElementById('pendidikan').value,
        alamat: document.getElementById('alamat').value,
        skills: skills,
        pengalaman: document.getElementById('pengalaman').value,
        deskripsi: document.getElementById('deskripsi').value
    };
    
    console.log('Form Data:', formData);
    alert('Profil berhasil disimpan! Data telah dikirim.');
});

profilForm.addEventListener('reset', () => {
    skills.length = 0;
    skillsContainer.innerHTML = '';
    selectedDate = null;
    tanggalLahirInput.value = '';
    document.querySelectorAll('.file-name').forEach(el => {
        el.textContent = 'No file chosen';
    });
});