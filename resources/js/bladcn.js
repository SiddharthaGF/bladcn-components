/**
 * Register Alpine.data factories on first load and after wire:navigate.
 * Load this file from resources/js/app.js: import './bladcn';
 * Helpers are also registered on demand via @pushOnce('bladcn-scripts') in UI components.
 */
window.bladcnOnAlpine =
  window.bladcnOnAlpine ??
  ((callback) => {
    const run = () => {
      if (typeof window.Alpine === "undefined") {
        return;
      }

      callback(window.Alpine);
    };

    if (typeof window.Alpine !== "undefined") {
      run();

      return;
    }

    document.addEventListener("alpine:init", run, { once: true });
  });

window.bladcnRegister =
  window.bladcnRegister ??
  ((name, factory) => {
    bladcnOnAlpine((Alpine) => {
      Alpine.data(name, factory);
    });
  });

bladcnRegister("bladcnScrollArea", () => ({
  updateThumbs() {
    const viewport = this.$refs.viewport;

    if (!viewport) {
      return;
    }

    this.updateThumb("vertical", viewport);
    this.updateThumb("horizontal", viewport);
  },

  updateThumb(orientation, viewport) {
    const thumb = this.$refs[`${orientation}Thumb`];

    if (!thumb) {
      return;
    }

    if (orientation === "vertical") {
      if (viewport.scrollHeight <= viewport.clientHeight) {
        thumb.style.height = "0px";

        return;
      }

      const ratio = viewport.clientHeight / viewport.scrollHeight;
      const thumbHeight = Math.max(ratio * viewport.clientHeight, 24);
      const maxScroll = viewport.scrollHeight - viewport.clientHeight;

      thumb.style.height = `${thumbHeight}px`;
      thumb.style.width = "";
      thumb.style.transform = `translateY(${(viewport.scrollTop / maxScroll) * (viewport.clientHeight - thumbHeight)}px)`;

      return;
    }

    if (viewport.scrollWidth <= viewport.clientWidth) {
      thumb.style.width = "0px";

      return;
    }

    const ratio = viewport.clientWidth / viewport.scrollWidth;
    const thumbWidth = Math.max(ratio * viewport.clientWidth, 24);
    const maxScroll = viewport.scrollWidth - viewport.clientWidth;

    thumb.style.width = `${thumbWidth}px`;
    thumb.style.height = "";
    thumb.style.transform = `translateX(${(viewport.scrollLeft / maxScroll) * (viewport.clientWidth - thumbWidth)}px)`;
  },

  init() {
    this.$nextTick(() => this.updateThumbs());
    this.$refs.viewport?.addEventListener("scroll", () => this.updateThumbs());
    window.addEventListener("resize", () => this.updateThumbs());
  },
}));

bladcnRegister("buttonCopyCode", () => ({
  isCopied: false,

  copy() {
    const wrapper = this.$el.closest(
      '[data-slot="highlighted-code-content"], [data-slot="highlighted-code"]',
    );
    const codeBlock = wrapper?.querySelector(
      '[data-slot="highlighted-code-block"]',
    );
    if (!codeBlock) return;

    const code = codeBlock.dataset.code;
    if (!code) return;

    if (navigator.clipboard?.writeText) {
      navigator.clipboard.writeText(code);
    } else {
      const textarea = document.createElement("textarea");
      textarea.value = code;
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand("copy");
      textarea.remove();
    }

    this.isCopied = true;
    setTimeout(() => {
      this.isCopied = false;
    }, 2000);
  },
}));
