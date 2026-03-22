/* ============================================================
   dashboard.js — Terminal-Luxe UI interactions
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {
  /* ── Profile dropdown ──────────────────────────────────── */
  const profile = document.getElementById("profileTrigger");
  const menu = document.getElementById("profileMenu");

  if (profile && menu) {
    profile.addEventListener("click", (e) => {
      e.stopPropagation();
      const isOpen = menu.classList.contains("open");
      menu.classList.toggle("open", !isOpen);
      profile.classList.toggle("active", !isOpen);
    });

    document.addEventListener("click", () => {
      menu.classList.remove("open");
      profile.classList.remove("active");
    });

    menu.addEventListener("click", (e) => e.stopPropagation());
  }

  /* ── Threshold type toggle (add product page) ──────────── */
  const toggleBtns = document.querySelectorAll(".type-toggle-btn");
  const inputWrap = document.getElementById("thresholdInputWrap");
  const thresholdInput = document.getElementById("thresholdValue");
  const suffixEl = document.getElementById("thresholdSuffix");
  const typeHidden = document.getElementById("thresholdType");

  if (toggleBtns.length && inputWrap) {
    toggleBtns.forEach((btn) => {
      btn.addEventListener("click", () => {
        const type = btn.dataset.type; // 'percent' | 'absolute'

        // Update active state
        toggleBtns.forEach((b) => b.classList.remove("active"));
        btn.classList.add("active");

        // Update hidden input
        if (typeHidden) typeHidden.value = type;

        // Show input
        inputWrap.classList.add("visible");

        // Update suffix and placeholder
        if (suffixEl) suffixEl.textContent = type === "percent" ? "%" : "₽";
        if (thresholdInput) {
          thresholdInput.placeholder =
            type === "percent" ? "напр. 10" : "напр. 1500";
          thresholdInput.focus();
        }
      });
    });
  }

  /* ── URL input — auto-focus ────────────────────────────── */
  const urlInput = document.querySelector('.cmd-form input[type="url"]');
  if (urlInput) urlInput.focus();

  /* ── DELETE confirm ────────────────────────────────────── */
  document.querySelectorAll(".action-btn.danger").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      if (
        !confirm("CONFIRM_DELETE? This node will be removed from monitoring.")
      ) {
        e.preventDefault();
        e.stopPropagation();
      }
    });
  });

  /* ── Occasional CRT glitch flicker ────────────────────── */
  const glitchLoop = () => {
    const el = document.querySelector(".scanlines");
    if (el) {
      el.style.opacity = "0.3";
      setTimeout(() => {
        el.style.opacity = "1";
      }, 55);
      setTimeout(() => {
        el.style.opacity = "0.6";
      }, 90);
      setTimeout(() => {
        el.style.opacity = "1";
      }, 130);
    }
    setTimeout(glitchLoop, 6000 + Math.random() * 8000);
  };
  setTimeout(glitchLoop, 4000);
});
