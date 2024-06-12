import _isArray from "lodash/isArray";
import _isEmpty from "lodash/isEmpty";
import _map from "lodash/map";
import _isNull from "lodash/isNull";
import _isUndefined from "lodash/isUndefined";
import _keys from "lodash/keys";
import _isObject from "lodash/isObject";


export function findByIds (options, ids) {
    if (_isArray(ids) && _isArray(options)) {
        return options.filter(itm => ids.findIndex(id => itm.id == id) !== -1);
    } else {
        return [];
    }
};

export function highlight(domEl){
    domEl.classList.add("highlighted");
    setTimeout(function(){
        domEl.classList.remove("highlighted");
    }, 700);
}

export function paramsToUrl(params) {

    return _map(params, function (value, key) {

        if (_isArray(value)) {
            if(_isEmpty(value)){
                return null;
            } else {
                return _map(value, function (value2) {
                    return encodeURIComponent(key + '[]') + '=' + encodeURIComponent(value2);
                }).join('&');
            }
        } else if (_isObject(value)) {
            return null;
        } else  if(_isNull(value) || _isUndefined(value) || value === ""){
            return null;
        } else {
            return key + '=' + encodeURIComponent(value);
        }

    }).filter(itm => !_isEmpty(itm)).join('&');
}

export function countNotEmpty(obj, fields = null) {
    let count = 0;
    if(fields === null){
        fields = _keys(obj);
    }
    for (let field of fields){
        if(obj.hasOwnProperty(field)) {
            if(_isArray(obj[field])){
                if(obj[field].length > 0){
                    count++;
                }
            } else if (obj[field] !== '' && obj[field] !== null){
                count++;
            }
        }
    }
    return count;
}

export function countNotEmptyDateRange(obj, fields = null) {
    let count = 0;
    if(!_isArray(fields)){
        fields = [fields];
    }
    for (let field of fields){
        if(Utils.countNotEmpty(obj, [field + '_from', field + '_to'])){
            count++;
        }
    }
    return count;
};


export async function bufferCopy (value){
    await navigator.clipboard.writeText(value);
};

export function dateGetterSetter (l1, l2, forceUpdate = false){
    return {
        get() {
            return DateUtils.fromFormatToIso(this[l1][l2]);
        },
        set(value) {
            this[l1][l2] = DateUtils.fromIsoToFormat(value);
            if(forceUpdate) this.$forceUpdate();
        }
    }
};
