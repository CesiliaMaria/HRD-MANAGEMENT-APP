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
        document.getElementById('current-time').textContent = 
            now.getHours().toString().padStart(2, '0') + ':' + 
            now.getMinutes().toString().padStart(2, '0') + ':' + 
            now.getSeconds().toString().padStart(2, '0');
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    
                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lng;
                    
                    // Reverse geocoding sederhana (di production gunakan API seperti Google Maps)
                    fetch(`https://api.bigdatacloud.net/data/reverse-geocode-client?latitude=${lat}&longitude=${lng}&localityLanguage=id`)
                        .then(response => response.json())
                        .then(data => {
                            const address = data.locality || 'Lokasi tidak diketahui';
                            document.getElementById('location-address').textContent = address;
                            document.getElementById('location_address').value = address;
                            document.getElementById('checkin-btn').disabled = false;
                        })
                        .catch(error => {
                            document.getElementById('location-address').textContent = 'Lokasi terdeteksi';
                            document.getElementById('location_address').value = 'Lokasi terdeteksi';
                            document.getElementById('checkin-btn').disabled = false;
                        });
                },
                function(error) {
                    alert('Tidak dapat mengakses lokasi. Pastikan izin lokasi diaktifkan.');
                    console.error('Error getting location:', error);
                }
            );
        } else {
            alert('Browser tidak mendukung geolocation.');
        }
    }

    // Update waktu setiap detik
    setInterval(updateTime, 1000);
    
    // Dapatkan lokasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        getLocation();
        updateTime();
    });
</script>
@endpush