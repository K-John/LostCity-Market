<?php

namespace App\Http\Controllers\Admin;

use App\Data\Item\AdminItemData;
use App\Data\Item\AdminItemFormData;
use App\Http\Controllers\Controller;
use App\Http\Traits\HandlesQuerySort;
use App\Models\Item;
use App\Pages\Admin\ItemFiltersData;
use App\Pages\Admin\ItemsEditPage;
use App\Pages\Admin\ItemsIndexPage;
use App\Services\FilterService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\LaravelData\PaginatedDataCollection;

class ItemController extends Controller
{
    use HandlesQuerySort;

    public function index(Request $request)
    {
        $defaultFilterValues = [
            'page' => 1,
            'search' => '',
            'is_active' => true,
            'sort' => '-id',
        ];

        $sessionKey = 'admin.items.index.filters';

        $filters = FilterService::resolve(
            $request,
            $sessionKey,
            $defaultFilterValues
        );

        $request->query->set('sort', $filters['sort']);
        $request->query->set('page', $filters['page']);

        $allowedSorts = ['id', 'name', 'is_active', 'created_at'];

        $items = $this->applyFilterSort($request, Item::query(), $allowedSorts, defaultSort: '-id');

        if (! is_null($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];

            $items = $items->where('name', 'LIKE', '%' . $search . '%')
                ->orWhere('slug', 'LIKE', '%' . str_replace(' ', '_', $search) . '%');
        }

        if (! is_null($filters['is_active'])) {
            $items = $items->where('is_active', filter_var($filters['is_active'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
        }

        return inertia('admin/items/index/page', new ItemsIndexPage(
            items: AdminItemData::collect($items->paginate(20)->withQueryString(), PaginatedDataCollection::class),
            filters: new ItemFiltersData(
                search: $filters['search'] ?? null,
                is_active: filter_var($filters['is_active'] ?? null, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
                sort: $filters['sort'] ?? null,
            )
        ));
    }

    public function edit(Item $item)
    {
        return Inertia::modal('admin/items/edit/page', new ItemsEditPage(
            item: AdminItemData::from($item),
            itemForm: new AdminItemFormData(
                id: $item->id,
                name: $item->name,
                slug: $item->slug,
                cost: $item->cost,
                is_active: $item->is_active
            ),
        ))
            ->baseRoute('admin.items.index');
    }

    public function update(AdminItemFormData $data, Item $item)
    {
        $item->timestamps = false;

        $item->update($data->toArray());

        return back()->success('The item has been updated');
    }
}
