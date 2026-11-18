<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Details | Lost & Found</title>
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>
<body>

<div class="admin-container">

    <aside class="sidebar">
        <div class="logo">
            <h2>Lost & Found</h2>
        </div>

        <nav>
            <a href="{{ url('/admin/dashboard') }}">Dashboard</a>
            <a href="{{ url('/admin/users') }}">Manage Users</a>
            <a href="{{ url('/admin/items') }}">Manage Items</a>
            <a href="{{ url('/admin/claims') }}">Manage Claims</a>
            <a href="{{ url('/admin/reports') }}">Reports</a>
        </nav>
    </aside>

    <main class="main-content">

        <div class="topbar">
            <h2>Item Details</h2>
        </div>

        <section class="content-section">

            <div class="section-heading">
                <h3>{{ $item->item_name }}</h3>
            </div>

            <div class="table-container">

                <p><strong>Item ID:</strong> {{ $item->id }}</p>

                <p><strong>Description:</strong>
                    {{ $item->description ?? 'No description provided' }}
                </p>

                <p><strong>Report Type:</strong>
                    {{ ucfirst($item->report_type) }}
                </p>

                <p><strong>Status:</strong>
                    {{ ucfirst($item->status) }}
                </p>

                <p><strong>Category:</strong>
                    {{ $item->category->category_name ?? 'Not specified' }}
                </p>

                <p><strong>Location:</strong>
                    {{ $item->location->location_name ?? 'Not specified' }}
                </p>

                <p><strong>Reported By:</strong>
                    {{ $item->reporter->first_name ?? '' }}
                    {{ $item->reporter->last_name ?? '' }}
                </p>

                <p><strong>Date Lost/Found:</strong>
                    {{ $item->date_lost_found ?? 'Not specified' }}
                </p>

            </div>

            <br>

            <div class="section-heading">
                <h3>Item Images</h3>
            </div>

            <div class="table-container">

                @forelse($item->images as $image)
                    <img
                        src="{{ asset('storage/' . $image->image_path) }}"
                        alt="Item image"
                        style="width: 180px; height: 150px; object-fit: cover; margin: 10px; border-radius: 8px;"
                    >
                @empty
                    <p>No images available for this item.</p>
                @endforelse

            </div>

            <br>

            <div class="section-heading">
                <h3>Related Claims</h3>
            </div>

            <div class="table-container">

                @if($item->claims->count() > 0)

                    <table>
                        <thead>
                            <tr>
                                <th>Claim ID</th>
                                <th>Claimant</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date Submitted</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($item->claims as $claim)
                                <tr>
                                    <td>{{ $claim->id }}</td>

                                    <td>
                                        {{ $claim->claimant->first_name ?? '' }}
                                        {{ $claim->claimant->last_name ?? '' }}
                                    </td>

                                    <td>{{ $claim->claim_description }}</td>

                                    <td>{{ ucfirst($claim->claim_status) }}</td>

                                    <td>{{ $claim->claimed_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                @else
                    <p>No claims have been submitted for this item.</p>
                @endif

            </div>

            <br>

            <a href="{{ url('/admin/items') }}" class="btn-primary">
                Back to Items
            </a>

        </section>

    </main>

</div>

</body>
</html>