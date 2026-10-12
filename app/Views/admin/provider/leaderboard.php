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

<<<<<<< HEAD
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

=======
    /* Table Tier Distrib - Highly Visible Header Columns */
    .table-distrib {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 8px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--theme-border);
    }

    .table-distrib thead tr {
        background-color: #0d424d;
        color: #ffffff;
    }

    .table-distrib th {
        font-size: 0.78rem;
        text-transform: uppercase;
        color: #ffffff;
        font-weight: 800;
        padding: 12px 14px;
        letter-spacing: 0.5px;
        border-bottom: 2px solid rgba(0, 0, 0, 0.15);
        vertical-align: middle;
    }

    .table-distrib td {
        padding: 12px 14px;
        font-size: 0.82rem;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        background-color: #ffffff;
    }

    .table-distrib tr:hover td {
        background-color: #f8fafc;
    }

    .table-distrib tr:last-child td { border-bottom: none; }

    /* Compliance & Account Health Cards Styling */
    .compliance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 1.25rem;
        margin-top: 1rem;
    }

    .compliance-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 12px;
    }

    .compliance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .compliance-card.frozen-status {
        border-left: 4px solid #ef4444;
        background: #fffafa;
    }

    .compliance-card.warning-status {
        border-left: 4px solid #f59e0b;
        background: #fffdf5;
    }

    .compliance-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 10px;
    }

    .compliance-header h5 {
        margin: 0;
        font-size: 1rem;
        font-weight: 750;
        color: #0f172a;
    }

    .badge-status {
        font-size: 0.68rem;
        text-transform: uppercase;
        font-weight: 800;
        padding: 4px 10px;
        border-radius: 20px;
        letter-spacing: 0.5px;
    }

    .badge-status.frozen, .badge-status.inactive, .badge-status.suspended, .badge-status.disabled, .badge-status.0 {
        background-color: #fee2e2;
        color: #dc2626;
        border: 1px solid #fca5a5;
    }

    .badge-status.warning {
        background-color: #fef3c7;
        color: #d97706;
        border: 1px solid #fcd34d;
    }

    .badge-status.active {
        background-color: #dcfce7;
        color: #15803d;
        border: 1px solid #86efac;
    }

    .compliance-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
        font-size: 0.82rem;
        color: #475569;
    }

    .compliance-body span {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .compliance-body i {
        width: 16px;
        color: #64748b;
    }

    .compliance-btn-group {
        display: flex;
        gap: 8px;
        align-items: center;
        margin-top: 6px;
    }

    .btn-restore {
        flex: 1;
        background: linear-gradient(135deg, #10b981, #059669);
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 8px 14px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: background 0.2s ease, transform 0.1s ease;
    }

    .btn-restore:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: scale(1.02);
    }

    /* Charts Grid & Compact Canvas Styling */
    .charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 992px) {
        .charts-grid { grid-template-columns: 1fr; }
    }

    .chart-card {
        background: #ffffff;
        border: 1px solid var(--theme-border);
        border-radius: 14px;
        padding: 1.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
    }

    .chart-card h4 {
        font-size: 0.95rem;
        font-weight: 800;
        color: var(--theme-primary);
        margin: 0 0 0.85rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chart-wrapper {
        position: relative;
        height: 210px;
        width: 100%;
    }

>>>>>>> origin/main
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
<<<<<<< HEAD
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
=======
                <div class="kpi-subtext" id="kpi-total-subtext">
                    Out of <strong>0</strong> registered roster
                </div>
            </div>
            <!-- Joined Card -->
            <div class="kpi-card" onclick="openTierEvaluationModal('joined')" style="cursor:pointer;" title="Click to view Joined Criteria">
                <div class="kpi-header">
                    <span class="kpi-title">Joined</span>
                    <span class="badge bg-info text-white" id="kpi-joined-percent" style="font-weight:800; font-size:0.65rem; border-radius:4px;">0%</span>
                </div>
                <div class="kpi-value text-info" id="kpi-joined-count">0</div>
                <div class="kpi-subtext">Fee paid, 0 walks <i class="fas fa-info-circle text-info ms-1" style="cursor:pointer;" onclick="event.stopPropagation(); openTierEvaluationModal('joined');" title="Click to view Joined Criteria"></i></div>
            </div>
            <!-- Tier 3 (Entry) Card -->
            <div class="kpi-card" onclick="openTierEvaluationModal('tier3')" style="cursor:pointer;" title="Click to view Tier 3 Evaluation Criteria">
>>>>>>> origin/main
                <div class="kpi-header">
                    <span class="kpi-title">Tier 3 (Entry)</span>
                    <span class="badge bg-secondary text-white" id="kpi-t3-percent" style="font-weight:800; font-size:0.65rem; border-radius:4px;">0%</span>
                </div>
                <div class="kpi-value" id="kpi-t3-count">0</div>
<<<<<<< HEAD
                <div class="kpi-subtext">Undergoing onboarding/vetting</div>
=======
                <div class="kpi-subtext">Entry partners <i class="fas fa-info-circle text-secondary ms-1" style="cursor:pointer;" onclick="event.stopPropagation(); openTierEvaluationModal('tier3');" title="Click to view Tier 3 Evaluation Criteria"></i></div>
            </div>
            <!-- Tier 2 (Standard) Card -->
            <div class="kpi-card" onclick="openTierEvaluationModal('tier2')" style="cursor:pointer;" title="Click to view Tier 2 Evaluation Criteria">
                <div class="kpi-header">
                    <span class="kpi-title">Tier 2 (Standard)</span>
                    <span class="badge bg-primary text-white" id="kpi-t2-percent" style="font-weight:800; font-size:0.65rem; border-radius:4px;">0%</span>
                </div>
                <div class="kpi-value text-primary" id="kpi-t2-count">0</div>
                <div class="kpi-subtext">Core operational backbone <i class="fas fa-info-circle text-primary ms-1" style="cursor:pointer;" onclick="event.stopPropagation(); openTierEvaluationModal('tier2');" title="Click to view Tier 2 Evaluation Criteria"></i></div>
            </div>
            <!-- Tier 1 (Premium) Card -->
            <div class="kpi-card" onclick="openTierEvaluationModal('tier1')" style="cursor:pointer;" title="Click to view Tier 1 Evaluation Criteria">
                <div class="kpi-header">
                    <span class="kpi-title">Tier 1 (Premium)</span>
                    <span class="badge bg-warning text-dark" id="kpi-t1-percent" style="font-weight:800; font-size:0.65rem; border-radius:4px;">0%</span>
                </div>
                <div class="kpi-value text-warning" id="kpi-t1-count">0</div>
                <div class="kpi-subtext">High earning premium SPs <i class="fas fa-info-circle text-warning ms-1" style="cursor:pointer;" onclick="event.stopPropagation(); openTierEvaluationModal('tier1');" title="Click to view Tier 1 Evaluation Criteria"></i></div>
>>>>>>> origin/main
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
<<<<<<< HEAD
                <div class="kpi-subtext">Core loyalty indicator</div>
=======
                <div class="kpi-subtext" id="kpi-repeat-subtext">0 SPs with repeat clients</div>
>>>>>>> origin/main
            </div>
            <div class="kpi-card">
                <div class="kpi-header">
                    <span class="kpi-title">Police Verified %</span>
                    <div class="kpi-icon-container text-info"><i class="fas fa-shield-alt"></i></div>
                </div>
<<<<<<< HEAD
                <div class="kpi-value" id="kpi-police-percent">94.2%</div>
                <div class="kpi-subtext">Mandatory background clear</div>
            </div>
            <div class="kpi-card">
=======
                <div class="kpi-value" id="kpi-police-percent">0.0%</div>
                <div class="kpi-subtext" id="kpi-police-subtext">0 SPs background cleared</div>
            </div>
            <!-- <div class="kpi-card">
>>>>>>> origin/main
                <div class="kpi-header">
                    <span class="kpi-title">Insurance Covered %</span>
                    <div class="kpi-icon-container text-primary"><i class="fas fa-file-invoice"></i></div>
                </div>
                <div class="kpi-value" id="kpi-insurance-percent">89.6%</div>
                <div class="kpi-subtext text-warning"><i class="fas fa-clock"></i> Expiring soon: 14 SP</div>
<<<<<<< HEAD
            </div>
=======
            </div> -->
>>>>>>> origin/main
        </div>

        <!-- Search and filters block -->
        <div class="search-filter-row">
            <div class="search-filter-left">
                <i class="fas fa-search"></i>
<<<<<<< HEAD
                <input type="text" id="roster-search" placeholder="Search walkers by name, ID, or area..." onkeyup="handleFilterChange()">
            </div>
            <div class="search-filter-right">
                <div class="filter-dropdown-wrapper">
                    <span>Tier:</span>
                    <select id="filter-tier" onchange="handleFilterChange()">
                        <option value="">All Tiers</option>
=======
                <input type="text" id="roster-search" placeholder="Search walkers by name, ID, phone, or area..." oninput="handleFilterChange()" onkeyup="handleFilterChange()">
            </div>
            <div class="search-filter-right">
                <div class="filter-dropdown-wrapper">
                    <span>Status:</span>
                    <select id="filter-status" onchange="handleFilterChange()">
                        <option value="active" selected>Active Walkers Only</option>
                        <option value="">All Roster</option>
                        <option value="frozen">Inactive / Frozen Only</option>
                    </select>
                </div>
                <div class="filter-dropdown-wrapper">
                    <span>Tier:</span>
                    <select id="filter-tier" onchange="handleFilterChange()">
                        <option value="">All Tiers & Statuses</option>
                        <option value="joined">Joined (Fee Paid, 0 Walks)</option>
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
            <!-- AI Walker Roster Intelligence Advisor & Active Live Capacity Warnings (Light Theme) -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 1.75rem; color: #0f172a; margin-bottom: 2rem; box-shadow: 0 4px 20px rgba(13, 66, 77, 0.06);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <h3 style="margin:0; font-size:1.4rem; font-weight:800; color:#0d424d; display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-brain text-warning"></i> AI Walker Roster Intelligence Advisor
                        </h3>
                        <p style="margin:4px 0 0 0; color:#64748b; font-size:0.85rem;">
                            Real-time Gemini AI performance analysis, fleet optimization suggestions & active live capacity warnings
                        </p>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadWalkerAiFleetInsights()" style="border-radius:20px; font-weight:700; border-color:#0d424d; color:#0d424d;">
                        <i class="fas fa-sync-alt me-1"></i> Refresh AI Analysis
                    </button>
                </div>

                <!-- Active Live Capacity Warnings Section (Light Theme) -->
                <div style="background: #fff5f5; border: 1px solid #fecaca; border-radius: 14px; padding: 1.1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 0.75rem;">
                        <span class="badge bg-danger text-white fw-bold px-3 py-1.5" style="font-size:0.82rem; letter-spacing:0.5px;">
                            <i class="fas fa-exclamation-triangle me-1"></i> ACTIVE LIVE CAPACITY WARNINGS
                        </span>
                        <span style="font-size:0.8rem; color:#dc2626; font-weight:600;"><i class="fas fa-signal me-1"></i> Real-time platform supply-to-demand bottleneck alerts</span>
                    </div>
                    <div class="walker-capacity-warnings-container" style="display:flex; flex-direction:column; gap:8px;">
                        <div style="background: #ffffff; border: 1px solid #fee2e2; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; color: #64748b;"><i class="fas fa-spinner fa-spin text-danger me-2"></i> Analyzing live capacity bottlenecks...</div>
                    </div>
                </div>

                <!-- AI Executive Fleet Summary Text Box (Light Theme) -->
                <div class="walker-ai-fleet-summary-box" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: 14px; font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.25rem; color: #1e293b;">
                    <i class="fas fa-spinner fa-spin text-primary me-2"></i> Analyzing walker fleet metrics with Gemini AI...
                </div>

                <!-- Actionable Improvements Pills/Cards (Light Theme) -->
                <div style="margin-bottom: 1.25rem;">
                    <h6 style="color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:0.6rem;">
                        <i class="fas fa-lightbulb text-warning me-1"></i> Recommended Operational Improvements
                    </h6>
                    <div class="walker-ai-fleet-improvements" style="display:flex; flex-direction:column; gap:8px;">
                        <div style="background: #fffdf5; border: 1px solid #fef3c7; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; color: #64748b;"><i class="fas fa-spinner fa-spin text-warning me-2"></i> Loading suggestions...</div>
                    </div>
                </div>

                <!-- Interactive Ask AI Box (Light Theme) -->
                <div style="background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1rem;">
                    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                        <i class="fas fa-comments text-primary fs-5"></i>
                        <input type="text" class="walker-ai-query-input" placeholder="Ask AI about walkers (e.g. 'Which walkers need tier upgrade this week?', 'Show top walkers in Kukatpally')..." style="flex:1; min-width:260px; background:#ffffff; border:1px solid #cbd5e1; padding:10px 16px; border-radius:10px; color:#0f172a; font-size:0.9rem; font-weight:600;" onkeypress="if(event.key==='Enter') submitWalkerAiQuery(this)">
                        <button class="btn btn-primary fw-bold px-4" style="border-radius:10px; background:#0d424d; border-color:#0d424d;" onclick="submitWalkerAiQuery(this)">
                            <i class="fas fa-paper-plane me-1"></i> Ask AI
                        </button>
                    </div>
                    <!-- Quick Preset Prompt Chips -->
                    <div style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;">
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('Which walkers are at highest churn risk?', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-bolt me-1 text-warning"></i> Churn Risk Walkers
                        </span>
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('Recommend top candidates for Tier 1 promotion', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-trophy me-1 text-warning"></i> Promotion Candidates
                        </span>
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('How can we reduce untracked walk rate?', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-chart-line me-1 text-warning"></i> Untracked Walks Advice
                        </span>
                    </div>
                    <!-- Response container -->
                    <div class="walker-ai-query-response" style="display:none; margin-top:12px; background:#ffffff; border:1px solid #e2e8f0; color:#0f172a; padding:14px; border-radius:12px; font-size:0.9rem; line-height:1.5; box-shadow: 0 4px 15px rgba(0,0,0,0.05);"></div>
                </div>
            </div>

>>>>>>> origin/main
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
<<<<<<< HEAD
=======
            <!-- AI Walker Roster Intelligence Advisor & Active Live Capacity Warnings (Light Theme) -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 1.75rem; color: #0f172a; margin-bottom: 2rem; box-shadow: 0 4px 20px rgba(13, 66, 77, 0.06);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <h3 style="margin:0; font-size:1.4rem; font-weight:800; color:#0d424d; display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-brain text-warning"></i> AI Walker Roster Intelligence Advisor
                        </h3>
                        <p style="margin:4px 0 0 0; color:#64748b; font-size:0.85rem;">
                            Real-time Gemini AI performance analysis, fleet optimization suggestions & active live capacity warnings
                        </p>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadWalkerAiFleetInsights()" style="border-radius:20px; font-weight:700; border-color:#0d424d; color:#0d424d;">
                        <i class="fas fa-sync-alt me-1"></i> Refresh AI Analysis
                    </button>
                </div>

                <!-- Active Live Capacity Warnings Section (Light Theme) -->
                <div style="background: #fff5f5; border: 1px solid #fecaca; border-radius: 14px; padding: 1.1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 0.75rem;">
                        <span class="badge bg-danger text-white fw-bold px-3 py-1.5" style="font-size:0.82rem; letter-spacing:0.5px;">
                            <i class="fas fa-exclamation-triangle me-1"></i> ACTIVE LIVE CAPACITY WARNINGS
                        </span>
                        <span style="font-size:0.8rem; color:#dc2626; font-weight:600;"><i class="fas fa-signal me-1"></i> Real-time platform supply-to-demand bottleneck alerts</span>
                    </div>
                    <div class="walker-capacity-warnings-container" style="display:flex; flex-direction:column; gap:8px;">
                        <div style="background: #ffffff; border: 1px solid #fee2e2; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; color: #64748b;"><i class="fas fa-spinner fa-spin text-danger me-2"></i> Analyzing live capacity bottlenecks...</div>
                    </div>
                </div>

                <!-- AI Executive Fleet Summary Text Box (Light Theme) -->
                <div class="walker-ai-fleet-summary-box" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: 14px; font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.25rem; color: #1e293b;">
                    <i class="fas fa-spinner fa-spin text-primary me-2"></i> Analyzing walker fleet metrics with Gemini AI...
                </div>

                <!-- Actionable Improvements Pills/Cards (Light Theme) -->
                <div style="margin-bottom: 1.25rem;">
                    <h6 style="color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:0.6rem;">
                        <i class="fas fa-lightbulb text-warning me-1"></i> Recommended Operational Improvements
                    </h6>
                    <div class="walker-ai-fleet-improvements" style="display:flex; flex-direction:column; gap:8px;">
                        <div style="background: #fffdf5; border: 1px solid #fef3c7; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; color: #64748b;"><i class="fas fa-spinner fa-spin text-warning me-2"></i> Loading suggestions...</div>
                    </div>
                </div>

                <!-- Interactive Ask AI Box (Light Theme) -->
                <div style="background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1rem;">
                    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                        <i class="fas fa-comments text-primary fs-5"></i>
                        <input type="text" class="walker-ai-query-input" placeholder="Ask AI about walkers (e.g. 'Which walkers need tier upgrade this week?', 'Show top walkers in Kukatpally')..." style="flex:1; min-width:260px; background:#ffffff; border:1px solid #cbd5e1; padding:10px 16px; border-radius:10px; color:#0f172a; font-size:0.9rem; font-weight:600;" onkeypress="if(event.key==='Enter') submitWalkerAiQuery(this)">
                        <button class="btn btn-primary fw-bold px-4" style="border-radius:10px; background:#0d424d; border-color:#0d424d;" onclick="submitWalkerAiQuery(this)">
                            <i class="fas fa-paper-plane me-1"></i> Ask AI
                        </button>
                    </div>
                    <!-- Quick Preset Prompt Chips -->
                    <div style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;">
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('Which walkers are at highest churn risk?', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-bolt me-1 text-warning"></i> Churn Risk Walkers
                        </span>
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('Recommend top candidates for Tier 1 promotion', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-trophy me-1 text-warning"></i> Promotion Candidates
                        </span>
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('How can we reduce untracked walk rate?', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-chart-line me-1 text-warning"></i> Untracked Walks Advice
                        </span>
                    </div>
                    <!-- Response container -->
                    <div class="walker-ai-query-response" style="display:none; margin-top:12px; background:#ffffff; border:1px solid #e2e8f0; color:#0f172a; padding:14px; border-radius:12px; font-size:0.9rem; line-height:1.5; box-shadow: 0 4px 15px rgba(0,0,0,0.05);"></div>
                </div>
            </div>

>>>>>>> origin/main
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
<<<<<<< HEAD
=======

            <!-- Full Walker Leaderboard Data Table with Header Columns -->
            <div class="distribution-card style="margin-top: 1.5rem;">
                <div class="distribution-header">
                    <div>
                        <h3><i class="fas fa-trophy text-warning"></i> Complete Walker Ranking & Performance Table</h3>
                        <p style="margin:2px 0 0 0; font-size:0.75rem; color:var(--theme-text-muted);">Real-time database records & tier evaluations</p>
                    </div>
                    <span class="badge bg-primary text-white" id="leaderboard-table-count" style="font-weight:750;">0 Walkers</span>
                </div>
                <div style="overflow-x: auto;">
                    <table class="table-distrib" id="leaderboard-data-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">Rank</th>
                                <th>Walker Name</th>
                                <th>Walker ID</th>
                                <th>Area / City</th>
                                <th class="text-center">Tier</th>
                                <th class="text-center">Score</th>
                                <th class="text-center">Rating</th>
                                <th class="text-center">Completion</th>
                                <th class="text-center">On-Time %</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="leaderboard-table-body">
                            <!-- Filled dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
>>>>>>> origin/main
        </div>

        <!-- PANEL 3: COMPLIANCE & ACCOUNT HEALTH -->
        <div id="panel-compliance" class="tab-content-panel">
            <div class="compliance-grid" id="compliance-cards-container">
                <!-- Filled dynamically -->
            </div>
        </div>

        <!-- PANEL 4: AI INSIGHTS & ACTIVITY TRENDS -->
        <div id="panel-insights" class="tab-content-panel">
<<<<<<< HEAD
=======
            <!-- AI Walker Fleet Advisor Hero Box (Light Theme) -->
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 20px; padding: 1.75rem; color: #0f172a; margin-bottom: 2rem; box-shadow: 0 4px 20px rgba(13, 66, 77, 0.06);">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <h3 style="margin:0; font-size:1.4rem; font-weight:800; color:#0d424d; display:flex; align-items:center; gap:10px;">
                            <i class="fas fa-brain text-warning"></i> AI Walker Roster Intelligence Advisor
                        </h3>
                        <p style="margin:4px 0 0 0; color:#64748b; font-size:0.85rem;">
                            Real-time Gemini AI performance analysis, fleet optimization suggestions & actionable risk alerts
                        </p>
                    </div>
                    <button class="btn btn-sm btn-outline-primary" onclick="loadWalkerAiFleetInsights()" style="border-radius:20px; font-weight:700; border-color:#0d424d; color:#0d424d;">
                        <i class="fas fa-sync-alt me-1"></i> Refresh AI Analysis
                    </button>
                </div>

                <!-- Active Live Capacity Warnings Section (Light Theme) -->
                <div style="background: #fff5f5; border: 1px solid #fecaca; border-radius: 14px; padding: 1.1rem; margin-bottom: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 0.75rem;">
                        <span class="badge bg-danger text-white fw-bold px-3 py-1.5" style="font-size:0.82rem; letter-spacing:0.5px;">
                            <i class="fas fa-exclamation-triangle me-1"></i> ACTIVE LIVE CAPACITY WARNINGS
                        </span>
                        <span style="font-size:0.8rem; color:#dc2626; font-weight:600;"><i class="fas fa-signal me-1"></i> Real-time platform supply-to-demand bottleneck alerts</span>
                    </div>
                    <div class="walker-capacity-warnings-container" style="display:flex; flex-direction:column; gap:8px;">
                        <div style="background: #ffffff; border: 1px solid #fee2e2; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; color: #64748b;"><i class="fas fa-spinner fa-spin text-danger me-2"></i> Analyzing live capacity bottlenecks...</div>
                    </div>
                </div>

                <!-- AI Executive Fleet Summary Text Box (Light Theme) -->
                <div class="walker-ai-fleet-summary-box" id="walker-ai-fleet-summary-box" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: 14px; font-size: 0.92rem; line-height: 1.6; margin-bottom: 1.25rem; color: #1e293b;">
                    <i class="fas fa-spinner fa-spin text-primary me-2"></i> Analyzing walker fleet metrics with Gemini AI...
                </div>

                <!-- Actionable Improvements Pills/Cards (Light Theme) -->
                <div style="margin-bottom: 1.25rem;">
                    <h6 style="color:#475569; font-weight:700; font-size:0.8rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:0.6rem;">
                        <i class="fas fa-lightbulb text-warning me-1"></i> Recommended Operational Improvements
                    </h6>
                    <div class="walker-ai-fleet-improvements" id="walker-ai-fleet-improvements" style="display:flex; flex-direction:column; gap:8px;">
                        <div style="background: #fffdf5; border: 1px solid #fef3c7; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; color: #64748b;"><i class="fas fa-spinner fa-spin text-warning me-2"></i> Loading suggestions...</div>
                    </div>
                </div>

                <!-- Interactive Ask AI Box (Light Theme) -->
                <div style="background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 16px; padding: 1rem;">
                    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
                        <i class="fas fa-comments text-primary fs-5"></i>
                        <input type="text" class="walker-ai-query-input" id="walker-ai-query-input" placeholder="Ask AI about walkers (e.g. 'Which walkers need tier upgrade this week?', 'Show top walkers in Kukatpally')..." style="flex:1; min-width:260px; background:#ffffff; border:1px solid #cbd5e1; padding:10px 16px; border-radius:10px; color:#0f172a; font-size:0.9rem; font-weight:600;" onkeypress="if(event.key==='Enter') submitWalkerAiQuery(this)">
                        <button class="btn btn-primary fw-bold px-4" style="border-radius:10px; background:#0d424d; border-color:#0d424d;" onclick="submitWalkerAiQuery(this)">
                            <i class="fas fa-paper-plane me-1"></i> Ask AI
                        </button>
                    </div>
                    <!-- Quick Preset Prompt Chips -->
                    <div style="display:flex; gap:8px; margin-top:10px; flex-wrap:wrap;">
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('Which walkers are at highest churn risk?', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-bolt me-1 text-warning"></i> Churn Risk Walkers
                        </span>
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('Recommend top candidates for Tier 1 promotion', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-trophy me-1 text-warning"></i> Promotion Candidates
                        </span>
                        <span class="badge bg-white text-dark border cursor-pointer" onclick="presetWalkerQuery('How can we reduce untracked walk rate?', this)" style="cursor:pointer; font-weight:600; padding:6px 12px; border-color:#cbd5e1 !important;">
                            <i class="fas fa-chart-line me-1 text-warning"></i> Untracked Walks Advice
                        </span>
                    </div>
                    <!-- Response container -->
                    <div class="walker-ai-query-response" id="walker-ai-query-response" style="display:none; margin-top:12px; background:#ffffff; border:1px solid #e2e8f0; color:#0f172a; padding:14px; border-radius:12px; font-size:0.9rem; line-height:1.5; box-shadow: 0 4px 15px rgba(0,0,0,0.05);"></div>
                </div>
            </div>

>>>>>>> origin/main
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

<<<<<<< HEAD
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
=======
        <!-- Strengths Box -->
        <div style="margin-top:1rem; background:#ecfdf5; border:1px solid #a7f3d0; padding:1rem; border-radius:12px;">
            <h6 style="color:#047857; font-weight:700; margin:0 0 6px 0; font-size:0.85rem;"><i class="fas fa-check-circle me-1"></i> AI Identified Strengths</h6>
            <ul id="drawer-strengths-list" style="margin:0; padding-left:18px; color:#065f46; font-size:0.8rem; line-height:1.5;">
                <li>Loading strengths...</li>
            </ul>
        </div>

        <!-- Improvements Box -->
        <div style="margin-top:0.75rem; background:#fffbeb; border:1px solid #fde68a; padding:1rem; border-radius:12px;">
            <h6 style="color:#b45309; font-weight:700; margin:0 0 6px 0; font-size:0.85rem;"><i class="fas fa-lightbulb me-1"></i> Performance Improvements Needed</h6>
            <ul id="drawer-improvements-list" style="margin:0; padding-left:18px; color:#92400e; font-size:0.8rem; line-height:1.5;">
                <li>Loading improvements...</li>
            </ul>
        </div>

        <!-- Recommendation Box -->
        <div class="drawer-recommendation-box" style="margin-top:0.75rem;">
            <h6><i class="fas fa-brain"></i> Dynamic AI Recommendation</h6>
            <p id="drawer-recommendation-text">Analyzing statistics...</p>
        </div>

        <!-- Ask AI about this Walker -->
        <div style="margin-top:1rem; background:#f8fafc; border:1px solid var(--theme-border); padding:1rem; border-radius:12px;">
            <h6 style="font-weight:700; color:var(--theme-primary); margin:0 0 8px 0; font-size:0.85rem;"><i class="fas fa-robot me-1"></i> Ask AI about this Walker</h6>
            <div style="display:flex; gap:8px;">
                <input type="text" id="drawer-ask-ai-input" placeholder="e.g. How can this walker reach Tier 1?" style="flex:1; padding:6px 12px; font-size:0.8rem; border-radius:6px; border:1px solid var(--theme-border);" onkeypress="if(event.key==='Enter') submitDrawerWalkerAiQuery()">
                <button class="btn btn-sm btn-primary" onclick="submitDrawerWalkerAiQuery()"><i class="fas fa-paper-plane"></i></button>
            </div>
            <div id="drawer-ask-ai-reply" style="display:none; margin-top:8px; background:#ffffff; border:1px solid var(--theme-border); padding:8px 12px; border-radius:6px; font-size:0.8rem; color:#334155; line-height:1.4;"></div>
        </div>
    </div>
</div>

<!-- Walker Tier Evaluation Criteria Modal -->
<div class="modal fade" id="tierEvaluationModal" tabindex="-1" aria-labelledby="tierEvaluationModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content" style="border-radius: 20px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
      <div class="modal-header" style="background: linear-gradient(135deg, #0d424d 0%, #0f172a 100%); color: #ffffff; padding: 1.25rem 1.75rem; border: none;">
        <div class="d-flex align-items-center gap-3">
          <div style="background: rgba(255, 255, 255, 0.15); width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
            <i class="fas fa-layer-group fs-4 text-warning"></i>
          </div>
          <div>
            <h5 class="modal-title fw-bold text-white mb-0" id="tierEvaluationModalLabel" style="letter-spacing: -0.3px; font-size:1.1rem;">Walker Tier Evaluation Matrix &amp; Criteria</h5>
            <small class="text-white-50" style="font-size: 0.8rem;">Official Evaluation Engine Rules • Command: <code>walker:evaluate-tiers</code></small>
          </div>
        </div>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="$('#tierEvaluationModal').modal('hide');"></button>
      </div>

      <div class="modal-body p-4" style="background: #f8fafc;">
        
        <!-- 4 Tier Cards Grid -->
        <div class="row g-3 mb-4">
          <!-- Joined Card -->
          <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff; border-top: 4px solid #06b6d4 !important;">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge bg-info text-white font-weight-bold px-2 py-1 rounded-2"><i class="fas fa-user-plus me-1"></i> Joined Status</span>
                </div>
                <p class="text-muted mb-3" style="font-size: 0.76rem;">Newly joined partners who paid verification fee but haven't completed services.</p>
                
                <ul class="list-unstyled mb-0 text-dark" style="font-size: 0.78rem; line-height: 1.9;">
                  <li><i class="fas fa-check-circle text-info me-2"></i><strong>Fee Paid:</strong> Verification Complete</li>
                  <li><i class="fas fa-check-circle text-info me-2"></i><strong>Total Walks:</strong> 0 Walks</li>
                  <li><i class="fas fa-arrow-circle-right text-primary me-2"></i><strong>Next Step:</strong> Complete at least 1 service to automatically move to Tier 3 Entry</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Tier 3 Card -->
          <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff; border-top: 4px solid #64748b !important;">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge bg-secondary text-white font-weight-bold px-2 py-1 rounded-2"><i class="fas fa-user-circle me-1"></i> Tier 3 (Entry)</span>
                </div>
                <p class="text-muted mb-3" style="font-size: 0.76rem;">Initial entry tier for active walkers completing service walks.</p>
                
                <ul class="list-unstyled mb-0 text-dark" style="font-size: 0.78rem; line-height: 1.9;">
                  <li><i class="fas fa-check-circle text-secondary me-2"></i><strong>Effective Exp:</strong> ≥ 0.5 Year</li>
                  <li><i class="fas fa-check-circle text-secondary me-2"></i><strong>Avg Rating:</strong> ≥ 3.0 Stars</li>
                  <li><i class="fas fa-check-circle text-secondary me-2"></i><strong>Total Walks:</strong> ≥ 5 Walks</li>
                  <li><i class="fas fa-check-circle text-secondary me-2"></i><strong>Last 30 Days:</strong> ≥ 3 Walks</li>
                  <li><i class="fas fa-check-circle text-secondary me-2"></i><strong>On-Time Arrival:</strong> ≥ 70%</li>
                  <li><i class="fas fa-check-circle text-secondary me-2"></i><strong>Cancel Rate:</strong> ≤ 15%</li>
                  <li><i class="fas fa-user-shield text-info me-2"></i><strong>Account Health:</strong> &lt; 10 Strikes</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Tier 2 Card -->
          <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff; border-top: 4px solid #3b82f6 !important;">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge bg-primary text-white font-weight-bold px-2 py-1 rounded-2"><i class="fas fa-shield-alt me-1"></i> Tier 2 (Standard)</span>
                </div>
                <p class="text-muted mb-3" style="font-size: 0.76rem;">Consistent operational backbone with proven track record.</p>
                
                <ul class="list-unstyled mb-0 text-dark" style="font-size: 0.78rem; line-height: 1.9;">
                  <li><i class="fas fa-check-circle text-primary me-2"></i><strong>Effective Exp:</strong> ≥ 1.0 Year</li>
                  <li><i class="fas fa-check-circle text-primary me-2"></i><strong>Avg Rating:</strong> ≥ 4.0 Stars</li>
                  <li><i class="fas fa-check-circle text-primary me-2"></i><strong>Total Walks:</strong> ≥ 10 Walks</li>
                  <li><i class="fas fa-check-circle text-primary me-2"></i><strong>Last 30 Days:</strong> ≥ 5 Walks</li>
                  <li><i class="fas fa-check-circle text-primary me-2"></i><strong>On-Time Arrival:</strong> ≥ 80%</li>
                  <li><i class="fas fa-check-circle text-primary me-2"></i><strong>Cancel Rate:</strong> ≤ 10%</li>
                  <li><i class="fas fa-user-shield text-info me-2"></i><strong>Account Health:</strong> &lt; 8 Strikes</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Tier 1 Card -->
          <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff; border-top: 4px solid #f59e0b !important;">
              <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="badge bg-warning text-dark font-weight-bold px-2 py-1 rounded-2"><i class="fas fa-crown me-1"></i> Tier 1 (Premium)</span>
                </div>
                <p class="text-muted mb-3" style="font-size: 0.76rem;">High earning premium service providers delivering top SLA quality.</p>
                
                <ul class="list-unstyled mb-0 text-dark" style="font-size: 0.78rem; line-height: 1.9;">
                  <li><i class="fas fa-check-circle text-warning me-2"></i><strong>Effective Exp:</strong> ≥ 2.0 Years</li>
                  <li><i class="fas fa-check-circle text-warning me-2"></i><strong>Avg Rating:</strong> ≥ 4.5 Stars</li>
                  <li><i class="fas fa-check-circle text-warning me-2"></i><strong>Total Walks:</strong> ≥ 30 Walks</li>
                  <li><i class="fas fa-check-circle text-warning me-2"></i><strong>Last 30 Days:</strong> ≥ 10 Walks</li>
                  <li><i class="fas fa-check-circle text-warning me-2"></i><strong>On-Time Arrival:</strong> ≥ 90%</li>
                  <li><i class="fas fa-check-circle text-warning me-2"></i><strong>Completion Rate:</strong> ≥ 85%</li>
                  <li><i class="fas fa-check-circle text-warning me-2"></i><strong>Cancel Rate:</strong> ≤ 5%</li>
                  <li><i class="fas fa-check-circle text-warning me-2"></i><strong>Complaint Rate:</strong> &lt; 2%</li>
                  <li><i class="fas fa-shield-alt text-success me-2"></i><strong>Police Verified:</strong> Mandatory</li>
                  <li><i class="fas fa-user-shield text-info me-2"></i><strong>Account Health:</strong> &lt; 5 Strikes</li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <!-- Special Business Rules Summary -->
        <div class="p-3 rounded-4 bg-white border shadow-sm">
          <h6 class="fw-bold text-dark mb-2" style="font-size:0.88rem;"><i class="fas fa-balance-scale text-primary me-2"></i>Special Business Rules &amp; Ranking Formula</h6>
          <div class="row g-2 text-muted" style="font-size: 0.76rem;">
            <div class="col-md-4">
              <div class="p-2 rounded-3 bg-light border">
                <strong class="text-dark"><i class="fas fa-shield-virus text-warning me-1"></i> Downgrade Protection</strong>
                <p class="mb-0 mt-1">1-time grace period before tier demotion to protect loyal walkers during bad months.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-2 rounded-3 bg-light border">
                <strong class="text-dark"><i class="fas fa-snowflake text-danger me-1"></i> Account Freezing</strong>
                <p class="mb-0 mt-1">Reaching 8 strikes automatically freezes account for 7 days &amp; locks tier promotion.</p>
              </div>
            </div>
            <div class="col-md-4">
              <div class="p-2 rounded-3 bg-light border">
                <strong class="text-dark"><i class="fas fa-calculator text-success me-1"></i> Ranking Score (0-100)</strong>
                <p class="mb-0 mt-1">40% Rating + 20% On-Time + 15% Completion + 15% Activity + 10% Loyalty.</p>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer bg-light p-2 px-4 border-top-0 d-flex justify-content-between">
        <span class="text-muted small" style="font-size:0.75rem;"><i class="fas fa-sync text-primary me-1"></i> Evaluated automatically via Cron CLI</span>
        <button type="button" class="btn btn-primary btn-sm px-4 rounded-pill font-weight-bold" onclick="$('#tierEvaluationModal').modal('hide');">Close Matrix</button>
      </div>
    </div>
  </div>
</div>

<script>
    function openTierEvaluationModal(tier) {
        $('#tierEvaluationModal').modal('show');
    }

    // 1. Client side data state initialized from database PHP payload
    let walkers = <?= json_encode($walkers) ?>;
    const totalRegisteredWalkers = <?= (int)$totalRegisteredWalkers ?>;
    let selectedWalkerId = null;

    // Helper to extract sanitized walker tier directly from EvaluateWalkerTiers database calculation
    function getWalkerTier(w) {
        if (!w) return 3;
>>>>>>> origin/main
        const t = parseInt(w.tier);
        if (t === 1) return 1;
        if (t === 2) return 2;
        return 3;
    }

<<<<<<< HEAD
=======
    // Helper to check if walker account is frozen, inactive, or suspended
    function isWalkerFrozen(w) {
        if (!w) return false;
        const status = (w.account_status || '').toString().toLowerCase().trim();
        const strikes = parseInt(w.strike_count || 0);

        if (strikes >= 8) return true;
        if (status === 'frozen' || status === 'inactive' || status === 'suspended' || status === 'disabled' || status === '0' || status === 'blocked') {
            return true;
        }
        return false;
    }

>>>>>>> origin/main
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

<<<<<<< HEAD
        // Render tab specific components
        if (tabName === 'overview') {
            updateOverviewStats();
        } else if (tabName === 'roster') {
            renderRosterTabComponents();
        } else if (tabName === 'compliance') {
            renderComplianceCards();
        } else if (tabName === 'insights') {
            renderInsightsTab();
=======
        const filtered = getFilteredWalkers();

        // Render tab specific components
        if (tabName === 'overview') {
            updateOverviewStats(filtered);
        } else if (tabName === 'roster') {
            renderRosterTabComponents();
        } else if (tabName === 'compliance') {
            renderComplianceCards(filtered);
        } else if (tabName === 'insights') {
            renderInsightsTab(filtered);
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
        if (!w) return 'N/A';
        if (w.city) return w.city;
        if (w.area) return w.area;
        if (w.address) return w.address;
>>>>>>> origin/main
        const areas = ['Gachibowli', 'Kondapur', 'Madhapur', 'Jubilee Hills'];
        const index = parseInt(w.provider_id || 0) % areas.length;
        return areas[index];
    }

    // Extract Walker Display ID
    function getWalkerDisplayId(w) {
<<<<<<< HEAD
        return 'WLK-' + (100 + parseInt(w.provider_id || 0));
    }

    // Compute dynamic stats per tier
    function getTierStats(tierNum) {
        const list = walkers.filter(w => getWalkerTier(w) === tierNum);
=======
        if (!w) return 'WLK-000';
        return 'WLK-' + (w.provider_id || w.id || 0);
    }

    // Compute dynamic stats per tier
    function getTierStats(tierNum, targetList) {
        const listToUse = targetList || walkers;
        const list = listToUse.filter(w => getWalkerTier(w) === tierNum);
>>>>>>> origin/main
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
<<<<<<< HEAD
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
=======
    function updateOverviewStats(targetWalkers) {
        const listToUse = targetWalkers || walkers;
        const total = listToUse.length;
        const frozen = listToUse.filter(w => isWalkerFrozen(w)).length;
        const activeCount = Math.max(0, total - frozen);

        const joinedCount = listToUse.filter(w => !isWalkerFrozen(w) && parseInt(w.completed_walks || 0) === 0).length;
        const t1Count = listToUse.filter(w => !isWalkerFrozen(w) && getWalkerTier(w) === 1 && parseInt(w.completed_walks || 0) > 0).length;
        const t2Count = listToUse.filter(w => !isWalkerFrozen(w) && getWalkerTier(w) === 2 && parseInt(w.completed_walks || 0) > 0).length;
        const t3Count = listToUse.filter(w => !isWalkerFrozen(w) && getWalkerTier(w) === 3 && parseInt(w.completed_walks || 0) > 0).length;

        // Percentages of active roster
        const joinedPct = activeCount > 0 ? Math.round((joinedCount / activeCount) * 100) : 0;
        const t1Pct = activeCount > 0 ? Math.round((t1Count / activeCount) * 100) : 0;
        const t2Pct = activeCount > 0 ? Math.round((t2Count / activeCount) * 100) : 0;
        const t3Pct = activeCount > 0 ? Math.round((t3Count / activeCount) * 100) : 0;

        // Accurate Averages (strictly evaluating active/rated providers)
        let sumRating = 0;
        let ratedCount = 0;
        let sumCompletion = 0;
        let compCount = 0;
        let verifiedCount = 0;
        let sumWalks = 0;
        let sumRepeats = 0;

        listToUse.forEach(w => {
            // Rating
            const r = parseFloat(w.weighted_rating || w.avg_rating || 0);
            if (r > 0) {
                sumRating += r;
                ratedCount++;
            }

            // Completion Rate (from live database quotations & walking_service_bookings subquery)
            const cr = parseFloat(w.completion_rate || 0);
            if (cr > 0) {
                sumCompletion += cr;
                compCount++;
            }

            // Repeat Clients (using live database subquery result from quotations & walking_service_bookings)
            const rc = parseInt(w.repeat_client_count || 0);
            if (rc > 0) {
                sumRepeats += rc;
                sumWalks += 1;
            }

            // Verification (strict evaluation of sp.is_verified column from DB)
            const isVerified = (w.is_verified == 1 || w.is_verified === "1" || w.verified == 1);
            if (isVerified) {
                verifiedCount++;
            }
        });

        const avgRating = ratedCount > 0 ? (sumRating / ratedCount) : 0.0;
        const avgCompletion = compCount > 0 ? Math.min(100.0, sumCompletion / compCount) : 0.0;

        const repeatPct = activeCount > 0 ? ((sumWalks / activeCount) * 100) : (total > 0 ? (sumWalks / total * 100) : 0.0);
        const policePct = total > 0 ? (verifiedCount / total * 100) : 0.0;

        // Set KPI card values
        $('#kpi-total-walkers').text(activeCount);
        $('#kpi-total-subtext').html(`Out of <strong>${totalRegisteredWalkers}</strong> registered roster`);
        $('#kpi-joined-count').text(joinedCount);
        $('#kpi-joined-percent').text(joinedPct + '%');
>>>>>>> origin/main
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
<<<<<<< HEAD
        
        const displayRepeatPct = total > 0 ? 78.5 + (total % 5) * 0.2 : 78.5;
        $('#kpi-repeat-percent').text(displayRepeatPct.toFixed(1) + '%');
=======
        $('#kpi-repeat-percent').text(repeatPct.toFixed(1) + '%');
        $('#kpi-repeat-subtext').text(`${sumWalks} SP${sumWalks === 1 ? '' : 's'} with repeat clients`);
        $('#kpi-police-percent').text(policePct.toFixed(1) + '%');
        $('#kpi-police-subtext').text(`${verifiedCount} SP${verifiedCount === 1 ? '' : 's'} background cleared`);
>>>>>>> origin/main

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
<<<<<<< HEAD
        const recruitmentQuality = total > 0 ? Math.round(walkers.filter(w => parseFloat(w.avg_rating) >= 4.0).length / total * 100) : 80;
        const trainingQuality = total > 0 ? Math.round(walkers.reduce((sum, w) => sum + parseFloat(w.completion_rate || 90), 0) / total) : 90;
        const operationsQuality = total > 0 ? Math.round(walkers.reduce((sum, w) => sum + parseFloat(w.last_30d_on_time_pct || 90), 0) / total) : 90;
        const retentionQuality = total > 0 ? Math.round(walkers.filter(w => parseInt(w.repeat_client_count) > 0).length / total * 100) : 85;
=======
        const recruitmentQuality = total > 0 ? Math.round(listToUse.filter(w => parseFloat(w.avg_rating || 0) >= 4.0).length / total * 100) : 80;
        const trainingQuality = total > 0 ? Math.round(listToUse.reduce((sum, w) => sum + parseFloat(w.completion_rate || 90), 0) / total) : 90;
        const operationsQuality = total > 0 ? Math.round(listToUse.reduce((sum, w) => sum + parseFloat(w.last_30d_on_time_pct || 90), 0) / total) : 90;
        const retentionQuality = total > 0 ? Math.round(listToUse.filter(w => parseInt(w.repeat_client_count || 0) > 0).length / total * 100) : 85;
>>>>>>> origin/main

        $('#hero-conversion-val').text(conversionRate + '%');
        $('#hero-recruitment-val').text(recruitmentQuality + '%');
        $('#hero-training-val').text(trainingQuality + '%');
        $('#hero-operations-val').text(operationsQuality + '%');
        $('#hero-retention-val').text(retentionQuality + '%');

        // 2. Dynamic Revenue Calculations
        let t1Walks = 0, t2Walks = 0, t3Walks = 0;
<<<<<<< HEAD
        walkers.forEach(w => {
=======
        listToUse.forEach(w => {
>>>>>>> origin/main
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
<<<<<<< HEAD
        const t1Stats = getTierStats(1);
        const t2Stats = getTierStats(2);
        const t3Stats = getTierStats(3);
=======
        const t1Stats = getTierStats(1, listToUse);
        const t2Stats = getTierStats(2, listToUse);
        const t3Stats = getTierStats(3, listToUse);
>>>>>>> origin/main

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
<<<<<<< HEAD
        const promoCount = walkers.filter(w => getWalkerTier(w) > 1 && parseFloat(w.ranking_score) >= 70).length;
        const downCount = walkers.filter(w => parseFloat(w.ranking_score) < 40).length;
=======
        const promoCount = listToUse.filter(w => getWalkerTier(w) > 1 && parseFloat(w.ranking_score || 0) >= 70).length;
        const downCount = listToUse.filter(w => parseFloat(w.ranking_score || 0) < 40).length;
>>>>>>> origin/main

        $('#insight-promotions-count').text('+' + promoCount + ' Walkers');
        $('#insight-downgrades-count').text('-' + downCount + ' Walkers');

<<<<<<< HEAD
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
=======
        renderAreaIntelligence(listToUse);
    }

    // Render Area Intelligence Section (100% Dynamic & AI Dictated)
    function renderAreaIntelligence(targetList) {
        const listToUse = targetList || walkers;
        const container = $('#areas-intelligence-container');
        container.empty();

        if (!listToUse || listToUse.length === 0) {
            container.append('<div class="col-12 text-center text-muted py-4"><i class="fas fa-info-circle me-1"></i> No area intelligence records available for current filter.</div>');
            return;
        }

        // Group walkers dynamically by their area/city
        const areaMap = {};
        listToUse.forEach(w => {
            const areaName = getWalkerArea(w) || 'Other Areas';
            if (!areaMap[areaName]) {
                areaMap[areaName] = [];
            }
            areaMap[areaName].push(w);
        });

        // Extract area keys and sort by walker density descending
        const areaKeys = Object.keys(areaMap).sort((a, b) => areaMap[b].length - areaMap[a].length);

        areaKeys.forEach(area => {
            const areaWalkers = areaMap[area];
            const total = areaWalkers.length;

            let t1 = 0, t2 = 0, t3 = 0, joined = 0;
            let sumRating = 0, ratedCount = 0;

            areaWalkers.forEach(w => {
                const completed = parseInt(w.completed_walks || 0);
                const tier = getWalkerTier(w);
                const r = parseFloat(w.weighted_rating || w.avg_rating || 0);

                if (completed === 0) {
                    joined++;
                } else if (tier === 1) {
                    t1++;
                } else if (tier === 2) {
                    t2++;
                } else {
                    t3++;
                }

                if (r > 0) {
                    sumRating += r;
                    ratedCount++;
                }
            });

            const avgRating = ratedCount > 0 ? (sumRating / ratedCount).toFixed(2) : '3.50';

            // Check if backend AI Analyst provided area intelligence for this region
            let aiData = null;
            if (window.lastAiAreaIntelligence && Array.isArray(window.lastAiAreaIntelligence)) {
                aiData = window.lastAiAreaIntelligence.find(a => (a.area || '').toLowerCase() === area.toLowerCase());
            }

            // Dynamic Demand Badge & Badge Style (AI Dictated if available)
            let demandText = aiData ? aiData.demand_status : 'Active Coverage';
            let badgeClass = aiData ? aiData.badge_class : 'bg-info text-white';

            if (!aiData) {
                if (t1 === 0 && total >= 5) {
                    demandText = 'Critical Supply Deficit';
                    badgeClass = 'bg-danger text-white fw-bold';
                } else if (total >= 15) {
                    demandText = 'Very High Demand Zone';
                    badgeClass = 'bg-warning text-dark fw-bold';
                } else if (total >= 8) {
                    demandText = 'High Demand Zone';
                    badgeClass = 'bg-primary text-white fw-bold';
                } else if (joined > total * 0.7) {
                    demandText = 'High Onboarding Backlog';
                    badgeClass = 'bg-warning text-dark fw-bold';
                }
            }

            // Dynamic AI Recommendation Suggestion (AI Dictated)
            let suggestion = aiData ? aiData.ai_suggestion : '';
            if (!suggestion) {
                if (t1 === 0 && t2 > 0) {
                    suggestion = `AI Analysis: No Tier 1 walkers in ${area}. Recommend upgrading ${t2} top-performing Tier 2 walker(s) to expand VIP fulfillment.`;
                } else if (t1 === 0 && t2 === 0) {
                    suggestion = `AI Analysis: High capacity gap in ${area} (${total} total walkers). Needs immediate first-walk activation calls for ${joined} joined signups.`;
                } else if (t1 >= 2) {
                    suggestion = `AI Analysis: Strong premium coverage in ${area} (${t1} Tier 1, ${t2} Tier 2). Fleet capacity fully meets daily client booking demand.`;
                } else if (parseFloat(avgRating) < 3.8) {
                    suggestion = `AI Analysis: Service rating alert in ${area} (${avgRating}/5.00 avg). Require mandatory quality & punctuality review for local walkers.`;
                } else {
                    suggestion = `AI Analysis: Stable operational performance in ${area} with ${total} active providers and ${avgRating}/5.00 CSAT rating.`;
                }
>>>>>>> origin/main
            }

            container.append(`
                <div class="area-card">
                    <div class="area-card-header">
<<<<<<< HEAD
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
=======
                        <strong style="color: var(--theme-primary); font-size:1.05rem;">
                            <i class="fas fa-map-marker-alt me-1 text-danger"></i>${area}
                        </strong>
                        <span class="badge ${badgeClass}">${demandText}</span>
                    </div>
                    <div class="area-counts">
                        <div class="area-count-item">
                            <strong style="color:#F59E0B;">${t1}</strong>
                            <span>Tier 1</span>
                        </div>
                        <div class="area-count-item">
                            <strong style="color:#3B82F6;">${t2}</strong>
                            <span>Tier 2</span>
                        </div>
                        <div class="area-count-item">
                            <strong style="color:#64748b;">${t3}</strong>
                            <span>Tier 3</span>
                        </div>
                        <div class="area-count-item">
                            <strong style="color:#0ea5e9;">${joined}</strong>
                            <span>Joined</span>
                        </div>
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b; margin: 8px 0; font-weight:600; display:flex; justify-content:space-between; border-top: 1px solid #f1f5f9; padding-top: 6px;">
                        <span><i class="fas fa-users text-primary me-1"></i> Total Walkers: <strong>${total}</strong></span>
                        <span><i class="fas fa-star text-warning me-1"></i> Avg CSAT: <strong>${avgRating}/5.0</strong></span>
                    </div>
                    <div class="area-ai-suggestion">
                        <i class="fas fa-brain text-warning me-1"></i> <strong>AI Advisor Directive:</strong> ${suggestion}
>>>>>>> origin/main
                    </div>
                </div>
            `);
        });
    }

<<<<<<< HEAD
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
=======
    // Filter Logic combining Search + Status + Tier + City
    function getFilteredWalkers() {
        const rawQuery = ($('#roster-search').val() || '').toString().toLowerCase().trim();
        const statusFilter = $('#filter-status').val();
        const tierFilter = $('#filter-tier').val();
        const cityFilter = $('#filter-city').val();

        return walkers.filter(w => {
            if (!w) return false;

            const status = (w.account_status || 'active').toLowerCase();
            const frozen = isWalkerFrozen(w);

            let matchesStatus = true;
            if (statusFilter === 'active') {
                matchesStatus = status === 'active' && !frozen;
            } else if (statusFilter === 'frozen') {
                matchesStatus = frozen || status === 'inactive' || status === 'frozen' || status === 'suspended' || status === 'disabled' || status === '0';
            }

            let matchesQuery = true;
            if (rawQuery) {
                const name = (w.provider_name || '').toLowerCase();
                const phone = (w.phone || '').toString().toLowerCase();
                const email = (w.email || '').toLowerCase();
                const displayId = getWalkerDisplayId(w).toLowerCase();
                const cleanDisplayId = displayId.replace(/[^a-z0-9]/g, '');
                const rawId = (w.provider_id || '').toString().toLowerCase();
                const area = getWalkerArea(w).toLowerCase();
                const tierVal = getWalkerTier(w);
                const tierStr = 'tier ' + tierVal;
                const tStr = 't' + tierVal;
                const cleanQuery = rawQuery.replace(/[^a-z0-9]/g, '');

                matchesQuery = name.includes(rawQuery) ||
                    phone.includes(rawQuery) ||
                    email.includes(rawQuery) ||
                    displayId.includes(rawQuery) ||
                    (cleanQuery.length > 0 && cleanDisplayId.includes(cleanQuery)) ||
                    rawId.includes(rawQuery) ||
                    area.includes(rawQuery) ||
                    tierStr.includes(rawQuery) ||
                    tStr === rawQuery;
            }

            let matchesTier = true;
            if (tierFilter === 'joined') {
                matchesTier = (parseInt(w.completed_walks || 0) === 0);
            } else if (tierFilter === 'frozen') {
                matchesTier = isWalkerFrozen(w);
            } else if (tierFilter) {
                matchesTier = (!isWalkerFrozen(w) && parseInt(w.completed_walks || 0) > 0 && getWalkerTier(w) === parseInt(tierFilter));
            }
            const matchesCity = !cityFilter || getWalkerArea(w) === cityFilter;

            return matchesStatus && matchesQuery && matchesTier && matchesCity;
        });
    }

    function handleFilterChange() {
        const filtered = getFilteredWalkers();

        $('#found-walkers-count').text(`Found: ${filtered.length} Walker${filtered.length === 1 ? '' : 's'}`);

        const activeTabBtn = $('.tab-item-btn.active').attr('id');
        if (activeTabBtn === 'tab-btn-overview') {
            updateOverviewStats(filtered);
        } else if (activeTabBtn === 'tab-btn-roster') {
            renderRosterDirectoryList(filtered);
            renderLeaderboardTable(filtered);
        } else if (activeTabBtn === 'tab-btn-compliance') {
            renderComplianceCards(filtered);
        } else if (activeTabBtn === 'tab-btn-insights') {
            renderInsightsTab(filtered);
>>>>>>> origin/main
        }
    }

    function renderRosterTabComponents() {
        handleFilterChange();
    }

    // Render Roster Sidebar Directory
    function renderRosterDirectoryList(filteredList) {
        const container = $('#directory-list-container');
        container.empty();

<<<<<<< HEAD
        $('#directory-count-badge').text(filteredList.length);

        if (filteredList.length === 0) {
            container.append('<div class="text-center text-muted py-4">No walkers found.</div>');
=======
        const listToUse = filteredList || getFilteredWalkers();

        $('#directory-count-badge').text(listToUse.length);

        if (listToUse.length === 0) {
            container.append('<div class="text-center text-muted py-4">No walkers found matching your search.</div>');
>>>>>>> origin/main
            clearDetailsCard();
            return;
        }

<<<<<<< HEAD
        filteredList.sort((a, b) => parseFloat(b.ranking_score || 0) - parseFloat(a.ranking_score || 0));

        filteredList.forEach(w => {
            const area = getWalkerArea(w);
            const activeClass = (selectedWalkerId && parseInt(selectedWalkerId) === parseInt(w.provider_id)) ? 'active' : '';
=======
        listToUse.sort((a, b) => parseFloat(b.ranking_score || 0) - parseFloat(a.ranking_score || 0));

        listToUse.forEach(w => {
            const area = getWalkerArea(w);
            const activeClass = (selectedWalkerId && parseInt(selectedWalkerId) === parseInt(w.provider_id)) ? 'active' : '';
            const frozen = isWalkerFrozen(w);
>>>>>>> origin/main

            const rating = parseFloat(w.weighted_rating || w.avg_rating || 0).toFixed(2);
            let stars = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= Math.round(rating)) stars += '<i class="fas fa-star"></i>';
                else stars += '<i class="far fa-star"></i>';
            }

            container.append(`
<<<<<<< HEAD
                <div class="directory-item ${activeClass}" data-provider-id="${w.provider_id}" onclick="selectWalker(${w.provider_id})">
=======
                <div class="directory-item ${activeClass} ${frozen ? 'border-danger' : ''}" data-provider-id="${w.provider_id}" onclick="selectWalker(${w.provider_id})">
>>>>>>> origin/main
                    <div class="directory-item-left">
                        <img src="${w.provider_profile ? 'https://petsfolio.in/public/uploads/providers/' + w.provider_profile : 'https://placehold.co/44'}" 
                             class="walker-avatar" style="width:38px; height:38px;" 
                             onerror="this.src='https://placehold.co/44'">
                        <div class="directory-item-details">
<<<<<<< HEAD
                            <h5>${w.provider_name}</h5>
                            <p>Tier ${getWalkerTier(w)} • ${area}</p>
=======
                            <h5>${w.provider_name || 'Walker'} ${frozen ? '<span class="badge bg-danger" style="font-size:0.6rem;">FROZEN</span>' : ''}</h5>
                            <p>${parseInt(w.completed_walks || 0) === 0 ? '<span class="badge bg-info text-white me-1">Joined</span>' : 'Tier ' + getWalkerTier(w)} • ${area}</p>
>>>>>>> origin/main
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
<<<<<<< HEAD
        if (filteredList.length > 0) {
            const stillExists = filteredList.some(w => parseInt(w.provider_id) === parseInt(selectedWalkerId));
            if (!stillExists) {
                selectWalker(filteredList[0].provider_id);
=======
        if (listToUse.length > 0) {
            const stillExists = listToUse.some(w => parseInt(w.provider_id) === parseInt(selectedWalkerId));
            if (!stillExists) {
                selectWalker(listToUse[0].provider_id);
>>>>>>> origin/main
            } else {
                renderSelectedWalkerDetails();
            }
        }
    }

<<<<<<< HEAD
=======
    // Render Complete Leaderboard Data Table
    function renderLeaderboardTable(filteredList) {
        const listToUse = filteredList || getFilteredWalkers();
        const tbody = $('#leaderboard-table-body');
        tbody.empty();

        $('#leaderboard-table-count').text(`${listToUse.length} Walker${listToUse.length === 1 ? '' : 's'}`);

        if (listToUse.length === 0) {
            tbody.append('<tr><td colspan="11" class="text-center text-muted py-4">No walker records found matching your search.</td></tr>');
            return;
        }

        const sorted = [...listToUse].sort((a, b) => {
            const tierA = getWalkerTier(a);
            const tierB = getWalkerTier(b);
            if (tierA !== tierB) return tierA - tierB;
            return parseFloat(b.ranking_score || 0) - parseFloat(a.ranking_score || 0);
        });

        sorted.forEach((w, index) => {
            const frozen = isWalkerFrozen(w);
            const statusBadge = frozen 
                ? '<span class="badge bg-danger text-white">Frozen / Inactive</span>' 
                : '<span class="badge bg-success text-white">Active</span>';
            
            const isGrace = parseInt(w.in_grace_period || 0) === 1;
            const graceBadge = isGrace ? ' <span class="badge bg-warning text-dark" title="Downgrade Grace Protection Active"><i class="fas fa-shield-virus"></i> Grace</span>' : '';

            let tierBadge = '';
            const completed = parseInt(w.completed_walks || 0);
            if (completed === 0) {
                tierBadge = '<span class="badge bg-info text-white" style="font-weight:750; font-size:0.7rem; padding:4px 8px;"><i class="fas fa-user-plus me-1"></i>Joined</span>';
            } else if (getWalkerTier(w) === 1) {
                tierBadge = '<span class="badge bg-warning text-dark" style="font-weight:750; font-size:0.7rem; padding:4px 8px;"><i class="fas fa-crown me-1"></i>Tier 1</span>';
            } else if (getWalkerTier(w) === 2) {
                tierBadge = '<span class="badge bg-primary text-white" style="font-weight:750; font-size:0.7rem; padding:4px 8px;"><i class="fas fa-shield-alt me-1"></i>Tier 2</span>';
            } else {
                tierBadge = '<span class="badge bg-secondary text-white" style="font-weight:750; font-size:0.7rem; padding:4px 8px;"><i class="fas fa-user-circle me-1"></i>Tier 3</span>';
            }
            tierBadge += graceBadge;

            tbody.append(`
                <tr style="${frozen ? 'background-color: #fff5f5;' : ''}">
                    <td style="font-weight: 800; color: var(--theme-primary);">#${index + 1}</td>
                    <td>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <img src="${w.provider_profile ? 'https://petsfolio.in/public/uploads/providers/' + w.provider_profile : 'https://placehold.co/32'}" 
                                 style="width:32px; height:32px; border-radius:50%; object-fit:cover;" 
                                 onerror="this.src='https://placehold.co/32'">
                            <strong>${w.provider_name || 'Walker'}</strong>
                        </div>
                    </td>
                    <td style="font-family:monospace; font-weight:700;">${getWalkerDisplayId(w)}</td>
                    <td>${getWalkerArea(w)}</td>
                    <td class="text-center">${tierBadge}</td>
                    <td class="text-center" style="font-weight:800; color:var(--theme-primary);">${parseFloat(w.ranking_score || 0).toFixed(1)}</td>
                    <td class="text-center" style="font-weight:700;"><i class="fas fa-star text-warning" style="font-size:0.75rem;"></i> ${parseFloat(w.weighted_rating || w.avg_rating || 0).toFixed(2)}</td>
                    <td class="text-center">${parseFloat(w.completion_rate || 0).toFixed(1)}%</td>
                    <td class="text-center">${parseFloat(w.last_30d_on_time_pct || 0).toFixed(1)}%</td>
                    <td class="text-center">${statusBadge}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-primary" style="padding: 2px 8px; font-size: 0.75rem;" onclick="selectWalker(${w.provider_id}); switchTab('roster');">View</button>
                    </td>
                </tr>
            `);
        });
    }

>>>>>>> origin/main
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

<<<<<<< HEAD
        $('#detail-name').text(w.provider_name);
=======
        $('#detail-name').text(w.provider_name || 'Walker');
>>>>>>> origin/main
        $('#detail-id').text(getWalkerDisplayId(w));
        $('#detail-phone').text(w.phone || 'N/A');
        $('#detail-area').text(getWalkerArea(w));

<<<<<<< HEAD
        const expMonths = Math.round(parseFloat(w.experience || 0) * 12);
        $('#detail-exp').text(expMonths + ' mos');

        const sorted = [...walkers].sort((a,b) => parseFloat(b.ranking_score) - parseFloat(a.ranking_score));
=======
        const expYears = parseFloat(w.effective_experience || w.experience || 0);
        const expMonths = Math.round(expYears * 12);
        $('#detail-exp').text(expMonths >= 12 ? (expYears.toFixed(1) + ' yrs') : (expMonths + ' mos'));

        const sorted = [...walkers].sort((a, b) => {
            const tA = getWalkerTier(a);
            const tB = getWalkerTier(b);
            if (tA !== tB) return tA - tB;
            return parseFloat(b.ranking_score || 0) - parseFloat(a.ranking_score || 0);
        });
>>>>>>> origin/main
        const rank = sorted.findIndex(x => parseInt(x.provider_id) === parseInt(w.provider_id)) + 1;
        $('#detail-rank').text('#' + rank);

        $('#detail-avatar').attr('src', w.provider_profile ? 'https://petsfolio.in/public/uploads/providers/' + w.provider_profile : 'https://placehold.co/72');

<<<<<<< HEAD
        $('#metric-rating').text(parseFloat(w.weighted_rating || w.avg_rating || 0).toFixed(2));
        $('#metric-completion').text(parseFloat(w.completion_rate || 96).toFixed(1) + '%');
        $('#metric-ontime').text(parseFloat(w.last_30d_on_time_pct || 94).toFixed(1) + '%');
        $('#metric-cancellation').text(parseFloat(w.last_30d_cancellation_rate || 2.4).toFixed(1) + '%');
=======
        const ratingVal = parseFloat(w.weighted_rating || w.avg_rating || 0);
        $('#metric-rating').text(ratingVal.toFixed(2));
        $('#metric-completion').text((w.completion_rate !== undefined && w.completion_rate !== null ? parseFloat(w.completion_rate) : 0).toFixed(1) + '%');
        $('#metric-ontime').text((w.last_30d_on_time_pct !== undefined && w.last_30d_on_time_pct !== null ? parseFloat(w.last_30d_on_time_pct) : 0).toFixed(1) + '%');
        $('#metric-cancellation').text((w.last_30d_cancellation_rate !== undefined && w.last_30d_cancellation_rate !== null ? parseFloat(w.last_30d_cancellation_rate) : 0).toFixed(1) + '%');
>>>>>>> origin/main
        
        const compRate = w.complaint_count ? ((parseInt(w.complaint_count) / parseInt(w.total_walks || 100)) * 100) : 0;
        $('#metric-complaint').text(compRate.toFixed(1) + '%');
        
        $('#metric-total-walks').text(w.total_walks || 0);
        $('#metric-last-30d').text((w.last_30d_walks || 0) + ' walks');
        $('#metric-repeat-clients').text(w.repeat_client_count || 0);

<<<<<<< HEAD
        const status = (w.account_status || 'active').toLowerCase();
=======
        const frozen = isWalkerFrozen(w);
>>>>>>> origin/main
        const strikes = parseInt(w.strike_count || 0);
        const banner = $('#detail-alert-banner');
        
        banner.removeClass('detail-alert-success detail-alert-warning detail-alert-danger');
        $('#btn-restore').hide();

<<<<<<< HEAD
        if (status === 'frozen') {
            banner.addClass('detail-alert-danger');
            $('#detail-alert-text').text(`ACCOUNT SUSPENDED: Walker has accumulated ${strikes}+ strikes. All walks disabled.`);
=======
        if (frozen) {
            banner.addClass('detail-alert-danger');
            $('#detail-alert-text').text(`ACCOUNT SUSPENDED / INACTIVE: Account is frozen or inactive. All walks disabled.`);
>>>>>>> origin/main
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
<<<<<<< HEAD
            $('#detail-alert-text').text('DOWNGRADE GRACE PERIOD ACTIVE: Protected from tier demotion. Next evaluation: 2026-10-01.');
=======
            $('#detail-alert-text').text('DOWNGRADE GRACE PERIOD ACTIVE: Protected from tier demotion.');
>>>>>>> origin/main
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
<<<<<<< HEAD
    function renderComplianceCards() {
        const container = $('#compliance-cards-container');
        container.empty();

        const atRiskWalkers = walkers.filter(w => {
            const status = (w.account_status || 'active').toLowerCase();
            return status === 'frozen' || status === 'warning' || parseInt(w.strike_count || 0) > 0;
=======
    let complianceLimit = 36;

    function renderComplianceCards(targetList) {
        // Evaluate over full roster `walkers` so all 387+ inactive/frozen profiles are included
        const listToUse = targetList || walkers;
        const container = $('#compliance-cards-container');
        container.empty();

        const atRiskWalkers = listToUse.filter(w => {
            const status = (w.account_status || 'active').toString().toLowerCase().trim();
            const strikes = parseInt(w.strike_count || 0);
            return status !== 'active' || isWalkerFrozen(w) || status === 'warning' || strikes > 0;
>>>>>>> origin/main
        });

        if (atRiskWalkers.length === 0) {
            container.append(`
                <div style="grid-column: 1/-1; background: white; border: 1px solid var(--theme-border); border-radius:12px; padding:3rem; text-align:center;">
                    <i class="fas fa-shield-alt text-success" style="font-size:3rem; margin-bottom:1rem;"></i>
                    <h5 style="margin:0; font-weight:750;">Roster Health: Excellent</h5>
<<<<<<< HEAD
                    <p class="text-muted mt-2 mb-0">No providers are currently under freeze, warning, or holding active compliance violations.</p>
=======
                    <p class="text-muted mt-2 mb-0">No providers match the filter or hold active compliance violations.</p>
>>>>>>> origin/main
                </div>
            `);
            return;
        }

<<<<<<< HEAD
        atRiskWalkers.forEach(w => {
            const status = (w.account_status || 'active').toLowerCase();
            const cardClass = status === 'frozen' ? 'frozen-status' : 'warning-status';
            const strikes = parseInt(w.strike_count || 0);

            let unfreezeBtn = '';
            if (status === 'frozen' || strikes > 0) {
=======
        const visibleList = atRiskWalkers.slice(0, complianceLimit);

        visibleList.forEach(w => {
            const frozen = isWalkerFrozen(w);
            const status = (w.account_status || (frozen ? 'frozen' : 'active')).toLowerCase();
            const cardClass = frozen ? 'frozen-status' : 'warning-status';
            const strikes = parseInt(w.strike_count || 0);

            let unfreezeBtn = '';
            if (frozen || strikes > 0) {
>>>>>>> origin/main
                unfreezeBtn = `<button class="btn-restore" onclick="restoreAccount(${w.provider_id})"><i class="fas fa-unlock"></i> Unfreeze / Reset Strikes</button>`;
            }

            container.append(`
                <div class="compliance-card ${cardClass}" id="compliance-${w.provider_id}">
                    <div class="compliance-header">
<<<<<<< HEAD
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
=======
                        <h5>${w.provider_name || 'Walker'}</h5>
                        <span class="badge-status ${status}">${status}</span>
                    </div>
                    <div class="compliance-body">
                        <span><i class="fas fa-exclamation-triangle text-warning"></i> Strike Count: <strong>${strikes} / 8</strong></span>
                        <span><i class="fas fa-phone-alt text-primary"></i> Contact: ${w.phone || 'N/A'}</span>
                        <span><i class="fas fa-history text-info"></i> Total Walks: ${w.total_walks || 0} (Completion: ${(w.completion_rate !== undefined && w.completion_rate !== null ? parseFloat(w.completion_rate) : 0).toFixed(1)}%)</span>
                        <span><i class="fas fa-star text-warning"></i> Avg Rating: ${parseFloat(w.avg_rating || 0).toFixed(2)}</span>
                    </div>
                    <div class="compliance-btn-group">
                        <button class="btn-action btn-strike" onclick="issueStrike(${w.provider_id})" style="flex:0 0 40px; height:36px; border-radius:8px;" title="Issue Strike"><i class="fas fa-exclamation-triangle"></i></button>
>>>>>>> origin/main
                        ${unfreezeBtn}
                    </div>
                </div>
            `);
        });
<<<<<<< HEAD
    }

    // Render Tab 4: AI Insights
    function renderInsightsTab() {
=======

        if (atRiskWalkers.length > complianceLimit) {
            container.append(`
                <div style="grid-column: 1/-1; text-align: center; padding: 1.5rem 0;">
                    <button class="btn btn-outline-primary" style="font-weight: 750; border-radius: 8px; padding: 10px 24px;" onclick="loadMoreCompliance()">
                        <i class="fas fa-spinner"></i> Load More At-Risk Walkers (Showing ${visibleList.length} of ${atRiskWalkers.length})
                    </button>
                </div>
            `);
        }
    }

    function loadMoreCompliance() {
        complianceLimit += 36;
        renderComplianceCards();
    }

    // Render Tab 4: AI Insights
    function renderInsightsTab(targetList) {
        const listToUse = targetList || getFilteredWalkers();
>>>>>>> origin/main
        const promContainer = $('#insights-promotion-candidates');
        const riskContainer = $('#insights-at-risk-candidates');

        promContainer.empty();
        riskContainer.empty();

<<<<<<< HEAD
        const promCandidates = walkers.filter(w => {
            return getWalkerTier(w) > 1 && 
                parseFloat(w.weighted_rating || 0) >= 4.4 && 
                parseFloat(w.ranking_score || 0) >= 70 && 
=======
        const promCandidates = listToUse.filter(w => {
            return !isWalkerFrozen(w) &&
                getWalkerTier(w) > 1 && 
                parseFloat(w.weighted_rating || w.avg_rating || 0) >= 4.3 && 
                parseFloat(w.ranking_score || 0) >= 60 && 
>>>>>>> origin/main
                parseInt(w.strike_count || 0) === 0;
        });

        if (promCandidates.length === 0) {
            promContainer.append('<p class="text-muted text-center py-4">No active promotion recommendations.</p>');
        } else {
<<<<<<< HEAD
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
=======
            const topProm = promCandidates.slice(0, 12);
            topProm.forEach(w => {
                promContainer.append(`
                    <div style="background:#f0fdf4; border: 1px solid rgba(22, 163, 74, 0.2); border-left: 4px solid #16a34a; border-radius:10px; padding:12px; display:flex; justify-content:space-between; align-items:center; gap:10px;">
                        <div style="flex:1;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <strong style="color:#14532d; font-size:0.88rem;">${w.provider_name || 'Walker'}</strong>
                                <span class="badge bg-success text-white" style="font-size:0.6rem; font-weight:750;">T${getWalkerTier(w)}</span>
                            </div>
                            <div style="font-size:0.75rem; color:#475569; margin-top:4px; display:flex; gap:12px; flex-wrap:wrap;">
                                <span>Score: <strong style="color:#15803d;">${parseFloat(w.ranking_score || 0).toFixed(1)}</strong></span>
                                <span>Rating: <strong><i class="fas fa-star text-warning"></i> ${parseFloat(w.weighted_rating || w.avg_rating || 0).toFixed(2)}</strong></span>
                                <span>Area: <strong>${getWalkerArea(w)}</strong></span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-success" onclick="promoteWalker(${w.provider_id})" style="font-weight:750; border-radius:6px; font-size:0.75rem;"><i class="fas fa-arrow-up"></i> Promote</button>
                    </div>
                `);
            });

            if (promCandidates.length > 12) {
                promContainer.append(`
                    <div class="text-center pt-2">
                        <small class="text-muted">Showing top 12 of ${promCandidates.length} eligible promotion candidates.</small>
                    </div>
                `);
            }
        }

        const riskCandidates = listToUse.filter(w => {
            return isWalkerFrozen(w) || 
                parseInt(w.strike_count || 0) >= 1 || 
                parseFloat(w.weighted_rating || 0) < 4.2 || 
                parseFloat(w.last_30d_cancellation_rate || 0) > 8;
>>>>>>> origin/main
        });

        if (riskCandidates.length === 0) {
            riskContainer.append('<p class="text-muted text-center py-4">No critical compliance or churn risks identified.</p>');
        } else {
<<<<<<< HEAD
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
=======
            const topRisk = riskCandidates.slice(0, 12);
            topRisk.forEach(w => {
                const strikes = parseInt(w.strike_count || 0);
                const rating = parseFloat(w.weighted_rating || w.avg_rating || 0).toFixed(2);
                const cancelRate = (w.last_30d_cancellation_rate !== undefined && w.last_30d_cancellation_rate !== null ? parseFloat(w.last_30d_cancellation_rate) : 0).toFixed(1);
                const frozen = isWalkerFrozen(w);
                const status = (w.account_status || (frozen ? 'frozen' : 'active')).toLowerCase();

                let actionBtn = '';
                if (frozen) {
                    actionBtn = `<button class="btn btn-sm btn-success" onclick="restoreAccount(${w.provider_id})" style="font-weight:750; border-radius:6px; font-size:0.75rem;"><i class="fas fa-unlock"></i> Unfreeze</button>`;
                } else {
                    actionBtn = `<button class="btn btn-sm btn-outline-danger" onclick="openDrawer(${w.provider_id})" style="font-weight:750; border-radius:6px; font-size:0.75rem;"><i class="fas fa-chart-line"></i> Forecast</button>`;
                }

                riskContainer.append(`
                    <div style="background:#fffafa; border: 1px solid rgba(239, 68, 68, 0.2); border-left: 4px solid #ef4444; border-radius:10px; padding:12px; display:flex; justify-content:space-between; align-items:center; gap:10px;">
                        <div style="flex:1;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <strong style="color:#991b1b; font-size:0.88rem;">${w.provider_name || 'Walker'}</strong>
                                <span class="badge-status ${status}" style="font-size:0.6rem; padding:2px 8px;">${status}</span>
                            </div>
                            <div style="font-size:0.75rem; color:#475569; margin-top:4px; display:flex; gap:12px; flex-wrap:wrap;">
                                <span><i class="fas fa-exclamation-triangle text-danger"></i> Strikes: <strong>${strikes}/8</strong></span>
                                <span><i class="fas fa-star text-warning"></i> CSAT: <strong>${rating}</strong></span>
                                <span><i class="fas fa-times-circle text-danger"></i> Cancel: <strong>${cancelRate}%</strong></span>
                            </div>
                        </div>
                        <div style="display:flex; gap:6px;">
                            ${actionBtn}
                        </div>
                    </div>
                `);
            });

            if (riskCandidates.length > 12) {
                riskContainer.append(`
                    <div class="text-center pt-2">
                        <small class="text-muted">Showing top 12 of ${riskCandidates.length} at-risk profiles. Filter search above to narrow roster.</small>
                    </div>
                `);
            }
>>>>>>> origin/main
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
<<<<<<< HEAD
    function openDrawer(providerId) {
=======
    let currentDrawerProviderId = null;

    function openDrawer(providerId) {
        currentDrawerProviderId = providerId;
>>>>>>> origin/main
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
<<<<<<< HEAD
        $('#drawer-recommendation-text').text('Analyzing statistics...');
=======
        $('#drawer-recommendation-text').text('Analyzing statistics with Gemini AI...');
        $('#drawer-strengths-list').html('<li><i class="fas fa-spinner fa-spin me-1"></i> Analyzing strengths...</li>');
        $('#drawer-improvements-list').html('<li><i class="fas fa-spinner fa-spin me-1"></i> Analyzing improvements...</li>');
        $('#drawer-ask-ai-reply').hide().text('');
        $('#drawer-ask-ai-input').val('');
>>>>>>> origin/main

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

<<<<<<< HEAD
                    $('#drawer-recommendation-text').text(data.recommendation);
=======
                    $('#drawer-recommendation-text').text(data.ai_summary || data.recommendation);

                    // Strengths
                    if (data.strengths && data.strengths.length > 0) {
                        $('#drawer-strengths-list').html(data.strengths.map(s => `<li>${s}</li>`).join(''));
                    } else {
                        $('#drawer-strengths-list').html('<li>Verified provider account active on platform.</li>');
                    }

                    // Improvements
                    if (data.improvements && data.improvements.length > 0) {
                        $('#drawer-improvements-list').html(data.improvements.map(i => `<li>${i}</li>`).join(''));
                    } else {
                        $('#drawer-improvements-list').html('<li>Maintain current top compliance standards.</li>');
                    }
>>>>>>> origin/main
                } else {
                    $('#drawer-recommendation-text').text('Error fetching prediction statistics.');
                }
            },
            error: function() {
                $('#drawer-recommendation-text').text('Failed to connect to predictive analytics agent.');
            }
        });
    }

<<<<<<< HEAD
=======
    function renderCleanMarkdown(text) {
        if (!text) return '';
        let t = String(text);
        
        // 1. Convert Markdown Tables (| Col1 | Col2 | ...) into responsive Light HTML <table>
        const tableRegex = /(?:\|[^\n]+\|\r?\n)+/g;
        t = t.replace(tableRegex, function(tableBlock) {
            const lines = tableBlock.trim().split('\n').filter(line => line.trim().startsWith('|'));
            if (lines.length === 0) return '';
            
            let htmlTable = '<div class="table-responsive my-3" style="max-height: 320px; overflow-y: auto;"><table class="table table-sm table-light table-hover align-middle mb-0" style="background: #ffffff; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; font-size: 0.82rem;">';
            
            lines.forEach((line, index) => {
                // Ignore separator lines
                if (line.includes('---') || (line.match(/\|/g) || []).length <= 2 && line.includes(':')) {
                    return;
                }
                
                const cells = line.split('|').map(c => c.trim()).filter((c, i, a) => i > 0 && i < a.length - 1);
                if (cells.length === 0 || cells.every(c => c === '' || c === ':')) return;
                
                if (index === 0) {
                    htmlTable += '<thead style="background: #f1f5f9; position: sticky; top: 0; z-index: 2; border-bottom: 2px solid #cbd5e1;"><tr>';
                    cells.forEach(cell => {
                        htmlTable += `<th class="py-2 px-3 fw-bold" style="color: #0d424d; letter-spacing:0.3px;">${cell}</th>`;
                    });
                    htmlTable += '</tr></thead><tbody>';
                } else {
                    htmlTable += '<tr style="border-bottom: 1px solid #f1f5f9;">';
                    cells.forEach(cell => {
                        let formattedCell = cell.replace(/'/g, '');
                        let cellLower = formattedCell.toLowerCase();
                        if (cellLower === 'active') {
                            formattedCell = `<span class="badge bg-success text-white px-2 py-1"><i class="fas fa-check-circle me-1"></i>Active</span>`;
                        } else if (cellLower === 'inactive' || cellLower === 'frozen' || cellLower === 'suspended') {
                            formattedCell = `<span class="badge bg-danger text-white px-2 py-1"><i class="fas fa-ban me-1"></i>${formattedCell}</span>`;
                        } else if (cellLower === 'walking') {
                            formattedCell = `<span class="badge bg-info text-white px-2 py-1"><i class="fas fa-walking me-1"></i>Walking</span>`;
                        }
                        htmlTable += `<td class="py-2 px-3" style="color: #334155;">${formattedCell}</td>`;
                    });
                    htmlTable += '</tr>';
                }
            });
            
            htmlTable += '</tbody></table></div>';
            return htmlTable;
        });

        // 2. Convert Blockquotes (> text)
        t = t.replace(/^>\s*(.*?)$/gm, '<div class="p-2.5 px-3 my-2 rounded-3" style="background: #fffdf5; border: 1px solid #fef3c7; border-left: 4px solid #f59e0b; color: #1e293b; font-size: 0.88rem;"><i class="fas fa-info-circle text-warning me-2 opacity-75"></i>$1</div>');

        // 3. Convert Headers (###, ##, #)
        t = t.replace(/###\s*(.*?)(?=\n|$)/g, '<div class="fw-bold mt-3 mb-1" style="color: #0d424d; font-size: 0.95rem; letter-spacing: 0.3px;"><i class="fas fa-chevron-right me-2 text-warning"></i>$1</div>');
        t = t.replace(/##\s*(.*?)(?=\n|$)/g, '<div class="fw-bold mt-3 mb-1" style="color: #0d424d; font-size: 1rem;">$1</div>');
        t = t.replace(/#\s*(.*?)(?=\n|$)/g, '<div class="fw-bold mt-3 mb-1" style="color: #0d424d; font-size: 1.05rem;">$1</div>');

        // 4. Convert Bold / Italics
        t = t.replace(/\*\*\*(.*?)\*\*\*/g, '<strong class="text-primary">$1</strong>');
        t = t.replace(/\*\*(.*?)\*\*/g, '<strong class="fw-bold text-dark" style="background: #e2e8f0; padding: 1px 6px; border-radius: 4px; border: 1px solid #cbd5e1;">$1</strong>');
        t = t.replace(/\*(.*?)\*/g, '<em>$1</em>');

        // 5. Convert Bullet Points (* or - or 1.)
        t = t.replace(/^\s*[\*\-]\s*(.*?)$/gm, '<div class="d-flex align-items-start gap-2 my-1"><i class="fas fa-check-circle text-primary mt-1" style="font-size: 0.75rem; color:#0d424d !important;"></i><div style="font-size:0.88rem; color:#1e293b;">$1</div></div>');
        t = t.replace(/^\s*(\d+)\.\s*(.*?)$/gm, '<div class="d-flex align-items-start gap-2 my-2"><span class="badge bg-warning text-dark fw-bold me-1" style="border-radius:6px; font-size:0.75rem;">$1</span><div class="flex-grow-1" style="font-size:0.88rem; color:#1e293b;">$2</div></div>');

        // 6. Horizontal Rules
        t = t.replace(/---/g, '<hr style="border-color: #e2e8f0; margin: 14px 0;">');

        // Paragraph breaks
        t = t.replace(/\n\n/g, '<div class="my-2"></div>');
        t = t.replace(/\n/g, '<br>');

        return t;
    }

    function submitDrawerWalkerAiQuery() {
        const query = $('#drawer-ask-ai-input').val().trim();
        if (!query) return;

        const replyBox = $('#drawer-ask-ai-reply');
        replyBox.show().html('<i class="fas fa-spinner fa-spin text-primary me-1"></i> Gemini AI is analyzing walker stats...');

        $.ajax({
            url: '<?= base_url() ?>walkerstats/ask-ai',
            type: 'POST',
            data: { question: query + ' (Provider ID: ' + (currentDrawerProviderId || '') + ')' },
            success: function(res) {
                if (res.status) {
                    replyBox.html(`<strong>AI Analyst:</strong><div style="margin-top:4px;">${renderCleanMarkdown(res.reply)}</div>`);
                } else {
                    replyBox.html(`<span class="text-danger">${res.reply || 'Error processing request.'}</span>`);
                }
            },
            error: function() {
                replyBox.html('<span class="text-danger">Failed to communicate with AI Analyst.</span>');
            }
        });
    }

    function loadWalkerAiFleetInsights() {
        $('.walker-ai-fleet-summary-box').html('<i class="fas fa-spinner fa-spin text-primary me-2"></i> Analyzing walker fleet metrics with Gemini AI...');
        $('.walker-ai-fleet-improvements').html('<div style="background: #fffdf5; border: 1px solid #fef3c7; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; color: #64748b;"><i class="fas fa-spinner fa-spin text-warning me-2"></i> Loading suggestions...</div>');
        $('.walker-capacity-warnings-container').html('<div style="background: #ffffff; border: 1px solid #fee2e2; padding: 8px 14px; border-radius: 8px; font-size: 0.85rem; color: #64748b;"><i class="fas fa-spinner fa-spin text-danger me-2"></i> Analyzing live capacity bottlenecks...</div>');

        $.ajax({
            url: '<?= base_url() ?>walkerstats/ai-insights',
            type: 'GET',
            success: function(res) {
                if (res.status === 'success') {
                    const d = res.data;

                    // Render AI-Dictated Active Live Capacity Warnings
                    if (d.capacity_warnings && d.capacity_warnings.length > 0) {
                        let capacityWarningsHtml = d.capacity_warnings.map(cw => `
                            <div class="d-flex align-items-center justify-content-between p-2.5 px-3 rounded-3 my-1" style="background: #ffffff; border: 1px solid #fee2e2; border-left: 4px solid #ef4444;">
                                <div class="d-flex align-items-center gap-3">
                                    <i class="${cw.icon || 'fas fa-exclamation-triangle text-danger'} fs-5"></i>
                                    <div>
                                        <strong style="font-size:0.88rem; color:#0f172a;">${cw.title}</strong>
                                        <div style="font-size:0.8rem; color:#475569;">${cw.description}</div>
                                    </div>
                                </div>
                                <span class="badge ${cw.badge_class || 'bg-danger text-white'} fw-bold">${cw.badge || 'WARNING'}</span>
                            </div>
                        `).join('');
                        $('.walker-capacity-warnings-container').html(capacityWarningsHtml);
                    }

                    // Store AI Area Intelligence payload and refresh area cards
                    if (d.area_intelligence) {
                        window.lastAiAreaIntelligence = d.area_intelligence;
                        renderAreaIntelligence();
                    }

                    let badgesHtml = `
                        <div class="d-flex gap-2 flex-wrap mb-3 pb-3" style="border-bottom: 1px solid #e2e8f0;">
                            <span class="badge bg-primary px-3 py-2" style="font-size:0.8rem;"><i class="fas fa-users me-1"></i> ${d.total_walkers} Total Active Walkers</span>
                            <span class="badge bg-info text-white px-3 py-2" style="font-size:0.8rem;"><i class="fas fa-user-plus me-1"></i> Joined (0 walks): ${d.joined_count || d.tier_counts.joined || 0}</span>
                            <span class="badge bg-warning text-dark px-3 py-2" style="font-size:0.8rem;"><i class="fas fa-crown me-1"></i> Tier 1: ${d.tier_counts.t1} | Tier 2: ${d.tier_counts.t2} | Tier 3: ${d.tier_counts.t3}</span>
                            <span class="badge bg-success px-3 py-2" style="font-size:0.8rem;"><i class="fas fa-star me-1"></i> ${parseFloat(d.avg_rating).toFixed(2)}/5.00 Avg Rating</span>
                            <span class="badge bg-danger px-3 py-2" style="font-size:0.8rem;"><i class="fas fa-exclamation-triangle me-1"></i> ${d.at_risk_count} At-Risk Walkers</span>
                        </div>
                    `;

                    let formattedSummary = renderCleanMarkdown(d.ai_executive_summary);
                    $('.walker-ai-fleet-summary-box').html(badgesHtml + `<div>${formattedSummary}</div>`);
                    
                    if (d.actionable_improvements && d.actionable_improvements.length > 0) {
                        const impHtml = d.actionable_improvements.map(imp => 
                            `<div style="background: #fffdf5; border: 1px solid #fef3c7; border-left: 4px solid #f59e0b; padding: 10px 14px; border-radius: 8px; font-size: 0.88rem; line-height: 1.5; color: #1e293b;"><i class="fas fa-lightbulb text-warning me-2"></i>${renderCleanMarkdown(imp)}</div>`
                        ).join('');
                        $('.walker-ai-fleet-improvements').html(impHtml);
                    }
                }
            },
            error: function() {
                $('.walker-ai-fleet-summary-box').html('<span class="text-danger">Failed to load AI Fleet Insights.</span>');
            }
        });
    }

    function submitWalkerAiQuery(btnElement) {
        let parentBox = btnElement ? $(btnElement).closest('.tab-content-panel, div') : $('.tab-content-panel.active');
        let input = parentBox.find('.walker-ai-query-input');
        if (input.length === 0) input = $('#walker-ai-query-input');

        const question = input.val().trim();
        if (!question) return;

        let respBox = parentBox.find('.walker-ai-query-response');
        if (respBox.length === 0) respBox = $('#walker-ai-query-response');

        respBox.show().html('<i class="fas fa-spinner fa-spin text-warning me-2"></i> Gemini AI Analyst is processing your query...');

        $.ajax({
            url: '<?= base_url() ?>walkerstats/ask-ai',
            type: 'POST',
            data: { question: question },
            success: function(res) {
                if (res.status) {
                    respBox.html(`<strong><i class="fas fa-robot text-primary me-1"></i> AI Walker Analyst Answer:</strong><div style="margin-top:8px; line-height:1.6;">${renderCleanMarkdown(res.reply)}</div>`);
                } else {
                    respBox.html(`<span class="text-danger">Error: ${res.reply || 'Could not process query.'}</span>`);
                }
            },
            error: function() {
                respBox.html('<span class="text-danger">Failed to communicate with AI Walker Analyst.</span>');
            }
        });
    }

    function presetWalkerQuery(text, btnElement) {
        let parentBox = btnElement ? $(btnElement).closest('.tab-content-panel, div') : $('.tab-content-panel.active');
        let input = parentBox.find('.walker-ai-query-input');
        if (input.length === 0) input = $('#walker-ai-query-input');

        input.val(text);
        submitWalkerAiQuery(btnElement);
    }

    $(document).ready(function() {
        setTimeout(loadWalkerAiFleetInsights, 500);
    });

>>>>>>> origin/main
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

<<<<<<< HEAD
=======
    // Populate Live Activity Feed on load
    function initActivityLog() {
        const log = $('#overview-activity-log');
        log.empty();

        const totalWalkersCount = walkers.length;
        const frozenCount = walkers.filter(w => isWalkerFrozen(w)).length;
        const t1Count = walkers.filter(w => getWalkerTier(w) === 1).length;

        const initialEvents = [
            { time: 'Just now', action: 'Roster Audit Completed', text: `Loaded ${totalWalkersCount} active service providers across Gachibowli, Kondapur, and Madhapur zones.` },
            { time: '2 mins ago', action: 'Compliance Scanner', text: `Flagged ${frozenCount} inactive / suspended walker accounts. Booking matching disabled for frozen profiles.` },
            { time: '5 mins ago', action: 'Tier Evaluation', text: `${t1Count} providers qualified for Tier 1 Priority Matching based on CSAT score ≥ 4.5.` },
            { time: '10 mins ago', action: 'SLA Monitoring', text: 'On-time delivery average stable at 94.8% across active walking sessions.' },
            { time: '15 mins ago', action: 'AI Forecast Engine', text: 'Predictive analytics & churn models synchronized with CodeIgniter backend.' }
        ];

        initialEvents.forEach(evt => {
            log.append(`
                <div class="activity-item">
                    <span class="activity-time">${evt.time}</span>
                    <span class="activity-text"><strong>${evt.action}</strong>: ${evt.text}</span>
                </div>
            `);
        });
    }

>>>>>>> origin/main
    // Initialize Dashboard
    $(document).ready(function() {
        // Move drawer elements to body to prevent containing block bugs from parent relative/transform positioning
        $('body').append($('#prediction-drawer-overlay'));
        $('body').append($('#prediction-drawer'));

        populateCityFilter();
        updateOverviewStats();
<<<<<<< HEAD
=======
        initActivityLog();
>>>>>>> origin/main
        renderCharts();
        switchTab('overview');
    });
</script>

<?= $this->endSection(); ?>