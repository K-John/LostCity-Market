declare namespace Data.Item {
export type ItemData = {
id: number;
name: string;
slug: string;
};
}
declare namespace Data.Listing {
export type ListingData = {
id: number;
type: Enums.ListingType;
price: string;
quantity: number;
notes: string | null;
username: string;
item: Data.Item.ItemData | null;
createdAt: string;
};
export type ListingFormData = {
id: number | null;
type: Enums.ListingType;
price: string;
quantity: number | null;
notes: string | null;
username: string;
item: Data.Item.ItemData;
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
listingForm: Data.Listing.ListingFormData;
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
};
}
