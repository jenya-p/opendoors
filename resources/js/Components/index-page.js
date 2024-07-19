import _isObject from "lodash/isObject";

export default {
    methods: {
        highlightItem(highlightId, items) {
            let $v = this;
            if (highlightId) {
                for (var itm of items) {
                    if (itm.id == highlightId) {
                        itm.highlight = true;
                        setTimeout(() => {
                            var itm = items.find(itm2 => itm2.id == highlightId);
                            if (itm) {
                                itm.highlight = false;
                                $v.$forceUpdate();
                            }
                        }, 1000);
                        break;
                    }
                }
            }
        }
    },
    created() {
        this.$initHighlighter = (event) => {
            if (event.origin !== document.location.origin) {
                return;
            }
            if (_isObject(event.data) && event.data.hasOwnProperty('item_updated')) {
                this.refreshPage(event.data.item_updated);
            }
        };
    },
    mounted() {
        window.addEventListener("message", this.$initHighlighter, false);
    },
    unmounted() {
        window.removeEventListener("message", this.$initHighlighter);
    }
}
