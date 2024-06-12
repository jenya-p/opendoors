import {DateTime} from 'luxon';

export default function (date, format = 'dd MMM yyyy HH:mm', na = 'N/A') {

    if (!date) {
        return na
    }
    var d;
    if (date.length == 10) {
        d = DateTime.fromFormat(date, 'yyyy-MM-dd');
    } else if (date.length > 19) {
        d = DateTime.fromISO(date);
    } else {
        d = DateTime.fromFormat(date, 'yyyy-MM-dd HH:mm:ss');
    }

    format = format
        .replace('MMMM', '{{{1}}}')
        .replace('MMM', '{{{2}}}');

    let result = d.toFormat(format);
    if (result.indexOf('{{{1}}}') !== -1) {
        result = result.replace('{{{1}}}', [
            'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря',
        ][d.toFormat('M') - 1]);
    }

    if (result.indexOf('{{{2}}}') !== -1) {
        result = result.replace('{{{2}}}', [
            'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'
        ][d.toFormat('M') - 1])
    }

    return result;

};
