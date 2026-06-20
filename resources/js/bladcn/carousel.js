import EmblaCarousel from "embla-carousel";
import Autoplay from "embla-carousel-autoplay";

const carouselPlugins = {
  autoplay: () => Autoplay({ delay: 2000, stopOnInteraction: true }),
};

export function registerBladcnCarousel() {
  bladcnRegister("bladcnCarousel", (config = {}) => ({
    orientation: config.orientation ?? "horizontal",
    opts: config.opts ?? {},
    plugin: config.plugin ?? null,
    index: 0,
    slideCount: 0,
    canScrollPrev: false,
    canScrollNext: false,
    api: null,
    pluginInstance: null,

    init() {
      this.$nextTick(() => {
        const viewport = this.$refs.viewport;

        if (!viewport) {
          return;
        }

        const plugins = [];

        if (this.plugin && carouselPlugins[this.plugin]) {
          this.pluginInstance = carouselPlugins[this.plugin]();
          plugins.push(this.pluginInstance);
        }

        const initEmbla = () => {
          if (this.api) {
            this.api.destroy();
          }

          this.api = EmblaCarousel(
            viewport,
            {
              ...this.opts,
              axis: this.orientation === "horizontal" ? "x" : "y",
            },
            plugins,
          );

          this.slideCount = this.api.scrollSnapList().length;
          this.syncFromApi();

          this.api.on("select", () => this.syncFromApi());
          this.api.on("reInit", () => {
            this.slideCount = this.api.scrollSnapList().length;
            this.syncFromApi();
          });

          requestAnimationFrame(() => {
            this.api?.reInit();
            this.api?.scrollTo(0, true);
          });
        };

        initEmbla();

        this.onResize = () => {
          initEmbla();
        };

        window.addEventListener("resize", this.onResize);
      });
    },

    destroy() {
      if (this.onResize) {
        window.removeEventListener("resize", this.onResize);
      }

      this.api?.destroy();
      this.api = null;
      this.pluginInstance = null;
    },

    pauseAutoplay() {
      this.pluginInstance?.stop?.();
    },

    resumeAutoplay() {
      this.pluginInstance?.reset?.();
    },

    syncFromApi() {
      if (!this.api) {
        return;
      }

      this.index = this.api.selectedScrollSnap();
      this.canScrollPrev = this.api.canScrollPrev();
      this.canScrollNext = this.api.canScrollNext();
    },

    scrollTo(targetIndex) {
      this.api?.scrollTo(targetIndex);
    },

    scrollPrev() {
      this.api?.scrollPrev();
    },

    scrollNext() {
      this.api?.scrollNext();
    },

    onKeydown(event) {
      if (this.orientation === "horizontal" && event.key === "ArrowLeft") {
        event.preventDefault();
        this.scrollPrev();

        return;
      }

      if (this.orientation === "horizontal" && event.key === "ArrowRight") {
        event.preventDefault();
        this.scrollNext();

        return;
      }

      if (this.orientation === "vertical" && event.key === "ArrowUp") {
        event.preventDefault();
        this.scrollPrev();

        return;
      }

      if (this.orientation === "vertical" && event.key === "ArrowDown") {
        event.preventDefault();
        this.scrollNext();
      }
    },
  }));
}
