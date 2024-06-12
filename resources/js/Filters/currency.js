export default function (value, showZerro = false, decimalCount) {

    if(decimalCount == undefined){
        if(value % 1 == 0){
            decimalCount = 0;
        } else {
            decimalCount = 2;
        }
    }

    value = Math.round(value * 100) / 100;
    try {
        if (value == null || (0 == value && showZerro == false)) {
            return ''
        }
        ;

        const negativeSign = value < 0 ? "-" : "";

        let i = parseInt(value = Math.abs(Number(value) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + ' ' : '') +
            i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + ' ') +
            (decimalCount ? '.' + Math.abs(value - i).toFixed(decimalCount).slice(2) : "");
    } catch (e) {
        console.log(e)
    }

};
