// tracking.js

function trackEvent(el) {
    const eventId = el.getAttribute("data-mask");
    console.log("Tracking click for:", eventId);
    if (!eventId) return;

    navigator.sendBeacon("http://127.0.0.1:5000/track",
        new Blob([JSON.stringify({ 
          MaSK: eventId, 
          action: "click" })], 
          { 
            type: "application/json" 
          })
    );
}

function trackSearchEvent(eventIds) {
    eventIds.forEach(id => {
        fetch("http://127.0.0.1:5000/track", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
              MaSK: id, 
              action: "search" 
            })
        });
    });
}
