@extends('admin.layouts.app')
@section('title', 'Mã giảm giá - Sửa')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Sửa mã giảm giá</h4>
    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.coupon.update', $model->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('admin.coupon.partials.form', ['model' => $model])
        </form>
    </div>
</div>
@endsection
