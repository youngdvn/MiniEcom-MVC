@if($errors->any())
    <div data-flash-message="{{ $errors->first() }}" data-flash-type="error" class="hidden"></div>
@endif

@if (session('message'))
    <div data-flash-message="{{ session('message') }}" data-flash-type="success" class="hidden"></div>
@endif
