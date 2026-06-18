(function ($) {
  "use strict";

  const NAV_UPDATE_INTERVAL = 120;
  let navTimerId = 0;

  const navState = {
    prevButton: null,
    nextButton: null,
  };

  const isVisible = function (el) {
    if (!el) {
      return false;
    }

    const style = window.getComputedStyle(el);
    return style.display !== "none" && style.visibility !== "hidden";
  };

  const hasVisibleWindow = function () {
    const windowEl = document.getElementById("TB_window");
    const overlayEl = document.getElementById("TB_overlay");
    return isVisible(windowEl) && isVisible(overlayEl);
  };

  const getGalleryProgress = function () {
    const countEl = document.getElementById("TB_count");
    if (!countEl) {
      return null;
    }

    const text = (countEl.textContent || "").trim();
    const match = text.match(/(\d+)\D+(\d+)/);
    if (!match) {
      return null;
    }

    const current = parseInt(match[1], 10);
    const total = parseInt(match[2], 10);

    if (!Number.isFinite(current) || !Number.isFinite(total) || total <= 0) {
      return null;
    }

    return { current, total };
  };

  const canGoPrev = function () {
    const progress = getGalleryProgress();
    return !!progress && progress.current > 1;
  };

  const canGoNext = function () {
    const progress = getGalleryProgress();
    return !!progress && progress.current < progress.total;
  };

  const triggerNext = function () {
    const next = document.querySelector("#TB_next a, #TB_next");
    if (next && canGoNext()) {
      next.click();
      return true;
    }
    return false;
  };

  const triggerPrev = function () {
    const prev = document.querySelector("#TB_prev a, #TB_prev");
    if (prev && canGoPrev()) {
      prev.click();
      return true;
    }
    return false;
  };

  const triggerClose = function () {
    const close = document.getElementById("TB_closeWindowButton");
    if (close) {
      close.click();
      return true;
    }
    return false;
  };

  const normalizeKey = function (event) {
    if (event.key === "ArrowRight" || event.code === "ArrowRight" || event.keyCode === 39 || event.which === 39) {
      return "right";
    }

    if (event.key === "ArrowLeft" || event.code === "ArrowLeft" || event.keyCode === 37 || event.which === 37) {
      return "left";
    }

    if (event.key === "Escape" || event.code === "Escape" || event.keyCode === 27 || event.which === 27) {
      return "escape";
    }

    return "";
  };

  const enhanceA11yControls = function () {
    const prevLink = document.querySelector("#TB_prev a");
    const nextLink = document.querySelector("#TB_next a");
    const closeButton = document.getElementById("TB_closeWindowButton");

    if (prevLink) {
      prevLink.setAttribute("aria-label", "Immagine precedente");
      prevLink.setAttribute("title", "Immagine precedente");
    }

    if (nextLink) {
      nextLink.setAttribute("aria-label", "Immagine successiva");
      nextLink.setAttribute("title", "Immagine successiva");
    }

    if (closeButton) {
      closeButton.setAttribute("aria-label", "Chiudi galleria");
      closeButton.setAttribute("title", "Chiudi galleria");
    }
  };

  const handleKeydown = function (event) {
    if (!hasVisibleWindow()) {
      return;
    }

    if (event.altKey || event.ctrlKey || event.metaKey) {
      return;
    }

    const key = normalizeKey(event);
    if (key === "") {
      return;
    }

    if (key === "right") {
      if (triggerNext()) {
        event.preventDefault();
        event.stopPropagation();
      }
      return;
    }

    if (key === "left") {
      if (triggerPrev()) {
        event.preventDefault();
        event.stopPropagation();
      }
      return;
    }

    if (key === "escape") {
      if (triggerClose()) {
        event.preventDefault();
        event.stopPropagation();
      }
    }
  };

  const ensureNavButtons = function () {
    if (!navState.prevButton) {
      const prevButton = document.createElement("button");
      prevButton.type = "button";
      prevButton.className = "cs-attivita-lightbox-nav cs-attivita-lightbox-nav--prev";
      prevButton.setAttribute("aria-label", "Immagine precedente");
      prevButton.setAttribute("aria-controls", "TB_window");
      prevButton.innerHTML = '<span aria-hidden="true">&#8249;</span>';
      prevButton.addEventListener("click", function () {
        triggerPrev();
      });
      document.body.appendChild(prevButton);
      navState.prevButton = prevButton;
    }

    if (!navState.nextButton) {
      const nextButton = document.createElement("button");
      nextButton.type = "button";
      nextButton.className = "cs-attivita-lightbox-nav cs-attivita-lightbox-nav--next";
      nextButton.setAttribute("aria-label", "Immagine successiva");
      nextButton.setAttribute("aria-controls", "TB_window");
      nextButton.innerHTML = '<span aria-hidden="true">&#8250;</span>';
      nextButton.addEventListener("click", function () {
        triggerNext();
      });
      document.body.appendChild(nextButton);
      navState.nextButton = nextButton;
    }
  };

  const hideNavButtons = function () {
    if (navState.prevButton) {
      navState.prevButton.hidden = true;
    }

    if (navState.nextButton) {
      navState.nextButton.hidden = true;
    }
  };

  const positionNavButtons = function () {
    const tbWindow = document.getElementById("TB_window");
    if (!tbWindow) {
      hideNavButtons();
      return;
    }

    const rect = tbWindow.getBoundingClientRect();
    const buttonSize = 44;
    const viewportPadding = 8;
    const outsideGap = 12;
    const top = rect.top + rect.height / 2 - buttonSize / 2;

    let left = rect.left - buttonSize - outsideGap;
    let right = rect.right + outsideGap;

    left = Math.max(viewportPadding, left);
    right = Math.min(window.innerWidth - buttonSize - viewportPadding, right);

    navState.prevButton.style.top = `${Math.round(top)}px`;
    navState.prevButton.style.left = `${Math.round(left)}px`;
    navState.nextButton.style.top = `${Math.round(top)}px`;
    navState.nextButton.style.left = `${Math.round(right)}px`;
  };

  const syncNavButtons = function () {
    ensureNavButtons();

    if (!hasVisibleWindow()) {
      hideNavButtons();
      return;
    }

    enhanceA11yControls();
    positionNavButtons();
    navState.prevButton.hidden = !canGoPrev();
    navState.nextButton.hidden = !canGoNext();
  };

  const startNavSync = function () {
    if (navTimerId) {
      return;
    }

    navTimerId = window.setInterval(syncNavButtons, NAV_UPDATE_INTERVAL);
    syncNavButtons();
  };

  const observeLightbox = function () {
    const observer = new MutationObserver(function () {
      syncNavButtons();
    });

    observer.observe(document.body, {
      childList: true,
      subtree: true,
      attributes: true,
      attributeFilter: ["style", "class"]
    });
  };

  $(document).on("keydown.centroServiziLightbox", handleKeydown);
  $(window).on("keydown.centroServiziLightbox", handleKeydown);
  $(window).on("resize.centroServiziLightbox", syncNavButtons);
  $(document).on("click.centroServiziLightbox", ".thickbox", function () {
    startNavSync();
    window.setTimeout(syncNavButtons, 80);
  });

  $(function () {
    startNavSync();
    observeLightbox();
  });
})(jQuery);
