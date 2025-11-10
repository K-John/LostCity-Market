declare namespace Data.Banner {
export type BannerData = {
displayScope: Enums.BannerDisplayScope;
id: number;
type: Enums.BannerType;
message: string;
isActive: boolean;
startAt: string | null;
endAt: string | null;
items: Array<Data.Item.ItemData> | null;
};
export type BannerFormData = {
id: number | null;
type: Enums.BannerType;
message: string;
is_active: boolean;
start_at: string | null;
end_at: string | null;
items: Array<Data.Item.ItemFormData> | null;
display_scope: Enums.BannerDisplayScope | null;
};
}
declare namespace Data.Item {
export type AdminItemData = {
id: number;
name: string;
slug: string;
cost: number;
isActive: boolean;
};
export type AdminItemFormData = {
id: number;
name: string;
slug: string;
cost: number;
is_active: boolean;
};
export type ItemData = {
isFavorite: boolean;
id: number;
name: string;
slug: string;
cost: number;
banners: Array<Data.Banner.BannerData> | null;
};
export type ItemFormData = {
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
updatedAt: string;
soldAt: string | null;
deletedAt: string | null;
pausedAt: string | null;
userId: number | null;
};
export type ListingFormData = {
id: number | null;
type: Enums.ListingType;
price: string;
quantity: number | null;
notes: string | null;
username: string;
item_id: number | null;
usernames: Array<any> | null;
};
export type ListingSaleFormData = {
id: number;
price: string;
quantity: number;
redirect: string | null;
};
}
declare namespace Data.Referral {
export type ReferralData = {
id: number;
code: string;
createdAt: string;
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
globalBanners: Array<Data.Banner.BannerData> | null;
};
export type UserData = {
name: string;
is_admin: boolean;
};
}
declare namespace Data.User {
export type AdminUserData = {
id: number;
name: string;
email: string;
discordId: string;
createdAt: string;
isAdmin: boolean;
isBanned: boolean;
bannedAt: string | null;
};
}
declare namespace Enums {
export type BannerDisplayScope = 'global' | 'item' | 'user';
export type BannerType = 'default' | 'success' | 'info' | 'warning' | 'error';
export type FavoritesListingType = 'all' | 'buy' | 'sell';
export type HomeTabType = 'buy' | 'sell' | 'favorites';
export type ListingType = 'buy' | 'sell';
export type NotificationType = 'success' | 'error' | 'warning' | 'info' | 'default';
}
declare namespace Pages {
export type FavoritesIndexPage = {
items: {data:Array<Data.Item.ItemData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
};
export type HomeIndexPage = {
tab: Enums.HomeTabType;
listingType: Enums.FavoritesListingType;
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
favorites: Array<Data.Item.ItemData> | null;
usernames: Array<any>;
};
export type ItemsShowPage = {
listingType: Enums.ListingType;
item: Data.Item.ItemData;
listingForm: Data.Listing.ListingFormData;
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
soldListings: Array<Data.Listing.ListingData>;
usernames: Array<any>;
banners: Array<Data.Banner.BannerData>;
};
export type ListingsCreatePage = {
listingForm: Data.Listing.ListingFormData;
};
export type ListingsDeletePage = {
listing: Data.Listing.ListingData;
redirect: string | null;
};
export type ListingsEditPage = {
listing: Data.Listing.ListingData;
listingForm: Data.Listing.ListingFormData;
};
export type ListingsIndexPage = {
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
expiredListings: Array<Data.Listing.ListingData>;
pausedListings: Array<Data.Listing.ListingData>;
usernames: Array<any>;
};
export type ListingsSalePage = {
listing: Data.Listing.ListingData;
listingSaleForm: Data.Listing.ListingSaleFormData;
};
export type ReferralsIndexPage = {
referrals: {data:Array<Data.Referral.ReferralData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
};
export type UsersIndexPage = {
username: string;
is_banned: boolean;
listings: {data:Array<Data.Listing.ListingData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
back: string | null;
};
}
declare namespace Pages.Admin {
export type BannersCreatePage = {
bannerForm: Data.Banner.BannerFormData;
};
export type BannersIndexPage = {
banners: {data:Array<Data.Banner.BannerData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
};
export type ItemFiltersData = {
search: string | null;
is_active: boolean | null;
sort: string | null;
};
export type ItemsEditPage = {
item: Data.Item.AdminItemData;
itemForm: Data.Item.AdminItemFormData;
};
export type ItemsIndexPage = {
items: {data:Array<Data.Item.AdminItemData>;links:Array<{url:string | null;label:string;active:boolean;}>;meta:{current_page:number;first_page_url:string;from:number | null;last_page:number;last_page_url:string;next_page_url:string | null;path:string;per_page:number;prev_page_url:string | null;to:number | null;total:number;};};
filters: Pages.Admin.ItemFiltersData;
};
}
declare namespace Pages.Auth {
export type AccountIndexPage = {
name: string;
register_date: string;
listing_count: number;
usernames: Array<any>;
};
}
