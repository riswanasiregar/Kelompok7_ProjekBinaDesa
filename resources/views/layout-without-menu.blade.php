@extends('layouts.admin.app')

@section('title', 'Layout Without Menu')

@section('content')
<!-- Layout Demo -->
<div class="layout-demo-wrapper">
    <div class="layout-demo-placeholder">
        <img
            src="{{ asset('assets-admin/img/layouts/layout-without-menu-light.png') }}"
            class="img-fluid"
            alt="Layout without menu"
        />
    </div>
    <div class="layout-demo-info">
        <h4>Layout without Menu (Navigation)</h4>
        <button class="btn btn-primary" type="button" onclick="history.back()">Go Back</button>
    </div>
</div>
<!--/ Layout Demo -->
@endsection
