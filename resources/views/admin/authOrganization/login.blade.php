@extends('layouts.backend-blank')

@section('content')

    <main
        class="d-flex
                flex-column
                align-items-center
                justify-content-center
                auth-box"
    >
        <form class="text-center" method="POST" action="login">
            @csrf

            @include('admin.logo', ['class' => 'w-25'])

            <div class="card shadow p-2">
                <div class="card-body">

                    @include('admin.errors', ['errors' => $errors])

                    <div class="form-floating mb-3">
                        <input
                            type="email"
                            class="form-control"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Type email..."
                            autocomplete="email"
                            required
                            autofocus
                        >
                        <label for="email" class="fw-bold fs-7">{{ __('Email address') }}</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input
                            type="password"
                            class="form-control"
                            id="password"
                            name="password"
                            placeholder="Type password..."
                            autocomplete="current-password"
                            required
                        >
                        <label for="password" class="fw-bold fs-7">{{ __('Password') }}</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <label class="pt-1">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="remember"
                            >
                            {{ __('Remember me') }}
                        </label>

                        <button class="btn btn-primary" type="submit">
                            {{ __('Sign in') }}
                        </button>
                    </div>
                </div>
            </div>

        </form>

        <p class="mt-5 mb-3 text-muted">©2022</p>
    </main>

@endsection
