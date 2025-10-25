function trackEvent(eventId, action) {
    fetch('/track', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        event_id: eventId,
        action: action
      })
    })
    .then(res => res.json())
    .then(data => console.log(data));
}

document.getElementById("search-form").addEventListener("submit", function(e) {
    const keyword = document.getElementById("search-input").value.trim();

    if (keyword.length > 0) {
      fetch("http://127.0.0.1:5000/track", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          MaSK: keyword,     
          action: "search"
        })
      }).then(res => res.json())
        .then(data => console.log("Search tracked:", data));
    }

    // Cho phép form tiếp tục submit như bình thường
});
