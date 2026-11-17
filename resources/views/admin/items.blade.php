<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Items | Lost & Found</title>
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
            <a href="{{ url('/admin/items') }}" class="active">Manage Items</a>
            <a href="{{ url('/admin/claims') }}">Manage Claims</a>
            <a href="{{ url('/admin/reports') }}">Reports</a>
        </nav>
    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <h1>Manage Items</h1>
                <p>View and manage all reported lost and found items.</p>
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
                <h2>Reported Items</h2>
                <span>Total: {{ $items->total() }}</span>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Name</th>
                            <th>Report Type</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Reported By</th>
                            <th>Date Reported</th>
                            <th>Status</th>
                            <th>View Status</th>
                            <th>Update Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($items as $item)
                            <tr>
                                <td>{{ $item->id }}</td>

                                <td>{{ $item->item_name }}</td>

                                <td>{{ ucfirst($item->report_type) }}</td>

                                <td>
                                    {{ $item->category->category_name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $item->location->location_name ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $item->reporter->first_name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $item->date_lost_found?->format('d M Y') ?? 'N/A' }}
                                </td>

                                <td>
                                    <span class="status-badge">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                                <td>
    <a href="{{ url('/admin/items/'.$item->id) }}"
       class="btn-primary">
        View Details
    </a>
</td>
                                

                                <td>
                                    <form action="{{ url('/admin/items/'.$item->id.'/status') }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <select name="status" required>
                                            <option value="open"
                                                {{ $item->status == 'open' ? 'selected' : '' }}>
                                                Open
                                            </option>

                                            <option value="pending"
                                                {{ $item->status == 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>

                                            <option value="claimed"
                                                {{ $item->status == 'claimed' ? 'selected' : '' }}>
                                                Claimed
                                            </option>

                                            <option value="returned"
                                                {{ $item->status == 'returned' ? 'selected' : '' }}>
                                                Returned
                                            </option>
                                        </select>

                                        <button type="submit" class="btn-primary">
                                            Update
                                        </button>

                                    </form>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="10">No items have been reported yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $items->links() }}
            </div>

        </section>

    </main>

</div>

</body>
</html>