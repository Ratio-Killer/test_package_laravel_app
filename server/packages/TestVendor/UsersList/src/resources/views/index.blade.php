@extends('userslist::layouts.app')

@section('content')
    <h1 class="mb-4">Phonebook</h1>

    <div id="success-message" class="alert alert-success d-none"></div>
    <div id="error-message" class="alert alert-danger d-none"></div>

    <form id="user-form" method="POST" action="{{ route('phonebook.store') }}">
        @csrf

        <div class="row mb-3">
            <div class="col-md-6 mb-2">
                <input type="text" name="first_name" class="form-control" placeholder="First Name">
            </div>
            <div class="col-md-6 mb-2">
                <input type="text" name="last_name" class="form-control" placeholder="Last Name">
            </div>
        </div>

        <div id="phones-group" class="mb-3">
            <div class="input-group mb-2">
                <input type="text" name="phones[]" class="form-control" placeholder="Phone Number">
                <button type="button" class="btn btn-outline-secondary add-phone">+</button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Add User</button>
    </form>

    <hr>

    <h3 class="mb-3">User List</h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Name</th>
            <th>Phones</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                <td>
                    @foreach($user->phones as $phone)
                        <div>{{ $phone->number }}</div>
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('phonebook.edit', ['user' => $user, 'page' => request('page', 1)]) }}" class="btn btn-sm btn-warning">Edit</a>
                </td>
                <td>
                    <form method="POST" action="{{ route('phonebook.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this user?')">Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No users found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center">
        {{ $users->links('pagination::bootstrap-5') }}
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/just-validate@4.3.0/dist/just-validate.production.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const phoneGroup = document.getElementById('phones-group');
            const form = document.getElementById('user-form');

            phoneGroup.addEventListener('click', function (e) {
                if (e.target.classList.contains('add-phone')) {
                    const inputGroup = document.createElement('div');
                    inputGroup.className = 'input-group mb-2';
                    inputGroup.innerHTML = `
                    <input type="text" name="phones[]" class="form-control" placeholder="Phone Number">
                    <button type="button" class="btn btn-outline-danger remove-phone">−</button>
                `;
                    phoneGroup.appendChild(inputGroup);
                }

                if (e.target.classList.contains('remove-phone')) {
                    e.target.closest('.input-group').remove();
                }
            });

            const validation = new JustValidate('#user-form');

            validation
                .addField('[name="first_name"]', [
                    {rule: 'required', errorMessage: 'First name is required'},
                ])
                .addField('[name="last_name"]', [
                    {rule: 'required', errorMessage: 'Last name is required'},
                ])
                .onSuccess(function (event) {
                    event.preventDefault();

                    const phones = document.querySelectorAll('input[name="phones[]"]');
                    const hasAnyPhone = Array.from(phones).some(p => p.value.trim() !== '');

                    if (!hasAnyPhone) {
                        showError('At least one phone number is required.');
                        return;
                    }

                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json',
                        }
                    })
                        .then(async res => {
                            if (!res.ok) {
                                const data = await res.json();
                                if (data.errors) {
                                    const messages = Object.values(data.errors).flat().join('<br>');
                                    showError(messages);
                                } else {
                                    showError('An unknown error occurred.');
                                }
                            } else {
                                showSuccess('User added successfully.');
                                form.reset();
                                setTimeout(() => location.reload(), 1000);
                            }
                        })
                        .catch(() => showError('Server error. Please try again.'));
                });

            function showError(msg) {
                const el = document.getElementById('error-message');
                el.innerHTML = msg;
                el.classList.remove('d-none');
                document.getElementById('success-message').classList.add('d-none');
            }

            function showSuccess(msg) {
                const el = document.getElementById('success-message');
                el.innerText = msg;
                el.classList.remove('d-none');
                document.getElementById('error-message').classList.add('d-none');
            }
        });
    </script>
@endsection
