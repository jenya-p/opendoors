export default {
    data() {
        return {
            isMobile: window.innerWidth < 980
        }
    }, created() {
        let $v = this;
        window.addEventListener('resize', () => {
            $v.isMobile = window.innerWidth < 980
        });
    }
}
