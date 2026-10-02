{{-- Global Image Cropper Modal --}}
<div id="global-cropper-modal" class="global-cropper-modal">
    <div class="global-cropper-dialog">
        <div class="global-cropper-header">
            <h3 class="global-cropper-title">Sesuaikan Gambar</h3>
            <button type="button" onclick="closeGlobalCropper()" class="global-cropper-close-btn" aria-label="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="global-cropper-viewport">
            <img id="global-cropper-image" src="" class="global-cropper-img" alt="Crop Preview">
        </div>
        <div class="global-cropper-actions">
            <button type="button" onclick="closeGlobalCropper()" class="global-cropper-btn-cancel">Batal</button>
            <button type="button" id="global-cropper-save" class="global-cropper-btn-save">Gunakan Gambar</button>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ asset('js/global-cropper.js') }}"></script>
