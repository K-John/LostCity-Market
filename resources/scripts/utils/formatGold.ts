export default function formatGold(value: number): string {
    if (value >= 1_000_000) {
        return `${Number((value / 1_000_000).toFixed(2)).toLocaleString()}M`;
    }

    if (value >= 1_000) {
        return `${Number((value / 1_000).toFixed(2)).toLocaleString()}K`;
    }

    return value.toLocaleString();
}