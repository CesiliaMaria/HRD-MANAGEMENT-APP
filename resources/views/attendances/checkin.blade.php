@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-fingerprint me-2"></i> Check-in Absensi</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> 
                    Pastikan Anda berada di lokasi kantor untuk melakukan check-in.
                </div>

                <div id="location-info" class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Lokasi Anda:</strong>
                            <p id="location-address" class="text-muted">Mendeteksi lokasi...</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Waktu Sekarang:</strong>
                            <p id="current-time" class="text-muted">{{ now()->format('H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                <form id="checkin-form" action="{{ route('attendances.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="location_address" id="location_address">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="checkin-btn" disabled>
                            <i class="fas fa-check-circle me-2"></i> Check-in Sekarang
                        </button>
                        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateTime() {
        const now = new Date();
        // Format yang konsisten - HH:mm:ss
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const timeString = `${hours}:${minutes}:${seconds}`;
        
        document.getElementById('current-time').textContent = timeString;
    }

    function getLocation() {
        if (!navigator.geolocation) {
            alert('Browser tidak mendukung geolocation');
            enableCheckinButton();
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
                document.getElementById('location_address').value = 'Lokasi terdeteksi';
                document.getElementById('location-address').textContent = 'Lokasi terdeteksi';
                enableCheckinButton();
            },
            function() {
                // Jika gagal, tetap enable tombol dengan nilai default
                document.getElementById('latitude').value = -6.200000;
                document.getElementById('longitude').value = 106.816666;
                document.getElementById('location_address').value = 'Jakarta';
                document.getElementById('location-address').textContent = 'Lokasi default (Jakarta)';
                enableCheckinButton();
            }
        );
    }

    function enableCheckinButton() {
        document.getElementById('checkin-btn').disabled = false;
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateTime();
        setInterval(updateTime, 1000);
        getLocation();
    });
</script>
@endpush