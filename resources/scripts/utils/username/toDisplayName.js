import { toDisplayName } from "./jString.js";

const username = process.argv[2];

const result = toDisplayName(username);
console.log(JSON.stringify(result));