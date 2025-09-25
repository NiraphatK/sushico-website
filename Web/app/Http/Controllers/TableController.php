<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Pagination\Paginator;
use App\Models\TableModel;

class TableController extends Controller
{
    public function __construct()
    {
        // ใช้ middleware 'auth:user' เพื่อบังคับให้ต้องล็อกอินในฐานะ admin/staff ก่อนใช้งาน controller นี้
        // ถ้าไม่ล็อกอินหรือไม้ได้ใช้ guard 'user' จะถูก redirect ไปหน้า login
        $this->middleware(['auth:user', 'role:ADMIN,STAFF']);
    }

    public function index()
    {
        Paginator::useBootstrap();
        $tables = TableModel::orderBy('table_id', 'desc')->paginate(5);
        return view('table.list', compact('tables'));
    }

    public function adding()
    {
        return view('table.create');
    }

    public function create(Request $request)
    {
        // ข้อความ error
        $messages = [
            'table_number.required' => 'กรุณากรอกเลขโต๊ะ',
            'table_number.max'      => 'เลขโต๊ะยาวเกินไป (ไม่เกิน 10 ตัวอักษร)',
            'table_number.unique'   => 'เลขโต๊ะนี้ถูกใช้ไปแล้ว',
            'capacity.required'     => 'กรุณาใส่ความจุของโต๊ะ',
            'capacity.integer'      => 'ความจุต้องเป็นจำนวนเต็ม',
            'capacity.min'          => 'ความจุต่ำสุดคือ 1',
            'capacity.max'          => 'ความจุสูงสุดคือ 10',
            'seat_type.required'    => 'กรุณาเลือกประเภทที่นั่ง',
            'seat_type.in'          => 'ประเภทที่นั่งต้องเป็น BAR หรือ TABLE เท่านั้น',
            'is_active.in'          => 'ค่าสถานะไม่ถูกต้อง',
        ];

        // ตรวจสอบข้อมูล
        $validator = Validator::make($request->all(), [
            'table_number' => 'required|string|max:10|unique:tables,table_number',
            'capacity'     => 'required|integer|min:1|max:10',
            'seat_type'    => 'required|in:BAR,TABLE',
            'is_active'    => 'nullable|in:0,1',
        ], $messages);

        if ($validator->fails()) {
            return redirect('table/adding')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            TableModel::create([
                'table_number' => strip_tags($request->table_number),
                'capacity'     => (int) $request->capacity,
                'seat_type'    => $request->seat_type,
                'is_active'    => $request->boolean('is_active', true) ? 1 : 0,
            ]);

            Alert::success('เพิ่มโต๊ะสำเร็จ');
            return redirect('/table');
        } catch (\Exception $e) {
            // สำหรับ debug: return response()->json(['error' => $e->getMessage()], 500);
            return view('errors.404');
        }
    }

    public function edit($table_id)
    {
        try {
            $table = TableModel::findOrFail($table_id);

            if (isset($table)) {
                $table_id     = $table->table_id;
                $table_number = $table->table_number;
                $capacity     = $table->capacity;
                $seat_type    = $table->seat_type;
                $is_active    = $table->is_active;

                return view('table.edit', compact(
                    'table_id',
                    'table_number',
                    'capacity',
                    'seat_type',
                    'is_active'
                ));
            }
        } catch (\Exception $e) {
            // สำหรับ debug: return response()->json(['error' => $e->getMessage()], 500);
            return view('errors.404');
        }
    }

    public function update($table_id, Request $request)
    {
        $messages = [
            'table_number.required' => 'กรุณากรอกเลขโต๊ะ',
            'table_number.max'      => 'เลขโต๊ะยาวเกินไป (ไม่เกิน 10 ตัวอักษร)',
            'table_number.unique'   => 'เลขโต๊ะนี้ถูกใช้ไปแล้ว',
            'capacity.required'     => 'กรุณาใส่ความจุของโต๊ะ',
            'capacity.integer'      => 'ความจุต้องเป็นจำนวนเต็ม',
            'capacity.min'          => 'ความจุต่ำสุดคือ 1',
            'capacity.max'          => 'ความจุสูงสุดคือ 10',
            'seat_type.required'    => 'กรุณาเลือกประเภทที่นั่ง',
            'seat_type.in'          => 'ประเภทที่นั่งต้องเป็น BAR หรือ TABLE เท่านั้น',
            'is_active.in'          => 'ค่าสถานะไม่ถูกต้อง',
        ];

        $validator = Validator::make($request->all(), [
            'table_number' => 'required|string|max:10|unique:tables,table_number,' . $table_id . ',table_id',
            'capacity'     => 'required|integer|min:1|max:10',
            'seat_type'    => 'required|in:BAR,TABLE',
            'is_active'    => 'nullable|in:0,1',
        ], $messages);

        if ($validator->fails()) {
            return redirect('table/' . $table_id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $table = TableModel::findOrFail($table_id);

            $table->table_number = strip_tags($request->table_number);
            $table->capacity     = (int) $request->capacity;
            $table->seat_type    = $request->seat_type;
            $table->is_active    = $request->boolean('is_active', $table->is_active) ? 1 : 0;

            $table->save();

            Alert::success('อัปเดตข้อมูลโต๊ะสำเร็จ');
            return redirect('/table');
        } catch (\Exception $e) {
            // สำหรับ debug: return response()->json(['error' => $e->getMessage()], 500);
            return view('errors.404');
        }
    }

    public function remove($table_id)
    {
        try {
            $table = TableModel::find($table_id);

            if (!$table) {
                Alert::error('ไม่พบข้อมูลโต๊ะ');
                return redirect('table');
            }

            $table->delete();

            Alert::success('ลบข้อมูลโต๊ะสำเร็จ');
            return redirect('table');
        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect('table');
        }
    }
}
