(function ($) {
  "use strict";

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

  const triggerNext = function () {
    const next = document.querySelector("#TB_next a, #TB_next");
    if (next && isVisible(next)) {
      next.click();
      return true;
    }
    return false;
  };

  const triggerPrev = function () {
    const prev = document.querySelector("#TB_prev a, #TB_prev");
    if (prev && isVisible(prev)) {
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

  const refreshControls = function () {
    if (hasVisibleWindow()) {
      enhanceA11yControls();
    }
  };

  $(document).on("keydown.centroServiziLightbox", handleKeydown);
  $(window).on("keydown.centroServiziLightbox", handleKeydown);
  $(window).on("resize.centroServiziLightbox", refreshControls);
  $(document).on("click.centroServiziLightbox", ".thickbox", function () {
    window.setTimeout(refreshControls, 80);
  });

  $(function () {
    refreshControls();
  });
})(jQuery);
