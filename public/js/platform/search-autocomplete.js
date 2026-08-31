// public/js/platform/search-autocomplete.js
/**
 * Generic autocomplete for admin search inputs.
 * Attaches to any input with a `data-suggest-url` attribute.
 * Expects a `list` attribute pointing to a <datalist> element.
 */
document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('input[data-suggest-url]');
  inputs.forEach(initAutocomplete);
});

function initAutocomplete(input) {
  const url = input.dataset.suggestUrl;
  const listId = input.getAttribute('list');
  if (!url || !listId) return;
  const datalist = document.getElementById(listId);
  if (!datalist) return;

  let timeoutId;
  input.addEventListener('input', (e) => {
    const query = e.target.value.trim();
    clearTimeout(timeoutId);
    if (!query) {
      datalist.innerHTML = '';
      return;
    }
    timeoutId = setTimeout(() => fetchSuggestions(query, url, datalist), 300);
  });
}

function fetchSuggestions(query, url, datalist) {
  fetch(`${url}?q=${encodeURIComponent(query)}`)
    .then(res => res.json())
    .then(data => {
      datalist.innerHTML = '';
      if (!Array.isArray(data) || data.length === 0) return;
      data.forEach(item => {
        const option = document.createElement('option');
        option.value = item.value;
        option.label = item.label;
        datalist.appendChild(option);
      });
    })
    .catch(err => console.error('Autocomplete fetch error:', err));
}
