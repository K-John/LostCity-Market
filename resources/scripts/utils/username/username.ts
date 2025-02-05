import { toDisplayName, toSafeName } from './jString.js';
import {
	RegExpMatcher,
	englishDataset,
	englishRecommendedTransformers,
} from 'obscenity';

type UsernameCheckResult = {
    success: boolean;
    message?: string;
}

const usernameCannotStartWith = [' ', 'mod_', 'm0d_'];
const usernameCannotEndWith = [' '];

const staticBlockedUsernames = [
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

const blockedUsernames = new RegExpMatcher({
    ...englishDataset.build(),
    ...englishRecommendedTransformers,
});

export function isUsernameValid(username: string): UsernameCheckResult {
    const name = toSafeName(username);
    if (name ==='invalid_name' || name.length < 1 || name.length > 12) {
        return {
            success: false,
            message: 'You must enter a valid username.'
        };
    }

    return { success: true };
}

export function isUsernameExplicit(username: string): UsernameCheckResult {
    const name = toSafeName(username);
    const displayName = toDisplayName(username);

    let passPrefixSuffixTest = true;
    
    usernameCannotStartWith.forEach(prefix => {
        if (name.startsWith(prefix)) passPrefixSuffixTest = false;
    });
    usernameCannotEndWith.forEach(suffix => {
        if (name.endsWith(suffix)) passPrefixSuffixTest = false;
    });

    if (blockedUsernames.hasMatch(displayName) || staticBlockedUsernames.includes(displayName) || !passPrefixSuffixTest) {
        return {
            success: false,
            message: 'That username is not available.'
        };
    }

    return { success: true };
}