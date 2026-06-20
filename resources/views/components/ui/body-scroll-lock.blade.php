@pushOnce('bladcn-scripts')
    <script>
        bladcnOnAlpine((Alpine) => {
            if (window.bladcnBodyScrollLock) {
                return;
            }

            window.bladcnBodyScrollLock = {
                count: 0,
                originalPaddingRight: '',

                getScrollbarWidth() {
                    return Math.max(
                        0,
                        window.innerWidth - document.documentElement
                        .clientWidth,
                    );
                },

                lock() {
                    this.count += 1;

                    if (this.count !== 1) {
                        return;
                    }

                    this.originalPaddingRight = document.body.style
                        .paddingRight;

                    const scrollbarWidth = this.getScrollbarWidth();

                    if (scrollbarWidth > 0) {
                        const currentPadding =
                            Number.parseFloat(
                                window.getComputedStyle(document.body)
                                .paddingRight,
                            ) || 0;

                        document.body.style.paddingRight =
                            `${currentPadding + scrollbarWidth}px`;
                    }

                    document.body.classList.add('overflow-hidden');
                },

                unlock() {
                    this.count = Math.max(0, this.count - 1);

                    if (this.count !== 0) {
                        return;
                    }

                    document.body.classList.remove('overflow-hidden');
                    document.body.style.paddingRight = this
                        .originalPaddingRight;
                },
            };
        });
    </script>
@endPushOnce
