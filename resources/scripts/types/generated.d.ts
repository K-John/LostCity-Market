declare namespace Data.Item {
export type ItemData = {
id: number;
name: string;
slug: string;
cost: number;
};
}
declare namespace Data.Listing {
export type ListingData = {
canManage: boolean;
id: number;
type: Enums.ListingType;
price: number;
quantity: number;
notes: string | null;
username: string;
item: Data.Item.ItemData | null;
tokenPrefix: string;
updatedAt: string;
deletedAt: string | null;
};
export type ListingFormData = {
id: number | null;
type: Enums.ListingType;
price: string;
quantity: number | null;
notes: string | null;
username: string;
item: Data.Item.ItemData | null;
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
declare namespace Data.Token {
export type TokenFormData = {
token: string;
};
}
declare namespace Enums {
export type ListingType = 'buy' | 'sell';
export type NotificationType = 'success' | 'error' | 'warning' | 'info' | 'default';
}
declare namespace Pages {
export type HomeIndexPage = {
listingType: Enums.ListingType;
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
};
export type ItemsShowPage = {
listingType: Enums.ListingType;
item: Data.Item.ItemData;
listingForm: Data.Listing.ListingFormData;
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
deletedListings: Array<Data.Listing.ListingData>;
};
export type ListingsCreatePage = {
listingForm: Data.Listing.ListingFormData;
};
export type ListingsEditPage = {
listingForm: Data.Listing.ListingFormData;
};
export type ListingsIndexPage = {
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
token: string;
tokenForm: Data.Token.TokenFormData;
};
export type TokensShowPage = {
token: string;
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
};
export type UsersIndexPage = {
username: string;
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
};
}
