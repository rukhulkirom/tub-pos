<!-- view form -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal" id="myForm">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="nama" class="col-lg-2 col-lg-offset-1 control-label">Nama</label>
                        <div class="col-lg-6">
                            <input type="text" name="nama" id="nama" class="form-control" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="telepon" class="col-lg-2 col-lg-offset-1 control-label">Telepon</label>
                        <div class="col-lg-6">
                            <input type="text" name="telepon" id="telepon" class="form-control" required pattern="\d{1,12}" maxlength="12" title="Hanya angka yang diperbolehkan dan maksimal 12 karakter">
                            <span class="help-block with-errors"></span>
                            <span id="telepon-error" class="error-message" style="display: none; color: red;">Input tidak valid. Hanya angka yang diperbolehkan dan maksimal 12 karakter.</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="alamat" class="col-lg-2 col-lg-offset-1 control-label">Alamat</label>
                        <div class="col-lg-6">
                            <textarea name="alamat" id="alamat" rows="3" required class="form-control"></textarea>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary" type="submit"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('telepon').addEventListener('input', function (e) {
        var value = e.target.value;
        var errorMessage = document.getElementById('telepon-error');
        
        // Remove non-numeric characters
        e.target.value = value.replace(/\D/g, '');
        
        // Display error message if invalid
        if (e.target.value.length > 12) {
            errorMessage.style.display = 'block';
            e.target.value = e.target.value.slice(0, 12);
        } else if (e.target.value.match(/^\d{1,12}$/)) {
            errorMessage.style.display = 'none';
        } else {
            errorMessage.style.display = 'block';
        }
    });

    document.getElementById('myForm').addEventListener('submit', function (e) {
        var teleponInput = document.getElementById('telepon');
        var errorMessage = document.getElementById('telepon-error');
        if (!teleponInput.value.match(/^\d{1,12}$/)) {
            errorMessage.style.display = 'block';
            e.preventDefault();
        }
    });
</script>
