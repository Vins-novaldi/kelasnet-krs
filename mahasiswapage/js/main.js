function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");

    sidebar.classList.toggle("hiddenside");
    content.classList.toggle("expandside");
}

function toggleDropdown2() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("content");

    sidebar.classList.toggle("hiddendrop");
    content.classList.toggle("expanddown");
}

function setButtonBehavior() {
    const button = document.getElementById("toggleButton");

    if (window.innerWidth > 768) {
        button.onclick = toggleSidebar; // Desktop
    } else {
        button.onclick = toggleDropdown2; // Mobile
    }
}
setButtonBehavior();
window.addEventListener("resize", setButtonBehavior);

// ==============================================================

function toggleDropdown() {
    const dropdown = document.getElementById("dropdown");
    if (dropdown.style.display === "block") {
        dropdown.style.display = "none";
    } else {
        dropdown.style.display = "block";
    }
}