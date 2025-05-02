export default function formatGold(value: number): string {
    const truncate = (num: number): number => Math.floor(num * 100) / 100;

    const format = (val: number, suffix: string): string => {
        const truncated = truncate(val);
        return `${truncated % 1 === 0 ? truncated.toFixed(0) : truncated}${suffix}`;
    };

    if (value >= 1_000_000) {
        return format(value / 1_000_000, "M");
    }

    if (value >= 1_000) {
        return format(value / 1_000, "K");
    }

    return value.toString();
}