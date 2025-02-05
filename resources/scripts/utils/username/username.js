"use strict";
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.isUsernameValid = isUsernameValid;
exports.isUsernameExplicit = isUsernameExplicit;
var jString_js_1 = require("./jString.js");
var obscenity_1 = require("obscenity");
var usernameCannotStartWith = [' ', 'mod_', 'm0d_'];
var usernameCannotEndWith = [' '];
var staticBlockedUsernames = [
    // thank you all:
    'Andrew',
    'Paul',
    'Ian',
    'Ash', // always just "mod ash" but put some respect on em!
    // and every other mod that contributed to the success and longevity of the game
    // extras that players want to abuse and will surely circumvent :(
    'Admin',
    'Administrator',
    'Mod',
    'Moderator'
];
var blockedUsernames = new obscenity_1.RegExpMatcher(__assign(__assign({}, obscenity_1.englishDataset.build()), obscenity_1.englishRecommendedTransformers));
function isUsernameValid(username) {
    var name = (0, jString_js_1.toSafeName)(username);
    if (name === 'invalid_name' || name.length < 1 || name.length > 12) {
        return {
            success: false,
            message: 'You must enter a valid username.'
        };
    }
    return { success: true };
}
function isUsernameExplicit(username) {
    var name = (0, jString_js_1.toSafeName)(username);
    var displayName = (0, jString_js_1.toDisplayName)(username);
    var passPrefixSuffixTest = true;
    usernameCannotStartWith.forEach(function (prefix) {
        if (name.startsWith(prefix))
            passPrefixSuffixTest = false;
    });
    usernameCannotEndWith.forEach(function (suffix) {
        if (name.endsWith(suffix))
            passPrefixSuffixTest = false;
    });
    if (blockedUsernames.hasMatch(displayName) || staticBlockedUsernames.includes(displayName) || !passPrefixSuffixTest) {
        return {
            success: false,
            message: 'That username is not available.'
        };
    }
    return { success: true };
}
