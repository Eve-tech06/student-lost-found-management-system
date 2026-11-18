<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Lost & Found</title>
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>
<body>

<div class="admin-container">

    <aside class="sidebar">
        <div class="logo">
            <h2>Lost & Found</h2>
            <p>Administration</p>
        </div>

        <nav>
            <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
            <a href="{{ url('/admin/users') }}" class="active">Manage Users</a>
            <a href="{{ url('/admin/items') }}">Manage Items</a>
            <a href="{{ url('/admin/claims') }}">Manage Claims</a>
            <a href="{{ url('/admin/reports') }}">Reports</a>
        </nav>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <h1>Manage Users</h1>
                <p>View and manage registered users.</p>
            </div>
        </header>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <section class="content-section">

            <div class="section-heading">
                <h2>Registered Users</h2>
                <span>Total: {{ $users->total() }}</span>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Role</th>
                            <th>Change Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>

                                <td>
                                    {{ $user->first_name }}
                                    {{ $user->last_name }}
                                </td>

                                <td>{{ $user->email }}</td>

                                <td>{{ $user->phone_number ?? 'N/A' }}</td>

                                <td>
                                    <span class="status-badge">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <td>
                                    <form action="{{ url('/admin/users/'.$user->id.'/role') }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <select name="role" required>
                                            <option value="student"
                                                {{ $user->role == 'student' ? 'selected' : '' }}>
                                                Student
                                            </option>

                                            <option value="staff"
                                                {{ $user->role == 'staff' ? 'selected' : '' }}>
                                                Staff
                                            </option>

                                            <option value="admin"
                                                {{ $user->role == 'admin' ? 'selected' : '' }}>
                                                Admin
                                            </option>
                                        </select>

                                        <button type="submit" class="btn-primary">
                                            Update
                                        </button>

                                    </form>
                                </td>

                                <td>
                                    <a href="{{ url('/admin/users/'.$user->id) }}"
                                       class="btn-primary">
                                        View Details
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7">No users have registered yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $users->links() }}
            </div>

        </section>

    </main>

</div>

</body>
</html>