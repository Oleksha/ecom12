<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Front\IndexService;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __construct(
        protected IndexService $indexService
    )
    {}

    public function index()
    {
        $banners = $this->indexService->getHomePageBanners();
        $featured = $this->indexService->featuredProducts();
        $newArrivals = $this->indexService->newArrivalProducts();
        return view('front.index')
            ->with($banners)
            ->with($featured)
            ->with($newArrivals);
    }
}
