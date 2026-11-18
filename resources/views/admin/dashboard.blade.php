<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Lost & Found</title>

    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>

<body>

<div class="admin-container">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>Lost & Found</h2>
            <p>Administration</p>
        </div>

        <nav>
            <a href="{{ url('/admin/dashboard') }}" class="active">Dashboard</a>
            <a href="{{ url('/admin/users') }}">Manage Users</a>
            <a href="{{ url('/admin/items') }}">Manage Items</a>
            <a href="{{ url('/admin/claims') }}">Manage Claims</a>
            <a href="{{ url('/admin/reports') }}">Reports</a>
        </nav>

        <div class="sidebar-bottom">
            <p>Administration Panel</p>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">

        <header class="topbar">
            <div>
                <h1>Dashboard</h1>
                <p>Welcome to the Lost and Found Management System.</p>
            </div>

            <div class="admin-profile">
                <span>Administrator</span>
            </div>
        </header>

        <!-- Success and Error Messages -->
        @if(session('success'))
            <div class="alert success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert error">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <section class="stats-grid">

            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <h3>Total Users</h3>
                <p>{{ $stats['total_users'] }}</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📦</div>
                <h3>Total Items</h3>
                <p>{{ $stats['total_items'] }}</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">📋</div>
                <h3>Total Claims</h3>
                <p>{{ $stats['total_claims'] }}</p>
            </div>

            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <h3>Pending Claims</h3>
                <p>{{ $stats['pending_claims'] }}</p>
            </div>

        </section>

        <!-- Recent Items -->
        <section class="content-section">

            <div class="section-heading">
                <h2>Recent Reported Items</h2>
                <a href="{{ url('/admin/items') }}">View All</a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Report Type</th>
                            <th>Category</th>
                            <th>Reported By</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($recentItems as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ ucfirst($item->report_type) }}</td>
                                <td>{{ $item->category->category_name ?? 'N/A' }}</td>
                                <td>
                                    {{ $item->reporter->first_name ?? 'Unknown' }}
                                </td>
                                <td>
                                    <span class="status-badge">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No items have been reported yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>

        <!-- Recent Claims -->
        <section class="content-section">

            <div class="section-heading">
                <h2>Recent Claims</h2>
                <a href="{{ url('/admin/claims') }}">View All</a>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Claimant</th>
                            <th>Date Claimed</th>
                            <th>Claim Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($recentClaims as $claim)
                            <tr>
                                <td>{{ $claim->item->item_name ?? 'N/A' }}</td>
                                <td>
                                    {{ $claim->claimant->first_name ?? 'Unknown' }}
                                </td>
                                <td>
                                    {{ $claim->claimed_at?->format('d M Y') ?? 'N/A' }}
                                </td>
                                <td>
                                    <span class="status-badge">
                                        {{ ucfirst($claim->claim_status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No claims have been submitted yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </section>

        <footer class="dashboard-footer">
            <p>Lost & Found Management System &copy; {{ date('Y') }}</p>
        </footer>

    </main>

</div>

</body>
</html>