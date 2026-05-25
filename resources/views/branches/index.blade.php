@extends('layouts.customer')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-geo-alt-fill"></i> Our Branches</h2>
    <div class="row g-4">
        @foreach($branches as $branch)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-building"></i> {{ $branch->name }}</h5>
                    <p class="card-text">{{ $branch->address }}</p>
                    @if($branch->landmark)<p class="small text-muted"><i class="bi bi-pin-map-fill"></i> Landmark: {{ $branch->landmark }}</p>@endif
                    <p><i class="bi bi-telephone"></i> {{ $branch->phone }}</p>
                    <a href="https://maps.google.com/?q={{ urlencode($branch->address) }}" target="_blank" class="btn btn-outline-primary rounded-pill btn-sm">
                        <i class="bi bi-map"></i> View on Map
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection