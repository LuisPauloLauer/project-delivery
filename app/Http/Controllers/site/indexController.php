<?php

namespace App\Http\Controllers\site;

use App\Http\Controllers\Controller;
use App\Library\FilesControl;
use App\mdSegments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class indexController extends Controller
{
    public function index()
    {
        $Segments = mdSegments::pesqSegments();

        $pathImagens = FilesControl::getPathImages();

        return view('site.siteHome',[
            'listSegment'   =>  $Segments,
            'pathImagens'   =>  $pathImagens
        ]);
    }
}
