@extends('frontend')
@section('css_before')
@section('navbar')
@endsection

@section('body')
    <div class="row">
        @foreach ($menu as $item)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                <article class="menu-card h-100">
                    <div class="menu-thumb">
                        <img src="{{ asset('storage/' . $item->image_path) }}" class="card-img-top rounded-top"
                            alt="{{ $item->name }}">
                    </div>
                    <div class="menu-body">
                        <div class="menu-title"> {{ $item->name }}</div>
                        <p class="menu-desc mt-1"> {{ Str::limit($item->description, 80, '...') }}</p>
                        <div class="btn btn-salmon w-100 mt-2"> ฿{{ number_format($item->price, 2) }}</div>
                    </div>
                </article>
            </div>
        @endforeach
    </div>




    <div class="row mt-2 mb-2">
        <!-- Pagination links -->
        <div class="col-sm-5 col-md-5"></div>
        <div class="col-sm-3 col-md-3">
            <center>
                {{ $menu->links() }}
            </center>
        </div>
    </div>
@endsection
@endsection

@section('footer')
@endsection

@section('js_before')
@endsection
