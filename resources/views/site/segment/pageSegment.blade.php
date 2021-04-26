@extends('site.layout_master.site_design')

@section('content')
    <!-- Section tittle -->
    <div class="row">
        <div class="col-xl-12">
            <div class="text-center">
                <h2>{{$Segment->name}}</h2>
            </div>
        </div>
    </div>
    <section class="new-product-area section-padding30">
        <div class="container">
            <div class="row">
            <div class="col-lg-4">
                <div class="blog_right_sidebar">
                    <aside class="single_sidebar_widget post_category_widget">
                        <h4 class="widget_title">Categoria</h4>
                        <ul class="list cat-list">
                            <li>
                                <a href="{{ route('segment.page', ['segment' => $Segment->slug]) }}" class="d-flex">
                                    <p>Todos</p>
                                    <p>( {{ $Segment::find($Segment->id)->pesqStoresBySegment('S','S')->count() }} )</p>
                                </a>
                            </li>
                            @foreach($listCategoriesStores as $CategoryStores)
                                <li>
                                    <a href="{{ route('segment.category.page', ['segment' => $Segment->slug, 'category' => $CategoryStores->slug] ) }}" class="d-flex">
                                        <p>{{ $CategoryStores->name }} </p>
                                        <p>( {{ $CategoryStores::find($CategoryStores->id)->allStoresByCategory->where('status', 'S')->whereIn('id', $inStores)->count() }} )</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </aside>
                </div>
            </div>
            <div class="col-lg-8 mb-5 mb-lg-0">
                <div class="row">
                    @foreach($listStore as $Store)
                        <div class="col-12 col-sm-6 col-md-6">
                            <div class="single-new-pro mb-30 text-center">
                                <div class="row">
                                <div class="col">
                                <div class="product-img">
                                    <a href="{{ route('store.page', ['segment' => $Segment->slug, 'store' => $Store->slug]) }}" class="d-flex">
                                        <img src="{{ $pathImagens }}/stores/logo/{{ $Store->id}}/small/{{ $Store->path_image_logo }}"/>
                                    </a>
                                </div>
                                </div>
                                <div class="col">
                                <div><h6>{{ $Store->name}}</h6></div>
                                </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
    </section>
@endsection
