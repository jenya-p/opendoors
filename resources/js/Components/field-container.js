// Vue 3


let refresh = function (el) {
    let p = el.clientWidth >= 800;
    if(p){
        el.classList.remove('vertical');
    } else {
        el.classList.add('vertical');
    }
    return function(){
        if (el.clientWidth < 800) {
            if (p) {
                el.classList.add('vertical');
                p = false;
            }
        } else {
            if (!p) {
                el.classList.remove('vertical');
                p = true;
            }
        }
    }
}

export default {
    mounted: (el, binding) => {
        window.addEventListener('resize', refresh(el));
    },
    unmounted: (el, binding) => {
        window.removeEventListener('resize', refresh(el));
    },
}


