// Vue 3


let refresh = function (el) {
    let p = null;
    if (el.clientWidth < 800) {
        el.classList.add('vertical');
        el.classList.remove('wide');
        p = 1;

    } else if (el.clientWidth > 1200) {
        el.classList.add('wide');
        el.classList.remove('vertical');
        p = 2;

    } else {
        el.classList.remove('vertical');
        el.classList.remove('wide');
        p = 3;

    }

    return function () {
        if (el.clientWidth < 800) {
            if (p !== 1) {
                el.classList.add('vertical');
                el.classList.remove('wide');
                p = 1;
            }
        } else if (el.clientWidth > 1200) {
            if (p !== 2) {
                el.classList.add('wide');
                el.classList.remove('vertical');
                p = 2;
            }
        } else {
            if (p !== 3) {
                el.classList.remove('vertical');
                el.classList.remove('wide');
                p = 3;
            }
        }
    }
}

export default {
    mounted: (el, binding) => {
        let e = refresh(el);
        window.addEventListener('resize', refresh(el));
        setTimeout(e, 1);
    },
    unmounted: (el, binding) => {
        window.removeEventListener('resize', refresh(el));
    },
}


