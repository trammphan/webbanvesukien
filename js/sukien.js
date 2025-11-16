document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("event-filter");
  if (!form) return; // Ngăn lỗi nếu form không tồn tại
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

function handleTicketClick(e, el) {
    const isEnded = el.getAttribute("data-ended") === "true";
    if (isEnded) {
        e.preventDefault();
        document.getElementById("custom-alert").classList.remove("hidden");
        return false;
    }

    const eventId = el.getAttribute("data-mask");
    if (eventId) {
        navigator.sendBeacon("http://127.0.0.1:5000/track",
            new Blob([JSON.stringify({ MaSK: eventId, action: "click" })], {
                type: "application/json"
            })
        );
    }
}

function showEndedAlert(event) {
    event.preventDefault();
    document.getElementById('custom-alert').classList.remove('hidden');
}
function closeCustomAlert() {
    document.getElementById('custom-alert').classList.add('hidden');
}
