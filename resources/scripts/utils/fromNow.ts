import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import updateLocale from "dayjs/plugin/updateLocale";

dayjs.extend(relativeTime);
dayjs.extend(updateLocale);

dayjs.updateLocale("en", {
  relativeTime: {
    future: "%s",
    past: "%s",
    s: "few secs",
    m: "1 min",
    mm: "%d mins",
    h: "1 hr",
    hh: "%d hrs",
    d: "1 day",
    dd: "%d days",
    M: "1 mo",
    MM: "%d mo",
    y: "1 yr",
    yy: "%d yr",
  },
});

/**
 * Convert a date to a shortened relative time format
 */
export const fromNow = (date: string) => {
  return dayjs(date).fromNow();
};