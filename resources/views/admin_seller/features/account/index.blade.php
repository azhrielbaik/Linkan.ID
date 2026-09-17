@extends("admin_seller.layouts.settings")

@section("page_title", __('admin.my_account_title'))

@push("styles")
<link rel="stylesheet" href="{{ asset('css/pages/myaccount.css') }}" data-turbo-track="reload">
@endpush

@section("settings_content")
<div class="dashboard-account-page">

    @if(session('success'))
        <div class="account-alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="account-alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif





    <!-- MODAL DELETE ACCOUNT -->
    <div id="deleteConfirmationPopup" class="account-modal-overlay" style="display: none;">
        <div class="account-modal-card modal-delete-card">
            <div class="delete-icon-circle">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3>{{ __('admin.delete_account') }}</h3>
            <p class="delete-warning-text">{{ __('admin.delete_account_warning') }}</p>
            
            <div class="delete-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeDeletePopup()">{{ __('admin.cancel') }}</button>
                <form action="{{ route('admin.account.delete') }}" method="POST" style="display: inline; flex: 1;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-modal-danger">{{ __('admin.yes_delete_account') }}</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@push("scripts")
<script>
    function showDeletePopup() {
        document.getElementById('deleteConfirmationPopup').style.display = 'flex';
    }

    function closeDeletePopup() {
        document.getElementById('deleteConfirmationPopup').style.display = 'none';
    }

    // Close on overlay click
    window.addEventListener('click', function(event) {
        const deleteModal = document.getElementById('deleteConfirmationPopup');
        if (event.target === deleteModal) {
            closeDeletePopup();
        }
    });
</script>
@endpush
