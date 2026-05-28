@extends('layouts.admin')

@section('title', $title . ' - Vape Expo')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ $title }}</h5>
                </div>
                <div class="card-body text-center">
                    @if($url)
                        <img src="{{ $url }}" class="img-fluid rounded" style="max-height: 600px;" alt="{{ $title }}">
                    @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> No image available.
                        </div>
                    @endif
                    <div class="mt-4">
                        <a href="{{ $url }}" download class="btn btn-success" target="_blank">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <a href="{{ route('admin.deliveries.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Back to Deliveries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection