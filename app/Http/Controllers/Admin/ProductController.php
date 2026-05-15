<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Support\Str;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword ?? '';
        $limit = $request->limit ?? 5;
        $sort = $request->sort ?? '';
        $min = $request->min ?? '';
        $max = $request->max ?? '';

        // giới hạn limit
        if (!in_array($limit,[5,10,20])) {
            $limit = 5;
        }

        $query = DB::table('products')
            ->join('categories', 'products.cateid', '=', 'categories.cateid')
            ->leftJoin('brands', 'products.brandid', '=', 'brands.id')
            ->select(
                'products.id',
                'products.proname',
                'products.price',
                'products.sale_price',
                'categories.catename',
                'brands.brandname'
            );

        // tìm kiếm tên sản phẩm
        if ($keyword != '') {
            $query->where('products.proname','like','%'.$keyword.'%');
        }

        // tìm theo khoảng giá
        if ($min != '') {
            $query->where('products.price','>=',$min);
        }

        if ($max != '') {
            $query->where('products.price','<=',$max);
        }

        // sắp xếp
        switch ($sort) {

            case 'name_asc':
                $query->orderBy('products.proname','asc');
                break;

            case 'name_desc':
                $query->orderBy('products.proname','desc');
                break;

            case 'price_asc':
                $query->orderBy('products.price','asc');
                break;

            case 'price_desc':
                $query->orderBy('products.price','desc');
                break;

            default:
                $query->orderBy('products.id','desc');
        }

        $products = $query->paginate($limit)->withQueryString();

        return view('admin.product.index',
            compact('products','keyword','limit','sort','min','max'));
    }

    public function index2(Request $request)
    {
        $keyword = trim((string) $request->keyword);
        $limit = (int) ($request->limit ?? 10);
        $sort = trim((string) $request->sort);
        $min = $request->min !== null && $request->min !== '' ? (int) $request->min : null;
        $max = $request->max !== null && $request->max !== '' ? (int) $request->max : null;
        $status = (string) $request->status;
        $cateid = $request->cateid !== null && $request->cateid !== '' ? (int) $request->cateid : null;
        $brandid = $request->brandid !== null && $request->brandid !== '' ? (int) $request->brandid : null;

        if (!in_array($limit, [5, 10, 20, 50], true)) {
            $limit = 10;
        }

        $products = Product::with([
            'category:cateid,catename',
            'brand:id,brandname'
        ])

        ->when($keyword, function ($query) use ($keyword) {
            $query->where('proname', 'like', '%' . $keyword . '%');
        })

        ->when(!is_null($min), function ($query) use ($min) {
            $query->where('price', '>=', $min);
        })

        ->when(!is_null($max), function ($query) use ($max) {
            $query->where('price', '<=', $max);
        })

        ->when($status !== '' && in_array($status, ['0', '1'], true), function ($query) use ($status) {
            $query->where('status', (int) $status);
        })

        ->when(!is_null($cateid), function ($query) use ($cateid) {
            $query->where('cateid', $cateid);
        })

        ->when(!is_null($brandid), function ($query) use ($brandid) {
            $query->where('brandid', $brandid);
        })

        ->when($sort, function ($query) use ($sort) {
            if ($sort == 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sort == 'price_desc') {
                $query->orderBy('price', 'desc');
            } elseif ($sort == 'name_asc') {
                $query->orderBy('proname', 'asc');
            } elseif ($sort == 'name_desc') {
                $query->orderBy('proname', 'desc');
            } elseif ($sort == 'newest') {
                $query->orderByDesc('id');
            } elseif ($sort == 'oldest') {
                $query->orderBy('id');
            }
        })

        ->when(!$sort, function ($query) {
            $query->orderByDesc('id');
        })

        ->paginate($limit)
        ->withQueryString();

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 1)->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'low_stock' => Product::whereBetween('stock_quantity', [1, 5])->count(),
        ];

        $categories = Category::select('cateid', 'catename')->orderBy('catename')->get();
        $brands = Brand::select('id', 'brandname')->orderBy('brandname')->get();

        return view('admin.product.index', compact(
            'products',
            'keyword',
            'limit',
            'sort',
            'min',
            'max',
            'status',
            'cateid',
            'brandid',
            'stats',
            'categories',
            'brands'
        ));
    }

    public function create()
    {

        $categories = Category::select('cateid','catename')->orderBy('catename')->get();
        $brands = Brand::select('id','brandname')->orderBy('brandname')->get();
                
        return view('admin.product.create', compact('categories','brands'));
    }

    public function store(ProductRequest $request)
    {
        try {
            $data = $request->validated();

            $filename = 'default.png';
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = uniqid() . '_' . Str::slug($data['proname'])
                    . '.' . $file->getClientOriginalExtension();
                $file->storeAs('products', $filename, 'public');
            }

            $product = Product::create([
                'proname' => $data['proname'],
                'slug' => $data['slug'],
                'price' => $data['price'],
                'sale_price' => isset($data['sale_price']) && $data['sale_price'] !== '' ? $data['sale_price'] : null,
                'stock_quantity' => $data['stock_quantity'],
                'brandid' => $data['brandid'] ?? null,
                'cateid' => $data['cateid'],
                'description' => $data['description'] ?? null,
                'thumbnail' => $filename,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            $variantsInput = collect($request->input('variants', []))
                ->filter(fn ($v) => !empty($v['size']))
                ->map(function ($v) {
                    $price = isset($v['price']) && $v['price'] !== '' ? (int) $v['price'] : null;
                    $salePrice = isset($v['sale_price']) && $v['sale_price'] !== '' ? (int) $v['sale_price'] : null;
                    if ($price && $salePrice && $salePrice >= $price) {
                        $salePrice = null;
                    }
                    return [
                        'size' => strtoupper(trim((string) $v['size'])),
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'stock_quantity' => (int) ($v['stock_quantity'] ?? 0),
                        'status' => (int) ($v['status'] ?? 1),
                    ];
                })
                ->unique('size')
                ->values();

            if ($variantsInput->isNotEmpty()) {
                $payload = $variantsInput->map(fn ($v) => [
                    ...$v,
                    'product_id' => $product->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->all();
                ProductVariant::insert($payload);
                $product->update(['stock_quantity' => (int) $variantsInput->sum('stock_quantity')]);
            }

            $dataImages = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $name = $product->id . '_' . time() . '_' . uniqid()
                        . '.' . $img->getClientOriginalExtension();
                    $img->storeAs('products', $name, 'public');
                    $dataImages[] = [
                        'image' => $name,
                        'product_id' => $product->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            if (!empty($dataImages)) {
                ProductImage::insert($dataImages);
            }

            return redirect()->route('admin.product.index')->with('message', 'Thêm sản phẩm thành công');

        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Thêm sản phẩm thất bại'])
                ->withInput();
        }
    }

    public function edit($id)
    {
        $model = Product::with(['images', 'variants'])->find($id);
        $categories = Category::select('cateid', 'catename')->orderBy('catename')->get();
        $brands = Brand::select('id', 'brandname')->orderBy('brandname')->get();

        return view('admin.product.edit', compact('model', 'categories', 'brands'));
    }

    public function update(ProductRequest $request, $id)
    {
        try {
            $data = $request->validated();

            $model = Product::find($id);
            $filename = $model->thumbnail;
            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $filename = uniqid() . '_' . Str::slug($data['proname'])
                    . '.' . $file->getClientOriginalExtension();
                $file->storeAs('products', $filename, 'public');
            }

            $model->update([
                'proname' => $data['proname'],
                'cateid' => $data['cateid'],
                'brandid' => $data['brandid'] ?? null,
                'slug' => $data['slug'],
                'price' => $data['price'],
                'sale_price' => isset($data['sale_price']) && $data['sale_price'] !== '' ? $data['sale_price'] : null,
                'stock_quantity' => $data['stock_quantity'],
                'description' => $data['description'] ?? null,
                'thumbnail' => $filename,
                'status' => $request->has('status') ? 1 : 0,
            ]);

            $variantsInput = collect($request->input('variants', []))
                ->filter(fn ($v) => !empty($v['size']))
                ->map(function ($v) {
                    $price = isset($v['price']) && $v['price'] !== '' ? (int) $v['price'] : null;
                    $salePrice = isset($v['sale_price']) && $v['sale_price'] !== '' ? (int) $v['sale_price'] : null;
                    if ($price && $salePrice && $salePrice >= $price) {
                        $salePrice = null;
                    }
                    return [
                        'id' => isset($v['id']) && $v['id'] !== '' ? (int) $v['id'] : null,
                        'size' => strtoupper(trim((string) $v['size'])),
                        'price' => $price,
                        'sale_price' => $salePrice,
                        'stock_quantity' => (int) ($v['stock_quantity'] ?? 0),
                        'status' => (int) ($v['status'] ?? 1),
                    ];
                })
                ->unique('size')
                ->values();

            $existingIds = $model->variants->pluck('id')->all();
            $keptIds = [];
            foreach ($variantsInput as $variantData) {
                if (!empty($variantData['id']) && in_array($variantData['id'], $existingIds, true)) {
                    $variant = $model->variants->firstWhere('id', $variantData['id']);
                    $variant->update([
                        'size' => $variantData['size'],
                        'price' => $variantData['price'],
                        'sale_price' => $variantData['sale_price'],
                        'stock_quantity' => $variantData['stock_quantity'],
                        'status' => $variantData['status'],
                    ]);
                    $keptIds[] = $variant->id;
                } else {
                    $variant = $model->variants()->create([
                        'size' => $variantData['size'],
                        'price' => $variantData['price'],
                        'sale_price' => $variantData['sale_price'],
                        'stock_quantity' => $variantData['stock_quantity'],
                        'status' => $variantData['status'],
                    ]);
                    $keptIds[] = $variant->id;
                }
            }

            if (!empty($existingIds)) {
                $toDelete = array_diff($existingIds, $keptIds);
                if (!empty($toDelete)) {
                    ProductVariant::whereIn('id', $toDelete)->delete();
                }
            }

            $model->refresh();
            if ($model->variants->isNotEmpty()) {
                $model->update([
                    'stock_quantity' => (int) $model->variants->sum('stock_quantity'),
                ]);
            }

            return redirect()->route('admin.product.index')->with('message', 'Cập nhật sản phẩm thành công');
        } catch (Throwable $e) {
            return back()
                ->withErrors(['general' => 'Cập nhật sản phẩm thất bại'])
                ->withInput();
        }
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->route('admin.product.index')->with('message', 'Xóa sản phẩm thành công');
    }
}
