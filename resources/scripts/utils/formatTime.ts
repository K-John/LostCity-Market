import dayjs from "dayjs";
import LocalizedFormat from "dayjs/plugin/localizedFormat";

dayjs.extend(LocalizedFormat);

export const formatTime = (date: string) => {
    return dayjs(date).format("lll");
};
