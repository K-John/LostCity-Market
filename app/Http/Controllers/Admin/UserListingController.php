<?php

namespace App\Http\Controllers\Admin;

use App\Data\Listing\AdminListingData;
use App\Data\User\AdminUserData;
use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesQuerySort;
use App\Models\Listing;
use App\Models\User;
use App\Pages\Admin\UserListingFiltersData;
use App\Pages\Admin\UserListingsIndexPage;
use App\Services\FilterService;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class UserListingController extends Controller
{
    use HandlesQuerySort;

    public function index(Request $request, User $user)
    {
        $defaultFilterValues = [
            'page' => 1,
            'item' => '',
            'sort' => '-updated_at',
        ];

        $sessionKey = 'admin.users.listings.index.filters';

        $filters = FilterService::resolve(
            $request,
            $sessionKey,
            $defaultFilterValues
        );

        $request->query->set('sort', $filters['sort']);
        $request->query->set('page', $filters['page']);

        $allowedSorts = ['id', 'item', 'updated_at', 'created_at'];

        $listings = $this->applyFilterSort($request, Listing::where('user_id', $user->id)->with('item')->withTrashed(), $allowedSorts, defaultSort: '-updated_at')
            ->with('item');

        if (! is_null($filters['item']) && $filters['item'] !== '') {
            $item = $filters['item'];

            $listings = $listings
                ->whereHas('item', function ($query) use ($item) {
                    $query->where('name', 'LIKE', '%' . $item . '%')
                        ->orWhere('slug', 'LIKE', '%' . str_replace(' ', '_', $item) . '%');
                });
        }

        return inertia('admin/userlistings/index/page', new UserListingsIndexPage(
            selected_user: AdminUserData::from($user),
            listings: AdminListingData::collect($listings->paginate(20)->withQueryString(), PaginatedDataCollection::class),
            filters: new UserListingFiltersData(
                item: $filters['item'] ?? null,
                sort: $filters['sort'] ?? null,
            )
        ));
    }
}
