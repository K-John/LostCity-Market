<?php

namespace App\Http\Controllers\Admin;

use App\Data\User\AdminUserData;
use App\Http\Traits\HandlesQuerySort;
use App\Models\User;
use App\Pages\Admin\UserFiltersData;
use App\Pages\Admin\UsersIndexPage;
use App\Pages\Admin\UsersShowPage;
use App\Services\FilterService;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class UserController
{
    use HandlesQuerySort;

    public function index(Request $request)
    {
        $defaultFilterValues = [
            'page' => 1,
            'search' => '',
            'is_banned' => false,
            'is_admin' => false,
            'sort' => '-id',
        ];

        $sessionKey = 'admin.users.index.filters';

        $filters = FilterService::resolve(
            $request,
            $sessionKey,
            $defaultFilterValues
        );

        $request->query->set('sort', $filters['sort']);
        $request->query->set('page', $filters['page']);

        $allowedSorts = ['id', 'name', 'email', 'created_at'];

        $users = $this->applyFilterSort($request, User::with('usernames'), $allowedSorts, defaultSort: '-id')
            ->with('usernames');

        if (! is_null($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];

            $users = $users->where(function ($q) use ($search) {
                $q->where('discord_id', $search)
                    ->orWhere('name', $search)
                    ->orWhere('email', $search)
                    ->orWhereHas('usernames', fn ($uq) => $uq->where('username', $search));
            });
        }

        $is_banned = filter_var($filters['is_banned'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($is_banned) {
            $users = $users->where('is_banned', true);
        }

        $is_admin = filter_var($filters['is_admin'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if ($is_admin) {
            $users = $users->where('is_admin', true);
        }

        return inertia('admin/users/index/page', new UsersIndexPage(
            users: AdminUserData::collect($users->paginate(20)->withQueryString(), PaginatedDataCollection::class),
            filters: new UserFiltersData(
                search: $filters['search'] ?? null,
                is_banned: filter_var($filters['is_banned'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
                is_admin: filter_var($filters['is_admin'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
                sort: $filters['sort'] ?? null,
            )
        ));
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(Request $request, User $user)
    {
        return inertia('admin/users/show/page', new UsersShowPage(
            selected_user: AdminUserData::from($user),
            listing_count: $user->listings()->count(),
            usernames: $user->usernames->pluck('username')->toArray() ?? []
        ));
    }

    public function edit(User $user) {}

    public function update(Request $request, User $user) {}

    public function destroy(User $user) {}
}
