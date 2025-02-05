"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.toBase37 = toBase37;
exports.fromBase37 = fromBase37;
exports.toTitleCase = toTitleCase;
exports.toSafeName = toSafeName;
exports.toDisplayName = toDisplayName;
function toBase37(string) {
    string = string.trim();
    var l = 0n;
    for (var i = 0; i < string.length && i < 12; i++) {
        var c = string.charCodeAt(i);
        l *= 37n;
        if (c >= 0x41 && c <= 0x5a) {
            // A-Z
            l += BigInt(c + 1 - 0x41);
        }
        else if (c >= 0x61 && c <= 0x7a) {
            // a-z
            l += BigInt(c + 1 - 0x61);
        }
        else if (c >= 0x30 && c <= 0x39) {
            // 0-9
            l += BigInt(c + 27 - 0x30);
        }
    }
    return l;
}
// prettier-ignore
var BASE37_LOOKUP = [
    '_', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i',
    'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
    't', 'u', 'v', 'w', 'x', 'y', 'z',
    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
];
function fromBase37(value) {
    // >= 37 to the 12th power
    if (value < 0n || value >= 6582952005840035281n) {
        return "invalid_name";
    }
    if (value % 37n === 0n) {
        return "invalid_name";
    }
    var len = 0;
    var chars = Array(12);
    while (value !== 0n) {
        var l1 = value;
        value /= 37n;
        chars[11 - len++] = BASE37_LOOKUP[Number(l1 - value * 37n)];
    }
    return chars.slice(12 - len).join("");
}
function toTitleCase(str) {
    return str.replace(/\w\S*/g, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
    });
}
function toSafeName(name) {
    return fromBase37(toBase37(name));
}
function toDisplayName(name) {
    return toTitleCase(toSafeName(name).replaceAll("_", " ")).replaceAll("_", "&nbsp;");
}
