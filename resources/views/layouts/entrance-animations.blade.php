{{-- Shared entrance animations for the school and administration modules. --}}
<script>
(() => {
    const content = document.querySelector('[data-module-content]');
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
    const printMedia = window.matchMedia('print');
    if (!content || reducedMotion.matches || printMedia.matches
        || !('IntersectionObserver' in window) || !('animate' in Element.prototype)) return;

    const animations = new Map();
    const targets = new Set();
    const selectors = [
        'h1', 'h2', 'h1 + p', 'h2 + p',
        'div.bg-white[class*="rounded"]',
        'form', '.overflow-x-auto', '.grid > a', 'a.inline-flex', '[role="alert"]',
        '[data-entrance]',
    ].join(',');
    const excluded = '[hidden], [aria-hidden="true"], .hidden, .fixed, dialog, '
        + '[role="dialog"], [data-entrance="off"], table, nav';

    // Animate each meaningful block as a whole, without nesting animations.
    content.querySelectorAll(selectors).forEach((element) => {
        if (element.closest(excluded)) return;
        let parent = element.parentElement;
        while (parent && parent !== content) {
            if (targets.has(parent)) return;
            parent = parent.parentElement;
        }
        targets.add(element);
    });

    const observer = new IntersectionObserver((entries) => {
        let order = 0;
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            const element = entry.target;
            observer.unobserve(element);
            if (element.contains(document.activeElement)) return;

            const animation = element.animate([
                { opacity: 0, transform: 'translateY(18px)' },
                { opacity: 1, transform: 'translateY(0)' },
            ], {
                duration: 450,
                delay: Math.min(order++, 4) * 60,
                easing: 'cubic-bezier(0.22, 1, 0.36, 1)',
                fill: 'backwards',
            });
            animations.set(element, animation);
            animation.onfinish = () => animations.delete(element);
        });
    }, { threshold: 0 });

    targets.forEach((element) => observer.observe(element));

    // Reveal a focused block immediately, including forms and their controls.
    content.addEventListener('focusin', (event) => {
        let element = event.target;
        while (element && element !== content) {
            if (targets.has(element)) {
                observer.unobserve(element);
                animations.get(element)?.cancel();
                animations.delete(element);
            }
            element = element.parentElement;
        }
    });

    const stopAnimations = () => {
        observer.disconnect();
        animations.forEach((animation) => animation.cancel());
        animations.clear();
    };
    reducedMotion.addEventListener('change', (event) => {
        if (event.matches) stopAnimations();
    });
    printMedia.addEventListener('change', (event) => {
        if (event.matches) stopAnimations();
    });
    window.addEventListener('beforeprint', stopAnimations);
})();
</script>
