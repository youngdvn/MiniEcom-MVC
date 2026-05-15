<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CouponRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $status = (string) $request->status;
        $type = trim((string) $request->type);
        $limit = (int) ($request->limit ?? 10);
        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $list = Coupon::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('code', 'like', "%{$keyword}%");
            })
            ->when($status !== '' && in_array($status, ['0', '1'], true), function ($query) use ($status) {
                $query->where('status', (int) $status);
            })
            ->when($type !== '' && in_array($type, ['percent', 'fixed'], true), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate($limit)
            ->withQueryString();

        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('status', 1)->count(),
            'expired' => Coupon::whereNotNull('ends_at')->where('ends_at', '<', now())->count(),
            'used_up' => Coupon::whereNotNull('usage_limit')->whereColumn('used_count', '>=', 'usage_limit')->count(),
        ];

        return view('admin.coupon.index', compact('list', 'keyword', 'status', 'type', 'limit', 'stats'));
    }

    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(CouponRequest $request)
    {
        $data = $request->validated();
        Coupon::create([
            ...$data,
            'code' => strtoupper($data['code']),
            'min_order' => $data['min_order'] ?? 0,
            'max_discount' => $data['max_discount'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
        ]);

        return redirect()->route('admin.coupon.index')->with('message', 'Đã thêm mã giảm giá');
    }

    public function edit($id)
    {
        $model = Coupon::findOrFail($id);
        return view('admin.coupon.edit', compact('model'));
    }

    public function update(CouponRequest $request, $id)
    {
        $data = $request->validated();
        $model = Coupon::findOrFail($id);
        $model->update([
            ...$data,
            'code' => strtoupper($data['code']),
            'min_order' => $data['min_order'] ?? 0,
            'max_discount' => $data['max_discount'] ?? null,
            'usage_limit' => $data['usage_limit'] ?? null,
        ]);

        return redirect()->route('admin.coupon.index')->with('message', 'Đã cập nhật mã giảm giá');
    }

    public function destroy($id)
    {
        Coupon::destroy($id);
        return redirect()->route('admin.coupon.index')->with('message', 'Đã xóa mã giảm giá');
    }
}
