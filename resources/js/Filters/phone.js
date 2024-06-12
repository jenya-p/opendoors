export default function (value) {

    if(!_.isString(value) || value == '') {return ''};
    return '+7 (' + value.substr(0, 3) + ')'  +
        ' ' + value.substr( 3, 3) +
        '-' + value.substr( 6, 2) +
        '-' + value.substr( 8);

};
