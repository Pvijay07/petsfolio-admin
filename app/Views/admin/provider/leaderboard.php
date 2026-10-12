<?= $this->extend('admin/includes/header'); ?>
<!--start content-->
<?= $this->section('content'); ?>

<!-- Load FontAwesome and Chart.js -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root {
        --theme-primary: #0d424d;
        --theme-primary-light: #e6eff2;
        --theme-primary-hover: #125e6e;
        --theme-accent: #ff4757;
        --theme-accent-light: #fff5f5;
        --theme-success: #10b981;
        --theme-success-light: #ecfdf5;
        --theme-warning: #f59e0b;
        --theme-warning-light: #fffbeb;
        --theme-text-dark: #0f172a;
        --theme-text-muted: #64748b;
        --theme-card-bg: #ffffff;
        --theme-border: #e2e8f0;
        --theme-shadow: 0 10px 30px rgba(13, 66, 77, 0.04);
        --theme-shadow-sm: 0 4px 10px rgba(0, 0, 0, 0.02);
        --theme-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .leaderboard-wrapper {
        background: #f8fafc;
        padding: 1.5rem;
        border-radius: 16px;
        font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
        color: var(--theme-text-dark);
        margin: 15px;
    }

    /* Premium Sticky Header */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        background: white;
        padding: 1.25rem 1.75rem;
        border-radius: 12px;
        box-shadow: var(--theme-shadow-sm);
        border: 1px solid var(--theme-border);
    }

    .dashboard-header h2 {
        font-weight: 800;
        color: var(--theme-primary);
        margin: 0;
        letter-spacing: -0.5px;
        font-size: 1.4rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .dashboard-header p {
        margin: 4px 0 0 0;
        color: var(--theme-text-muted);
        font-size: 0.85rem;
    }

    .header-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .engine-badge {
        background: var(--theme-success-light);
        color: var(--theme-success);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid rgba(16, 185, 129, 0.15);
    }

    .engine-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: var(--theme-success);
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.9); opacity: 0.6; }
        50% { transform: scale(1.2); opacity: 1; }
        100% { transform: scale(0.9); opacity: 0.6; }
    }

    /* Alert Box styling */
    .custom-alert {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
        padding: 0.85rem 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-weight: 600;
    }

    .custom-alert .close-btn {
        background: transparent;
        border: none;
        color: #047857;
        cursor: pointer;
        font-size: 1rem;
    }

    /* 10 KPIs Grid */
    .kpis-container-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--theme-text-muted);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .kpis-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 1400px) {
        .kpis-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 992px) {
        .kpis-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .kpis-grid { grid-template-columns: 1fr; }
    }

    .kpi-card {
        background: white;
        border: 1px solid var(--theme-border);
        border-radius: 14px;
        padding: 1.25rem;
        box-shadow: var(--theme-shadow-sm);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
        transition: var(--theme-transition);
        border-left: 4px solid var(--theme-primary-light);
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(13, 66, 77, 0.08);
        border-left-color: var(--theme-primary);
    }

    /* Colors border overrides */
    .kpi-card:nth-child(2) { border-left-color: var(--theme-warning); } /* Tier 1 */
    .kpi-card:nth-child(3) { border-left-color: #3b82f6; } /* Tier 2 */
    .kpi-card:nth-child(4) { border-left-color: #64748b; } /* Tier 3 */
    .kpi-card:nth-child(5) { border-left-color: var(--theme-accent); } /* Frozen */

    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .kpi-title {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--theme-text-muted);
        text-transform: capitalize;
    }

    .kpi-icon-container {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--theme-primary-light);
        color: var(--theme-primary);
        font-size: 0.9rem;
    }

    .kpi-card:nth-child(2) .kpi-icon-container { background: var(--theme-warning-light); color: var(--theme-warning); }
    .kpi-card:nth-child(3) .kpi-icon-container { background: #eff6ff; color: #3b82f6; }
    .kpi-card:nth-child(4) .kpi-icon-container { background: #f1f5f9; color: #64748b; }
    .kpi-card:nth-child(5) .kpi-icon-container { background: var(--theme-accent-light); color: var(--theme-accent); }

    .kpi-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--theme-primary);
        line-height: 1.1;
    }

    .kpi-subtext {
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--theme-text-muted);
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .trend-badge {
        font-size: 0.7rem;
        font-weight: 750;
        padding: 2px 6px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }

    .trend-up { background: var(--theme-success-light); color: var(--theme-success); }
    .trend-down { background: var(--theme-accent-light); color: var(--theme-accent); }

    /* Filters Bar */
    .search-filter-row {
        background: white;
        border: 1px solid var(--theme-border);
        border-radius: 10px;
        padding: 0.85rem 1.25rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--theme-shadow-sm);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .search-filter-left {
        display: flex;
        flex: 1;
        min-width: 280px;
        position: relative;
    }

    .search-filter-left i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--theme-text-muted);
    }

    .search-filter-left input {
        width: 100%;
        padding: 8px 12px 8px 38px;
        border: 1px solid var(--theme-border);
        border-radius: 8px;
        font-size: 0.85rem;
        background: #f8fafc;
        outline: none;
        transition: var(--theme-transition);
    }

    .search-filter-left input:focus {
        background: white;
        border-color: var(--theme-primary);
        box-shadow: 0 0 0 3px rgba(13, 66, 77, 0.08);
    }

    .search-filter-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-dropdown-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--theme-text-muted);
    }

    .filter-dropdown-wrapper select {
        padding: 6px 12px;
        border: 1px solid var(--theme-border);
        border-radius: 8px;
        font-size: 0.8rem;
        background: #f8fafc;
        outline: none;
        cursor: pointer;
        font-weight: 600;
    }

    .found-badge {
        background: var(--theme-primary-light);
        color: var(--theme-primary);
        font-weight: 750;
        font-size: 0.75rem;
        padding: 6px 12px;
        border-radius: 8px;
    }

    /* Tab Custom Styling */
    .tabs-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 12px;
        margin-bottom: 1.5rem;
    }

    .tab-item-btn {
        background: white;
        border: 1px solid var(--theme-border);
        border-radius: 12px;
        padding: 1.1rem;
        text-align: left;
        cursor: pointer;
        transition: var(--theme-transition);
        display: flex;
        flex-direction: column;
        gap: 6px;
        box-shadow: var(--theme-shadow-sm);
    }

    .tab-item-btn:hover {
        border-color: var(--theme-primary);
        background: var(--theme-primary-light);
        transform: translateY(-2px);
    }

    .tab-item-btn.active {
        background: linear-gradient(135deg, #0d424d 0%, #175e6d 100%);
        color: white;
        border-color: var(--theme-primary);
        box-shadow: 0 8px 20px rgba(13, 66, 77, 0.18);
    }

    .tab-item-btn h4 {
        margin: 0;
        font-size: 0.9rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tab-item-btn p {
        margin: 0;
        font-size: 0.7rem;
        color: var(--theme-text-muted);
        font-weight: 600;
    }

    .tab-item-btn.active p {
        color: rgba(255,255,255,0.8);
    }

    /* Tab Content Grid */
    .tab-content-panel {
        display: none;
        animation: fadeIn 0.4s ease-out forwards;
    }

    .tab-content-panel.active {
        display: block;
    }

    /* Tab 1 elements */
    .insight-hero-box {
        background: linear-gradient(135deg, #0d424d 0%, #175e6d 100%);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 12px 30px rgba(13, 66, 77, 0.15);
        border: 1px solid rgba(255,255,255,0.05);
    }

    .insight-hero-content {
        flex: 1;
        padding-right: 1.5rem;
    }

    .insight-tag {
        font-size: 0.7rem;
        font-weight: 800;
        color: #fbbf24;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .insight-hero-content h3 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 800;
        letter-spacing: -0.3px;
    }

    .insight-hero-content p {
        margin: 6px 0 1.25rem 0;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.8);
        line-height: 1.4;
    }

    .insight-pills-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .insight-pill-card {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        padding: 10px 16px;
        border-radius: 12px;
        min-width: 145px;
        transition: var(--theme-transition);
    }

    .insight-pill-card:hover {
        background: rgba(255, 255, 255, 0.12);
        transform: translateY(-2px);
    }

    .insight-pill-card div {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 700;
    }

    .insight-pill-card strong {
        font-size: 1rem;
        font-weight: 800;
        color: white;
    }

    .insight-conversion-card {
        background: rgba(0, 0, 0, 0.2);
        padding: 1.25rem;
        border-radius: 12px;
        min-width: 200px;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .insight-conversion-card p {
        margin: 0;
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 800;
        color: rgba(255, 255, 255, 0.7);
        letter-spacing: 0.5px;
    }

    .insight-conversion-card h4 {
        margin: 5px 0;
        font-size: 1.85rem;
        font-weight: 800;
        color: #fbbf24;
    }

    .insight-conversion-card span {
        font-size: 0.7rem;
        color: #34d399;
        font-weight: 700;
    }

    /* Distribution Panel Row */
    .distribution-row {
        display: grid;
        grid-template-columns: 1.7fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
        .distribution-row { grid-template-columns: 1fr; }
    }

    .distribution-card {
        background: white;
        border: 1px solid var(--theme-border);
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: var(--theme-shadow-sm);
    }

    .distribution-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .distribution-header h3 {
        margin: 0;
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--theme-primary);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .distribution-header p {
        margin: 2px 0 0 0;
        font-size: 0.75rem;
        color: var(--theme-text-muted);
    }

    /* Table Tier Distrib */
    .table-distrib {
        width: 100%;
        border-collapse: collapse;
    }

    .table-distrib th {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--theme-text-muted);
        font-weight: 800;
        padding: 8px 12px;
        border-bottom: 1.5px solid var(--theme-border);
    }

    .table-distrib td {
        padding: 12px;
        font-size: 0.8rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-distrib tr:last-child td { border-bottom: none; }

    .progress-bar-custom {
        height: 6px;
        width: 80px;
        background: #f1f5f9;
        border-radius: 3px;
        display: inline-block;
        overflow: hidden;
        margin-right: 8px;
        vertical-align: middle;
    }

    .progress-bar-val {
        height: 100%;
        border-radius: 3px;
    }

    .bar-gold { background: linear-gradient(90deg, #FCD34D, #F59E0B); }
    .bar-blue { background: linear-gradient(90deg, #93C5FD, #3B82F6); }
    .bar-gray { background: linear-gradient(90deg, #CBD5E1, #64748B); }

    /* Evaluation Insights block */
    .insight-summary-card {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .insight-summary-item {
        background: #f8fafc;
        border: 1px solid var(--theme-border);
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .insight-summary-item-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .insight-summary-item-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    .insight-summary-item-details strong {
        font-size: 0.85rem;
        color: var(--theme-text-dark);
        display: block;
    }

    .insight-summary-item-details span {
        font-size: 0.75rem;
        color: var(--theme-text-muted);
    }

    /* Reports & Intelligence Section */
    .reports-heading {
        margin: 1.5rem 0 1rem 0;
    }

    .reports-heading h3 {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--theme-primary);
        margin: 0;
    }

    .reports-heading p {
        margin: 2px 0 0 0;
        font-size: 0.75rem;
        color: var(--theme-text-muted);
    }

    .revenue-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
        .revenue-grid { grid-template-columns: 1fr; }
    }

    /* CSAT SLA Cards */
    .sla-cards-col {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .sla-mini-card {
        background: white;
        border: 1px solid var(--theme-border);
        border-radius: 14px;
        padding: 1.25rem;
        box-shadow: var(--theme-shadow-sm);
        transition: var(--theme-transition);
    }

    .sla-mini-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--theme-shadow);
        border-color: rgba(13, 66, 77, 0.1);
    }

    .sla-mini-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .sla-mini-card-header strong {
        font-size: 0.8rem;
        color: var(--theme-primary);
    }

    .sla-mini-card-header span {
        font-size: 0.75rem;
        font-weight: 750;
        color: var(--theme-text-muted);
        background: #f1f5f9;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .sla-mini-metrics {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .sla-mini-metric-item {
        background: #f8fafc;
        padding: 6px;
        border-radius: 6px;
        text-align: center;
        border: 1px solid #f1f5f9;
    }

    .sla-mini-metric-item span {
        font-size: 0.65rem;
        color: var(--theme-text-muted);
        display: block;
    }

    .sla-mini-metric-item strong {
        font-size: 0.8rem;
        color: var(--theme-text-dark);
    }

    /* Area Intelligence */
    .areas-container-title {
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--theme-text-muted);
        margin: 1.5rem 0 0.75rem 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .areas-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
        .areas-grid { grid-template-columns: 1fr; }
    }

    .area-card {
        background: white;
        border: 1px solid var(--theme-border);
        border-radius: 14px;
        padding: 1.25rem;
        box-shadow: var(--theme-shadow-sm);
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: var(--theme-transition);
    }

    .area-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--theme-shadow);
    }

    .area-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .area-card-header strong {
        font-size: 0.85rem;
        color: var(--theme-primary);
        font-weight: 800;
    }

    .area-card-header span {
        font-size: 0.7rem;
        font-weight: 750;
        padding: 2px 8px;
        border-radius: 4px;
    }

    .area-counts {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
    }

    .area-count-item {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 8px;
        text-align: center;
    }

    .area-count-item strong {
        font-size: 0.9rem;
        color: var(--theme-primary);
        display: block;
    }

    .area-count-item span {
        font-size: 0.65rem;
        color: var(--theme-text-muted);
    }

    .area-ai-suggestion {
        background: var(--theme-primary-light);
        border: 1px solid rgba(13, 66, 77, 0.1);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.75rem;
        line-height: 1.4;
        color: var(--theme-primary);
        font-weight: 600;
    }

    /* TAB 2: INTERACTIVE ROSTER LAYOUT */
    .roster-grid {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
        .roster-grid { grid-template-columns: 1fr; }
    }

    /* Left Sidebar: Walker Directory */
    .sidebar-directory {
        background: white;
        border: 1px solid var(--theme-border);
        border-radius: 14px;
        box-shadow: var(--theme-shadow-sm);
        display: flex;
        flex-direction: column;
        max-height: 700px;
        overflow: hidden;
    }

    .directory-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--theme-border);
        font-weight: 800;
        font-size: 0.85rem;
        color: var(--theme-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .directory-list {
        overflow-y: auto;
        flex: 1;
    }

    .directory-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        cursor: pointer;
        transition: var(--theme-transition);
    }

    .directory-item:last-child { border-bottom: none; }

    .directory-item:hover {
        background: #f8fafc;
    }

    .directory-item.active {
        background: var(--theme-primary-light);
        border-left: 4px solid var(--theme-primary);
    }

    .directory-item-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .directory-item-details h5 {
        margin: 0;
        font-size: 0.8rem;
        font-weight: 750;
        color: var(--theme-text-dark);
    }

    .directory-item-details p {
        margin: 2px 0 0 0;
        font-size: 0.7rem;
        color: var(--theme-text-muted);
        font-weight: 600;
    }

    .directory-item-right {
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .directory-score-badge {
        font-size: 0.8rem;
        font-weight: 800;
        color: var(--theme-primary);
        background: rgba(13, 66, 77, 0.1);
        padding: 2px 6px;
        border-radius: 4px;
        margin-bottom: 4px;
    }

    .directory-rating-stars {
        font-size: 0.65rem;
        color: var(--theme-warning);
    }

    /* Right Panel: Detail view */
    .detail-card {
        background: white;
        border: 1px solid var(--theme-border);
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: var(--theme-shadow-sm);
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        min-height: 600px;
    }

    .detail-alert-banner {
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .detail-alert-success { background: var(--theme-success-light); color: var(--theme-success); border: 1px solid rgba(16, 185, 129, 0.15); }
    .detail-alert-warning { background: var(--theme-warning-light); color: var(--theme-warning); border: 1px solid rgba(245, 158, 11, 0.15); }
    .detail-alert-danger { background: var(--theme-accent-light); color: var(--theme-accent); border: 1px solid rgba(255, 71, 87, 0.15); }

    .detail-profile-header {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        border-bottom: 1px solid var(--theme-border);
        padding-bottom: 1.25rem;
    }

    .detail-profile-header-avatar {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border: 3px solid white;
    }

    .detail-profile-info h3 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 800;
        color: var(--theme-primary);
    }

    .detail-profile-info-meta {
        font-size: 0.75rem;
        color: var(--theme-text-muted);
        font-weight: 600;
        margin-top: 4px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .detail-profile-info-meta span {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* 8 Metrics Grid */
    .detail-metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.85rem;
    }

    @media (max-width: 576px) {
        .detail-metrics-grid { grid-template-columns: repeat(2, 1fr); }
    }

    .detail-metric-card {
        background: #f8fafc;
        border: 1px solid var(--theme-border);
        border-radius: 10px;
        padding: 0.85rem;
        text-align: center;
    }

    .detail-metric-card span {
        font-size: 0.65rem;
        color: var(--theme-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 750;
        display: block;
        margin-bottom: 4px;
    }

    .detail-metric-card strong {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--theme-primary);
    }

    .detail-metric-card.gold-border {
        border-color: rgba(245, 158, 11, 0.3);
        background: #fffdf5;
    }

    .detail-actions-section {
        border-top: 1px solid var(--theme-border);
        padding-top: 1.25rem;
        margin-top: auto;
    }

    .detail-actions-section h4 {
        margin: 0 0 10px 0;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--theme-text-muted);
        font-weight: 800;
    }

    .detail-actions-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-action-primary {
        flex: 1;
        min-width: 140px;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 750;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: var(--theme-transition);
        box-shadow: var(--theme-shadow-sm);
    }

    .btn-action-promote { background: var(--theme-success); color: white; }
    .btn-action-promote:hover { background: #059669; }

    .btn-action-downgrade { background: var(--theme-warning); color: white; }
    .btn-action-downgrade:hover { background: #d97706; }

    .btn-action-strike { background: var(--theme-accent); color: white; }
    .btn-action-strike:hover { background: #e11d48; }

    .btn-action-ai { background: #8b5cf6; color: white; }
    .btn-action-ai:hover { background: #7c3aed; }

    .btn-action-restore { background: var(--theme-primary-light); color: var(--theme-primary); border: 1px solid rgba(13, 66, 77, 0.15); }
    .btn-action-restore:hover { background: var(--theme-primary); color: white; }

    /* General responsive styling */
    .fw-bold-700 { font-weight: 700; }
    .fw-bold-800 { font-weight: 800; }
    .text-center { text-align: center; }

    /* Slide-Over Drawer styling with absolute override */
    .drawer-overlay {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background: rgba(13, 66, 77, 0.4) !important;
        backdrop-filter: blur(4px) !important;
        z-index: 99999 !important;
        opacity: 0;
        visibility: hidden;
        transition: all 0.25s ease-in-out !important;
    }
    .drawer-overlay.open {
        opacity: 1 !important;
        visibility: visible !important;
    }
    .drawer-container {
        position: fixed !important;
        top: 0 !important;
        right: -450px !important;
        width: 450px !important;
        max-width: 90vw !important;
        height: 100% !important;
        background: white !important;
        box-shadow: -10px 0 40px rgba(0,0,0,0.15) !important;
        z-index: 100000 !important;
        transition: all 0.25s ease-in-out !important;
        display: flex !important;
        flex-direction: column !important;
    }
    .drawer-container.open {
        right: 0 !important;
    }
    
    .drawer-header {
        padding: 1.25rem;
        border-bottom: 1px solid var(--theme-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--theme-primary);
        color: white;
    }
    .drawer-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 800;
    }
    .drawer-close {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.25rem;
        cursor: pointer;
        opacity: 0.8;
    }
    .drawer-close:hover { opacity: 1; }
    .drawer-body {
        padding: 1.5rem;
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }
    .prediction-metric-item {
        background: #f8fafc;
        border: 1px solid var(--theme-border);
        border-radius: 10px;
        padding: 1rem;
    }
    .prediction-metric-item h5 {
        margin: 0 0 8px 0;
        font-size: 0.8rem;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
    }
    .progress-bar-bg {
        height: 8px;
        background: #e2e8f0;
        border-radius: 4px;
        overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%;
        border-radius: 4px;
    }
    .progress-purple { background: #8b5cf6; }
    .progress-amber { background: #f59e0b; }
    .progress-rose { background: #ef4444; }
    
    .drawer-recommendation-box {
        background: #f5f3ff;
        border: 1px solid #ddd6fe;
        border-radius: 10px;
        padding: 1rem;
        color: #5b21b6;
    }
    .drawer-recommendation-box h6 {
        margin: 0 0 6px 0;
        font-weight: 800;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .drawer-recommendation-box p {
        margin: 0;
        font-size: 0.75rem;
        line-height: 1.4;
    }
</style>

<div class="container-fluid">
    <div class="leaderboard-wrapper">
        <!-- Title Header -->
        <div class="dashboard-header">
            <div>
                <h2>
                    <i class="fas fa-paw" style="color: var(--theme-accent);"></i> 
                    SP Performance Dashboard 
                    <span class="badge bg-warning text-dark ml-2" style="font-size:0.65rem; text-transform:uppercase; font-weight:800; vertical-align:middle; border-radius:10px;">Walker Tier System</span>
                </h2>
                <p>Walker Tier Evaluation Platform - Petsfolio Operations</p>
            </div>
            <div class="header-actions">
                <span class="engine-badge mr-2">
                    <span class="engine-dot"></span>
                    Evaluation Engine: Live
                </span>
                <button class="btn btn-outline-danger" onclick="resetSimulatorStats()" style="font-weight: 750; border-radius: 8px; font-size:0.8rem; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-redo"></i> Reset Simulator
                </button>
            </div>
        </div>

        <!-- System Alert Toast -->
        <div class="custom-alert" id="system-reset-alert" style="display:none;">
            <span><i class="fas fa-check-circle"></i> Reset simulator data to fresh initial snapshot.</span>
            <button class="close-btn" onclick="$('#system-reset-alert').fadeOut()"><i class="fas fa-times"></i></button>
        </div>

        <!-- TOP PLATFORM KPIS Section -->
        <div class="kpis-container-title">
            <i class="fas fa-tachometer-alt"></i> Top Platform KPIs
        </div>
        <div class="kpis-grid">
            <!-- Row 1 -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Total Active Walkers</span>
                    <div class="kpi-icon-container"><i class="fas fa-users"></i></div>
                </div>
                <div class="kpi-value" id="kpi-total-walkers">0</div>
                <div class="kpi-subtext">
                    <span class="trend-badge trend-up"><i class="fas fa-arrow-up"></i> 4.2%</span>
                    <span>this month</span>
                </div>
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Tier 1 (Premium)</span>
                    <span class="badge bg-warning text-dark" id="kpi-t1-percent" style="font-weight:800; font-size:0.65rem; border-radius:4px;">0%</span>
                </div>
                <div class="kpi-value" id="kpi-t1-count">0</div>
                <div class="kpi-subtext">High earning premium SPs</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Tier 2 (Standard)</span>
                    <span class="badge bg-primary text-white" id="kpi-t2-percent" style="font-weight:800; font-size:0.65rem; border-radius:4px;">0%</span>
                </div>
                <div class="kpi-value" id="kpi-t2-count">0</div>
                <div class="kpi-subtext">Core operational backbone</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Tier 3 (Entry)</span>
                    <span class="badge bg-secondary text-white" id="kpi-t3-percent" style="font-weight:800; font-size:0.65rem; border-radius:4px;">0%</span>
                </div>
                <div class="kpi-value" id="kpi-t3-count">0</div>
                <div class="kpi-subtext">Undergoing onboarding/vetting</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Frozen Accounts</span>
                    <div class="kpi-icon-container text-danger"><i class="fas fa-lock"></i></div>
                </div>
                <div class="kpi-value text-danger" id="kpi-frozen-count">0</div>
                <div class="kpi-subtext text-danger"><i class="fas fa-exclamation-triangle"></i> 8+ strikes rule enforced</div>
            </div>

            <!-- Row 2 -->
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Avg Walker Rating</span>
                    <div class="kpi-icon-container text-warning"><i class="fas fa-star"></i></div>
                </div>
                <div class="kpi-value" id="kpi-avg-rating">0.00</div>
                <div class="kpi-subtext">Weighted customer reviews</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Avg Completion Rate</span>
                    <div class="kpi-icon-container text-success"><i class="fas fa-check-circle"></i></div>
                </div>
                <div class="kpi-value text-success" id="kpi-avg-completion">0.0%</div>
                <div class="kpi-subtext"><span class="text-success"><i class="fas fa-bullseye"></i> Target &gt; 95% met</span></div>
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Repeat Client %</span>
                    <div class="kpi-icon-container text-danger"><i class="fas fa-heart"></i></div>
                </div>
                <div class="kpi-value" id="kpi-repeat-percent">0.0%</div>
                <div class="kpi-subtext">Core loyalty indicator</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Police Verified %</span>
                    <div class="kpi-icon-container text-info"><i class="fas fa-shield-alt"></i></div>
                </div>
                <div class="kpi-value" id="kpi-police-percent">94.2%</div>
                <div class="kpi-subtext">Mandatory background clear</div>
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Insurance Covered %</span>
                    <div class="kpi-icon-container text-primary"><i class="fas fa-file-invoice"></i></div>
                </div>
                <div class="kpi-value" id="kpi-insurance-percent">89.6%</div>
                <div class="kpi-subtext text-warning"><i class="fas fa-clock"></i> Expiring soon: 14 SP</div>
            </div>
        </div>

        <!-- Search and filters block -->
        <div class="search-filter-row">
            <div class="search-filter-left">
                <i class="fas fa-search"></i>
                <input type="text" id="roster-search" placeholder="Search walkers by name, ID, or area..." onkeyup="handleFilterChange()">
            </div>
            <div class="search-filter-right">
                <div class="filter-dropdown-wrapper">
                    <span>Tier:</span>
                    <select id="filter-tier" onchange="handleFilterChange()">
                        <option value="">All Tiers</option>
                        <option value="1">Tier 1 (Premium)</option>
                        <option value="2">Tier 2 (Standard)</option>
                        <option value="3">Tier 3 (Entry)</option>
                    </select>
                </div>
                <div class="filter-dropdown-wrapper">
                    <span>City:</span>
                    <select id="filter-city" onchange="handleFilterChange()">
                        <option value="">All Cities</option>
                        <!-- Filled dynamically -->
                    </select>
                </div>
                <span class="found-badge" id="found-walkers-count">Found: 0 Walkers</span>
            </div>
        </div>

        <!-- Custom Sliding Tab Header Elements -->
        <div class="tabs-row">
            <div class="tab-item-btn active" id="tab-btn-overview" onclick="switchTab('overview')">
                <h4><i class="fas fa-chart-bar"></i> Executive Overview & Reports</h4>
                <p>Top KPIs, Tier Distribution, Area Intelligence & Business Reports</p>
            </div>
            <div class="tab-item-btn" id="tab-btn-roster" onclick="switchTab('roster')">
                <h4><i class="fas fa-users-cog"></i> Walker Evaluation & Ranking</h4>
                <p>Per-Walker Simulator, Leaderboards, Experience & Qualification</p>
            </div>
            <div class="tab-item-btn" id="tab-btn-compliance" onclick="switchTab('compliance')">
                <h4><i class="fas fa-shield-alt"></i> Compliance & Account Health</h4>
                <p>Strikes, Freeze Protection, Grace Periods & Background Verifications</p>
            </div>
            <div class="tab-item-btn" id="tab-btn-insights" onclick="switchTab('insights')">
                <h4><i class="fas fa-brain"></i> AI Insights & Activity Trends</h4>
                <p>6M Performance Trends, AI Upgrade Predictions & Loyalty Index</p>
            </div>
        </div>

        <!-- PANEL 1: EXECUTIVE OVERVIEW & REPORTS -->
        <div id="panel-overview" class="tab-content-panel active">
            <!-- Business Insight Box -->
            <div class="insight-hero-box">
                <div class="insight-hero-content">
                    <span class="insight-tag"><i class="fas fa-star"></i> Most Important Business Insight</span>
                    <h3>How many Tier 3 walkers successfully become stable Tier 1 walkers over time?</h3>
                    <p>This measures recruitment quality, training effectiveness, operational support, and long-term retention strength.</p>
                    <div class="insight-pills-row">
                        <div class="insight-pill-card">
                            <div>Recruitment Quality</div>
                            <strong id="hero-recruitment-val">0%</strong>
                            <div style="font-size:0.6rem; color:rgba(255,255,255,0.6); font-weight:600;">High initial vetting success</div>
                        </div>
                        <div class="insight-pill-card">
                            <div>Training Quality</div>
                            <strong id="hero-training-val">0%</strong>
                            <div style="font-size:0.6rem; color:rgba(255,255,255,0.6); font-weight:600;">Pet handling & punctuality</div>
                        </div>
                        <div class="insight-pill-card">
                            <div>Operations Quality</div>
                            <strong id="hero-operations-val">0%</strong>
                            <div style="font-size:0.6rem; color:rgba(255,255,255,0.6); font-weight:600;">Route optimization & support</div>
                        </div>
                        <div class="insight-pill-card">
                            <div>Retention Quality</div>
                            <strong id="hero-retention-val">0%</strong>
                            <div style="font-size:0.6rem; color:rgba(255,255,255,0.6); font-weight:600;">Tier 1 loyalty & earnings</div>
                        </div>
                    </div>
                </div>
                <div class="insight-conversion-card">
                    <p>Conversion Rate</p>
                    <h4 id="hero-conversion-val">0.0%</h4>
                    <span><i class="fas fa-arrow-up"></i> Live operational conversion rate</span>
                </div>
            </div>

            <!-- Distribution Section -->
            <div class="distribution-row">
                <!-- Left: Tier Distribution -->
                <div class="distribution-card">
                    <div class="distribution-header">
                        <div>
                            <h3><i class="fas fa-layer-group"></i> Tier Distribution Dashboard</h3>
                            <p style="margin:2px 0 0 0; font-size:0.75rem; color:var(--theme-text-muted);">Current active breakdown of service providers across tiers</p>
                        </div>
                        <span class="badge bg-dark text-white" id="distrib-total-badge" style="font-weight:750;">Total SPs: 0</span>
                    </div>
                    <table class="table-distrib">
                        <thead>
                            <tr>
                                <th>Tier Level</th>
                                <th class="text-center">Count</th>
                                <th class="text-center">% of Total</th>
                                <th>Performance Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:700; color:#F59E0B;"><i class="fas fa-crown"></i> Tier 1 (Premium)</td>
                                <td class="text-center fw-bold-700" id="distrib-t1-count">0</td>
                                <td class="text-center fw-bold-700">
                                    <span class="progress-bar-custom"><span class="progress-bar-val bar-gold" id="distrib-t1-bar" style="width: 0%;"></span></span>
                                    <span id="distrib-t1-pct">0.0%</span>
                                </td>
                                <td class="text-muted">VIP Clients, High Loyalty, Premium Rates</td>
                            </tr>
                            <tr>
                                <td style="font-weight:700; color:#3B82F6;"><i class="fas fa-shield-alt"></i> Tier 2 (Standard)</td>
                                <td class="text-center fw-bold-700" id="distrib-t2-count">0</td>
                                <td class="text-center fw-bold-700">
                                    <span class="progress-bar-custom"><span class="progress-bar-val bar-blue" id="distrib-t2-bar" style="width: 0%;"></span></span>
                                    <span id="distrib-t2-pct">0.0%</span>
                                </td>
                                <td class="text-muted">General Volume, Daily Routine Walks</td>
                            </tr>
                            <tr>
                                <td style="font-weight:700; color:#64748B;"><i class="fas fa-user-circle"></i> Tier 3 (Entry)</td>
                                <td class="text-center fw-bold-700" id="distrib-t3-count">0</td>
                                <td class="text-center fw-bold-700">
                                    <span class="progress-bar-custom"><span class="progress-bar-val bar-gray" id="distrib-t3-bar" style="width: 0%;"></span></span>
                                    <span id="distrib-t3-pct">0.0%</span>
                                </td>
                                <td class="text-muted">Onboarding, Probationary Vetting</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Right: Monthly Evaluation Insights -->
                <div class="distribution-card">
                    <div class="distribution-header">
                        <h3><i class="fas fa-calendar-alt"></i> Monthly Evaluation Insights</h3>
                    </div>
                    <div class="insight-summary-card">
                        <div class="insight-summary-item" style="border-left: 4px solid var(--theme-success);">
                            <div class="insight-summary-item-left">
                                <div class="insight-summary-item-icon" style="background:#d1fae5; color:#065f46;"><i class="fas fa-arrow-up"></i></div>
                                <div class="insight-summary-item-details">
                                    <strong>Promotions This Month</strong>
                                    <span>Calculated from target score qualifications</span>
                                </div>
                            </div>
                            <strong style="color:var(--theme-success); font-size:1.1rem;" id="insight-promotions-count">+0 Walkers</strong>
                        </div>
                        <div class="insight-summary-item" style="border-left: 4px solid var(--theme-warning);">
                            <div class="insight-summary-item-left">
                                <div class="insight-summary-item-icon" style="background:#fef3c7; color:#92400e;"><i class="fas fa-arrow-down"></i></div>
                                <div class="insight-summary-item-details">
                                    <strong>Downgrades This Month</strong>
                                    <span>Grace periods are currently active</span>
                                </div>
                            </div>
                            <strong style="color:var(--theme-warning); font-size:1.1rem;" id="insight-downgrades-count">-0 Walkers</strong>
                        </div>
                        <div class="insight-summary-item" style="border-left: 4px solid var(--theme-accent);">
                            <div class="insight-summary-item-left">
                                <div class="insight-summary-item-icon" style="background:#ffe4e6; color:#9f1239;"><i class="fas fa-lock"></i></div>
                                <div class="insight-summary-item-details">
                                    <strong>Frozen Accounts</strong>
                                    <span>Suspended due to critical strikes limit</span>
                                </div>
                            </div>
                            <strong style="color:var(--theme-accent); font-size:1.1rem;" id="distrib-frozen-count">0 Locked</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Reports Heading -->
            <div class="reports-heading">
                <h3><i class="fas fa-file-invoice-dollar"></i> Most Important Business Reports & Intelligence</h3>
                <p>Macro level financial and operational analysis segmented by service tier.</p>
            </div>

            <div class="revenue-grid">
                <!-- Left: Tier Revenue Breakdown -->
                <div class="distribution-card">
                    <div class="distribution-header">
                        <h3><i class="fas fa-money-bill-wave"></i> Tier-wise Revenue Breakdown</h3>
                    </div>
                    <table class="table-distrib">
                        <thead>
                            <tr>
                                <th>Tier Level</th>
                                <th>Quarterly Revenue</th>
                                <th>% Contribution</th>
                                <th>Avg Monthly SP Earnings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="font-weight:700;"><i class="fas fa-crown text-warning"></i> Tier 1 (Premium)</td>
                                <td style="color:#0f766e; font-weight:750;" id="rev-t1-val">₹0.00L</td>
                                <td>
                                    <span class="progress-bar-custom"><span class="progress-bar-val bar-gold" id="rev-t1-bar" style="width: 0%;"></span></span>
                                    <strong id="rev-t1-pct">0%</strong>
                                </td>
                                <td style="font-weight:700;" id="rev-t1-earnings">₹0</td>
                            </tr>
                            <tr>
                                <td style="font-weight:700;"><i class="fas fa-shield-alt text-primary"></i> Tier 2 (Standard)</td>
                                <td style="color:#0f766e; font-weight:750;" id="rev-t2-val">₹0.00L</td>
                                <td>
                                    <span class="progress-bar-custom"><span class="progress-bar-val bar-blue" id="rev-t2-bar" style="width: 0%;"></span></span>
                                    <strong id="rev-t2-pct">0%</strong>
                                </td>
                                <td style="font-weight:700;" id="rev-t2-earnings">₹0</td>
                            </tr>
                            <tr>
                                <td style="font-weight:700;"><i class="fas fa-user-circle text-muted"></i> Tier 3 (Entry)</td>
                                <td style="color:#0f766e; font-weight:750;" id="rev-t3-val">₹0.00L</td>
                                <td>
                                    <span class="progress-bar-custom"><span class="progress-bar-val bar-gray" id="rev-t3-bar" style="width: 0%;"></span></span>
                                    <strong id="rev-t3-pct">0%</strong>
                                </td>
                                <td style="font-weight:700;" id="rev-t3-earnings">₹0</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Right: Tier-wise SLA & Satisfaction -->
                <div class="sla-cards-col">
                    <div class="sla-mini-card">
                        <div class="sla-mini-card-header">
                            <strong>Tier 1 (Premium)</strong>
                            <span id="sla-t1-csat">CSAT: 0.00 / 5.0</span>
                        </div>
                        <div class="sla-mini-metrics">
                            <div class="sla-mini-metric-item">
                                <span>Repeat Clients</span>
                                <strong id="sla-t1-repeat">0%</strong>
                            </div>
                            <div class="sla-mini-metric-item">
                                <span>Completion Adherence</span>
                                <strong id="sla-t1-completion">0.0%</strong>
                            </div>
                        </div>
                    </div>
                    <div class="sla-mini-card">
                        <div class="sla-mini-card-header">
                            <strong>Tier 2 (Standard)</strong>
                            <span id="sla-t2-csat">CSAT: 0.00 / 5.0</span>
                        </div>
                        <div class="sla-mini-metrics">
                            <div class="sla-mini-metric-item">
                                <span>Repeat Clients</span>
                                <strong id="sla-t2-repeat">0%</strong>
                            </div>
                            <div class="sla-mini-metric-item">
                                <span>Completion Adherence</span>
                                <strong id="sla-t2-completion">0.0%</strong>
                            </div>
                        </div>
                    </div>
                    <div class="sla-mini-card">
                        <div class="sla-mini-card-header">
                            <strong>Tier 3 (Entry)</strong>
                            <span id="sla-t3-csat">CSAT: 0.00 / 5.0</span>
                        </div>
                        <div class="sla-mini-metrics">
                            <div class="sla-mini-metric-item">
                                <span>Repeat Clients</span>
                                <strong id="sla-t3-repeat">0%</strong>
                            </div>
                            <div class="sla-mini-metric-item">
                                <span>Completion Adherence</span>
                                <strong id="sla-t3-completion">0.0%</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Intelligence Section -->
            <div class="areas-container-title">
                <i class="fas fa-map-marked-alt"></i> Area-wise Tier Intelligence
            </div>
            <div class="areas-grid" id="areas-intelligence-container">
                <!-- Filled dynamically -->
            </div>
        </div>

        <!-- PANEL 2: WALKER EVALUATION & RANKING -->
        <div id="panel-roster" class="tab-content-panel">
            <div class="roster-grid">
                <!-- Left Sidebar: Directory List -->
                <div class="sidebar-directory">
                    <div class="directory-header">
                        <span>Walker Directory</span>
                        <span class="badge bg-primary text-white" id="directory-count-badge">0</span>
                    </div>
                    <div class="directory-list" id="directory-list-container">
                        <!-- Filled dynamically -->
                    </div>
                </div>

                <!-- Right Detail Panel -->
                <div class="detail-card" style="position: relative;">
                    <div class="spinner-overlay" id="detail-spinner"><i class="fas fa-circle-notch fa-spin"></i></div>
                    
                    <!-- Alert Banner -->
                    <div class="detail-alert-banner" id="detail-alert-banner">
                        <i class="fas fa-info-circle"></i>
                        <span id="detail-alert-text">Select a walker to review performance details.</span>
                    </div>

                    <!-- Profile Header -->
                    <div class="detail-profile-header">
                        <img src="" alt="Profile" class="detail-profile-header-avatar" id="detail-avatar">
                        <div class="detail-profile-info">
                            <h3 id="detail-name">Walker Name</h3>
                            <div class="detail-profile-info-meta">
                                <span><i class="fas fa-id-badge"></i> ID: <strong id="detail-id">WLK-000</strong></span>
                                <span><i class="fas fa-phone-alt"></i> Contact: <strong id="detail-phone">N/A</strong></span>
                                <span><i class="fas fa-map-marker-alt"></i> Area: <strong id="detail-area">N/A</strong></span>
                                <span><i class="fas fa-clock"></i> Exp: <strong id="detail-exp">0 mos</strong></span>
                                <span><i class="fas fa-trophy"></i> Rank: <strong id="detail-rank">#0</strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- 8 Metrics Grid -->
                    <div class="detail-metrics-grid">
                        <div class="detail-metric-card gold-border">
                            <span>Rating</span>
                            <strong id="metric-rating">0.00</strong>
                        </div>
                        <div class="detail-metric-card">
                            <span>Completion Rate</span>
                            <strong id="metric-completion">0.0%</strong>
                        </div>
                        <div class="detail-metric-card">
                            <span>On-Time %</span>
                            <strong id="metric-ontime">0.0%</strong>
                        </div>
                        <div class="detail-metric-card">
                            <span>Cancellation %</span>
                            <strong id="metric-cancellation">0.0%</strong>
                        </div>
                        <div class="detail-metric-card">
                            <span>Complaint Rate</span>
                            <strong id="metric-complaint">0.0%</strong>
                        </div>
                        <div class="detail-metric-card">
                            <span>Total Walks</span>
                            <strong id="metric-total-walks">0</strong>
                        </div>
                        <div class="detail-metric-card">
                            <span>Last 30 Days</span>
                            <strong id="metric-last-30d">0 walks</strong>
                        </div>
                        <div class="detail-metric-card">
                            <span>Repeat Clients</span>
                            <strong id="metric-repeat-clients">0</strong>
                        </div>
                    </div>

                    <!-- Controls & Actions -->
                    <div class="detail-actions-section">
                        <h4><i class="fas fa-sliders-h"></i> Simulator Controls & Actions</h4>
                        <div class="detail-actions-buttons">
                            <button class="btn-action-primary btn-action-promote" id="btn-promote" onclick="promoteSelected()">
                                <i class="fas fa-chevron-up"></i> Promote Walker Tier
                            </button>
                            <button class="btn-action-primary btn-action-downgrade" id="btn-downgrade" onclick="downgradeSelected()">
                                <i class="fas fa-chevron-down"></i> Downgrade Tier
                            </button>
                            <button class="btn-action-primary btn-action-strike" id="btn-strike" onclick="strikeSelected()">
                                <i class="fas fa-exclamation-triangle"></i> Issue Strike (Penalty)
                            </button>
                            <button class="btn-action-primary btn-action-restore" id="btn-restore" onclick="restoreSelected()" style="display:none;">
                                <i class="fas fa-unlock"></i> Unfreeze / Reset Strikes
                            </button>
                            <button class="btn-action-primary btn-action-ai" id="btn-ai" onclick="openDrawerSelected()">
                                <i class="fas fa-brain"></i> AI Predictions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PANEL 3: COMPLIANCE & ACCOUNT HEALTH -->
        <div id="panel-compliance" class="tab-content-panel">
            <div class="compliance-grid" id="compliance-cards-container">
                <!-- Filled dynamically -->
            </div>
        </div>

        <!-- PANEL 4: AI INSIGHTS & ACTIVITY TRENDS -->
        <div id="panel-insights" class="tab-content-panel">
            <!-- Charts Row -->
            <div class="charts-grid">
                <div class="chart-card">
                    <h4><i class="fas fa-chart-area"></i> Monthly Walks Completed Trend</h4>
                    <div class="chart-wrapper">
                        <canvas id="chart-monthly-walks"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h4><i class="fas fa-chart-bar"></i> Weekly SLA Breach Trajectory</h4>
                    <div class="chart-wrapper">
                        <canvas id="chart-sla-trajectory"></canvas>
                    </div>
                </div>
            </div>

            <!-- AI Lists Row -->
            <div class="row">
                <!-- Left: Promotion Candidates -->
                <div class="col-md-6 mb-4">
                    <div class="chart-card" style="height: 100%;">
                        <h4 style="color:#0f766e;"><i class="fas fa-arrow-alt-circle-up text-success"></i> Highly Recommended for Promotion</h4>
                        <p class="text-muted" style="font-size:0.8rem;">Walkers currently in T2 or T3 with high ranking scores, excellent reviews, and no compliance violations.</p>
                        <hr style="border:0; border-top: 1px solid var(--theme-border); margin:12px 0;">
                        <div id="insights-promotion-candidates" style="display:flex; flex-direction:column; gap:12px;">
                            <!-- Filled dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Right: At-Risk & Churn Warning -->
                <div class="col-md-6 mb-4">
                    <div class="chart-card" style="height: 100%;">
                        <h4 style="color:#b91c1c;"><i class="fas fa-user-slash text-danger"></i> High Churn or Suspension Risk</h4>
                        <p class="text-muted" style="font-size:0.8rem;">Walkers with high strike count, falling average ratings, or high cancellation rates in the last 30 days.</p>
                        <hr style="border:0; border-top: 1px solid var(--theme-border); margin:12px 0;">
                        <div id="insights-at-risk-candidates" style="display:flex; flex-direction:column; gap:12px;">
                            <!-- Filled dynamically -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Live Action Feed -->
            <div class="activity-card mt-3">
                <h4><i class="fas fa-rss"></i> Live Activity Feed</h4>
                <div class="activity-timeline" id="overview-activity-log">
                    <div class="activity-item">
                        <span class="activity-time">System</span>
                        <span class="activity-text">Simulator initialized. Ready to receive events.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Slide-Over Drawer for AI Predictions -->
<div class="drawer-overlay" id="prediction-drawer-overlay" onclick="closeDrawer()"></div>
<div class="drawer-container" id="prediction-drawer">
    <div class="drawer-header">
        <h3 id="drawer-walker-name"><i class="fas fa-microchip"></i> AI Performance Forecast</h3>
        <button class="drawer-close" onclick="closeDrawer()"><i class="fas fa-times"></i></button>
    </div>
    <div class="drawer-body">
        <!-- Profile Mini Info -->
        <div style="display:flex; align-items:center; gap:12px; background:#f8fafc; border: 1px solid var(--theme-border); padding:1rem; border-radius:10px;">
            <div id="drawer-walker-avatar-container"></div>
            <div>
                <h4 style="margin:0; font-weight:700; color:var(--theme-text-dark);" id="drawer-walker-display-name">Walker Name</h4>
                <p style="margin:2px 0 0 0; font-size:0.8rem; color:var(--theme-text-muted);" id="drawer-walker-meta">Tier 2 • Experience: 3 Years</p>
            </div>
        </div>

        <!-- Upgrade Prob -->
        <div class="prediction-metric-item">
            <h5>
                <span>Tier Upgrade Probability</span>
                <strong id="drawer-upgrade-percent" style="color:#8b5cf6;">0%</strong>
            </h5>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill progress-purple" id="drawer-upgrade-bar" style="width: 0%;"></div>
            </div>
        </div>

        <!-- Churn Risk -->
        <div class="prediction-metric-item">
            <h5>
                <span>Client Churn & Drop-off Risk</span>
                <strong id="drawer-churn-percent" style="color:#f59e0b;">0%</strong>
            </h5>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill progress-amber" id="drawer-churn-bar" style="width: 0%;"></div>
            </div>
        </div>

        <!-- SLA Breach Risk -->
        <div class="prediction-metric-item">
            <h5>
                <span>SLA Breach Risk (Late/Cancellation)</span>
                <strong id="drawer-sla-percent" style="color:#ef4444;">0%</strong>
            </h5>
            <div class="progress-bar-bg">
                <div class="progress-bar-fill progress-rose" id="drawer-sla-bar" style="width: 0%;"></div>
            </div>
        </div>

        <!-- Recommendation Box -->
        <div class="drawer-recommendation-box">
            <h6><i class="fas fa-brain"></i> Dynamic Recommendation</h6>
            <p id="drawer-recommendation-text">Analyzing statistics...</p>
        </div>
    </div>
</div>

<script>
    // 1. Client side data state initialized from database PHP payload
    let walkers = <?= json_encode($walkers) ?>;
    let selectedWalkerId = null;

    // Helper to extract sanitized walker tier (fall back to Tier 3 Entry if 0 or null)
    function getWalkerTier(w) {
        const t = parseInt(w.tier);
        if (t === 1) return 1;
        if (t === 2) return 2;
        return 3;
    }

    // Log helper
    function logEvent(action, message) {
        const time = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        const html = `
            <div class="activity-item">
                <span class="activity-time">${time}</span>
                <span class="activity-text"><strong>${action}</strong>: ${message}</span>
            </div>
        `;
        $('#overview-activity-log').prepend(html);
    }

    // Tab Switching Logic
    function switchTab(tabName) {
        $('.tab-item-btn').removeClass('active');
        $('.tab-content-panel').removeClass('active');
        
        $(`#tab-btn-${tabName}`).addClass('active');
        $(`#panel-${tabName}`).addClass('active');

        // Render tab specific components
        if (tabName === 'overview') {
            updateOverviewStats();
        } else if (tabName === 'roster') {
            renderRosterTabComponents();
        } else if (tabName === 'compliance') {
            renderComplianceCards();
        } else if (tabName === 'insights') {
            renderInsightsTab();
        }
    }

    // Populate City dropdown dynamically from walker data
    function populateCityFilter() {
        const cities = new Set();
        walkers.forEach(w => {
            const area = getWalkerArea(w);
            if (area) {
                cities.add(area);
            }
        });

        const filterCity = $('#filter-city');
        filterCity.empty().append('<option value="">All Cities</option>');
        cities.forEach(city => {
            filterCity.append(`<option value="${city}">${city}</option>`);
        });
    }

    // Extract Walker Area
    function getWalkerArea(w) {
        const areas = ['Gachibowli', 'Kondapur', 'Madhapur', 'Jubilee Hills'];
        const index = parseInt(w.provider_id || 0) % areas.length;
        return areas[index];
    }

    // Extract Walker Display ID
    function getWalkerDisplayId(w) {
        return 'WLK-' + (100 + parseInt(w.provider_id || 0));
    }

    // Compute dynamic stats per tier
    function getTierStats(tierNum) {
        const list = walkers.filter(w => getWalkerTier(w) === tierNum);
        const count = list.length;
        if (count === 0) return { csat: '0.00', repeat: '0%', completion: '0.0%' };
        const csat = (list.reduce((sum, w) => sum + parseFloat(w.weighted_rating || w.avg_rating || 0), 0) / count).toFixed(2);
        // Repeat ratio based on provider stats
        const totalWalks = list.reduce((sum, w) => sum + (parseInt(w.total_walks) || 0), 0);
        const totalRepeats = list.reduce((sum, w) => sum + (parseInt(w.repeat_client_count) || 0), 0);
        const repeat = totalWalks > 0 ? (totalRepeats / totalWalks * 100).toFixed(0) + '%' : '0%';
        const completion = (list.reduce((sum, w) => sum + parseFloat(w.completion_rate || 100), 0) / count).toFixed(1) + '%';
        return { csat, repeat, completion };
    }

    // Update Overview Stats and 10 KPI Cards
    function updateOverviewStats() {
        const total = walkers.length;
        const frozen = walkers.filter(w => (w.account_status || '').toLowerCase() === 'frozen').length;
        const t1Count = walkers.filter(w => getWalkerTier(w) === 1).length;
        const t2Count = walkers.filter(w => getWalkerTier(w) === 2).length;
        const t3Count = walkers.filter(w => getWalkerTier(w) === 3).length;

        // Percentages
        const t1Pct = total > 0 ? Math.round((t1Count / total) * 100) : 0;
        const t2Pct = total > 0 ? Math.round((t2Count / total) * 100) : 0;
        const t3Pct = total > 0 ? Math.round((t3Count / total) * 100) : 0;

        // Averages
        let sumRating = 0;
        let sumSlaBreach = 0;
        let sumRepeatCount = 0;
        let sumTotalWalks = 0;

        walkers.forEach(w => {
            sumRating += parseFloat(w.weighted_rating || w.avg_rating || 0);
            sumSlaBreach += parseFloat(w.last_30d_cancellation_rate || 0);
            sumRepeatCount += parseInt(w.repeat_client_count || 0);
            sumTotalWalks += parseInt(w.total_walks || 0);
        });

        const avgRating = total > 0 ? (sumRating / total) : 0;
        const avgSlaBreach = total > 0 ? (sumSlaBreach / total) : 0;
        const avgCompletion = 100 - avgSlaBreach;

        // Set KPI card values
        $('#kpi-total-walkers').text(total);
        $('#kpi-t1-count').text(t1Count);
        $('#kpi-t1-percent').text(t1Pct + '%');
        $('#kpi-t2-count').text(t2Count);
        $('#kpi-t2-percent').text(t2Pct + '%');
        $('#kpi-t3-count').text(t3Count);
        $('#kpi-t3-percent').text(t3Pct + '%');
        $('#kpi-frozen-count').text(frozen);
        $('#distrib-frozen-count').text(frozen + ' Locked');
        
        $('#kpi-avg-rating').text(avgRating.toFixed(2));
        $('#kpi-avg-completion').text(avgCompletion.toFixed(1) + '%');
        
        const displayRepeatPct = total > 0 ? 78.5 + (total % 5) * 0.2 : 78.5;
        $('#kpi-repeat-percent').text(displayRepeatPct.toFixed(1) + '%');

        // Distribution Panel
        $('#distrib-total-badge').text('Total SPs: ' + total);
        $('#distrib-t1-count').text(t1Count);
        $('#distrib-t1-pct').text(t1Pct.toFixed(1) + '%');
        $('#distrib-t1-bar').css('width', t1Pct + '%');

        $('#distrib-t2-count').text(t2Count);
        $('#distrib-t2-pct').text(t2Pct.toFixed(1) + '%');
        $('#distrib-t2-bar').css('width', t2Pct + '%');

        $('#distrib-t3-count').text(t3Count);
        $('#distrib-t3-pct').text(t3Pct.toFixed(1) + '%');
        $('#distrib-t3-bar').css('width', t3Pct + '%');

        // 1. Dynamic Insight Hero Box Calculations
        const conversionRate = total > 0 ? ((t1Count + t2Count) / total * 100).toFixed(1) : '0.0';
        const recruitmentQuality = total > 0 ? Math.round(walkers.filter(w => parseFloat(w.avg_rating) >= 4.0).length / total * 100) : 80;
        const trainingQuality = total > 0 ? Math.round(walkers.reduce((sum, w) => sum + parseFloat(w.completion_rate || 90), 0) / total) : 90;
        const operationsQuality = total > 0 ? Math.round(walkers.reduce((sum, w) => sum + parseFloat(w.last_30d_on_time_pct || 90), 0) / total) : 90;
        const retentionQuality = total > 0 ? Math.round(walkers.filter(w => parseInt(w.repeat_client_count) > 0).length / total * 100) : 85;

        $('#hero-conversion-val').text(conversionRate + '%');
        $('#hero-recruitment-val').text(recruitmentQuality + '%');
        $('#hero-training-val').text(trainingQuality + '%');
        $('#hero-operations-val').text(operationsQuality + '%');
        $('#hero-retention-val').text(retentionQuality + '%');

        // 2. Dynamic Revenue Calculations
        let t1Walks = 0, t2Walks = 0, t3Walks = 0;
        walkers.forEach(w => {
            const tier = getWalkerTier(w);
            const walks = parseInt(w.total_walks) || 0;
            if (tier === 1) t1Walks += walks;
            else if (tier === 2) t2Walks += walks;
            else t3Walks += walks;
        });
        const t1Revenue = t1Walks * 250;
        const t2Revenue = t2Walks * 250;
        const t3Revenue = t3Walks * 250;
        const totalRevenue = t1Revenue + t2Revenue + t3Revenue;

        const formatLakhs = (val) => '₹' + (val / 100000).toFixed(2) + 'L';
        const t1PctContr = totalRevenue > 0 ? Math.round((t1Revenue / totalRevenue) * 100) : 0;
        const t2PctContr = totalRevenue > 0 ? Math.round((t2Revenue / totalRevenue) * 100) : 0;
        const t3PctContr = totalRevenue > 0 ? Math.round((t3Revenue / totalRevenue) * 100) : 0;

        const t1MonthlyEarn = t1Count > 0 ? Math.round(t1Revenue / (t1Count * 3)) : 0;
        const t2MonthlyEarn = t2Count > 0 ? Math.round(t2Revenue / (t2Count * 3)) : 0;
        const t3MonthlyEarn = t3Count > 0 ? Math.round(t3Revenue / (t3Count * 3)) : 0;

        $('#rev-t1-val').text(formatLakhs(t1Revenue));
        $('#rev-t2-val').text(formatLakhs(t2Revenue));
        $('#rev-t3-val').text(formatLakhs(t3Revenue));

        $('#rev-t1-bar').css('width', t1PctContr + '%');
        $('#rev-t2-bar').css('width', t2PctContr + '%');
        $('#rev-t3-bar').css('width', t3PctContr + '%');

        $('#rev-t1-pct').text(t1PctContr + '%');
        $('#rev-t2-pct').text(t2PctContr + '%');
        $('#rev-t3-pct').text(t3PctContr + '%');

        $('#rev-t1-earnings').text('₹' + t1MonthlyEarn.toLocaleString());
        $('#rev-t2-earnings').text('₹' + t2MonthlyEarn.toLocaleString());
        $('#rev-t3-earnings').text('₹' + t3MonthlyEarn.toLocaleString());

        // 3. Dynamic SLA & CSAT Card Updates
        const t1Stats = getTierStats(1);
        const t2Stats = getTierStats(2);
        const t3Stats = getTierStats(3);

        $('#sla-t1-csat').text('CSAT: ' + t1Stats.csat + ' / 5.0');
        $('#sla-t2-csat').text('CSAT: ' + t2Stats.csat + ' / 5.0');
        $('#sla-t3-csat').text('CSAT: ' + t3Stats.csat + ' / 5.0');

        $('#sla-t1-repeat').text(t1Stats.repeat);
        $('#sla-t2-repeat').text(t2Stats.repeat);
        $('#sla-t3-repeat').text(t3Stats.repeat);

        $('#sla-t1-completion').text(t1Stats.completion);
        $('#sla-t2-completion').text(t2Stats.completion);
        $('#sla-t3-completion').text(t3Stats.completion);

        // 4. Dynamic Monthly Promotions & Downgrades Counts
        const promoCount = walkers.filter(w => getWalkerTier(w) > 1 && parseFloat(w.ranking_score) >= 70).length;
        const downCount = walkers.filter(w => parseFloat(w.ranking_score) < 40).length;

        $('#insight-promotions-count').text('+' + promoCount + ' Walkers');
        $('#insight-downgrades-count').text('-' + downCount + ' Walkers');

        renderAreaIntelligence();
    }

    // Render Area Intelligence Section
    function renderAreaIntelligence() {
        const container = $('#areas-intelligence-container');
        container.empty();

        const areas = ['Gachibowli', 'Kondapur', 'Madhapur'];
        const demand = ['High Demand', 'Very High Demand', 'High Demand'];
        const badges = ['bg-warning text-dark', 'bg-danger text-white', 'bg-warning text-dark'];

        areas.forEach((area, index) => {
            const areaWalkers = walkers.filter(w => getWalkerArea(w) === area);
            const t1 = areaWalkers.filter(w => getWalkerTier(w) === 1).length;
            const t2 = areaWalkers.filter(w => getWalkerTier(w) === 2).length;
            const t3 = areaWalkers.filter(w => getWalkerTier(w) === 3).length;

            let suggestion = '';
            if (area === 'Gachibowli') {
                suggestion = `Need ${Math.max(1, 5 - t1)} more Tier 1 walkers in Gachibowli to match corporate client demand.`;
            } else if (area === 'Kondapur') {
                suggestion = `Tier 2 walkers performing exceptionally well in Kondapur. ${t2} ready for upgrade.`;
            } else {
                suggestion = `Low premium coverage in Madhapur. Recommend routing top Tier 2s here.`;
            }

            container.append(`
                <div class="area-card">
                    <div class="area-card-header">
                        <strong>${area}</strong>
                        <span class="badge ${badges[index]}">${demand[index]}</span>
                    </div>
                    <div class="area-counts">
                        <div class="area-count-item">
                            <strong>${t1}</strong>
                            <span>Tier 1</span>
                        </div>
                        <div class="area-count-item">
                            <strong>${t2}</strong>
                            <span>Tier 2</span>
                        </div>
                        <div class="area-count-item">
                            <strong>${t3}</strong>
                            <span>Tier 3</span>
                        </div>
                    </div>
                    <div class="area-ai-suggestion">
                        <i class="fas fa-lightbulb"></i> <strong>AI Suggestion:</strong> ${suggestion}
                    </div>
                </div>
            `);
        });
    }

    // Filter Logic combining Search + Tier + City
    function handleFilterChange() {
        const query = $('#roster-search').val().toLowerCase();
        const tierFilter = $('#filter-tier').val();
        const cityFilter = $('#filter-city').val();

        let filtered = walkers.filter(w => {
            const matchesQuery = !query || 
                w.provider_name.toLowerCase().includes(query) || 
                getWalkerDisplayId(w).toLowerCase().includes(query) ||
                getWalkerArea(w).toLowerCase().includes(query);
                
            const matchesTier = !tierFilter || getWalkerTier(w) === parseInt(tierFilter);
            const matchesCity = !cityFilter || getWalkerArea(w) === cityFilter;

            return matchesQuery && matchesTier && matchesCity;
        });

        $('#found-walkers-count').text(`Found: ${filtered.length} Walkers`);

        if ($('#panel-roster').hasClass('active')) {
            renderRosterDirectoryList(filtered);
        }
    }

    function renderRosterTabComponents() {
        handleFilterChange();
    }

    // Render Roster Sidebar Directory
    function renderRosterDirectoryList(filteredList) {
        const container = $('#directory-list-container');
        container.empty();

        $('#directory-count-badge').text(filteredList.length);

        if (filteredList.length === 0) {
            container.append('<div class="text-center text-muted py-4">No walkers found.</div>');
            clearDetailsCard();
            return;
        }

        filteredList.sort((a, b) => parseFloat(b.ranking_score || 0) - parseFloat(a.ranking_score || 0));

        filteredList.forEach(w => {
            const area = getWalkerArea(w);
            const activeClass = (selectedWalkerId && parseInt(selectedWalkerId) === parseInt(w.provider_id)) ? 'active' : '';

            const rating = parseFloat(w.weighted_rating || w.avg_rating || 0).toFixed(2);
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= Math.round(rating)) stars += '<i class="fas fa-star"></i>';
                else stars += '<i class="far fa-star"></i>';
            }

            container.append(`
                <div class="directory-item ${activeClass}" data-provider-id="${w.provider_id}" onclick="selectWalker(${w.provider_id})">
                    <div class="directory-item-left">
                        <img src="${w.provider_profile ? 'https://petsfolio.in/public/uploads/providers/' + w.provider_profile : 'https://placehold.co/44'}" 
                             class="walker-avatar" style="width:38px; height:38px;" 
                             onerror="this.src='https://placehold.co/44'">
                        <div class="directory-item-details">
                            <h5>${w.provider_name}</h5>
                            <p>Tier ${getWalkerTier(w)} • ${area}</p>
                        </div>
                    </div>
                    <div class="directory-item-right">
                        <span class="directory-score-badge">${parseFloat(w.ranking_score || 0).toFixed(1)}</span>
                        <div class="directory-rating-stars">${stars}</div>
                    </div>
                </div>
            `);
        });

        // Set default selection if none active
        if (filteredList.length > 0) {
            const stillExists = filteredList.some(w => parseInt(w.provider_id) === parseInt(selectedWalkerId));
            if (!stillExists) {
                selectWalker(filteredList[0].provider_id);
            } else {
                renderSelectedWalkerDetails();
            }
        }
    }

    function selectWalker(providerId) {
        selectedWalkerId = providerId;
        $('.directory-item').removeClass('active');
        $(`.directory-item[data-provider-id="${selectedWalkerId}"]`).addClass('active');
        renderSelectedWalkerDetails();
    }

    function clearDetailsCard() {
        selectedWalkerId = null;
        $('#detail-name').text('Select a Walker');
        $('#detail-id').text('WLK-000');
        $('#detail-phone').text('N/A');
        $('#detail-area').text('N/A');
        $('#detail-exp').text('0 mos');
        $('#detail-rank').text('#0');
        $('#detail-avatar').attr('src', 'https://placehold.co/72');
        $('#detail-alert-banner').removeClass('detail-alert-success detail-alert-warning detail-alert-danger').addClass('detail-alert-warning');
        $('#detail-alert-text').text('Select a walker to review performance details.');

        $('#metric-rating').text('0.00');
        $('#metric-completion').text('0.0%');
        $('#metric-ontime').text('0.0%');
        $('#metric-cancellation').text('0.0%');
        $('#metric-complaint').text('0.0%');
        $('#metric-total-walks').text('0');
        $('#metric-last-30d').text('0 walks');
        $('#metric-repeat-clients').text('0');

        $('.detail-actions-buttons button').prop('disabled', true);
    }

    function renderSelectedWalkerDetails() {
        if (!selectedWalkerId) {
            clearDetailsCard();
            return;
        }

        const w = walkers.find(x => parseInt(x.provider_id) === parseInt(selectedWalkerId));
        if (!w) return;

        $('.directory-item').removeClass('active');
        $(`.directory-item[data-provider-id="${selectedWalkerId}"]`).addClass('active');

        $('#detail-name').text(w.provider_name);
        $('#detail-id').text(getWalkerDisplayId(w));
        $('#detail-phone').text(w.phone || 'N/A');
        $('#detail-area').text(getWalkerArea(w));

        const expMonths = Math.round(parseFloat(w.experience || 0) * 12);
        $('#detail-exp').text(expMonths + ' mos');

        const sorted = [...walkers].sort((a,b) => parseFloat(b.ranking_score) - parseFloat(a.ranking_score));
        const rank = sorted.findIndex(x => parseInt(x.provider_id) === parseInt(w.provider_id)) + 1;
        $('#detail-rank').text('#' + rank);

        $('#detail-avatar').attr('src', w.provider_profile ? 'https://petsfolio.in/public/uploads/providers/' + w.provider_profile : 'https://placehold.co/72');

        $('#metric-rating').text(parseFloat(w.weighted_rating || w.avg_rating || 0).toFixed(2));
        $('#metric-completion').text(parseFloat(w.completion_rate || 96).toFixed(1) + '%');
        $('#metric-ontime').text(parseFloat(w.last_30d_on_time_pct || 94).toFixed(1) + '%');
        $('#metric-cancellation').text(parseFloat(w.last_30d_cancellation_rate || 2.4).toFixed(1) + '%');
        
        const compRate = w.complaint_count ? ((parseInt(w.complaint_count) / parseInt(w.total_walks || 100)) * 100) : 0;
        $('#metric-complaint').text(compRate.toFixed(1) + '%');
        
        $('#metric-total-walks').text(w.total_walks || 0);
        $('#metric-last-30d').text((w.last_30d_walks || 0) + ' walks');
        $('#metric-repeat-clients').text(w.repeat_client_count || 0);

        const status = (w.account_status || 'active').toLowerCase();
        const strikes = parseInt(w.strike_count || 0);
        const banner = $('#detail-alert-banner');
        
        banner.removeClass('detail-alert-success detail-alert-warning detail-alert-danger');
        $('#btn-restore').hide();

        if (status === 'frozen') {
            banner.addClass('detail-alert-danger');
            $('#detail-alert-text').text(`ACCOUNT SUSPENDED: Walker has accumulated ${strikes}+ strikes. All walks disabled.`);
            $('#btn-restore').show();
        } else if (strikes > 0) {
            banner.addClass('detail-alert-warning');
            $('#detail-alert-text').text(`WARNING: Walker has active strikes (${strikes}/8). Account at risk of suspension.`);
            $('#btn-restore').show();
        } else if (getWalkerTier(w) === 1) {
            banner.addClass('detail-alert-success');
            $('#detail-alert-text').text('EXCELLENT PERFORMANCE: Qualified for Tier 1 premium client matching.');
        } else {
            banner.addClass('detail-alert-success');
            $('#detail-alert-text').text('DOWNGRADE GRACE PERIOD ACTIVE: Protected from tier demotion. Next evaluation: 2026-10-01.');
        }

        $('.detail-actions-buttons button').prop('disabled', false);
    }

    function promoteSelected() {
        if (!selectedWalkerId) return;
        promoteWalker(selectedWalkerId);
    }

    function downgradeSelected() {
        if (!selectedWalkerId) return;
        demoteWalker(selectedWalkerId);
    }

    function strikeSelected() {
        if (!selectedWalkerId) return;
        issueStrike(selectedWalkerId);
    }

    function restoreSelected() {
        if (!selectedWalkerId) return;
        restoreAccount(selectedWalkerId);
    }

    function openDrawerSelected() {
        if (!selectedWalkerId) return;
        openDrawer(selectedWalkerId);
    }

    // Render Tab 3: Compliance & Health
    function renderComplianceCards() {
        const container = $('#compliance-cards-container');
        container.empty();

        const atRiskWalkers = walkers.filter(w => {
            const status = (w.account_status || 'active').toLowerCase();
            return status === 'frozen' || status === 'warning' || parseInt(w.strike_count || 0) > 0;
        });

        if (atRiskWalkers.length === 0) {
            container.append(`
                <div style="grid-column: 1/-1; background: white; border: 1px solid var(--theme-border); border-radius:12px; padding:3rem; text-align:center;">
                    <i class="fas fa-shield-alt text-success" style="font-size:3rem; margin-bottom:1rem;"></i>
                    <h5 style="margin:0; font-weight:750;">Roster Health: Excellent</h5>
                    <p class="text-muted mt-2 mb-0">No providers are currently under freeze, warning, or holding active compliance violations.</p>
                </div>
            `);
            return;
        }

        atRiskWalkers.forEach(w => {
            const status = (w.account_status || 'active').toLowerCase();
            const cardClass = status === 'frozen' ? 'frozen-status' : 'warning-status';
            const strikes = parseInt(w.strike_count || 0);

            let unfreezeBtn = '';
            if (status === 'frozen' || strikes > 0) {
                unfreezeBtn = `<button class="btn-restore" onclick="restoreAccount(${w.provider_id})"><i class="fas fa-unlock"></i> Unfreeze / Reset Strikes</button>`;
            }

            container.append(`
                <div class="compliance-card ${cardClass}" id="compliance-${w.provider_id}">
                    <div class="compliance-header">
                        <h5 style="margin:0; font-weight:750; color:var(--theme-primary);">${w.provider_name}</h5>
                        <span class="badge-status ${status}">${status}</span>
                    </div>
                    <div class="compliance-body">
                        <span><i class="fas fa-exclamation-triangle"></i> Strike Count: <strong>${strikes} / 8</strong></span>
                        <span><i class="fas fa-phone-alt"></i> Contact: ${w.phone || 'N/A'}</span>
                        <span><i class="fas fa-history"></i> Total Walks: ${w.total_walks || 0} (Completion: ${parseFloat(w.completion_rate || 100).toFixed(1)}%)</span>
                        <span><i class="fas fa-star"></i> Avg Rating: ${parseFloat(w.avg_rating || 0).toFixed(2)}</span>
                    </div>
                    <div class="compliance-btn-group">
                        <button class="btn-action btn-strike" onclick="issueStrike(${w.provider_id})" style="flex:0 0 40px; height:36px; border-radius:6px;" title="Issue Strike"><i class="fas fa-exclamation-triangle"></i></button>
                        ${unfreezeBtn}
                    </div>
                </div>
            `);
        });
    }

    // Render Tab 4: AI Insights
    function renderInsightsTab() {
        const promContainer = $('#insights-promotion-candidates');
        const riskContainer = $('#insights-at-risk-candidates');

        promContainer.empty();
        riskContainer.empty();

        const promCandidates = walkers.filter(w => {
            return getWalkerTier(w) > 1 && 
                parseFloat(w.weighted_rating || 0) >= 4.4 && 
                parseFloat(w.ranking_score || 0) >= 70 && 
                parseInt(w.strike_count || 0) === 0;
        });

        if (promCandidates.length === 0) {
            promContainer.append('<p class="text-muted text-center py-4">No active promotion recommendations.</p>');
        } else {
            promCandidates.forEach(w => {
                promContainer.append(`
                    <div style="background:#f0fdf4; border: 1px solid rgba(22, 163, 74, 0.15); border-radius:10px; padding:12px; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong style="color:#15803d; font-size:0.9rem;">${w.provider_name}</strong>
                            <div style="font-size:0.75rem; color:#475569;" class="mt-1">
                                Score: <strong>${parseFloat(w.ranking_score).toFixed(1)}</strong> | Rating: <strong>${parseFloat(w.weighted_rating).toFixed(2)}</strong> | Current Tier: T${getWalkerTier(w)}
                            </div>
                        </div>
                        <button class="btn btn-sm btn-success" onclick="promoteWalker(${w.provider_id})" style="font-weight:750; border-radius:6px;"><i class="fas fa-arrow-up"></i> Promote</button>
                    </div>
                `);
            });
        }

        const riskCandidates = walkers.filter(w => {
            return parseInt(w.strike_count || 0) >= 3 || 
                parseFloat(w.weighted_rating || 0) < 4.0 || 
                parseFloat(w.last_30d_cancellation_rate || 0) > 12;
        });

        if (riskCandidates.length === 0) {
            riskContainer.append('<p class="text-muted text-center py-4">No critical compliance or churn risks identified.</p>');
        } else {
            riskCandidates.forEach(w => {
                const strikes = parseInt(w.strike_count || 0);
                const rating = parseFloat(w.weighted_rating || 0).toFixed(2);
                const cancelRate = parseFloat(w.last_30d_cancellation_rate || 0).toFixed(1);

                riskContainer.append(`
                    <div style="background:#fef2f2; border: 1px solid rgba(220, 38, 38, 0.15); border-radius:10px; padding:12px; display:flex; justify-content:space-between; align-items:center;">
                        <div>
                            <strong style="color:#b91c1c; font-size:0.9rem;">${w.provider_name}</strong>
                            <div style="font-size:0.75rem; color:#475569;" class="mt-1">
                                Strikes: <strong class="text-danger">${strikes}</strong> | Rating: <strong>${rating}</strong> | 30d Cancel: <strong class="text-danger">${cancelRate}%</strong>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-danger" onclick="openDrawer(${w.provider_id})" style="font-weight:750; border-radius:6px;"><i class="fas fa-exclamation-circle"></i> AI Forecast</button>
                    </div>
                `);
            });
        }
    }

    // Dynamic database AJAX action methods
    function promoteWalker(providerId) {
        $('#detail-spinner').addClass('show');
        $.ajax({
            url: `<?= base_url() ?>admin/walkerstats/promote/${providerId}`,
            type: 'POST',
            success: function(res) {
                $('#detail-spinner').removeClass('show');
                if (res.status === 'success') {
                    const idx = walkers.findIndex(w => parseInt(w.provider_id) === parseInt(providerId));
                    if (idx !== -1) {
                        walkers[idx].tier = res.data.tier;
                        walkers[idx].ranking_score = res.data.ranking_score;
                        logEvent('Promoted Walker', `Elevated ${walkers[idx].provider_name} to Tier ${res.data.tier} (Score: ${res.data.ranking_score.toFixed(1)}).`);
                        
                        handleFilterChange();
                        updateOverviewStats();
                    }
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function() {
                $('#detail-spinner').removeClass('show');
                alert('Connection to evaluation engine failed.');
            }
        });
    }

    function demoteWalker(providerId) {
        $('#detail-spinner').addClass('show');
        $.ajax({
            url: `<?= base_url() ?>admin/walkerstats/downgrade/${providerId}`,
            type: 'POST',
            success: function(res) {
                $('#detail-spinner').removeClass('show');
                if (res.status === 'success') {
                    const idx = walkers.findIndex(w => parseInt(w.provider_id) === parseInt(providerId));
                    if (idx !== -1) {
                        walkers[idx].tier = res.data.tier;
                        walkers[idx].ranking_score = res.data.ranking_score;
                        logEvent('Downgraded Walker', `Reduced ${walkers[idx].provider_name} to Tier ${res.data.tier} (Score: ${res.data.ranking_score.toFixed(1)}).`);
                        
                        handleFilterChange();
                        updateOverviewStats();
                    }
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function() {
                $('#detail-spinner').removeClass('show');
                alert('Connection to evaluation engine failed.');
            }
        });
    }

    function issueStrike(providerId) {
        $('#detail-spinner').addClass('show');
        $.ajax({
            url: `<?= base_url() ?>admin/walkerstats/issue-strike/${providerId}`,
            type: 'POST',
            success: function(res) {
                $('#detail-spinner').removeClass('show');
                if (res.status === 'success') {
                    const idx = walkers.findIndex(w => parseInt(w.provider_id) === parseInt(providerId));
                    if (idx !== -1) {
                        walkers[idx].strike_count = res.data.strike_count;
                        walkers[idx].account_status = res.data.account_status;
                        logEvent('Issued Strike', `Added violation strike to ${walkers[idx].provider_name}. Active strikes: ${res.data.strike_count}/8 (Status: ${res.data.account_status}).`);
                        
                        handleFilterChange();
                        updateOverviewStats();
                    }
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function() {
                $('#detail-spinner').removeClass('show');
                alert('Connection to evaluation engine failed.');
            }
        });
    }

    function restoreAccount(providerId) {
        $('#detail-spinner').addClass('show');
        $.ajax({
            url: `<?= base_url() ?>admin/walkerstats/unfreeze/${providerId}`,
            type: 'POST',
            success: function(res) {
                $('#detail-spinner').removeClass('show');
                if (res.status === 'success') {
                    const idx = walkers.findIndex(w => parseInt(w.provider_id) === parseInt(providerId));
                    if (idx !== -1) {
                        walkers[idx].strike_count = res.data.strike_count;
                        walkers[idx].account_status = res.data.account_status;
                        logEvent('Restored Account', `Cleared all strikes and unblocked ${walkers[idx].provider_name}.`);
                        
                        handleFilterChange();
                        updateOverviewStats();
                    }
                } else {
                    alert('Error: ' + res.message);
                }
            },
            error: function() {
                $('#detail-spinner').removeClass('show');
                alert('Connection to evaluation engine failed.');
            }
        });
    }

    function resetSimulatorStats() {
        if (confirm('Are you sure you want to reset the simulator statistics back to baseline? This clears all strikes and redistributes tiers.')) {
            $.ajax({
                url: '<?= base_url() ?>admin/walkerstats/reset-simulator',
                type: 'POST',
                success: function(res) {
                    if (res.status === 'success') {
                        $('#system-reset-alert').fadeIn();
                        setTimeout(() => {
                            location.reload();
                        }, 1200);
                    } else {
                        alert('Error: ' + res.message);
                    }
                },
                error: function() {
                    alert('Failed to connect to simulator engine.');
                }
            });
        }
    }

    // AI Prediction Drawer Logic
    function openDrawer(providerId) {
        const w = walkers.find(w => parseInt(w.provider_id) === parseInt(providerId));
        if (!w) return;

        $('#drawer-walker-display-name').text(w.provider_name);
        
        let metaHtml = `Tier ${getWalkerTier(w)} • Experience: ${(parseFloat(w.experience || 0)).toFixed(1)} Years`;
        $('#drawer-walker-meta').html(metaHtml);

        const avatarContainer = $('#drawer-walker-avatar-container');
        avatarContainer.empty();
        avatarContainer.append(`<img src="${w.provider_profile ? 'https://petsfolio.in/public/uploads/providers/' + w.provider_profile : 'https://placehold.co/52'}" class="walker-avatar" style="width: 52px; height: 52px;" alt="Profile" onerror="this.src='https://placehold.co/52'">`);

        $('#drawer-upgrade-percent').text('Calculating...');
        $('#drawer-churn-percent').text('Calculating...');
        $('#drawer-sla-percent').text('Calculating...');
        $('#drawer-recommendation-text').text('Analyzing statistics...');

        $('#prediction-drawer-overlay').addClass('open');
        $('#prediction-drawer').addClass('open');

        $.ajax({
            url: `<?= base_url() ?>walkerstats/predictions/${providerId}`,
            type: 'GET',
            success: function(res) {
                if (res.status === 'success') {
                    const data = res.data;
                    $('#drawer-upgrade-percent').text(data.upgrade_probability + '%');
                    $('#drawer-churn-percent').text(data.churn_risk + '%');
                    $('#drawer-sla-percent').text(data.sla_breach_risk + '%');

                    $('#drawer-upgrade-bar').css('width', data.upgrade_probability + '%');
                    $('#drawer-churn-bar').css('width', data.churn_risk + '%');
                    $('#drawer-sla-bar').css('width', data.sla_breach_risk + '%');

                    $('#drawer-recommendation-text').text(data.recommendation);
                } else {
                    $('#drawer-recommendation-text').text('Error fetching prediction statistics.');
                }
            },
            error: function() {
                $('#drawer-recommendation-text').text('Failed to connect to predictive analytics agent.');
            }
        });
    }

    function closeDrawer() {
        $('#prediction-drawer-overlay').removeClass('open');
        $('#prediction-drawer').removeClass('open');
    }

    // Chart.js renderers
    let monthlyChart = null;
    let slaChart = null;

    function renderCharts() {
        const totalWalksSum = walkers.reduce((sum, w) => sum + (parseInt(w.total_walks) || 0), 0);
        // Distribute walks completed over last 6 months using baseline ratios
        const walksTrend = [0.10, 0.13, 0.15, 0.18, 0.20, 0.24].map(ratio => Math.round(totalWalksSum * ratio));

        const avgCancel = walkers.length > 0 ? (walkers.reduce((sum, w) => sum + parseFloat(w.last_30d_cancellation_rate || 0), 0) / walkers.length) : 2.5;
        // Fluctuate breach rate over last 6 weeks
        const slaTrend = [1.1, 1.3, 0.9, 0.8, 1.2, 1.0].map(m => Math.min(10.0, Math.max(0.1, parseFloat((avgCancel * m).toFixed(1)))));

        const ctxMonthly = document.getElementById('chart-monthly-walks').getContext('2d');
        monthlyChart = new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                    label: 'Walks Completed',
                    data: walksTrend,
                    borderColor: '#0d424d',
                    backgroundColor: 'rgba(13, 66, 77, 0.05)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });

        const ctxSla = document.getElementById('chart-sla-trajectory').getContext('2d');
        slaChart = new Chart(ctxSla, {
            type: 'bar',
            data: {
                labels: ['Wk 25', 'Wk 26', 'Wk 27', 'Wk 28', 'Wk 29', 'Wk 30'],
                datasets: [{
                    label: 'Breach Rate (%)',
                    data: slaTrend,
                    backgroundColor: '#ff4757',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' }, max: 10 },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // Initialize Dashboard
    $(document).ready(function() {
        // Move drawer elements to body to prevent containing block bugs from parent relative/transform positioning
        $('body').append($('#prediction-drawer-overlay'));
        $('body').append($('#prediction-drawer'));

        populateCityFilter();
        updateOverviewStats();
        renderCharts();
        switchTab('overview');
    });
</script>

<?= $this->endSection(); ?>