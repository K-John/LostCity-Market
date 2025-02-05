import { isUsernameExplicit } from "./username.js";

const username = process.argv[2];

const result = isUsernameExplicit(username);
console.log(JSON.stringify(result));