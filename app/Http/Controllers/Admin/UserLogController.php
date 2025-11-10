<?php

namespace App\Http\Controllers\Admin;

use App\Data\ActivityLog\ActivityLogData;
use App\Data\User\AdminUserData;
use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesQuerySort;
use App\Models\User;
use App\Pages\Admin\UserFiltersData;
use App\Pages\Admin\UserLogsIndexPage;
use App\Services\FilterService;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Spatie\LaravelData\PaginatedDataCollection;

class UserLogController extends Controller
{
    use HandlesQuerySort;

    public function index(Request $request, User $user)
    {
        $defaultFilterValues = [
            'page' => 1,
            'search' => '',
            'sort' => '-id',
        ];

        $sessionKey = 'admin.users.logs.index.filters';

        $filters = FilterService::resolve(
            $request,
            $sessionKey,
            $defaultFilterValues
        );

        $request->query->set('sort', $filters['sort']);
        $request->query->set('page', $filters['page']);

        $allowedSorts = ['id', 'event', 'subject_type', 'subject_id', 'created_at'];

        $logsQuery = Activity::query()
            ->with(['causer', 'subject'])
            ->where(function ($q) use ($user) {
                $q->causedBy($user)
                    ->orWhere(function ($qq) use ($user) {
                        $qq->forSubject($user);
                    });
            });

        $logs = $this->applyFilterSort($request, $logsQuery, $allowedSorts, defaultSort: '-id')
            ->with(['causer', 'subject']);

        return inertia('admin/userlogs/index/page', new UserLogsIndexPage(
            selected_user: AdminUserData::from($user),
            logs: ActivityLogData::collect($logs->paginate(20), PaginatedDataCollection::class),
            filters: UserFiltersData::from(request()->only(['search', 'sort']))
        ));
    }

    public function show(User $user, ActivityLogData $log)
    {
        return null;
    }
}
