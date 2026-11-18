<!DOCTYPE html>

<html lang="en">
<head>
&#x20;   <meta charset="UTF-8">
&#x20;   <meta name="viewport" content="width=device-width, initial-scale=1.0">
&#x20;   <title>Claim Details | Lost & Found\</title>


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
            <h1>Claim Details</h1>
            <p>Review the information submitted by the claimant.</p>
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
            <h2>Claim #{{ $claim->id }}</h2>

            <span class="status-badge">
                {{ ucfirst($claim->claim_status) }}
            </span>
        </div>

        <div class="details-grid">

            <!-- Item Information -->
            <div class="detail-card">
                <h3>Item Information</h3>

                <p>
                    <strong>Item Name:</strong>
                    {{ $claim->item->item_name ?? 'N/A' }}
                </p>

                <p>
                    <strong>Description:</strong>
                    {{ $claim->item->description ?? 'N/A' }}
                </p>

                <p>
                    <strong>Report Type:</strong>
                    {{ ucfirst($claim->item->report_type ?? 'N/A') }}
                </p>

                <p>
                    <strong>Item Status:</strong>
                    {{ ucfirst($claim->item->status ?? 'N/A') }}
                </p>

                <p>
                    <strong>Category:</strong>
                    {{ $claim->item->category->category_name ?? 'N/A' }}
                </p>

                <p>
                    <strong>Location:</strong>
                    {{ $claim->item->location->location_name ?? 'N/A' }}
                </p>
            </div>

            <!-- Claimant Information -->
            <div class="detail-card">
                <h3>Claimant Information</h3>

                <p>
                    <strong>Name:</strong>
                    {{ $claim->claimant->first_name ?? 'Unknown' }}
                    {{ $claim->claimant->last_name ?? '' }}
                </p>

                <p>
                    <strong>Email:</strong>
                    {{ $claim->claimant->email ?? 'N/A' }}
                </p>

                <p>
                    <strong>Phone:</strong>
                    {{ $claim->claimant->phone_number ?? 'N/A' }}
                </p>

                <p>
                    <strong>Date Claimed:</strong>
                    {{ $claim->claimed_at?->format('d M Y, H:i') ?? 'N/A' }}
                </p>
            </div>

            <!-- Item Images -->
            <div class="detail-card full-width">
                <h3>Item Images</h3>

                @if($claim->item && $claim->item->images->count() > 0)

                    @foreach($claim->item->images as $image)
                        <img
                            src="{{ asset('storage/' . $image->image_path) }}"
                            alt="Claimed item image"
                            style="width:180px; height:150px; object-fit:cover; margin:10px; border-radius:8px;"
                        >
                    @endforeach

                @else
                    <p>No images available for this item.</p>
                @endif
            </div>

            <!-- Claim Description -->
            <div class="detail-card full-width">
                <h3>Claim Description</h3>

                <p>
                    {{ $claim->claim_description ?? 'No description provided.' }}
                </p>
            </div>

            <!-- Review Information -->
            <div class="detail-card full-width">
                <h3>Review Information</h3>

                <p>
                    <strong>Review Status:</strong>
                    {{ ucfirst($claim->claim_status) }}
                </p>

                <p>
                    <strong>Reviewed By:</strong>
                    {{ $claim->reviewer->first_name ?? 'Not reviewed' }}
                    {{ $claim->reviewer->last_name ?? '' }}
                </p>

                <p>
                    <strong>Review Date:</strong>
                    {{ $claim->reviewed_at?->format('d M Y, H:i') ?? 'Not reviewed' }}
                </p>
            </div>

        </div>

        <!-- Approve and Reject Actions -->
        @if($claim->claim_status === 'pending')

            <div class="claim-actions">

                <form action="{{ url('/admin/claims/'.$claim->id.'/approve') }}"
                      method="POST">

                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn-success">
                        Approve Claim
                    </button>
                </form>

                <form action="{{ url('/admin/claims/'.$claim->id.'/reject') }}"
                      method="POST">

                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn-danger">
                        Reject Claim
                    </button>
                </form>

            </div>

        @else

            <p>
                This claim has already been reviewed.
                No further action is available.
            </p>

        @endif

        <br>

        <a href="{{ url('/admin/claims') }}" class="btn-primary">
            Back to Claims
        </a>

    </section>

</main>


</div>

</body> </html>
