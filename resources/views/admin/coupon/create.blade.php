@extends('admin.layouts.app')
@section('title', 'Mã giảm giá - Thêm')

@section('content')
<div class="container-fluid">
    <h4 class="mb-3 fw-bold">Thêm mã giảm giá</h4>
    <x-admin.panel-error/>

    <div class="admin-section">
        <form action="{{ route('admin.coupon.store') }}" method="POST">
            @csrf
            @include('admin.coupon.partials.form', ['model' => null])
        </form>
    </div>
</div>
@endsection
