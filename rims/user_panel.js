const profileLink = document.getElementById("profileLink");
const dashboardLink = document.getElementById("dashboardLink");
const appointmentsLink = document.getElementById("appointmentsLink");
const settingsLink = document.getElementById("settingsLink");
const supportLink = document.getElementById("supportLink");

profileLink.addEventListener("click", function () {
    hideAllSections();
    document.getElementById("profileSection").style.display = "block";
});

function hideAllSections() {
    const sections = document.querySelectorAll(".content div");
    sections.forEach(section => section.style.display = "none");
}

hideAllSections();
document.getElementById("profileSection").style.display = "block"; // By default, show profile
