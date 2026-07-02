<x-app>


    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow p-3">
        <h5 class="fw-bold mb-0">{{ $title }}</h5>
    </div>

    <div class="card shadow p-3">

        <form method="POST" action="{{ route('users.update', $user) }}" class="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3 mb-3">

                <div class="col-md-6">
                    <label for="name" class="form-label required">Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label required">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label ">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" minlength="8">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label ">Konfirmasi Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" name="password_confirmation" minlength="8"
                        data-parsley-equalto="#password">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="role" class="form-label required">Role</label>
                    <select class="form-select select2-default" id="role" name="role" required>
                        <option value="" disabled @if (!old('role', $user->role)) selected @endif>Pilih Role
                        </option>
                        <option value="Superadmin" @selected(old('role', $user->role) == 'Superadmin')>Superadmin</option>
                        <option value="Admin" @selected(old('role', $user->role) == 'Admin')>Admin</option>
                    </select>

                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="avatar" class="form-label">Avatar (maxSize: 1MB)</label>
                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="upload"
                        name="avatar">
                    @error('avatar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('niceadmin/img/noprofil.png') }}"
                        alt="Avatar" class="w-50 rounded mt-2" id="preview">
                </div>
            </div>


            <div class="text-end">
                <a class="btn btn-warning" href="{{ route('users.index') }}" role="button">Cancel</a>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

        </form>
    </div>

    @push('modals')
    @endpush

    @push('scripts')
    @endpush


</x-app>
