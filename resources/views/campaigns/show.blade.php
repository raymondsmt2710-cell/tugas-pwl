<!-- resources/views/campaigns/show.blade.php -->
<div class="campaign-detail">
    <div class="row">
        <div class="col-md-8">
            <!-- Foto -->
            <img src="{{ asset('storage/' . $campaign->image) }}" class="img-fluid">
            
            <!-- Progress Bar -->
            <div class="progress-section mt-4">
                <div class="progress">
                    <div class="progress-bar" style="width: {{ $campaign->progress_percentage }}%"></div>
                </div>
                <div class="progress-info">
                    <h5>Rp {{ number_format($campaign->current_amount) }}</h5>
                    <p>dari target Rp {{ number_format($campaign->target_amount) }}</p>
                    <p class="text-muted">{{ $campaign->donor_count }} donatur</p>
                </div>
            </div>
            
            <!-- Deskripsi -->
            <div class="description mt-4">
                <h4>Tentang Campaign Ini</h4>
                {!! $campaign->story !!}
            </div>
            
            <!-- Donasi Recent -->
            <div class="recent-donations mt-4">
                <h5>Donasi Terbaru</h5>
                @foreach($donations as $donation)
                    <div class="donation-item">
                        <p><strong>{{ $donation->donor_name }}</strong></p>
                        <p>Rp {{ number_format($donation->amount) }}</p>
                        <small>{{ $donation->created_at->diffForHumans() }}</small>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <div class="campaign-card">
                <h6>{{ $campaign->category }}</h6>
                <h3>{{ $campaign->title }}</h3>
                <p>{{ $campaign->description }}</p>
                
                <button class="btn btn-primary btn-lg w-100" data-toggle="modal" 
                        data-target="#donateModal">
                    Berikan Donasi
                </button>
                
                <div class="mt-3 small">
                    <p>📅 Berakhir: {{ $campaign->end_date->format('d M Y') }}</p>
                    <p>👤 Oleh: {{ $campaign->user->name }}</p>
                </div>
            </div>
        </div>
    </div>
</div>