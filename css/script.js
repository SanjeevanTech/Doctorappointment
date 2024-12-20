function closeNavbar() {
    const navbarCollapse = document.getElementById('navbarNav');
    if (navbarCollapse.classList.contains('show')) {
        navbarCollapse.classList.remove('show');
    }
}

// Declare navbarCollapse and content once
const navbarCollapse = document.getElementById('navbarNav');
const content = document.querySelector('.dashboard-section'); // Your main content

// Adjust content padding/margin on navbar toggle
navbarCollapse.addEventListener('shown.bs.collapse', function () {
    content.style.marginTop = navbarCollapse.clientHeight + 'px'; // Adjust margin based on navbar height
});

navbarCollapse.addEventListener('hidden.bs.collapse', function () {
    content.style.marginTop = '0'; // Reset margin when navbar is collapsed
});

function adjustContentMargin() {
    if (window.innerWidth < 768 && navbarCollapse.classList.contains('show')) {
        content.style.marginTop = navbarCollapse.clientHeight + 'px';
    } else {
        content.style.marginTop = '0';
    }
}


  document.querySelector('.navbar-toggler').addEventListener('click', function () {
      setTimeout(adjustContentMargin, 300);
  });

  window.addEventListener('resize', adjustContentMargin);
