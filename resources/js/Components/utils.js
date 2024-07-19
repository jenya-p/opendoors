import _isArray from "lodash/isArray";
import _isEmpty from "lodash/isEmpty";
import _map from "lodash/map";
import _isNull from "lodash/isNull";
import _isUndefined from "lodash/isUndefined";
import _keys from "lodash/keys";
import _isObject from "lodash/isObject";
import {router} from "@inertiajs/vue3";


export function findByIds(options, ids) {
    if (ids != null && options !== null &&
        typeof ids.findIndex == 'function' && typeof options.filter == 'function') {
        return options.filter(itm => ids.findIndex(id => itm.id == id) !== -1);
    } else {
        return [];
    }
};

export function selectable(options, container, field) {
    return {
        get() {
            return this[options].find(itm => itm.id == this[container][field]);
        },
        set(value) {
            if (value) {
                this[container][field] = value.id;
            } else {
                this[container][field] = null;
            }
        }
    }
}

export function selectables(options, container, field) {
    return {
        get() {
            return findByIds(this[options], this[container][field]);
        },
        set(value) {
            this[container][field] = value.map(itm => itm.id);
        }
    }
}

export function highlight(domEl) {
    domEl.classList.add("highlighted");
    setTimeout(function () {
        domEl.classList.remove("highlighted");
    }, 700);
}

export function paramsToUrl(params) {

    return _map(params, function (value, key) {

        if (_isArray(value)) {
            if (_isEmpty(value)) {
                return null;
            } else {
                return _map(value, function (value2) {
                    return encodeURIComponent(key + '[]') + '=' + encodeURIComponent(value2);
                }).join('&');
            }
        } else if (_isObject(value)) {
            return null;
        } else if (_isNull(value) || _isUndefined(value) || value === "") {
            return null;
        } else {
            return key + '=' + encodeURIComponent(value);
        }

    }).filter(itm => !_isEmpty(itm)).join('&');
}

export function countNotEmpty(obj, fields = null) {
    let count = 0;
    if (fields === null) {
        fields = _keys(obj);
    }
    for (let field of fields) {
        if (obj.hasOwnProperty(field)) {
            if (_isArray(obj[field])) {
                if (obj[field].length > 0) {
                    count++;
                }
            } else if (obj[field] !== '' && obj[field] !== null) {
                count++;
            }
        }
    }
    return count;
}

export function countNotEmptyDateRange(obj, fields = null) {
    let count = 0;
    if (!_isArray(fields)) {
        fields = [fields];
    }
    for (let field of fields) {
        if (Utils.countNotEmpty(obj, [field + '_from', field + '_to'])) {
            count++;
        }
    }
    return count;
};


export async function bufferCopy(value) {
    await navigator.clipboard.writeText(value);
};

export function dateGetterSetter(l1, l2, forceUpdate = false) {
    return {
        get() {
            return DateUtils.fromFormatToIso(this[l1][l2]);
        },
        set(value) {
            this[l1][l2] = DateUtils.fromIsoToFormat(value);
            if (forceUpdate) this.$forceUpdate();
        }
    }
};


export function openEditor(pageName, parameterName = null) {
    if(parameterName === null){
        parameterName = pageName;
    }
    return function (item, event) {
        let params = {};
        params[parameterName] = item.id;
        if (event.ctrlKey) {
            localStorage.setItem('back_state', JSON.stringify({
                type: pageName + '.' + item.id,
                url: document.location.toString(),
                scroll: (window.pageYOffset || document.scrollTop) - (document.clientTop || 0)
            }));
            this.$inertia.visit(route('admin.' + pageName + '.edit', params));
        } else {
            window.open(route('admin.' + pageName + '.edit', params), '_blank');
        }
    }
};


export function closeEditor(page_name, item_id, withHighlight = true) {
    let $v = this;
    if (window.opener) {
        if(withHighlight){
            window.opener.postMessage({'item_updated': item_id}, document.location.origin);
        }
        window.close();
    } else {
        if (localStorage.hasOwnProperty('back_state')) {
            let backState = JSON.parse(localStorage.getItem('back_state'));
            if (backState && backState.type == page_name + '.' + item_id) {
                router.visit(backState.url, {
                    onSuccess() {
                        window.scrollTo(0, backState.scroll);
                        if(withHighlight) {
                            window.postMessage({'item_updated': item_id}, document.location.origin);
                        }
                    }
                });
                return;
            }
        }
        router.visit(route('admin.' + page_name + '.index'));
    }
}

