<!-- resources/views/campaigns/donate-modal.blade.php -->
<div id="donateModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('donations.store') }}" method="POST" id="donateForm">
                @csrf
                <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                
                <div class="modal-header">
                    <h5>Lakukan Donasi</h5>
                </div>
                
                <div class="modal-body">
                    <!-- Nominal Presets -->
                    <div class="form-group">
                        <label>Pilih Nominal</label>
                        <div class="btn-group-vertical w-100">
                            <button type="button" class="btn btn-outline-primary amount-btn" 
                                    data-amount="50000">Rp 50.000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" 
                                    data-amount="100000">Rp 100.000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" 
                                    data-amount="500000">Rp 500.000</button>
                            <button type="button" class="btn btn-outline-primary amount-btn" 
                                    data-amount="1000000">Rp 1.000.000</button>
                        </div>
                    </div>
                    
                    <!-- Input Manual -->
                    <div class="form-group mt-3">
                        <label>Atau Masukkan Nominal</label>
                        <input type="number" name="amount" id="amount" class="form-control" 
                               min="10000" step="1000" placeholder="Rp">
                    </div>
                    
                    <!-- Data Donatur -->
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="donor_name" class="form-control" 
                               value="{{ auth()->user()?->name ?? '' }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="donor_email" class="form-control" 
                               value="{{ auth()->user()?->email ?? '' }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Pesan (Opsional)</label>
                        <textarea name="message" class="form-control" rows="3" 
                                  placeholder="Tinggalkan pesan doa..."></textarea>
                    </div>
                    
                    <!-- Metode Pembayaran -->
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="payment_method" class="form-control" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="credit_card">Kartu Kredit</option>
                            <option value="bank_transfer">Transfer Bank</option>
                            <option value="e_wallet">E-Wallet</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Lanjut Pembayaran</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.amount-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.getElementById('amount').value = this.dataset.amount;
    });
});
</script>