<?php echo $this->extend('admin/includes/header'); ?>
<!--start content-->

<?php echo $this->section('content'); ?>

<main class="main-content">
    <div class="position-relative iq-banner">
        <?php echo $this->include('admin/nav_bar') ?>
    </div>

    <div class="conatiner-fluid content-inner pb-0" id="page_layout">
        <!-- Modern Header Section -->
        <div class="container-fluid p-0 mb-4">
            <div class="bg-gradient-primary text-white p-5 rounded-3 shadow position-relative overflow-hidden">
                <div class="position-relative z-1">
                    <h1 class="h2 fw-bold mb-2 text-white">Missed / Not Done Dog Walks Report</h1>
                    <p class="text-white-75 mb-0">Track and manage missed walking services with reason codes</p>
                </div>
                <i class="bi bi-watch position-absolute end-0 bottom-0 opacity-10"
                    style="font-size: 14rem; transform: rotate(12deg); margin-right: -3rem; margin-bottom: -3rem;"></i>
            </div>
        </div>

        <!-- Search and Filter Card -->
        <div class="card border-0 shadow-sm mb-2 rounded-3">
            <div class="card-body p-3">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text bg-black border-end-0 text-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control border-start-0" placeholder="Search walks..." id="searchWalks">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2 mb-md-0">
                            <label class="text-muted small me-2">Show</label>
                            <select class="form-select form-select-sm" style="width: auto;" id="entriesPerPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="all">All</option>
                            </select>
                            <label class="text-muted small ms-2">entries</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-black bg-black p-2 text-white w-100" onclick="applyFilters()">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-black bg-black p-2 text-white w-100" onclick="clearFilters()">
                            <i class="bi bi-x-circle me-1"></i>Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card border-0 shadow-sm overflow-hidden rounded-3">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="walksTable">
                    <thead class="" style="background: #c6d2ff !important;">
                        <tr>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6" style="background: #c6d2ff !important;" data-sort="date">Date <i class="bi bi-arrow-down-up ms-1"></i></th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6" style="background: #c6d2ff !important;" data-sort="times">Times</th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6" style="background: #c6d2ff !important;" data-sort="status">Status <i class="bi bi-arrow-down-up ms-1"></i></th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6" style="background: #c6d2ff !important;" data-sort="client">Client <i class="bi bi-arrow-down-up ms-1"></i></th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6" style="background: #c6d2ff !important;" data-sort="pet">Pet <i class="bi bi-arrow-down-up ms-1"></i></th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6" style="background: #c6d2ff !important;" data-sort="provider">Provider <i class="bi bi-arrow-down-up ms-1"></i></th>
                            <th class="py-3 text-black fw-semibold border-bottom text-center fs-6" style="background: #c6d2ff !important; width: 250px;">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0" id="tableBody">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end align-items-center px-3 py-3 border-top">
                <div class="btn-group mt-0 pt-0 border-0" id="paginationControls">
                    <button class="btn bg-primary btn-sm text-white" id="prevPage" disabled>
                        <i class="bi bi-chevron-left"></i> Previous
                    </button>
                    <div class="" id="pageNumbers" style="gap: 2px;"></div>
                    <button class="btn bg-primary btn-sm text-white" id="nextPage">
                        Next <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reason Modal -->
    <div id="reasonModal" class="modal" tabindex="-1" aria-labelledby="reasonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow p-0">
                <div class="modal-header p-3" style="background: #c6d2ff !important; border-bottom: 2px solid #a3b4ff;">
                    <h5 class="modal-title text-dark fw-bold fs-5 mb-0" id="reasonModalLabel">
                        <i class="bi bi-pencil-square me-2"></i>Add/Edit Reason
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="closeReasonModal()"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="modalTrackId">
                    <input type="hidden" id="modalServiceId">
                    
                    <div class="mb-3">
                        <label for="modalReason" class="form-label fw-medium text-dark mb-2">Select Reason</label>
                        <select id="modalReason" class="reason-select w-100">
                            <option value="">Select a reason</option>
                            <option value="not_available">Client Not Available</option>
                            <option value="skip">Client asked to skip</option>
                            <option value="emergency">Emergency</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="modalOtherReasonContainer" style="display: none;">
                        <label for="modalOtherReason" class="form-label fw-medium text-dark mb-2">Enter Reason</label>
                        <input type="text" id="modalOtherReason" class="other-reason-input w-100" placeholder="Enter reason...">
                    </div>
                    
                    <div id="modalStatus" class="status-message mb-3" style="display: none;"></div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" onclick="closeReasonModal()">
                        <i class="bi bi-x-circle me-1"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary" id="modalSaveBtn" style="background: var(--primary-main); border: none;">
                        <i class="bi bi-check2 me-1"></i>Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php echo $this->include('admin/footer') ?>
</main>

<style>
    /* Modern Color Variables - Matching bookings management */
    :root {
        --primary-light: #c6d2ff;
        --primary-main: #3f30e3;
        --primary-dark: #2b1fb3;
        --primary-gradient: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%);
        --secondary-light: #f0f2ff;
        --success-light: rgba(34, 197, 94, 0.1);
        --success-border: rgba(34, 197, 94, 0.2);
        --success-text: #16a34a;
        --warning-light: rgba(245, 158, 11, 0.1);
        --warning-border: rgba(245, 158, 11, 0.2);
        --warning-text: #f59e0b;
        --danger-light: rgba(239, 68, 68, 0.1);
        --danger-border: rgba(239, 68, 68, 0.2);
        --danger-text: #ef4444;
        --info-light: rgba(59, 130, 246, 0.1);
        --info-border: rgba(59, 130, 246, 0.2);
        --info-text: #3b82f6;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f30e3 0%, #5555fa 100%) !important;
    }

    /* Table Styling */
    .table-hover tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.03) !important;
    }

    .table thead th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding-top: 1rem;
        padding-bottom: 1rem;
        cursor: pointer;
        white-space: nowrap;
    }

    .table thead th i {
        font-size: 0.7rem;
        opacity: 0.5;
    }

    .table thead th.sort-asc i,
    .table thead th.sort-desc i {
        opacity: 1;
    }

    /* Badge Styles */
    .badge-status-notdone {
        background: var(--danger-light) !important;
        color: var(--danger-text) !important;
        border: 1px solid var(--danger-border) !important;
        padding: 0.5rem 1rem;
        border-radius: 40px;
        font-weight: 500;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-status-completed {
        background: var(--success-light) !important;
        color: var(--success-text) !important;
        border: 1px solid var(--success-border) !important;
        padding: 0.5rem 1rem;
        border-radius: 40px;
        font-weight: 500;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-service {
        background: var(--info-light) !important;
        color: var(--info-text) !important;
        border: 1px solid var(--info-border) !important;
        font-weight: 500;
        padding: 0.3rem 0.8rem;
        border-radius: 40px;
        font-size: 0.75rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    /* Reason Badge - Clickable */
    .reason-badge {
        background: var(--primary-light) !important;
        color: var(--primary-dark) !important;
        border: 1px solid var(--primary-main) !important;
        padding: 0.5rem 1rem;
        border-radius: 40px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
        max-width: 200px;
    }

    .reason-badge:hover {
        background: var(--primary-main) !important;
        color: white !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(63, 48, 227, 0.2);
    }

    .reason-badge i {
        font-size: 0.9rem;
    }

    /* Add Reason Button */
    .add-reason-btn {
        background: transparent;
        color: var(--primary-main);
        border: 1px dashed var(--primary-main);
        padding: 0.5rem 1rem;
        border-radius: 40px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .add-reason-btn:hover {
        background: var(--primary-light);
        transform: translateY(-1px);
    }

    /* Avatar Styles */
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-light);
        color: var(--primary-dark);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        flex-shrink: 0;
    }

    /* Combined Column Styling */
    .combined-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 180px;
    }

    .info-details {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .info-name {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.95rem;
    }

    .info-phone {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6c757d;
        font-size: 0.85rem;
    }

    .info-phone i {
        color: var(--primary-main);
        font-size: 0.8rem;
    }

    /* Pet Name Link */
    .pet-link {
        color: var(--primary-main);
        text-decoration: none;
        font-weight: 500;
        padding: 4px 12px;
        border-radius: 20px;
        background: var(--primary-light);
        display: inline-block;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .pet-link:hover {
        background: var(--primary-main);
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(63, 48, 227, 0.2);
    }

    /* Reason Wrapper */
    .reason-wrapper {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 200px;
    }

    /* Reason Select Styling */
    .reason-select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        background-color: white;
        font-size: 0.9rem;
        color: #495057;
        transition: all 0.3s ease;
    }

    .reason-select:focus {
        border-color: var(--primary-main);
        box-shadow: 0 0 0 3px rgba(63, 48, 227, 0.1);
        outline: none;
    }

    /* Other Reason Input */
    .other-reason-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        font-size: 0.9rem;
    }

    .other-reason-input:focus {
        border-color: var(--primary-main);
        box-shadow: 0 0 0 3px rgba(63, 48, 227, 0.1);
        outline: none;
    }

    /* Status Message */
    .status-message {
        font-size: 0.85rem;
        padding: 8px 12px;
        border-radius: 8px;
        display: none;
    }

    .status-message.text-success {
        background: var(--success-light);
        color: var(--success-text);
        border: 1px solid var(--success-border);
        display: block;
    }

    .status-message.text-danger {
        background: var(--danger-light);
        color: var(--danger-text);
        border: 1px solid var(--danger-border);
        display: block;
    }

    .status-message.text-info {
        background: var(--info-light);
        color: var(--info-text);
        border: 1px solid var(--info-border);
        display: block;
    }

    /* Time badges container */
    .time-badges {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: center;
    }

    /* Modal Styling */
    .modal {
        display: none;
        position: fixed;
        z-index: 1999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        overflow: hidden;
        align-items: center;
        justify-content: center;
    }

    .modal.show {
        display: flex !important;
    }

    .modal-dialog {
        margin: auto;
        width: 90%;
        max-width: 500px;
    }

    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 3rem);
    }

    .modal-content {
        background-color: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        border: none;
        overflow: hidden;
    }

    .modal.show .modal-dialog {
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            transform: translateY(-30px) scale(0.95);
            opacity: 0;
        }
        to {
            transform: translateY(0) scale(1);
            opacity: 1;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .avatar-circle {
            width: 32px;
            height: 32px;
            font-size: 0.85rem;
        }

        .info-name {
            font-size: 0.85rem;
        }

        .info-phone {
            font-size: 0.75rem;
        }

        .combined-info {
            min-width: 150px;
            gap: 8px;
        }

        .reason-badge {
            font-size: 0.75rem;
            padding: 0.3rem 0.8rem;
        }
    }

    @media (max-width: 576px) {
        .combined-info {
            min-width: 130px;
        }

        .avatar-circle {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }

        .pet-link {
            font-size: 0.8rem;
            padding: 2px 8px;
        }
    }

    /* Utility Classes */
    .bg-primary-light {
        background: var(--primary-light) !important;
    }

    .text-primary-dark {
        color: var(--primary-dark) !important;
    }
</style>

<script>
    // Walk data from PHP
    const walksData = <?php echo json_encode($walks); ?>;

    // State management
    let currentPage = 1;
    let entriesPerPage = 10;
    let searchTerm = '';
    let sortColumn = 'date';
    let sortDirection = 'desc';
    let filteredData = [...walksData];

    // DOM elements
    const tableBody = document.getElementById('tableBody');
    const searchInput = document.getElementById('searchWalks');
    const entriesSelect = document.getElementById('entriesPerPage');
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    const pageNumbersDiv = document.getElementById('pageNumbers');

    // Modal elements
    const reasonModal = document.getElementById('reasonModal');
    const modalTrackId = document.getElementById('modalTrackId');
    const modalServiceId = document.getElementById('modalServiceId');
    const modalReason = document.getElementById('modalReason');
    const modalOtherReason = document.getElementById('modalOtherReason');
    const modalOtherReasonContainer = document.getElementById('modalOtherReasonContainer');
    const modalStatus = document.getElementById('modalStatus');
    const modalSaveBtn = document.getElementById('modalSaveBtn');

    // Initialize the table
    document.addEventListener('DOMContentLoaded', function() {
        renderTable();
        setupEventListeners();
        setupModalHandlers();
    });

    // Setup event listeners
    function setupEventListeners() {
        searchInput.addEventListener('input', function(e) {
            searchTerm = e.target.value.toLowerCase();
            currentPage = 1;
            filterData();
        });

        entriesSelect.addEventListener('change', function(e) {
            entriesPerPage = e.target.value === 'all' ? filteredData.length : parseInt(e.target.value);
            currentPage = 1;
            renderTable();
        });

        prevBtn.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        });

        nextBtn.addEventListener('click', function() {
            const totalPages = Math.ceil(filteredData.length / entriesPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderTable();
            }
        });

        // Sort on header click
        document.querySelectorAll('#walksTable thead th[data-sort]').forEach(header => {
            header.addEventListener('click', function() {
                const column = this.dataset.sort;
                if (sortColumn === column) {
                    sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    sortColumn = column;
                    sortDirection = 'asc';
                }
                
                // Update sort icons
                document.querySelectorAll('#walksTable thead th i').forEach(icon => {
                    icon.className = 'bi bi-arrow-down-up ms-1';
                });
                this.querySelector('i').className = sortDirection === 'asc' ? 'bi bi-arrow-up ms-1' : 'bi bi-arrow-down ms-1';
                
                filterData();
            });
        });
    }

    // Filter and sort data
    function filterData() {
        // Apply search filter
        filteredData = walksData.filter(walk => {
            const searchableText = `
                ${walk.user_name || ''} 
                ${walk.user_phone || ''} 
                ${walk.provider_name || ''} 
                ${walk.provider_phone || ''} 
                ${walk.pet_name || ''} 
                ${walk.tracking_date || ''}
                ${walk.status || ''}
                ${getReasonDisplayText(walk.reason)}
            `.toLowerCase();
            
            return searchableText.includes(searchTerm);
        });

        // Apply sorting
        filteredData.sort((a, b) => {
            let valA, valB;
            
            switch(sortColumn) {
                case 'date':
                    valA = new Date(a.tracking_date || '1970-01-01');
                    valB = new Date(b.tracking_date || '1970-01-01');
                    break;
                case 'status':
                    valA = a.status || '';
                    valB = b.status || '';
                    break;
                case 'client':
                    valA = a.user_name || '';
                    valB = b.user_name || '';
                    break;
                case 'pet':
                    valA = a.pet_name || '';
                    valB = b.pet_name || '';
                    break;
                case 'provider':
                    valA = a.provider_name || '';
                    valB = b.provider_name || '';
                    break;
                default:
                    return 0;
            }
            
            if (valA < valB) return sortDirection === 'asc' ? -1 : 1;
            if (valA > valB) return sortDirection === 'asc' ? 1 : -1;
            return 0;
        });

        renderTable();
    }

    // Helper function to get reason display text
    function getReasonDisplayText(reason) {
        if (!reason) return '';
        
        const reasonMap = {
            'not_available': 'Client Not Available',
            'skip': 'Client asked to skip',
            'emergency': 'Emergency',
            'other': 'Other'
        };
        
        return reasonMap[reason] || reason;
    }

    // Render table with current page data
    function renderTable() {
        const start = (currentPage - 1) * entriesPerPage;
        const end = Math.min(start + entriesPerPage, filteredData.length);
        const pageData = filteredData.slice(start, end);
        
        let html = '';
        
        pageData.forEach(walk => {
            const statusClass = (walk.status == 'Untracked') ? 'badge-status-notdone' : 'badge-status-completed';
            const statusText = (walk.status == 'Untracked') ? 'Not Done' : walk.status;
            const statusIcon = (walk.status == 'Untracked') ? 'bi-exclamation-triangle' : 'bi-check-circle';
            
            const clientInitials = (walk.user_name || 'C').charAt(0).toUpperCase();
            const providerInitials = (walk.provider_name || 'P').charAt(0).toUpperCase();
            
            const startTime = walk.start_time && walk.start_time != '0000-00-00 00:00:00' 
                ? new Date(walk.start_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                : null;
                
            const endTime = walk.end_time && walk.end_time != '0000-00-00 00:00:00'
                ? new Date(walk.end_time).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true })
                : null;
            
            // Generate reason HTML based on whether reason exists
            let reasonHtml = '';
            if (walk.reason) {
                const reasonText = getReasonDisplayText(walk.reason);
                reasonHtml = `
                    <div class="reason-badge" onclick="openReasonModal('${walk.track_id}', '4', '${walk.reason}', '${reasonText}')">
                        <i class="bi bi-chat-dots"></i>
                        <span>${reasonText}</span>
                        <i class="bi bi-pencil-fill ms-1" style="font-size: 0.7rem;"></i>
                    </div>
                `;
            } else {
                reasonHtml = `
                    <div class="add-reason-btn" onclick="openReasonModal('${walk.track_id}', '4', '', '')">
                        <i class="bi bi-plus-circle"></i>
                        <span>Add Reason</span>
                    </div>
                `;
            }
            
            html += `
                <tr class="walk-row">
                    <td class="text-center align-middle">
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-calendar3 text-primary"></i>
                            <span class="text-dark">${walk.tracking_date || '--'}</span>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <div class="time-badges">
                            ${startTime ? `
                                <span class="badge-service rounded-pill px-3 py-2">
                                    <i class="bi bi-play-circle me-1"></i>${startTime}
                                </span>
                            ` : '<span class="text-muted small">No start time</span>'}
                            ${endTime ? `
                                <span class="badge-service rounded-pill px-3 py-2">
                                    <i class="bi bi-stop-circle me-1"></i>${endTime}
                                </span>
                            ` : ''}
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <span class="${statusClass} rounded-pill px-3 py-2">
                            <i class="bi ${statusIcon} me-1"></i>
                            ${statusText}
                        </span>
                    </td>
                    <td class="align-middle">
                        <div class="combined-info">
                            <div class="avatar-circle">${clientInitials}</div>
                            <div class="info-details">
                                <span class="info-name">${walk.user_name || '--'}</span>
                                <span class="info-phone">
                                    <i class="bi bi-telephone"></i>
                                    ${walk.user_phone || '--'}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <a href="<?= base_url() ?>trackings?booking_id=${walk.booking_id}&service_id=4&provider_id=${walk.provider_id}" 
                           class="pet-link">
                            <i class="bi bi-paw me-1"></i>
                            ${walk.pet_name || '--'}
                        </a>
                    </td>
                    <td class="align-middle">
                        <div class="combined-info">
                            <div class="avatar-circle">${providerInitials}</div>
                            <div class="info-details">
                                <span class="info-name">${walk.provider_name || '--'}</span>
                                <span class="info-phone">
                                    <i class="bi bi-telephone"></i>
                                    ${walk.provider_phone || '--'}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="align-middle">
                        <div class="reason-wrapper">
                            ${reasonHtml}
                        </div>
                    </td>
                </tr>
            `;
        });
        
        tableBody.innerHTML = html;
        updatePaginationControls();
    }

    // Update pagination controls
    function updatePaginationControls() {
        const totalPages = Math.ceil(filteredData.length / entriesPerPage);
        
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages || totalPages === 0;
        
        let pageNumbersHtml = '';
        const maxVisiblePages = 5;
        
        if (totalPages <= maxVisiblePages) {
            for (let i = 1; i <= totalPages; i++) {
                pageNumbersHtml += getPageButtonHtml(i);
            }
        } else {
            if (currentPage <= 3) {
                for (let i = 1; i <= 4; i++) {
                    pageNumbersHtml += getPageButtonHtml(i);
                }
                pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
                pageNumbersHtml += getPageButtonHtml(totalPages);
            } else if (currentPage >= totalPages - 2) {
                pageNumbersHtml += getPageButtonHtml(1);
                pageNumbersHtml += `<span class="btn btn-sm bg-secondary border-0" disabled>...</span>`;
                for (let i = totalPages - 3; i <= totalPages; i++) {
                    pageNumbersHtml += getPageButtonHtml(i);
                }
            } else {
                pageNumbersHtml += getPageButtonHtml(1);
                pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
                for (let i = currentPage - 1; i <= currentPage + 1; i++) {
                    pageNumbersHtml += getPageButtonHtml(i);
                }
                pageNumbersHtml += `<span class="btn btn-sm btn-outline-secondary border-0" disabled>...</span>`;
                pageNumbersHtml += getPageButtonHtml(totalPages);
            }
        }
        
        pageNumbersDiv.innerHTML = pageNumbersHtml;
        
        document.querySelectorAll('.page-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentPage = parseInt(this.dataset.page);
                renderTable();
            });
        });
    }

    function getPageButtonHtml(pageNum) {
        const activeClass = pageNum === currentPage ? 'btn-primary' : 'btn-outline-primary';
        return `<button class="btn btn-sm ${activeClass} page-btn" data-page="${pageNum}">${pageNum}</button>`;
    }

    // Modal functions
    function openReasonModal(trackId, serviceId, currentReason, reasonText) {
        modalTrackId.value = trackId;
        modalServiceId.value = serviceId;
        
        // Reset modal
        modalReason.value = currentReason || '';
        modalOtherReason.value = '';
        modalOtherReasonContainer.style.display = 'none';
        modalStatus.style.display = 'none';
        modalStatus.className = 'status-message';
        
        // Show other reason input if current reason is 'other' or custom
        if (currentReason && !['not_available', 'skip', 'emergency', 'other'].includes(currentReason)) {
            modalReason.value = 'other';
            modalOtherReason.value = currentReason;
            modalOtherReasonContainer.style.display = 'block';
        } else if (currentReason === 'other') {
            modalOtherReasonContainer.style.display = 'block';
        }
        
        reasonModal.classList.add('show');
        document.body.classList.add('modal-open');
    }

    function closeReasonModal() {
        reasonModal.classList.remove('show');
        document.body.classList.remove('modal-open');
    }

    function setupModalHandlers() {
        // Close modal when clicking outside
        reasonModal.addEventListener('click', function(e) {
            if (e.target === reasonModal) {
                closeReasonModal();
            }
        });

        // Handle reason change in modal
        modalReason.addEventListener('change', function() {
            if (this.value === 'other') {
                modalOtherReasonContainer.style.display = 'block';
            } else {
                modalOtherReasonContainer.style.display = 'none';
                modalOtherReason.value = '';
            }
        });

        // Save button click
        modalSaveBtn.addEventListener('click', function() {
            const trackId = modalTrackId.value;
            const serviceId = modalServiceId.value;
            const selectedReason = modalReason.value;
            const customReason = modalOtherReason.value.trim();
            
            let finalReason = '';
            
            if (!selectedReason) {
                showModalStatus('Please select a reason', 'danger');
                return;
            }
            
            if (selectedReason === 'other') {
                if (!customReason) {
                    showModalStatus('Please enter a reason', 'danger');
                    return;
                }
                finalReason = customReason;
            } else {
                finalReason = selectedReason;
            }
            
            saveReasonFromModal(trackId, finalReason, serviceId);
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && reasonModal.classList.contains('show')) {
                closeReasonModal();
            }
        });
    }

    function showModalStatus(message, type) {
        modalStatus.textContent = message;
        modalStatus.className = `status-message text-${type}`;
        modalStatus.style.display = 'block';
    }

    function saveReasonFromModal(trackId, reason, serviceId) {
        showModalStatus('Saving...', 'info');
        
        fetch('<?= base_url() ?>updateReason', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                track_id: trackId,
                reason: reason,
                service_id: serviceId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === true) {
                showModalStatus('Saved successfully!', 'success');
                
                
                // Update the walk data
                const walkIndex = walksData.findIndex(w => w.track_id == trackId);
                if (walkIndex !== -1) {
                    walksData[walkIndex].reason = reason;
                }
                
                // Re-filter and re-render
                filterData();
                
                setTimeout(() => {
                   closeReasonModal();
                  location.reload();
                   
                }, 1000);
            } else {
                showModalStatus(data.message || 'Error saving reason', 'danger');
            }
        })
        .catch(error => {
            showModalStatus('Request failed', 'danger');
        });
    }

    function applyFilters() {
        searchTerm = searchInput.value.toLowerCase();
        currentPage = 1;
        filterData();
    }

    function clearFilters() {
        searchInput.value = '';
        searchTerm = '';
        currentPage = 1;
        filterData();
    }
</script>

<?php echo $this->endSection() ?>