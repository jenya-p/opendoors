import _isString from "lodash/isString";

export default function (value) {
    if(_isString(value)){
        return value.replaceAll("\n", '<br>').replaceAll(";", '<br>');
    } else {
        return value;
    }
};
