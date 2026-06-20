{{-- Must run before @stack('bladcn-scripts'): inline component scripts call these synchronously. --}}
{{-- bladcn.js also assigns the same helpers (??=) after the Vite module loads. --}}
<script>
    window.bladcnOnAlpine = window.bladcnOnAlpine ?? ((callback) => {
        const run = () => {
            if (typeof window.Alpine === 'undefined') {
                return;
            }

            callback(window.Alpine);
        };

        if (typeof window.Alpine !== 'undefined') {
            run();

            return;
        }

        document.addEventListener('alpine:init', run, {
            once: true
        });
    });

    window.bladcnRegister = window.bladcnRegister ?? ((name, factory) => {
        bladcnOnAlpine((Alpine) => {
            Alpine.data(name, factory);
        });
    });
</script>
