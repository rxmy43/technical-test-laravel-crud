@extends('layouts.app')

@section('title', 'Users')
@section('header', 'User Management')

@section('content')
    <div class="container mx-auto max-w-6xl px-4">
        {{-- Flash Success Message --}}
        @if (session('success'))
            <div class="mb-6 rounded-md bg-green-100 border border-green-300 px-4 py-3 text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Flash Error Message --}}
        @if (session('error'))
            <div class="mb-6 rounded-md bg-red-100 border border-red-300 px-4 py-3 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif


        <div class="mb-6 flex items-center justify-between gap-4 flex-wrap">
            <form method="GET" action="" class="w-full sm:w-auto flex-1 flex items-center">
                <div class="relative w-full sm:w-72">
                    <input type="text" name="q" placeholder="Search by name or email..." value="{{ request('q') }}"
                        class="w-full pr-10 pl-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500" />

                    @if(request('q'))
                        <a href="{{ route(Route::currentRouteName(), request()->except('q', 'page')) }}"
                            class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 text-xl"
                            title="Clear search">
                            &times;
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="ml-2 px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition cursor-pointer">
                    Search
                </button>
            </form>
            <button type="button" id="createUserBtn"
                class="inline-block cursor-pointer bg-purple-600 text-white px-5 py-2 rounded-md shadow hover:bg-purple-700 transition">
                + Create User
            </button>
        </div>


        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
                @php
                    if (!function_exists('sortUrl')) {
                        function sortUrl($column)
                        {
                            $direction = request('sort_direction') === 'asc' ? 'desc' : 'asc';
                            return request()->fullUrlWithQuery([
                                'sort_by' => $column,
                                'sort_direction' => request('sort_by') === $column ? $direction : 'asc'
                            ]);
                        }
                    }
                @endphp

                <thead class="bg-gray-50 text-left text-sm font-semibold text-gray-700">
                    <tr>
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">
                            <a href="{{ sortUrl('name') }}" class="hover:underline">
                                Name @if($sortBy === 'name') <span
                                class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                            </a>
                        </th>
                        <th class="px-6 py-3">
                            <a href="{{ sortUrl('email') }}" class="hover:underline">
                                Email @if($sortBy === 'email') <span
                                class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                            </a>
                        </th>
                        <th class="px-6 py-3">
                            <a href="{{ sortUrl('age') }}" class="hover:underline">
                                Age @if($sortBy === 'age') <span
                                class="ml-1">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
                            </a>
                        </th>
                        <th class="px-6 py-3">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                    @forelse($users as $index => $user)
                        <tr>
                            <td class="px-6 py-4">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">{{ $user->name }}</td>
                            <td class="px-6 py-4">{{ $user->email }}</td>
                            <td class="px-6 py-4">{{ $user->age }}</td>
                            <td class="px-6 py-4 space-x-2">
                                <button type="button"
                                    class="edit-user-btn inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition cursor-pointer text-sm"
                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                    data-age="{{ $user->age }}">
                                    Edit
                                </button>
                                <button type="button"
                                    class="delete-user-btn inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-md hover:bg-red-700 transition cursor-pointer text-sm"
                                    data-id="{{ $user->id }}" data-name="{{ $user->name }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No users available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-4 p-3">
                {{ $users->appends(request()->except('page'))->links('vendor.pagination.tailwind') }}
            </div>

        </div>

        {{-- Create User Modal --}}
        <div id="createUserModal"
            class="fixed inset-0 bg-black bg-opacity-50 z-40 {{ $errors->any() ? 'flex' : 'hidden' }} items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg relative">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Create New User</h2>

                {{-- Validation Errors (inside modal) --}}
                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-100 border border-red-300 px-4 py-3 text-red-800 text-sm">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="createUserForm" method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500"
                            required />
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500"
                            required />
                    </div>

                    <div class="mb-4">
                        <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                        <input type="number" id="age" name="age" value="{{ old('age') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-purple-500"
                            required />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelCreateBtn"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-purple-600 text-white hover:bg-purple-700 rounded-md text-sm cursor-pointer">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>


        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden items-center justify-center">
            <div class="bg-white rounded-lg p-6 max-w-md w-full shadow-lg relative">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">
                    Confirm Deletion
                </h2>
                <p class="text-sm text-gray-600 mb-3">
                    Type the user's name <span class="font-semibold text-red-600" id="confirmUserNameText"></span> to
                    confirm deletion.
                </p>
                <form id="deleteForm" method="POST" action="#">
                    @csrf
                    @method('DELETE')
                    <input type="text" id="confirmUserNameInput" placeholder="Type name here..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4 focus:ring-2 focus:ring-purple-500"
                        required />
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelDeleteBtn"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md text-sm cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" id="confirmDeleteBtn"
                            class="px-4 py-2 bg-red-300 text-white rounded-md text-sm cursor-not-allowed opacity-50"
                            disabled>
                            Delete
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log("DOMContentLoaded event fired");
            // Create User Modal functionality
            const createUserModal = document.getElementById("createUserModal");
            const createUserBtn = document.getElementById("createUserBtn");
            const cancelCreateBtn = document.getElementById("cancelCreateBtn");

            createUserBtn.addEventListener("click", function () {
                createUserModal.classList.remove("hidden");
                createUserModal.classList.add("flex");
            });

            cancelCreateBtn.addEventListener("click", function () {
                createUserModal.classList.add("hidden");
                createUserModal.classList.remove("flex");
            });

            // Edit Modal functionality
            const editButtons = document.querySelectorAll(".edit-user-btn");
            const createUserForm = document.getElementById("createUserForm");
            const nameInput = document.getElementById("name");
            const emailInput = document.getElementById("email");
            const ageInput = document.getElementById("age");
            const modalTitle = createUserModal.querySelector("h2");
            const createButton = createUserForm.querySelector("button[type='submit']");

            editButtons.forEach((button) => {
                button.addEventListener("click", function () {
                    const userId = this.getAttribute("data-id");
                    const name = this.getAttribute("data-name");
                    const email = this.getAttribute("data-email");
                    const age = this.getAttribute("data-age");

                    modalTitle.textContent = "Edit User";
                    nameInput.value = name;
                    emailInput.value = email;
                    ageInput.value = age;
                    createUserForm.action = `/users/${userId}`;

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    methodInput.id = 'methodOverride';
                    createUserForm.appendChild(methodInput);

                    createButton.textContent = "Update User";

                    createUserModal.classList.remove("hidden");
                    createUserModal.classList.add("flex");
                });
            });

            // Reset modal to Create mode on cancel
            cancelCreateBtn.addEventListener("click", function () {
                modalTitle.textContent = "Create New User";
                nameInput.value = "";
                emailInput.value = "";
                ageInput.value = "";
                createUserForm.action = "{{ route('users.store') }}";
                createButton.textContent = "Create User";

                const methodOverride = document.getElementById('methodOverride');
                if (methodOverride) methodOverride.remove();

                createUserModal.classList.add("hidden");
                createUserModal.classList.remove("flex");
            });


            // Delete Modal functionality
            const deleteModal = document.getElementById("deleteModal");
            const confirmUserNameText = document.getElementById("confirmUserNameText");
            const confirmUserNameInput = document.getElementById(
                "confirmUserNameInput"
            );
            const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
            const deleteForm = document.getElementById("deleteForm");
            const cancelDeleteBtn = document.getElementById("cancelDeleteBtn");
            const deleteButtons = document.querySelectorAll(".delete-user-btn");

            deleteButtons.forEach((button) => {
                button.addEventListener("click", function () {
                    const userId = this.getAttribute("data-id");
                    const userName = this.getAttribute("data-name");

                    deleteModal.classList.remove("hidden");
                    deleteModal.classList.add("flex");
                    confirmUserNameText.textContent = `"${userName}"`;
                    confirmUserNameInput.value = "";
                    confirmDeleteBtn.disabled = true;
                    deleteForm.action = `/users/${userId}`;
                });
            });

            // Disable Delete button until exact name is typed
            confirmUserNameInput.addEventListener("input", function () {
                const expectedName = confirmUserNameText.textContent.replace(/"/g, "").trim();
                const currentInput = confirmUserNameInput.value.trim();

                const isMatch = currentInput.toLowerCase() === expectedName.toLowerCase(); // Case-insensitive comparison

                confirmDeleteBtn.disabled = !isMatch;
                confirmDeleteBtn.classList.toggle("bg-red-600", isMatch);
                confirmDeleteBtn.classList.toggle("hover:bg-red-700", isMatch);
                confirmDeleteBtn.classList.toggle("bg-red-300", !isMatch);
                confirmDeleteBtn.classList.toggle("opacity-50", !isMatch);
                confirmDeleteBtn.classList.toggle("cursor-not-allowed", !isMatch);
                confirmDeleteBtn.classList.toggle("cursor-pointer", isMatch);
            });

            cancelDeleteBtn.addEventListener("click", function () {
                deleteModal.classList.add("hidden");
                deleteModal.classList.remove("flex");
            });

            // Close modals when clicking outside
            window.addEventListener("click", function (event) {
                if (event.target === createUserModal) {
                    createUserModal.classList.add("hidden");
                    createUserModal.classList.remove("flex");
                }

                if (event.target === deleteModal) {
                    deleteModal.classList.add("hidden");
                    deleteModal.classList.remove("flex");
                }
            });
        });

    </script>
@endpush