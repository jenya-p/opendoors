var alphabet = "АБВГДЕЖЗИЙКЛМНОПРСТУФХЦЧШЩЭЮЯ"

export default function (value) {

    var result = "";
    let number = value;
    do {
        var charIndex = number % alphabet.length
        var quotient = number/alphabet.length
        if(charIndex-1 == -1){
            charIndex = alphabet.length
            quotient--;
        }
        result =  alphabet.charAt(charIndex-1) + result;
        number = parseInt(quotient);
    } while (quotient>=1)
    return result;

};
