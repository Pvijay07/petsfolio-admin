<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

  :root {
    --theme-primary: #0d424d;
    --theme-primary-light: #e6eff2;
    --theme-primary-hover: #125e6e;
    --theme-accent: #ff4757;
    --theme-text-dark: #0f172a;
    --theme-text-muted: #64748b;
    --theme-card-bg: #ffffff;
    --theme-border: #e2e8f0;
    --theme-shadow: 0 8px 30px rgba(0, 0, 0, 0.02);
    --theme-shadow-hover: 0 16px 40px rgba(13, 66, 77, 0.05);
  }

  .sp-dashboard-wrapper {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: #f8fafc;
    padding: 2rem;
    border-radius: 20px;
    color: var(--theme-text-dark);
    margin: 20px 0;
  }

  /* Premium Accordion Cards Styling */
  .city-accordion-card {
    border-radius: 16px;
    border: 1px solid var(--theme-border);
    overflow: hidden;
    margin-bottom: 1rem;
    background-color: var(--theme-card-bg);
    box-shadow: var(--theme-shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .city-accordion-card:hover {
    box-shadow: var(--theme-shadow-hover);
    border-color: #cbd5e1;
  }

  .city-accordion-header {
    background: #ffffff;
    padding: 1.25rem 1.75rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-left: 5px solid var(--theme-primary);
    border-bottom: 1px solid transparent;
    transition: all 0.3s ease;
  }

  .city-accordion-header:hover {
    background: #f8fafc;
  }

  .city-accordion-title {
    color: var(--theme-text-dark);
    font-weight: 700;
    margin: 0;
    font-size: 1.05rem;
    display: flex;
    align-items: center;
  }

  .city-accordion-icon {
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    color: var(--theme-text-muted);
    font-size: 0.85rem;
  }

  .city-accordion-card.is-open .city-accordion-icon {
    transform: rotate(180deg);
    color: var(--theme-primary);
  }

  .city-accordion-card.is-open .city-accordion-header {
    border-bottom-color: var(--theme-border);
    background: #f8fafc;
  }

  .city-accordion-body {
    padding: 1.75rem;
    display: none;
    background: #ffffff;
  }

  .city-accordion-card.is-open .city-accordion-body {
    display: block;
    animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(-8px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .warning-count-pill {
    font-size: 0.7rem;
    font-weight: 800;
    padding: 4px 12px;
    border-radius: 9999px;
    margin-left: 10px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
  }

  .warning-count-pill.has-warnings {
    background: rgba(239, 68, 68, 0.08);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.15);
  }

  .warning-count-pill.clear {
    background: rgba(16, 185, 129, 0.08);
    color: #10b981;
    border: 1px solid rgba(16, 185, 129, 0.15);
  }

  .category-group-header {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 1.2px;
    font-weight: 800;
    color: var(--theme-primary);
    border-bottom: 2px solid var(--theme-primary-light);
    padding-bottom: 0.5rem;
    margin-bottom: 1.25rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .alert-item {
    border-left: 4px solid var(--theme-border);
    padding: 1rem 1.25rem;
    margin-bottom: 0.85rem;
    background: #ffffff;
    border-radius: 0 12px 12px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.01);
    transition: all 0.2s ease;
  }

  .alert-item:hover {
    transform: translateX(6px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
  }

  .alert-item.critical {
    border-left-color: #ef4444;
    background: #fef2f2;
  }

  .alert-item.warning {
    border-left-color: #f59e0b;
    background: #fffbeb;
  }

  .alert-item.healthy {
    border-left-color: #10b981;
    background: #ecfdf5;
  }

  .sp-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    border-bottom: 1px solid var(--theme-border);
    padding-bottom: 1.5rem;
  }

  .sp-header h2 {
    font-weight: 800;
    color: var(--theme-primary);
    margin: 0;
    letter-spacing: -0.5px;
  }

  .sp-header p {
    margin: 0.4rem 0 0 0;
    color: var(--theme-text-muted);
    font-size: 0.95rem;
  }

  /* KPI Grid */
  .kpi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
  }

  .kpi-card {
    background: var(--theme-card-bg);
    border: 1px solid var(--theme-border);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    box-shadow: var(--theme-shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .kpi-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--theme-shadow-hover);
    border-color: rgba(13, 66, 77, 0.12);
  }

  .kpi-icon-container {
    width: 54px;
    height: 54px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    transition: transform 0.3s ease;
  }

  .kpi-card:hover .kpi-icon-container {
    transform: scale(1.1) rotate(5deg);
  }

  .kpi-info h3 {
    margin: 0;
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--theme-primary);
    letter-spacing: -0.5px;
  }

  .kpi-info p {
    margin: 0.2rem 0 0 0;
    font-size: 0.75rem;
    font-weight: 700;
    color: var(--theme-text-muted);
    text-transform: uppercase;
    letter-spacing: 0.8px;
  }

  /* Color Classes */
  .bg-teal-light {
    background: #e0f2f1;
    color: #00695c;
  }

  .bg-blue-light {
    background: #e3f2fd;
    color: #1565c0;
  }

  .bg-amber-light {
    background: #fff8e1;
    color: #ff8f00;
  }

  .bg-rose-light {
    background: #ffebee;
    color: #c62828;
  }

  /* Alert Container */
  .alert-section {
    background: #fff;
    border: 1px solid var(--theme-border);
    border-radius: 18px;
    padding: 1.75rem;
    margin-bottom: 2rem;
    box-shadow: var(--theme-shadow);
  }

  .alert-section h4 {
    font-weight: 800;
    margin-bottom: 1.5rem;
    color: var(--theme-primary);
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 1.15rem;
  }

  .alert-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .alert-content h5 {
    margin: 0 0 0.3rem 0;
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--theme-text-dark);
  }

  .alert-content p {
    margin: 0;
    font-size: 0.85rem;
    color: var(--theme-text-muted);
    line-height: 1.4;
  }

  /* Grid Layout */
  .dashboard-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
  }

  @media (max-width: 1024px) {
    .dashboard-grid {
      grid-template-columns: 1fr;
    }
  }

  /* Tables */
  .table-container {
    background: white;
    border: 1px solid var(--theme-border);
    border-radius: 18px;
    padding: 1.75rem;
    box-shadow: var(--theme-shadow);
  }

  .table-container h4 {
    margin-bottom: 1.5rem;
    font-weight: 800;
    color: var(--theme-primary);
    font-size: 1.15rem;
  }

  .custom-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
    /* Elegant gap between rows */
    font-size: 0.86rem;
    margin-top: -8px;
  }

  .custom-table th {
    background: transparent;
    color: var(--theme-text-muted);
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.8px;
    padding: 0.85rem 1.1rem;
    text-align: left;
    border-bottom: 2px solid var(--theme-border);
  }

  .custom-table tr td {
    padding: 1rem 1.1rem;
    background: #ffffff;
    border-top: 1px solid var(--theme-border);
    border-bottom: 1px solid var(--theme-border);
    color: #334155;
    transition: all 0.2s ease;
  }

  .custom-table tr td:first-child {
    border-left: 1px solid var(--theme-border);
    border-top-left-radius: 12px;
    border-bottom-left-radius: 12px;
  }

  .custom-table tr td:last-child {
    border-right: 1px solid var(--theme-border);
    border-top-right-radius: 12px;
    border-bottom-right-radius: 12px;
  }

  .custom-table tr:hover td {
    background-color: #fafcff;
    border-color: #cbd5e1;
  }

  /* Premium DataTables Styling Overrides */
  .dataTables_wrapper .dataTables_length {
    margin-bottom: 1rem;
  }

  .dataTables_wrapper .dataTables_length select {
    padding: 0.4rem 1.75rem 0.4rem 0.75rem !important;
    border: 1px solid var(--theme-border) !important;
    border-radius: 8px !important;
    background-color: #f8fafc !important;
    font-weight: 600 !important;
    font-size: 0.8rem !important;
    color: var(--theme-text-dark) !important;
    cursor: pointer !important;
  }

  .dataTables_wrapper .dataTables_filter {
    margin-bottom: 1rem;
  }

  .dataTables_wrapper .dataTables_filter input {
    padding: 0.5rem 1.25rem !important;
    border: 1px solid var(--theme-border) !important;
    border-radius: 9999px !important;
    background-color: #f8fafc !important;
    font-size: 0.82rem !important;
    color: var(--theme-text-dark) !important;
    width: 220px !important;
    transition: all 0.2s ease !important;
  }

  .dataTables_wrapper .dataTables_filter input:focus {
    outline: none !important;
    border-color: var(--theme-primary) !important;
    background-color: #ffffff !important;
    box-shadow: 0 0 0 3px rgba(13, 66, 77, 0.08) !important;
  }

  .dataTables_wrapper .dataTables_info {
    font-size: 0.8rem !important;
    color: var(--theme-text-muted) !important;
    font-weight: 600 !important;
    padding-top: 1.5rem !important;
  }

  .dataTables_wrapper .dataTables_paginate {
    padding-top: 1.25rem !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button {
    padding: 0.45rem 0.85rem !important;
    margin: 0 3px !important;
    border-radius: 8px !important;
    border: 1px solid transparent !important;
    background: #f1f5f9 !important;
    color: #475569 !important;
    font-weight: 700 !important;
    font-size: 0.78rem !important;
    transition: all 0.2s ease !important;
    cursor: pointer !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: #e2e8f0 !important;
    color: var(--theme-text-dark) !important;
    border-color: transparent !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.current,
  .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
    background: var(--theme-primary) !important;
    color: white !important;
    border-color: var(--theme-primary) !important;
    box-shadow: 0 4px 12px rgba(13, 66, 77, 0.15) !important;
  }

  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
  .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
    background: #f8fafc !important;
    color: #94a3b8 !important;
    cursor: not-allowed !important;
  }

  .badge-status {
    padding: 5px 12px;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 700;
    display: inline-block;
    letter-spacing: 0.3px;
  }

  .badge-status.critical {
    background: #fee2e2;
    color: #ef4444;
  }

  .badge-status.warning {
    background: #fef3c7;
    color: #d97706;
  }

  .badge-status.healthy {
    background: #d1fae5;
    color: #10b981;
  }

  .badge-status.shortage {
    background: #fee2e2;
    color: #ef4444;
  }

  .badge-status.tight {
    background: #fef3c7;
    color: #d97706;
  }

  .badge-status.balanced {
    background: #d1fae5;
    color: #10b981;
  }

  /* Interactive Simulator */
  .simulator-container {
    background: white;
    border: 1px solid var(--theme-border);
    border-radius: 18px;
    padding: 1.75rem;
    box-shadow: var(--theme-shadow);
    display: flex;
    flex-direction: column;
    gap: 1.4rem;
  }

  .simulator-container h4 {
    font-weight: 800;
    color: var(--theme-primary);
    margin: 0;
    font-size: 1.15rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }

  .form-group label {
    font-weight: 700;
    font-size: 0.8rem;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .form-control-sim {
    padding: 0.8rem 1rem;
    border: 1px solid var(--theme-border);
    border-radius: 10px;
    font-size: 0.9rem;
    font-family: inherit;
    color: var(--theme-text-dark);
    background-color: #f8fafc;
    transition: all 0.2s;
  }

  .form-control-sim:focus {
    outline: none;
    border-color: var(--theme-primary);
    background-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(13, 66, 77, 0.15);
  }

  .btn-sim {
    background: var(--theme-primary);
    color: white;
    border: none;
    padding: 0.85rem;
    border-radius: 10px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
    font-size: 0.9rem;
    letter-spacing: 0.2px;
    box-shadow: 0 4px 12px rgba(13, 66, 77, 0.15);
  }

  .btn-sim:hover {
    background: var(--theme-primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(13, 66, 77, 0.25);
  }

  /* Booking Density Map Styles */
  .density-map-card {
    background: var(--theme-card-bg);
    border: 1px solid var(--theme-border);
    border-radius: 18px;
    overflow: hidden;
    margin-bottom: 2rem;
    box-shadow: var(--theme-shadow);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .density-map-card:hover {
    box-shadow: var(--theme-shadow-hover);
  }

  .density-map-header {
    padding: 1.5rem 1.75rem;
    border-bottom: 1px solid var(--theme-border);
  }

  .density-map-header h4 {
    margin: 0;
    font-weight: 800;
    color: var(--theme-primary);
    font-size: 1.15rem;
  }

  .density-map-header .subtitle {
    margin: 0.3rem 0 0 0;
    font-size: 0.85rem;
    color: var(--theme-text-muted);
  }

  .density-map-container {
    position: relative;
    height: 480px;
    width: 100%;
    background: #fafcff;
    overflow: hidden;
  }

  .map-svg-grid {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
  }

  .map-markers-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
  }

  .map-marker-wrapper {
    position: absolute;
    transform: translate(-50%, -50%);
    z-index: 5;
    transition: z-index 0.2s ease;
  }

  .map-marker-wrapper:hover {
    z-index: 10;
  }

  .map-marker-bubble {
    background: #ffffff;
    border-radius: 8px;
    padding: 8px 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
    border: 1px solid var(--theme-border);
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
  }

  .map-marker-wrapper:hover .map-marker-bubble {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
  }

  .marker-info {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .marker-name {
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--theme-text-dark);
    white-space: nowrap;
  }

  .marker-value {
    font-size: 0.75rem;
    font-weight: 700;
    margin-top: 1px;
  }

  /* Status specific styles */
  .map-marker-bubble.shortage {
    border-color: #fee2e2;
  }

  .map-marker-bubble.shortage .marker-value {
    color: #ef4444;
  }

  .map-marker-bubble.tight {
    border-color: #fef3c7;
  }

  .map-marker-bubble.tight .marker-value {
    color: #d97706;
  }

  .map-marker-bubble.balanced {
    border-color: #d1fae5;
  }

  .map-marker-bubble.balanced .marker-value {
    color: #10b981;
  }

  /* Warning Badge */
  .marker-alert-badge {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #ef4444;
    color: white;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    font-size: 10px;
    font-weight: bold;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  /* Pulse Animations */
  .map-marker-pulse {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 70px;
    height: 70px;
    border-radius: 50%;
    z-index: -1;
    pointer-events: none;
  }

  .map-marker-pulse.shortage {
    background: rgba(239, 68, 68, 0.12);
    animation: marker-pulse-anim 2s infinite;
  }

  .map-marker-pulse.tight {
    background: rgba(217, 119, 6, 0.08);
    animation: marker-pulse-anim 2s infinite;
  }

  .map-marker-pulse.balanced {
    background: rgba(16, 185, 129, 0.06);
    animation: marker-pulse-anim 2s infinite;
  }

  @keyframes marker-pulse-anim {
    0% {
      transform: translate(-50%, -50%) scale(0.6);
      opacity: 0.8;
    }

    100% {
      transform: translate(-50%, -50%) scale(1.6);
      opacity: 0;
    }
  }

  /* Tooltip design */
  .marker-tooltip {
    position: absolute;
    bottom: calc(100% + 10px);
    left: 50%;
    transform: translateX(-50%) translateY(5px);
    background: #0f172a;
    color: white;
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 0.75rem;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
    z-index: 100;
    opacity: 0;
    pointer-events: none;
    transition: all 0.2s ease;
    min-width: 160px;
  }

  .marker-tooltip::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border-width: 5px;
    border-style: solid;
    border-color: #0f172a transparent transparent transparent;
  }

  .map-marker-wrapper:hover .marker-tooltip {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
  }

  .tooltip-header {
    font-weight: 700;
    border-bottom: 1px solid rgba(255, 255, 255, 0.15);
    padding-bottom: 4px;
    margin-bottom: 6px;
    font-size: 0.8rem;
  }

  .tooltip-body {
    display: flex;
    flex-direction: column;
    gap: 3px;
  }

  .tooltip-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 3px;
  }

  .tooltip-row:last-child {
    margin-bottom: 0;
  }

  .tooltip-row span {
    color: #94a3b8;
  }

  /* Legend Styles */
  .map-legend {
    position: absolute;
    bottom: 1.5rem;
    left: 1.5rem;
    background: white;
    border: 1px solid var(--theme-border);
    border-radius: 10px;
    padding: 0.75rem 1rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
    z-index: 5;
  }

  .legend-title {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--theme-text-muted);
    margin-bottom: 0.4rem;
  }

  .legend-items {
    display: flex;
    gap: 1.25rem;
    align-items: center;
  }

  .legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--theme-text-dark);
  }

  .legend-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
  }

  .legend-dot.shortage {
    background-color: #ef4444;
  }

  .legend-dot.tight {
    background-color: #f59e0b;
  }

  .legend-dot.balanced {
    background-color: #10b981;
  }

  /* Leaflet Custom Overrides */
  .custom-leaflet-icon {
    background: transparent !important;
    border: none !important;
  }

  .custom-leaflet-popup .leaflet-popup-content-wrapper {
    background: #0f172a !important;
    color: white !important;
    border-radius: 12px !important;
    padding: 0 !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3) !important;
    border: 1px solid rgba(255, 255, 255, 0.1) !important;
  }

  .custom-leaflet-popup .leaflet-popup-content {
    margin: 0 !important;
    padding: 0 !important;
  }

  .custom-leaflet-popup .leaflet-popup-tip {
    background: #0f172a !important;
  }

  .custom-leaflet-popup .leaflet-popup-close-button {
    display: none !important;
  }

  #density-map {
    z-index: 1;
  }
</style>

<div class="container-fluid">
  <div class="sp-dashboard-wrapper">
    <div class="sp-header">
      <div>
        <h2>🛡️ AI-Service Providers Dashboard</h2>
        <p>Monitor SP scoring, demand-supply capacities, and dispatch matches across regions.</p>
      </div>
      <div>
        <button class="btn btn-outline-primary" onclick="recalculateForecasting()">
          <i class="fas fa-calculator"></i> Run Supply Forecast
        </button>
      </div>
    </div>

    <!-- KPI Grid -->
    <div class="kpi-grid">
      <div class="kpi-card">
        <div class="kpi-icon-container bg-teal-light">
          <i class="fas fa-users-cog"></i>
        </div>
        <div class="kpi-info">
          <h3 id="stat-active-sps"><?= esc($live_supply['active_sps'] ?? 0) ?></h3>
          <p>Active Providers</p>
        </div>
      </div>
      <div class="kpi-card">
        <div class="kpi-icon-container bg-blue-light">
          <i class="fas fa-award"></i>
        </div>
        <div class="kpi-info">
          <h3 id="stat-genuine-sps"><?= esc($live_supply['genuine_sps'] ?? 0) ?></h3>
          <p>Genuine SPs (>70)</p>
        </div>
      </div>
      <div class="kpi-card">
        <div class="kpi-icon-container bg-amber-light">
          <i class="fas fa-route"></i>
        </div>
        <div class="kpi-info">
          <h3 id="stat-daily-jobs"><?= esc($live_demand['daily_bookings'] ?? 0) ?></h3>
          <p>Daily Bookings</p>
        </div>
      </div>
      <div class="kpi-card">
        <div class="kpi-icon-container bg-rose-light">
          <i class="fas fa-user-slash"></i>
        </div>
        <div class="kpi-info">
          <h3 id="stat-drop-off"><?= esc($live_supply['drop_off_rate_percent'] ?? 0) ?>%</h3>
          <p>SP Drop-off Rate</p>
        </div>
      </div>
    </div>

    <!-- Leaflet Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Booking Density Map -->
    <div class="density-map-card">
      <div class="density-map-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
        <div>
          <h4>Booking Density Map</h4>
          <p class="subtitle" id="map-subtitle">Hyderabad · Active SPs vs Demand</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
          <select id="map-state-select" class="form-control-sim" onchange="onStateChange(this.value)" style="padding: 0.5rem 2.25rem 0.5rem 1rem; font-weight: 700; font-size: 0.85rem; min-width: 155px; background-color: #f8fafc; border: 1px solid var(--theme-border); border-radius: 10px; cursor: pointer; color: var(--theme-text-dark); transition: border-color 0.2s;">
            <!-- Populated dynamically -->
          </select>
          <select id="map-city-select" class="form-control-sim" onchange="onCityChange(this.value)" style="padding: 0.5rem 2.25rem 0.5rem 1rem; font-weight: 700; font-size: 0.85rem; min-width: 155px; background-color: #f8fafc; border: 1px solid var(--theme-border); border-radius: 10px; cursor: pointer; color: var(--theme-text-dark); transition: border-color 0.2s;">
            <!-- Populated dynamically -->
          </select>
        </div>
      </div>
      <div class="density-map-container" style="position: relative;">
        <!-- Leaflet Map Container -->
        <div id="density-map" style="height: 480px; width: 100%;"></div>

        <!-- Legend -->
        <div class="map-legend" style="bottom: 1.5rem; left: 1.5rem; position: absolute; z-index: 1000;">
          <div class="legend-title">Demand level</div>
          <div class="legend-items">
            <div class="legend-item"><span class="legend-dot shortage"></span> Shortage</div>
            <div class="legend-item"><span class="legend-dot tight"></span> Tight</div>
            <div class="legend-item"><span class="legend-dot balanced"></span> Balanced</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Alerts Section -->
    <div class="alert-section">
      <h4><i class="fas fa-exclamation-triangle text-danger"></i> Active Live Capacity Warnings</h4>
      <div class="alert-list" id="live-alert-list">
        <?php if (!empty($alerts)) : ?>
          <div class="accordion-container mt-3">
            <?php $cityIndex = 0;
            foreach ($alerts as $city => $categories) :
              $cityIndex++;
              $isOpen = ($cityIndex === 1) ? 'is-open' : ''; ?>

              <div class="city-accordion-card <?= $isOpen ?>" data-city-index="<?= $cityIndex ?>">
                <div class="city-accordion-header" onclick="toggleCityAccordion(this)">
                  <?php
                  $totalCityAlerts = 0;
                  foreach ($categories as $categoryAlerts) {
                    $totalCityAlerts += count($categoryAlerts);
                  }
                  ?>
                  <h5 class="city-accordion-title">
                    <i class="fas fa-city mr-2" style="color: var(--theme-primary); opacity: 0.85;"></i>
                    <span><?= esc($city) ?></span>
                    <span class="warning-count-pill <?= $totalCityAlerts > 0 ? 'has-warnings' : 'clear' ?>">
                      <?= $totalCityAlerts ?> <?= $totalCityAlerts === 1 ? 'Warning' : 'Warnings' ?>
                    </span>
                  </h5>
                  <i class="fas fa-chevron-down city-accordion-icon"></i>
                </div>

                <div class="city-accordion-body">
                  <?php foreach ($categories as $category => $categoryAlerts) : ?>
                    <div class="category-group mb-4">
                      <h6 class="category-group-header"><i class="fas fa-tag"></i> <?= esc($category) ?></h6>

                      <?php foreach ($categoryAlerts as $alert) : ?>
                        <div class="alert-item <?= esc($alert['level']) ?>">
                          <div class="alert-content">
                            <h6 style="margin-bottom: 0.2rem; font-weight: 700; color: var(--theme-text-dark);">
                              <?= esc($alert['title']) ?></h6>
                            <p style="margin-bottom: 0; font-size: 0.85rem; color: var(--theme-text-muted);">
                              <?= esc($alert['message']) ?></p>
                          </div>
                          <span class="badge badge-light shadow-sm"><?= esc($alert['time']) ?></span>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>

            <?php endforeach; ?>
          </div>
        <?php else : ?>
          <p class="text-muted mt-3 p-3 bg-white border rounded shadow-sm">No active warnings.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Grid Layout -->
    <div class="dashboard-grid">
      <!-- Left Side: Table -->
      <div class="table-container">
        <h4>📊 Regional Demand-Supply Gaps</h4>
        <div style="overflow-x: auto;">
          <table class="custom-table">
            <thead>
              <tr>
                <th>Area</th>
                <th>Pincode</th>
                <th>Service Type</th>
                <th>Daily Jobs</th>
                <th>Active SPs</th>
                <th>Genuine SPs</th>
                <th>Required SPs</th>
                <th>Gap</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody id="grid-table-body">
              <?php if (!empty($grid_data)) :
                foreach ($grid_data as $row) : ?>
                  <tr>
                    <td><strong><?= esc($row['area']) ?></strong></td>
                    <td><span class="text-muted"><?= esc($row['pincode']) ?></span></td>
                    <td><?= esc(ucfirst($row['service_type'])) ?></td>
                    <td><?= esc($row['daily_jobs']) ?></td>
                    <td><?= esc($row['active_sp_count']) ?></td>
                    <td><?= esc($row['genuine_sp_count']) ?></td>
                    <td><?= esc($row['required_sp']) ?></td>
                    <td>
                      <span class="text-<?= $row['gap'] > 0 ? 'danger' : 'success' ?> font-weight-bold">
                        <?= $row['gap'] > 0 ? esc($row['gap']) . ' needed' : 'Covered' ?>
                      </span>
                    </td>
                    <td>
                      <span class="badge-status <?= esc(strtolower($row['status'])) ?>">
                        <?= esc($row['status']) ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach;
              else : ?>
                <tr>
                  <td colspan="9" class="text-center text-muted">No regional snapshots found. Trigger supply forecast
                    calculation.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Right Side: Dispatch Simulator -->
      <div class="simulator-container">
        <h4>⚡ Auto-Match Simulator</h4>
        <p class="text-muted" style="font-size: 0.8rem; margin:0;">Mock bookings to visualize matches in real-time.
        </p>

        <div class="form-group">
          <label>Client Pincode</label>
          <select class="form-control-sim" id="sim-pincode" onchange="updateSimCoords()">
            <?php foreach ($simulator_options as $opt) : ?>
              <option value="<?= esc($opt['value']) ?>" data-lat="<?= esc($opt['lat']) ?>"
                data-lng="<?= esc($opt['lng']) ?>">
                <?= esc($opt['label']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <input type="hidden" id="sim-lat" value="<?= esc($simulator_options[0]['lat'] ?? 17.4483) ?>">
          <input type="hidden" id="sim-lng" value="<?= esc($simulator_options[0]['lng'] ?? 78.3741) ?>">
        </div>

        <div class="form-group">
          <label>Dog Conditions (Special Needs)</label>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.8rem;">
            <label><input type="checkbox" class="sim-tag" value="aggressive"> Aggressive</label>
            <label><input type="checkbox" class="sim-tag" value="senior"> Senior</label>
            <label><input type="checkbox" class="sim-tag" value="puppy"> Puppy</label>
            <label><input type="checkbox" class="sim-tag" value="anxious"> Anxious</label>
          </div>
        </div>

        <button class="btn-sim" onclick="triggerAutoMatchSim()">Find Best Providers</button>

        <hr style="border:0; border-top:1px solid var(--theme-border); margin:0.5rem 0;">

        <div id="sim-results"
          style="display:flex; flex-direction:column; gap:0.75rem; max-height:280px; overflow-y:auto;">
          <p class="text-center text-muted" style="font-size:0.85rem;">Click 'Find Best Providers' to view simulation
            results.</p>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  // Map Data from controller
  var mapData = <?= json_encode($density_map) ?>;
  
  var currentState = 'Telangana';
  var currentCity = 'Hyderabad';
  var map;
  var markerLayerGroup;

  function populateDropdowns() {
    var stateSelect = document.getElementById('map-state-select');
    if (!stateSelect) return;

    // Clear
    stateSelect.innerHTML = '';
    
    // Populate states
    var states = Object.keys(mapData).sort();
    
    if (states.length > 0) {
      if (states.includes('Telangana')) {
        currentState = 'Telangana';
      } else {
        currentState = states[0];
      }
    }
    
    states.forEach(function(state) {
      var opt = document.createElement('option');
      opt.value = state;
      opt.innerText = state;
      if (state === currentState) opt.selected = true;
      stateSelect.appendChild(opt);
    });

    populateCitiesForState(currentState);
  }

  function populateCitiesForState(state) {
    var citySelect = document.getElementById('map-city-select');
    if (!citySelect) return;
    citySelect.innerHTML = '';
    
    if (!mapData[state]) return;
    
    var cities = Object.keys(mapData[state]).sort();
    
    if (cities.length > 0) {
      if (state === 'Telangana' && cities.includes('Hyderabad')) {
        currentCity = 'Hyderabad';
      } else {
        currentCity = cities[0];
      }
    }

    cities.forEach(function(city) {
      var opt = document.createElement('option');
      opt.value = city;
      opt.innerText = city;
      if (city === currentCity) opt.selected = true;
      citySelect.appendChild(opt);
    });
  }

  function onStateChange(stateName) {
    currentState = stateName;
    populateCitiesForState(currentState);
    onCityChange(currentCity);
  }

  function onCityChange(cityName) {
    currentCity = cityName;
    
    if (mapData[currentState] && mapData[currentState][currentCity]) {
      var cityInfo = mapData[currentState][currentCity];
      
      // Update subtitle
      document.getElementById('map-subtitle').innerText = currentCity + ' (' + currentState + ') · Active SPs vs Demand';

      var center = cityInfo.center;
      map.flyTo([center.lat, center.lng], center.zoom, {
        animate: true,
        duration: 1.2
      });
      renderCityMarkers(currentState, currentCity);

      // Open corresponding accordion card if it exists
      var accordionCards = document.querySelectorAll('.city-accordion-card');
      accordionCards.forEach(card => {
        var titleSpan = card.querySelector('.city-accordion-title span');
        if (titleSpan && titleSpan.innerText.trim() === currentCity) {
          if (!card.classList.contains('is-open')) {
            accordionCards.forEach(c => c.classList.remove('is-open'));
            card.classList.add('is-open');
          }
        }
      });
    }
  }

  function initMap() {
    var initialCenter = {lat: 17.4300, lng: 78.4000, zoom: 12};
    if (mapData[currentState] && mapData[currentState][currentCity]) {
      initialCenter = mapData[currentState][currentCity].center;
    }
    
    map = L.map('density-map', {
      zoomControl: true,
      scrollWheelZoom: false
    }).setView([initialCenter.lat, initialCenter.lng], initialCenter.zoom);

    // Beautiful clean tiles
    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
      subdomains: 'abcd',
      maxZoom: 20
    }).addTo(map);

    markerLayerGroup = L.layerGroup().addTo(map);
    renderCityMarkers(currentState, currentCity);
  }

  function renderCityMarkers(state, city) {
    markerLayerGroup.clearLayers();
    if (!mapData[state] || !mapData[state][city]) return;
    
    var cityInfo = mapData[state][city];
    if (!cityInfo.areas) return;

    cityInfo.areas.forEach(function(area) {
      var levelClass = area.level.toLowerCase(); // shortage, tight, balanced
      var badgeHtml = area.level === 'Shortage' ? '<div class="marker-alert-badge">!</div>' : '';

      var customHtml = `
        <div class="map-marker-wrapper" style="position:relative;">
          <div class="map-marker-pulse ${levelClass}"></div>
          <div class="map-marker-bubble ${levelClass}">
            <div class="marker-info">
              <span class="marker-name">${area.name}</span>
              <span class="marker-value">${area.demand}</span>
            </div>
            ${badgeHtml}
          </div>
        </div>
      `;

      var icon = L.divIcon({
        html: customHtml,
        className: 'custom-leaflet-icon',
        iconSize: [120, 45],
        iconAnchor: [60, 22]
      });

      var popupContent = `
        <div class="marker-tooltip" style="opacity: 1; pointer-events: auto; position: static; transform: none; min-width: 170px; box-shadow: none; padding: 4px 6px;">
          <div class="tooltip-header" style="color:white; font-weight:700; margin-bottom: 6px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom:4px;">${area.name}${area.pincode ? ' (' + area.pincode + ')' : ''}</div>
          <div class="tooltip-body" style="font-size:0.75rem; display:flex; flex-direction:column; gap:4px;">
            <div class="tooltip-row" style="display:flex; justify-content:space-between;"><span>Demand (30d):</span> <strong style="color:white;">${area.demand} bookings</strong></div>
            <div class="tooltip-row" style="display:flex; justify-content:space-between;"><span>Active SPs:</span> <strong style="color:white;">${area.supply}</strong></div>
            <div class="tooltip-row" style="display:flex; justify-content:space-between;"><span>Genuine SPs:</span> <strong style="color:white;">${area.genuine}</strong></div>
            <div class="tooltip-row" style="display:flex; justify-content:space-between; align-items:center;"><span>Status:</span> <span class="badge-status ${levelClass}" style="padding: 2px 8px; font-size: 0.65rem; border-radius:9999px;">${area.level}</span></div>
          </div>
        </div>
      `;

      var marker = L.marker([area.lat, area.lng], { icon: icon });
      marker.bindPopup(popupContent, {
        closeButton: false,
        className: 'custom-leaflet-popup',
        offset: L.point(0, -10)
      });
      
      // Hover bindings
      marker.on('mouseover', function(e) {
        this.openPopup();
      });
      marker.on('mouseout', function(e) {
        this.closePopup();
      });

      markerLayerGroup.addLayer(marker);
    });
  }

  function switchCity(cityName) {
    for (var stateName in mapData) {
      if (mapData[stateName][cityName]) {
        currentState = stateName;
        currentCity = cityName;
        
        var stateSelect = document.getElementById('map-state-select');
        var citySelect = document.getElementById('map-city-select');
        
        if (stateSelect && stateSelect.value !== currentState) {
          stateSelect.value = currentState;
          populateCitiesForState(currentState);
        }
        if (citySelect && citySelect.value !== currentCity) {
          citySelect.value = currentCity;
        }

        onCityChange(currentCity);
        break;
      }
    }
  }

  // Custom Accordion Toggle Logic
  function toggleCityAccordion(headerElement) {
    const card = headerElement.closest('.city-accordion-card');

    // Close other open cards
    const allCards = document.querySelectorAll('.city-accordion-card');
    allCards.forEach(c => {
      if (c !== card && c.classList.contains('is-open')) {
        c.classList.remove('is-open');
      }
    });

    // Toggle current card
    card.classList.toggle('is-open');

    // Change map view to matches this city
    const cityNameElement = headerElement.querySelector('span');
    if (cityNameElement) {
      const cityName = cityNameElement.innerText.trim();
      switchCity(cityName);
    }
  }

  function updateSimCoords() {
    const select = document.getElementById('sim-pincode');
    const selectedOption = select.options[select.selectedIndex];
    document.getElementById('sim-lat').value = selectedOption.getAttribute('data-lat');
    document.getElementById('sim-lng').value = selectedOption.getAttribute('data-lng');
  }

  function triggerAutoMatchSim() {
    const lat = document.getElementById('sim-lat').value;
    const lng = document.getElementById('sim-lng').value;
    const tagElements = document.querySelectorAll('.sim-tag:checked');
    const tags = Array.from(tagElements).map(el => el.value);

    const resultsContainer = document.getElementById('sim-results');
    resultsContainer.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Running dispatch engine...</div>';

    $.ajax({
      url: '<?= base_url() ?>sp-management/simulate-match',
      type: 'POST',
      data: {
        latitude: lat,
        longitude: lng,
        service_id: 4,
        pet_tags: tags
      },
      success: function(response) {
        if (response.status === 'success') {
          if (response.data.length === 0) {
            resultsContainer.innerHTML = '<p class="text-center text-muted" style="font-size:0.85rem;">No active providers found in this Geohash neighborhood.</p>';
            return;
          }

          let html = '';
          response.data.forEach((item, index) => {
            const levelClass = item.match_rating >= 80 ? 'healthy' : (item.match_rating >= 50 ? 'warning' : 'critical');
            const reasonsHtml = item.reasons.map(r => `
              <div style="display:flex; align-items:center; gap:6px; margin-bottom:4px; font-size:0.75rem; color:#475569;">
                <i class="fas fa-check" style="color:var(--theme-primary); font-size:9px;"></i>
                <span>${r}</span>
              </div>
            `).join('');

            html += `
              <div style="border: 1px solid var(--theme-border); border-radius: 12px; padding: 1rem; background: #ffffff; box-shadow: 0 4px 12px rgba(0,0,0,0.01); transition: transform 0.2s;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.6rem;">
                  <strong style="color:var(--theme-text-dark); font-size:0.88rem;">#${index + 1} ${item.name}</strong>
                  <span class="badge-status ${levelClass}" style="font-size:0.7rem; padding: 3px 8px;">${item.match_rating}% Fit</span>
                </div>
                <div style="font-size:0.78rem; color:var(--theme-text-muted); display:flex; justify-content:space-between; margin-bottom:0.6rem; font-weight: 500;">
                  <span><i class="fas fa-map-marker-alt" style="color:var(--theme-primary); margin-right:4px;"></i>${item.distance_km} km away</span>
                  <span><i class="fas fa-shield-alt" style="color:var(--theme-primary); margin-right:4px;"></i>Score: ${item.genuine_score}/100</span>
                </div>
                <div style="border-top:1px solid #f1f5f9; padding-top:0.6rem;">
                  ${reasonsHtml}
                </div>
              </div>
            `;
          });
          resultsContainer.innerHTML = html;
        }
      },
      error: function() {
        resultsContainer.innerHTML = '<p class="text-center text-danger" style="font-size:0.85rem;">Error executing simulation request.</p>';
      }
    });
  }

  function recalculateForecasting() {
    if (confirm('Trigger live cron evaluation of regional SP demands?')) {
      alert('Cron job triggered in background. Re-fetching fresh summaries...');
      location.reload();
    }
  }

  // Initialize DataTables and Leaflet Map
  $(document).ready(function() {
    // Populate dropdown options
    populateDropdowns();

    // Init Leaflet Map
    initMap();

    // Init DataTables
    if (typeof $.fn.DataTable === 'undefined') {
      $('head').append('<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" type="text/css" />');
      $.getScript("https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js", function() {
        $.getScript("https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js", function() {
          $('.custom-table').DataTable({
            pageLength: 5,
            lengthMenu: [
              [5, 10, 25, 50, -1],
              [5, 10, 25, 50, "All"]
            ],
            language: {
              search: "_INPUT_",
              searchPlaceholder: "Search areas, pincodes..."
            }
          });
        });
      });
    } else {
      $('.custom-table').DataTable({
        pageLength: 5,
        lengthMenu: [
          [5, 10, 25, 50, -1],
          [5, 10, 25, 50, "All"]
        ],
        language: {
          search: "_INPUT_",
          searchPlaceholder: "Search areas, pincodes..."
        }
      });
    }
  });
</script>

<?php echo $this->endSection(); ?>