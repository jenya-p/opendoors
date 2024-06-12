export default function (value, s1 = '', s2 = '', s3 = '') {

    if (value === 0) {
        return '';
    }

    if (value > 10 && value < 20) {
        return s3;
    }
    const last = value % 10;
    if (last === 1) {
        return s1;
    }
    if (last >= 2 && last <= 4) {
        return s2;
    } else {
        return s3;
    }
};
