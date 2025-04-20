@extends('userslist::layouts.app')

@section('content')
    <h1 class="mb-4">Edit User</h1>

    <a href="{{ route('phonebook.index', ['page' => request('page', 1)]) }}" class="btn btn-sm btn-secondary mb-3 me-2">Back</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('phonebook.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}"
                   required>
        </div>
        <div class="mb-3">
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}"
                   required>
        </div>

        <div id="phones-group">
            @foreach($user->phones as $phone)
                <div class="input-group mb-2">
                    <input type="text" name="phones[]" class="form-control" value="{{ $phone->number }}" required>
                    <button type="button" class="btn btn-outline-danger remove-phone">−</button>
                </div>
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-secondary mb-3 add-phone">+ Add Phone</button>

        <button type="submit" class="btn mb-3 btn-success">Update User</button>
    </form>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneGroup = document.getElementById('phones-group');

            document.querySelector('.add-phone').addEventListener('click', () => {
                const inputGroup = document.createElement('div');
                inputGroup.className = 'input-group mb-2';
                inputGroup.innerHTML = `
                    <input type="text" name="phones[]" class="form-control" placeholder="Phone Number" required>
                    <button type="button" class="btn btn-outline-danger remove-phone">−</button>
                `;
                phoneGroup.appendChild(inputGroup);
            });

            phoneGroup.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-phone')) {
                    e.target.closest('.input-group').remove();
                }
            });
        });
    </script>
@endsection
