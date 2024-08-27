@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-8">
            <form action="{{ route('profile.update') }}" method="post" class="bg-white shadow rounded-3 p-5" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <h2 class="h3 mb-3 fw-light text-muted">Update Profile</h2>

                <div class="row mb-3">
                    <div class="col-4">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle d-block mx-auto avatar-lg">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
                        @endif
                    </div>
                    <div class="col-auto align-self-end">
                        <input type="file" name="avatar" class="form-control form-control-sm mt-1" aria-describedby="avatar-info">
                        <div class="form-text" id="avatar-info">
                            Acceptable formats: jpeg, jpg, png, gif only <br>
                            Max file size is 1048kB
                        </div>
                        @error('avatar')
                        <div class="text-danger small">{{ $message }}</div>
                        {{-- p or div  block display or element --}}
                    @enderror   
                    </div>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" autofocus>
                    @error('name')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror   
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">E-Mail Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" autofocus>
                    @error('email')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror   
                </div>
                <div class="mb-3">
                    <label for="introduction" class="form-label fw-bold">Introduction</label>
                    <textarea name="introduction" id="introduction" cols="5" class="form-control" placeholder="Describe yourself">{{ old('introduction', $user->introduction) }}</textarea>
                    @error('introduction')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror   
                </div>
                <button type="submit" class="btn btn-warning px-5">Save</button>
            </form>
        </div>
    </div>
@endsection