<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Claims | Lost & Found</title>
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
            <a href="{{ url('/admin/users') }}">Manage Users</a>
            <a href="{{ url('/admin/items') }}">Manage Items</a>
            <a href="{{ url('/admin/claims') }}" class="active">Manage Claims</a>
            <a href="{{ url('/admin/reports') }}">Reports</a>
        </nav>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <h1>Manage Claims</h1>
                <p>Review and manage submitted item claims.</p>
            </div>
        </header>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        <section class="content-section">

            <div class="section-heading">
                <h2>Submitted Claims</h2>
                <span>Total: {{ $claims->total() }}</span>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Claim ID</th>
                            <th>Item</th>
                            <th>Claimant</th>
                            <th>Date Claimed</th>
                            <th>Claim Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($claims as $claim)

                            <tr>
                                <td>{{ $claim->id }}</td>

                                <td>
                                    {{ $claim->item->item_name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $claim->claimant->first_name ?? 'Unknown' }}
                                    {{ $claim->claimant->last_name ?? '' }}
                                </td>

                                <td>
                                    {{ $claim->claimed_at?->format('d M Y') ?? 'N/A' }}
                                </td>

                                <td>
                                    <span class="status-badge">
                                        {{ ucfirst($claim->claim_status) }}
                                    </span>
                                </td>

                                <td class="action-buttons">

                                    <a href="{{ url('/admin/claims/'.$claim->id) }}"
                                       class="btn-primary">
                                        View Details
                                    </a>

                                    @if($claim->claim_status === 'pending')

                                        <form action="{{ url('/admin/claims/'.$claim->id.'/approve') }}"
                                              method="POST">
                                            @csrf
                                            <button type="submit" class="btn-success">
                                                Approve
                                            </button>
                                        </form>

                                        <form action="{{ url('/admin/claims/'.$claim->id.'/reject') }}"
                                              method="POST">
                                            @csrf
                                            <button type="submit" class="btn-danger">
                                                Reject
                                            </button>
                                        </form>

                                    @endif

                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="6">No claims have been submitted yet.</td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $claims->links() }}
            </div>

        </section>

    </main>

</div>

</body>
</html>