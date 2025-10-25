document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('event-filter');
    const container = document.getElementById('event-list');
});

//Xu ly toggle bo loc
function toggleFilter() {
    const details = document.getElementById("filter-details");
    details.style.display = details.style.display === "none" || details.style.display === "" ? "block" : "none";
}

function trackEvent(button) {
const eventId = button.getAttribute("data-mask");
console.log("Click event:", eventId);
const action = "click"; 

if (eventId) {
    fetch("http://localhost:5000/track", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({
        MaSK: eventId,
        action: action
    })
    }).then(res => res.json())
    .then(data => console.log("Tracked:", data));
}}
