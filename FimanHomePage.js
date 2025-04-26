document.addEventListener("DOMContentLoaded", function () {
    const dashboardBtn = document.getElementById("dashboard-btn");
    const dropdown = document.getElementById("dashboard-dropdown");

    dashboardBtn.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevents the event from bubbling up
        dropdown.classList.toggle("show");
    });

    // Close dropdown if user clicks outside
    window.addEventListener("click", function (event) {
        if (!event.target.closest(".navbar")) {
            dropdown.classList.remove("show");
        }
    });
});
