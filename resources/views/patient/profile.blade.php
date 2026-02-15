@extends('layouts.app')
@section('page-title', 'My Profile')
@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 text-center">
            <div class="display-4 mb-3 text-primary"><i class="fas fa-user-circle"></i></div>
            <h4>{{ auth()->user()->name }}</h4>
            <p class="text-muted">{{ auth()->user()->email }}</p>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm p-4">
            <h5 class="mb-4 fw-bold">Edit Profile</h5>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ auth()->user()->phone }}">
                </div>
                <div class="mb-3">
                    <label>Address</label>
                    <textarea name="address" class="form-control">{{ auth()->user()->address }}</textarea>
                </div>
                <button class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection
