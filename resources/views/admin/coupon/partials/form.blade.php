@php
    $isEdit = !empty($model);
@endphp
<div class="row g-3">
    <div class="col-lg-4">
        <label class="form-label">Mã coupon</label>
        <input type="text" name="code" class="form-control" value="{{ old('code', $model->code ?? '') }}" placeholder="VD: WELCOME10">
    </div>
    <div class="col-lg-4">
        <label class="form-label">Loại</label>
        <select name="type" class="form-select">
            <option value="percent" {{ old('type', $model->type ?? 'percent') === 'percent' ? 'selected' : '' }}>Giảm theo %</option>
            <option value="fixed" {{ old('type', $model->type ?? '') === 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định</option>
        </select>
    </div>
    <div class="col-lg-4">
        <label class="form-label">Giá trị</label>
        <input type="number" min="1" name="value" class="form-control" value="{{ old('value', $model->value ?? '') }}">
    </div>

    <div class="col-lg-4">
        <label class="form-label">Giảm tối đa (nếu %)</label>
        <input type="number" min="1" name="max_discount" class="form-control" value="{{ old('max_discount', $model->max_discount ?? '') }}">
    </div>
    <div class="col-lg-4">
        <label class="form-label">Đơn tối thiểu</label>
        <input type="number" min="0" name="min_order" class="form-control" value="{{ old('min_order', $model->min_order ?? 0) }}">
    </div>
    <div class="col-lg-4">
        <label class="form-label">Giới hạn lượt dùng</label>
        <input type="number" min="1" name="usage_limit" class="form-control" value="{{ old('usage_limit', $model->usage_limit ?? '') }}">
    </div>

    <div class="col-lg-4">
        <label class="form-label">Bắt đầu</label>
        <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', isset($model?->starts_at) ? $model->starts_at->format('Y-m-d\\TH:i') : '') }}">
    </div>
    <div class="col-lg-4">
        <label class="form-label">Kết thúc</label>
        <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', isset($model?->ends_at) ? $model->ends_at->format('Y-m-d\\TH:i') : '') }}">
    </div>
    <div class="col-lg-4">
        <label class="form-label">Trạng thái</label>
        <select name="status" class="form-select">
            <option value="1" {{ (string) old('status', $model->status ?? 1) === '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ (string) old('status', $model->status ?? 1) === '0' ? 'selected' : '' }}>Tắt</option>
        </select>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <a href="{{ route('admin.coupon.index') }}" class="btn btn-outline-secondary">Quay lại</a>
    <button type="submit" class="btn btn-primary">{{ $isEdit ? 'Lưu mã giảm giá' : 'Tạo mã giảm giá' }}</button>
    <button type="reset" class="btn btn-light border">Làm lại</button>
</div>
