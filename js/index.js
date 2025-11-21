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
              action: "click" 
            })
        });
    });
}

document.addEventListener('DOMContentLoaded', function () {
    const sections = document.querySelectorAll('.event-section');
    sections.forEach((section) => {
        const carousel = section.querySelector('.event-carousel');
        if (!carousel) return;

        // Không tạo nút điều hướng cho khu vực kết quả tìm kiếm
        if (section.id === 'search-results-top') return;

        if (section.querySelector('.slider-nav')) return;
        const nav = document.createElement('div');
        nav.className = 'slider-nav';
        const prev = document.createElement('button');
        prev.type = 'button';
        prev.className = 'slider-btn prev';
        prev.innerHTML = '&#8249;';
        const next = document.createElement('button');
        next.type = 'button';
        next.className = 'slider-btn next';
        next.innerHTML = '&#8250;';
        nav.appendChild(prev);
        nav.appendChild(next);
        section.appendChild(nav);
        const step = Math.max(280, Math.floor(carousel.clientWidth * 0.9));
        prev.addEventListener('click', () => {
            carousel.scrollBy({ left: -step, behavior: 'smooth' });
        });
        next.addEventListener('click', () => {
            carousel.scrollBy({ left: step, behavior: 'smooth' });
        });
        function placeNav() {
            // Center vertically over the carousel area
            const offsetY = carousel.offsetTop + (carousel.clientHeight / 2);
            nav.style.top = offsetY + 'px';
            // Align buttons near the visible carousel edges
            const leftInset = Math.max(carousel.offsetLeft) + 2;
            const rightInset = Math.max(carousel.offsetLeft) + 2;
            prev.style.left = leftInset + 'px';
            next.style.right = rightInset + 'px';
            nav.style.display = 'block';
        }
        placeNav();
        const ro = new ResizeObserver(placeNav);
        ro.observe(carousel);
        window.addEventListener('load', placeNav);
        window.addEventListener('resize', placeNav);
    });
});
