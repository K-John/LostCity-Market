declare namespace Data.Item {
export type ItemData = {
id: number;
name: string;
slug: string;
};
}
declare namespace Data.Shared {
export type NotificationData = {
type: Enums.NotificationType;
body: string;
};
export type SharedData = {
user: Data.Shared.UserData;
notification: Data.Shared.NotificationData | null;
};
export type UserData = {
name: string;
email: string;
};
}
declare namespace Enums {
export type ListingType = 'buy' | 'sell';
export type NotificationType = 'success' | 'error' | 'warning' | 'info' | 'default';
}
declare namespace Pages.Items {
export type ItemsShowPage = {
item: Data.Item.ItemData;
listingForm: any;
};
}
