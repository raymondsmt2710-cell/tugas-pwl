<form action="{{ route('campaigns.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div class="form-group">
        <label>Judul Campaign</label>
        <input type="text" name="title" class="form-control" required>
    </div>
    
    <div class="form-group">
        <label>Kategori</label>
        <select name="category" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="kesehatan">Kesehatan</option>
            <option value="pendidikan">Pendidikan</option>
            <option value="bencana">Bencana Alam</option>
            <option value="anak">Anak-anak</option>
            <option value="sosial">Program Sosial</option>
        </select>
    </div>
    
    <div class="form-group">
        <label>Deskripsi Singkat</label>
        <textarea name="description" class="form-control" rows="3" required></textarea>
    </div>
    
    <div class="form-group">
        <label>Cerita Detail</label>
        <textarea name="story" id="story" class="form-control" rows="6" required></textarea>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Target Donasi (Rp)</label>
                <input type="number" name="target_amount" class="form-control" 
                       min="100000" step="100000" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>Batas Waktu Campaign</label>
                <input type="date" name="end_date" class="form-control" required>
            </div>
        </div>
    </div>
    
    <div class="form-group">
        <label>Foto Campaign</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
    </div>
    
    <div class="form-group">
        <label>Rekening Bank Penerima</label>
        <input type="text" name="bank_account" class="form-control" 
               placeholder="Nama Bank - Nomor Rekening - Atas Nama" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Buat Campaign</button>
</form>

<script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script>
<script>
    CKEDITOR.replace('story');
</script>