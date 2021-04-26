<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Library\FilesControl;
use App\mdCategoriesStore;
use App\mdRelCategoriesStore;
use App\mdSegments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class pageSegmentsController extends Controller
{
    public function showStoresBySegment($segment = null)
    {
        $pathImagens = FilesControl::getPathImages();

        if(!is_null($segment)){
            if(mdSegments::where('slug', $segment)->exists()){

                $Segment = mdSegments::where('slug', $segment)->first();

                $Stores = mdSegments::find($Segment->id)->pesqStoresBySegment('S','S')->get();

                for ($i = 0; $i < count($Stores); $i++){
                    $inStores[$i] = $Stores[$i]->id;
                }

                $RelCategoriesStores = mdRelCategoriesStore::select('category')->whereIn('store', $inStores)->groupBy('category')->get();

                for ($i = 0; $i < count($RelCategoriesStores); $i++){
                    $inCategoriesStores[$i] = $RelCategoriesStores[$i]->category;
                }

                $CategoriesStores = mdCategoriesStore::whereIn('id', $inCategoriesStores)->where('status', 'S')->get();

                return view('site.segment.pageSegment',[
                    'Segment'               =>  $Segment,
                    'listCategoriesStores'  =>  $CategoriesStores,
                    'listStore'             =>  $Stores,
                    'inStores'              =>  $inStores,
                    'pathImagens'           =>  $pathImagens
                ]);
            }
        }
    }

    public function showStoresBySegmentByCategory($segment = null, $category = null)
    {
        $pathImagens = FilesControl::getPathImages();

        if( (!is_null($segment)) && (!is_null($category)) ){
            if(mdSegments::where('slug', $segment)->exists()){

                $Segment = mdSegments::where('slug', $segment)->first();

                $Stores = mdSegments::find($Segment->id)->pesqStoresBySegment('S','S')->get();

                for ($i = 0; $i < count($Stores); $i++){
                    $inStores[$i] = $Stores[$i]->id;
                }

                $RelCategoriesStores = mdRelCategoriesStore::select('category')->whereIn('store', $inStores)->groupBy('category')->get();

                for ($i = 0; $i < count($RelCategoriesStores); $i++){
                    $inCategoriesStores[$i] = $RelCategoriesStores[$i]->category;
                }

                $CategoriesStores = mdCategoriesStore::whereIn('id', $inCategoriesStores)->where('status', 'S')->get();

                $CategoryStores = mdCategoriesStore::where('slug', $category)->first();

                $Stores = mdCategoriesStore::find($CategoryStores->id)->allStoresByCategory->whereIn('id', $inStores);

                return view('site.segment.pageSegment',[
                    'Segment'               =>  $Segment,
                    'listCategoriesStores'  =>  $CategoriesStores,
                    'listStore'             =>  $Stores,
                    'inStores'              =>  $inStores,
                    'pathImagens'           =>  $pathImagens
                ]);
            }
        }
    }
}
