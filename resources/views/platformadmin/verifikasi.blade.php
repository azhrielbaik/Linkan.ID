<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Content</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <style>
    body {
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
    display: flex;
    background-color: #f9f9f9;
}

.sidebar {
    width: 220px;
    background-color: #dbe7fd;
    padding: 20px;
    min-height: 100vh;
    border-top-right-radius: 40px;
    display: flex;
    flex-direction: column;
}

.menu-title {
    font-weight: bold;
    font-size: 12px;
    margin-bottom: 20px;
}

.sidebar a {
    display: flex;
    align-items: center;
    padding: 10px;
    text-decoration: none;
    color: #000;
    font-weight: 500;
    border-radius: 8px;
    margin-bottom: 10px;
}

.sidebar a.active {
    background-color: white;
    font-weight: 700;
}

.sidebar a:hover {
    background-color: #e1ecfa;
}

.content {
    flex: 1;
    padding: 40px;
}

.header {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 20px;
}

.tabs {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

.tab {
    border: none;
    background: none;
    font-weight: 600;
    cursor: pointer;
    padding: 8px 0;
    position: relative;
    color: #666;
}

.tab::after {
    content: '';
    height: 3px;
    background: #000;
    width: 0;
    position: absolute;
    left: 0;
    bottom: -5px;
    transition: width 0.3s;
}

.tab.active {
    color: #000;
}

.tab.active::after {
    width: 100%;
}

.filter-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    margin-bottom: 20px;
    gap: 15px;
}

.filter-controls {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.search-box {
    flex: 1 1 280px;
    position: relative;
}

.search-box input {
    width: 100%;
    padding: 10px 15px;
    padding-left: 40px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.search-box i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.filter-group {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: center;
    flex: 1 1 380px;
}

.filter-select {
    padding: 8px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    background-color: white;
    font-size: 14px;
}

.date-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.date-input {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ddd;
    background-color: white;
}

.archive-button {
    padding: 8px 15px;
    background-color: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.archive-button:hover {
    background-color: #5a6268;
}

.no-data {
    text-align: center;
    padding: 40px;
    color: #666;
}

.no-data i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #ddd;
}

.table-container {
    background: white;
    padding: 20px;
    border-radius: 20px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    padding: 12px;
    text-align: left;
}

th {
    background-color: #f4f4f4;
    font-weight: 600;
}

tr:not(:last-child) {
    border-bottom: 1px solid #eee;
}

.content-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.content-preview img {
    width: 100px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.2);
}

.description-cell {
    max-width: 300px; /* Optional: adjust as needed */
}

.read-more-link {
    color: #ff6600;
    cursor: pointer;
    text-decoration: underline;
    font-size: 12px;
}

.status-completed {
    color: green;
    font-weight: 600;
}

.status-pending {
    color: red;
    font-weight: 600;
}

/* Kembalikan kolom action seperti semula */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 5px;
    min-width: 200px;
}

.action-buttons .btn {
    width: 100%;
    text-align: center;
}

/* Tombol-tombol umum */
.btn {
    padding: 6px 12px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
}

.btn.accept {
    background-color: #ff6600;
    color: white;
    margin: 2px;
    min-width: 80px;
}

.btn.accepted {
    background-color: #555;
    color: white;
    margin: 2px;
    min-width: 80px;
}

.btn.reject {
    background-color: #dc3545;
    color: white;
    margin: 2px;
    min-width: 80px;
}

.action-buttons .rejection-reason {
    font-size: 12px;
    color: #666;
    margin-top: 5px;
    padding: 5px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    backdrop-filter: blur(5px);
}

.modal-content {
    width: 500px;
    max-width: 90%;
    border-radius: 20px;
    background-color: white;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
    padding: 20px 24px;
    background-color: #fff;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    font-size: 16px;
    font-weight: 600;
    color: #333;
    margin: 0;
}

.close-button {
    background: none;
    border: none;
    font-size: 24px;
    color: #666;
    cursor: pointer;
    padding: 0;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.3s;
}

.close-button:hover {
    color: #333;
}

.modal-body {
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    text-align: left;
}

.modal-footer {
    padding: 16px 24px;
    background-color: #fff;
    border-top: 1px solid #e9ecef;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.form-group {
    margin-bottom: 20px;
    width: 100%;
    box-sizing: border-box;
}

.form-group label {
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-control {
    width: 100%;
    min-height: 100px;
    max-height: 150px;
    padding: 20px;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    font-size: 14px;
    line-height: 1.6;
    resize: vertical;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
    color: #495057;
    box-sizing: border-box;
}

.form-control:focus {
    outline: none;
    border-color: #FF9040;
    background-color: #fff;
    box-shadow: 0 0 0 4px rgba(255, 144, 64, 0.1);
}

.form-control::placeholder {
    color: #adb5bd;
    font-size: 13px;
}

.btn.cancel {
    background-color: #e9ecef;
    color: #495057;
}

.btn.cancel:hover {
    background-color: #dee2e6;
}
</style>


</head>
<body>

    {{-- Sidebar --}}
    @include('platformadmin.sidebar.sidebarplatform')

    {{-- Main Content --}}
    <div class="content">
        <div class="header">Verification Content</div>

        {{-- Tabs --}}
        <div class="tabs">
            <button class="tab active" data-tab="pending">Pending Verification</button>
            <button class="tab" data-tab="approved">Approved</button>
            <button class="tab" data-tab="rejected">Rejected</button>
            <button class="tab" data-tab="archive">Archive</button>
        </div>

        {{-- Filter Container --}}
        <div class="filter-container">
            <div class="filter-controls">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by title, username, or description...">
                </div>
                <div class="filter-group">
                    <select class="filter-select" id="platformFilter">
                        <option value="">All Platforms</option>
                        <option value="upload">Upload</option>
                        <option value="dropbox">Dropbox</option>
                        <option value="gdrive">G-Drive</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="date-filter">
                        <input type="date" class="date-input" id="startDate">
                        <input type="date" class="date-input" id="endDate">
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Content</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Platform Type</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productTableBody">
                    @foreach($products as $index => $product)
                    <tr class="product-row" 
                        data-status="{{ $product->verification_status }}"
                        data-platform="{{ $product->platform_type }}"
                        data-date="{{ $product->created_at->format('Y-m-d') }}"
                        data-title="{{ strtolower($product->title) }}"
                        >
                        <td>{{ $index + 1 }}.</td>
                        <td>{{ $product->user->name }}</td>
                        <td>
                            <div class="content-preview">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image">
                                @else
                                    <img src="https://via.placeholder.com/100x60.png?text=No+Image" alt="No Image">
                                @endif
                            </div>
                        </td>
                        <td class="description-cell">
                            {{ Str::limit($product->description, 50) }}
                            @if(strlen($product->description) > 50)
                                <span class="read-more-link" onclick="showDescriptionModal(this)" data-full-description="{{ addslashes($product->description) }}">Baca Selengkapnya</span>
                            @endif
                        </td>
                        <td>
                            @if($product->sale_price)
                                <span style="text-decoration: line-through; color: #999;">Rp {{ number_format($product->price) }}</span>
                                <br>
                                <span style="color: #dc3545;">Rp {{ number_format($product->sale_price) }}</span>
                            @else
                                Rp {{ number_format($product->price) }}
                            @endif
                        </td>
                        <td>{{ ucfirst($product->platform_type) }}</td>
                        <td>
                            @if($product->has_quantity_limit)
                                {{ $product->quantity }}
                            @else
                                Unlimited
                            @endif
                        </td>
                        <td>{{ $product->created_at->format('d M Y') }}</td>
                        <td>
                            @if($product->verification_status == 'approved')
                                <span class="status-completed">● Approved</span>
                            @elseif($product->verification_status == 'rejected')
                                <span class="status-pending">● Rejected</span>
                            @else
                                <span class="status-pending">● Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($product->verification_status == 'pending')
                                <div class="action-buttons">
                                    <button type="button" class="btn accept" onclick="showPlatformModal({{ $product->id }}, '{{ $product->platform_type }}', '{{ $product->platform_url }}', '{{ $product->platform_file }}')">
                                        <i class="fas fa-eye"></i> View Platform
                                    </button>
                                    <form action="{{ route('verifikasi.verify', $product->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="btn accept">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn accept" style="background-color: #dc3545;" onclick="showRejectModal({{ $product->id }})">
                                        <i class="fas fa-times"></i> Reject
                                    </button>
                                </div>
                            @else
                                <div class="action-buttons">
                                    <button class="btn accepted" disabled>
                                        <i class="fas fa-check-circle"></i> {{ ucfirst($product->verification_status) }}
                                    </button>
                                    @if($product->verification_status == 'rejected' && $product->rejection_reason)
                                        <div class="rejection-reason">
                                            <i class="fas fa-info-circle"></i> Alasan: {{ $product->rejection_reason }}
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="noDataMessage" class="no-data" style="display: none;">
                <i class="fas fa-box-open"></i>
                <p>No products found</p>
            </div>
        </div>
    </div>

    <!-- Modal untuk Platform -->
    <div id="platformModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Platform Details</h2>
                <button class="close-button" onclick="closePlatformModal()">×</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Platform Type</label>
                    <p id="platformType"></p>
                </div>
                <div class="form-group" id="platformUrlGroup">
                    <label>Platform URL</label>
                    <p id="platformUrl"></p>
                </div>
                <div class="form-group" id="platformFileGroup">
                    <label>Platform File</label>
                    <p id="platformFile"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel" onclick="closePlatformModal()">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Modal untuk Reject -->
    <div id="rejectModal" class="modal">
        <div class="modal-content">
        <div class="modal-header">
            <h2>Reject Content</h2>
            <button class="close-button" onclick="closeRejectModal()">×</button>
        </div>

            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="status" value="rejected">
                    <div class="form-group">
                        <label>Alasan Penolakan</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Masukkan alasan penolakan produk..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn cancel" onclick="closeRejectModal()">Batal</button>
                    <button type="submit" class="btn reject">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal untuk Deskripsi -->
    <div id="descriptionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Product Description</h2>
                <button class="close-button" onclick="closeDescriptionModal()">×</button>
            </div>
            <div class="modal-body">
                <p id="fullDescription"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn cancel" onclick="closeDescriptionModal()">Tutup</button>
            </div>
        </div>
    </div>

    <script>
    function showPlatformModal(productId, platformType, platformUrl, platformFile) {
        document.getElementById('platformType').textContent = platformType.charAt(0).toUpperCase() + platformType.slice(1);
        
        const urlGroup = document.getElementById('platformUrlGroup');
        const fileGroup = document.getElementById('platformFileGroup');
        
        if (platformType === 'upload') {
            urlGroup.style.display = 'none';
            fileGroup.style.display = 'block';
            document.getElementById('platformFile').innerHTML = `<a href="{{ asset('storage/') }}/${platformFile}" target="_blank">Lihat File</a>`;
        } else {
            urlGroup.style.display = 'block';
            fileGroup.style.display = 'none';
            document.getElementById('platformUrl').innerHTML = `<a href="${platformUrl}" target="_blank">${platformUrl}</a>`;
        }
        
        document.getElementById('platformModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closePlatformModal() {
        document.getElementById('platformModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function showRejectModal(productId) {
        document.getElementById('rejectForm').action = `/platformadmin/verifikasi/${productId}`;
        document.getElementById('rejectModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    function showDescriptionModal(element) {
        const description = element.dataset.fullDescription;
        const formattedDescription = description.replace(/\r\n|\r|\n/g, '<br>');
        document.getElementById('fullDescription').innerHTML = formattedDescription;
        document.getElementById('descriptionModal').style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeDescriptionModal() {
        document.getElementById('descriptionModal').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.className === 'modal') {
            closeRejectModal();
            closePlatformModal();
            closeDescriptionModal();
        }
    }

    // Filter and Search Functions
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab');
        const searchInput = document.getElementById('searchInput');
        const platformFilter = document.getElementById('platformFilter');
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');
        const productRows = document.querySelectorAll('.product-row');
        const noDataMessage = document.getElementById('noDataMessage');

        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase();
            const platformValue = platformFilter.value;
            const startDateValue = startDate.value;
            const endDateValue = endDate.value;
            const activeTab = document.querySelector('.tab.active').dataset.tab;
            
            let visibleCount = 0;

            productRows.forEach(row => {
                const status = row.dataset.status;
                const platform = row.dataset.platform;
                const date = row.dataset.date;
                const title = row.dataset.title;
                const username = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const description = row.querySelector('td:nth-child(4)').textContent.toLowerCase();

                const matchesSearch = title.includes(searchTerm) || 
                                      username.includes(searchTerm) || 
                                      description.includes(searchTerm);
                
                const matchesPlatform = !platformValue || platform === platformValue;
                const matchesDate = (!startDateValue || date >= startDateValue) && 
                                  (!endDateValue || date <= endDateValue);

                let matchesStatus = false;
                if (activeTab === 'pending') {
                    matchesStatus = status === 'pending';
                } else if (activeTab === 'approved') {
                    matchesStatus = status === 'approved';
                } else if (activeTab === 'rejected') {
                    matchesStatus = status === 'rejected';
                } else if (activeTab === 'archive') {
                    matchesStatus = status !== 'pending'; 
                } else {
                    matchesStatus = true; 
                }

                if (matchesSearch && matchesPlatform && matchesDate && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            noDataMessage.style.display = visibleCount === 0 ? 'block' : 'none';
        }

        // Event Listeners
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                filterProducts();
            });
        });

        searchInput.addEventListener('input', filterProducts);
        platformFilter.addEventListener('change', filterProducts);
        startDate.addEventListener('change', filterProducts);
        endDate.addEventListener('change', filterProducts);

        // Initial filter
        filterProducts();
    });
    </script>

</body>
</html>
