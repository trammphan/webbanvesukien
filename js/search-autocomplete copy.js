(function() {
  const input = document.querySelector('.header-search .search-input');
  if (!input) return;

  const form = input.closest('form');
  const container = document.querySelector('.header-search');
  if (!container) return;

  let dropdown = document.createElement('ul');
  dropdown.className = 'search-suggest';
  dropdown.style.display = 'none';
  container.appendChild(dropdown);

  let items = [];
  let activeIndex = -1;
  let lastQuery = '';
  let debounceTimer = null;

  function debounce(fn, delay) {
    return function(...args) {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => fn.apply(this, args), delay);
    };
  }

  function hideDropdown() {
    dropdown.style.display = 'none';
    dropdown.innerHTML = '';
    items = [];
    activeIndex = -1;
  }

  function showDropdown(data) {
    items = data || [];
    if (!items.length) {
      hideDropdown();
      return;
    }
    dropdown.innerHTML = items.map((text, idx) => {
      return `<li class="search-suggest-item" data-index="${idx}">${escapeHtml(text)}</li>`;
    }).join('');
    dropdown.style.display = 'block';
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  async function fetchSuggest(q) {
    try {
      const url = `search_suggest.php?q=${encodeURIComponent(q)}`;
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error('network');
      const data = await res.json();
      if (input.value.trim() !== q) return; // stale response
      showDropdown(Array.isArray(data) ? data : []);
    } catch (e) {
      hideDropdown();
    }
  }

  const handleInput = debounce(function() {
    const q = input.value.trim();
    if (q.length < 2) {
      hideDropdown();
      lastQuery = q;
      return;
    }
    if (q === lastQuery) return;
    lastQuery = q;
    fetchSuggest(q);
  }, 250);

  input.addEventListener('input', handleInput);

  input.addEventListener('keydown', function(e) {
    if (dropdown.style.display === 'none') return;
    const max = items.length - 1;
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      activeIndex = activeIndex < max ? activeIndex + 1 : 0;
      updateActive();
    } else if (e.key === 'ArrowUp') {
      e.preventDefault();
      activeIndex = activeIndex > 0 ? activeIndex - 1 : max;
      updateActive();
    } else if (e.key === 'Enter') {
      if (activeIndex >= 0 && activeIndex < items.length) {
        e.preventDefault();
        input.value = items[activeIndex];
        hideDropdown();
        if (form) form.submit();
      }
    } else if (e.key === 'Escape') {
      hideDropdown();
    }
  });

  function updateActive() {
    const lis = dropdown.querySelectorAll('.search-suggest-item');
    lis.forEach(li => li.classList.remove('active'));
    if (activeIndex >= 0 && activeIndex < lis.length) {
      lis[activeIndex].classList.add('active');
    }
  }

  dropdown.addEventListener('mousedown', function(e) {
    const li = e.target.closest('.search-suggest-item');
    if (!li) return;
    const idx = Number(li.getAttribute('data-index'));
    if (!Number.isNaN(idx)) {
      input.value = items[idx] || '';
      hideDropdown();
      if (form) form.submit();
    }
  });

  document.addEventListener('click', function(e) {
    if (!container.contains(e.target)) hideDropdown();
  });
})();
