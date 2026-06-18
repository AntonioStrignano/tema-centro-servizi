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
    const next = document.getElementById("TB_next");
    if (isVisible(next)) {
      next.click();
      return true;
    }
    return false;
  };

  const triggerPrev = function () {
    const prev = document.getElementById("TB_prev");
    if (isVisible(prev)) {
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

  $(document).on("keydown.centroServiziLightbox", handleKeydown);
  $(window).on("keydown.centroServiziLightbox", handleKeydown);
})(jQuery);
