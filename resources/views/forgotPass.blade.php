@extends('layouts.backend-blank')

@section('content')

    <main
        class="d-flex
                flex-column
                align-items-center
                justify-content-center
                auth-box"
    >
        <form class="text-center" method="POST" action="{{ route('forgotPassConfirm') }}">
            @csrf

            @include('admin.logo', ['class' => 'w-25'])

            <input type="hidden" name="model" value="{{ $class }}">
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="card shadow p-2">
                <div class="card-body">

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div style="color: red">{{$error}}</div>
                        @endforeach
                    @endif

                    <div class="form-floating mb-3">
                        <input
                            type="password"
                            class="form-control"
                            id="email"
                            name="password"
                            value="{{ old('email') }}"
                            placeholder="New password..."
                            autocomplete="new-password"
                            required
                            autofocus
                        >
                        <label for="email" class="fw-bold fs-7">{{ __('New password') }}</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input
                            type="password"
                            class="form-control"
                            id="repeat_password"
                            name="repeat_password"
                            placeholder="Type password..."
                            autocomplete="repeat-password"
                            required
                        >
                        <label for="password" class="fw-bold fs-7">{{ __('Repeat Password') }}</label>
                    </div>

                    <div class="d-flex justify-content-between">
                        <label class="pt-1">
                        </label>

                        <button class="btn btn-primary" type="submit">
                            {{ __('Save') }}
                        </button>
                    </div>
                </div>
            </div>

        </form>

        <p class="mt-5 mb-3 text-muted">©2022</p>
    </main>

@endsection
