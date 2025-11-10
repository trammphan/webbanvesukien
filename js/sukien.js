document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("event-filter");
  const radios = form.querySelectorAll('input[name="diadiem"]');

  radios.forEach(function (radio) {
    radio.addEventListener("change", function () {
      form.submit();
      const filterBox = document.getElementById("filter-details");
      if (filterBox) {
        filterBox.classList.add("hidden");
      }
    });
  });
});


//Xu ly toggle bo loc
function toggleFilter() {
    const details = document.getElementById("filter-details");
    details.style.display = details.style.display === "none" || details.style.display === "" ? "block" : "none";
}

function trackEvent(el) {
    const eventId = el.getAttribute("data-mask");
    if (!eventId) return;

    navigator.sendBeacon("http://127.0.0.1:5000/track", 
        new Blob([JSON.stringify({ 
            MaSK: eventId, action: "click" 
        })], 
        { 
            type: "application/json" 
        })
    );
}
