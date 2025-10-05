@extends('frontend')

@section('css_before')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="{{ asset('css/menu-detail.css') }}">
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/menu-detail.js') }}" defer></script>
@endsection

@section('body')
    <div class="row">
        @foreach ($menu as $item)
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 mt-4">
                <article class="menu-card h-100" role="button" data-menu-id="{{ $item->menu_id }}"
                    data-name="{{ e($item->name) }}" data-desc="{{ e($item->description) }}" data-price="{{ $item->price }}"
                    data-img="{{ $item->image_path ? asset('storage/' . $item->image_path) : '' }}">
                    <div class="menu-thumb">
                        @if ($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" class="card-img-top rounded-top"
                                alt="{{ $item->name }}">
                        @else
                            <div
                                class="ratio ratio-4x3 bg-light rounded-top d-flex align-items-center justify-content-center text-muted">
                                No image
                            </div>
                        @endif
                    </div>

                    <div class="menu-body">
                        <div class="menu-title">{{ $item->name }}</div>
                        <p class="menu-desc mt-1">{{ Str::limit($item->description, 80, '...') }}</p>
                        <div class="btn btn-salmon w-100 mt-2">฿{{ number_format($item->price, 2) }}</div>
                    </div>
                </article>
            </div>

            {{-- เก็บ rich text (HTML) ของรายละเอียดไว้ใน DOM (ไว้ให้ modal ดึงไปแสดง) --}}
            <div id="detail-{{ $item->menu_id }}" class="d-none">{!! $item->detail !!}</div>
        @endforeach
    </div>

    <div class="row mt-2 mb-2">
        <div class="col-sm-5 col-md-5"></div>
        <div class="col-sm-3 col-md-3 text-center">
            {{ $menu->links() }}
        </div>
    </div>
@endsection

@section('footer')
@endsection
