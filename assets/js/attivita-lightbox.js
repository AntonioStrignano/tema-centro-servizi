(function ($) {
  "use strict";

  const hasVisibleWindow = function () {
    const windowEl = document.getElementById("TB_window");
    return !!windowEl && windowEl.style.display !== "none";
  };

  const triggerNext = function () {
    const next = document.getElementById("TB_next");
    if (next && next.offsetParent !== null) {
      next.click();
      return true;
    }
    return false;
  };

  const triggerPrev = function () {
    const prev = document.getElementById("TB_prev");
    if (prev && prev.offsetParent !== null) {
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

  $(document).on("keydown", function (event) {
    if (!hasVisibleWindow()) {
      return;
    }

    if (event.key === "ArrowRight") {
      if (triggerNext()) {
        event.preventDefault();
      }
      return;
    }

    if (event.key === "ArrowLeft") {
      if (triggerPrev()) {
        event.preventDefault();
      }
      return;
    }

    if (event.key === "Escape") {
      if (triggerClose()) {
        event.preventDefault();
      }
    }
  });
})(jQuery);
