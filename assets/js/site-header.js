(function () {
  var header = document.querySelector('.site-header');

  if (!header) {
    return;
  }

  var toggle = header.querySelector('[data-site-header-toggle]');
  var panel = header.querySelector('[data-site-header-panel]');

  if (!toggle || !panel) {
    return;
  }

  var mobileQuery = window.matchMedia('(max-width: 48rem)');

  function setMenuState(isOpen) {
    header.classList.toggle('is-menu-open', isOpen);
    toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    panel.hidden = !isOpen && mobileQuery.matches;
  }

  function syncMenuState() {
    if (mobileQuery.matches) {
      setMenuState(false);
      return;
    }

    header.classList.remove('is-menu-open');
    toggle.setAttribute('aria-expanded', 'false');
    panel.hidden = false;
  }

  toggle.addEventListener('click', function () {
    setMenuState(!header.classList.contains('is-menu-open'));
  });

  panel.addEventListener('click', function (event) {
    if (event.target.closest('a')) {
      setMenuState(false);
    }
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && header.classList.contains('is-menu-open')) {
      setMenuState(false);
      toggle.focus();
    }
  });

  if (typeof mobileQuery.addEventListener === 'function') {
    mobileQuery.addEventListener('change', syncMenuState);
  } else if (typeof mobileQuery.addListener === 'function') {
    mobileQuery.addListener(syncMenuState);
  }

  syncMenuState();
})();