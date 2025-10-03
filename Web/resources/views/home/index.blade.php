@extends('frontend')
@section('css_before')
@section('navbar')
@endsection

@section('body')
    <!-- MENU HIGHLIGHTS -->
    <div class="col-12">
        <h2 class="mb-3">เมนูไฮไลต์ <span class="text-salmon">วันนี้</span></h2>
        <div class="divider-chop"></div>
    </div>

    <div class="row g-4" data-aos="fade-up">
        <!-- Card 1 -->
        <div class="col-12 col-md-6 col-lg-4">
            <article class="menu-card h-100">
                <div class="menu-thumb">
                    <div class="menu-badges">
                        <span class="badge-fresh">Fresh Today</span>
                        <span class="badge-chef">Chef’s Choice</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=1200&auto=format&fit=crop"
                        alt="Salmon Nigiri">
                </div>
                <div class="menu-body">
                    <div class="menu-title">ซาลมอน นิกิริ <span class="price ms-auto">฿120</span></div>
                    <p class="menu-desc mt-1">ข้าวซูชิหอมญี่ปุ่นหน้าแซลมอนสดสไลซ์ หน้านุ่มละลายในปาก ไม่ต้องเคี้ยว</p>
                    <a href="/reservation" class="btn btn-salmon w-100 mt-2">จองโต๊ะ</a>
                </div>
            </article>
        </div>

        <!-- Card 2 -->
        <div class="col-12 col-md-6 col-lg-4">
            <article class="menu-card h-100">
                <div class="menu-thumb">
                    <div class="menu-badges">
                        <span class="badge-fresh">Fresh Today</span>
                        <span class="badge-spicy">Spicy</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1579584425555-c3ce17fd4351?q=80&w=1200&auto=format&fit=crop"
                        alt="Spicy Tuna Roll">
                </div>
                <div class="menu-body">
                    <div class="menu-title">สไปซี่ทูน่า โรล <span class="price ms-auto">฿220</span></div>
                    <p class="menu-desc mt-1">ทูน่าคุณภาพคลุกซอสเผ็ดหอม ม้วนสาหร่ายกรอบ เคลือบงาหอม</p>
                    <a href="/menus" class="btn btn-ghost w-100 mt-2">ดูรายละเอียด</a>
                </div>
            </article>
        </div>

        <!-- Card 3 -->
        <div class="col-12 col-md-6 col-lg-4">
            <article class="menu-card h-100">
                <div class="menu-thumb">
                    <div class="menu-badges">
                        <span class="badge-chef">Omakase</span>
                    </div>
                    <img src="https://images.unsplash.com/photo-1548946526-f69e2424cf45?q=80&w=1200&auto=format&fit=crop"
                        alt="Omakase Set">
                </div>
                <div class="menu-body">
                    <div class="menu-title">โอมากาเสะ คอร์ส <span class="price ms-auto">เริ่ม ฿1,990</span></div>
                    <p class="menu-desc mt-1">ประสบการณ์ตามใจเชฟ 12 คำ วัตถุดิบตามฤดูกาลจากทะเลญี่ปุ่น</p>
                    <a href="/reservation" class="btn btn-salmon w-100 mt-2">จองคอร์ส</a>
                </div>
            </article>
        </div>
    </div>
@endsection
@endsection

@section('footer')
@endsection

@section('js_before')
@endsection
