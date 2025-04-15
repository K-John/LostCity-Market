<?php

namespace App\Http\Controllers\Admin;

use App\Data\Banner\BannerData;
use App\Data\Banner\BannerFormData;
use App\Data\Item\ItemData;
use App\Enums\BannerType;
use App\Http\Traits\HandlesQuerySort;
use App\Models\Banner;
use App\Pages\Admin\BannersCreatePage;
use App\Pages\Admin\BannersIndexPage;
use Illuminate\Http\Request;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\PaginatedDataCollection;

class BannerController
{
    use HandlesQuerySort;

    public function index(Request $request)
    {
        $allowedSorts = ['id', 'message', 'start_at', 'end_at', 'type'];

        $banners = $this->applyFilterSort($request, Banner::query(), $allowedSorts, defaultSort: '-id')
            ->with('items')
            ->paginate(20);

        return inertia('admin/banners/index/page', new BannersIndexPage(
            banners: BannerData::collect($banners, PaginatedDataCollection::class),
        ));
    }

    public function create()
    {
        return inertia('admin/banners/create/page', new BannersCreatePage(
            bannerForm: new BannerFormData(
                id: null,
                message: '',
                type: BannerType::Default,
                is_active: true,
                start_at: null,
                end_at: null,
                items: null,
            )
        ));
    }

    public function store(BannerFormData $data)
    {
        $bannerData = collect($data->toArray())->except(['items', 'display_scope'])->toArray();

        $banner = Banner::create($bannerData);

        if ($data->items) {
            $banner->items()->sync(collect($data->items)->pluck('id')->toArray());
        }

        return to_route('admin.banners.index')->success('Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return inertia('admin/banners/create/page', new BannersCreatePage(
            bannerForm: new BannerFormData(
                id: $banner->id,
                message: $banner->message,
                type: BannerType::from($banner->type),
                is_active: $banner->is_active,
                start_at: $banner->start_at,
                end_at: $banner->end_at,
                items: ItemData::collect($banner->items, DataCollection::class),
            )
        ));
    }

    public function update(BannerFormData $data, Banner $banner)
    {
        $bannerData = collect($data->toArray())->except(['items', 'display_scope'])->toArray();

        $banner->update($bannerData);

        if ($data->items) {
            $banner->items()->sync(collect($data->items)->pluck('id')->toArray());
        } else {
            $banner->items()->detach();
        }

        return to_route('admin.banners.index')->success('Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        $banner->delete();

        return to_route('admin.banners.index')->success('Banner deleted successfully.');
    }
}
