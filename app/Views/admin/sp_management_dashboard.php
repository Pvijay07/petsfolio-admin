<<<<<<< HEAD
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

=======
<?php echo $this->extend('admin/includes/header'); ?>
<?php echo $this->section('content'); ?>
<?php
/** @var array $city_demand_supply */
$city_demand_supply = $city_demand_supply ?? [];
$google_maps_key = $google_maps_key ?? 'AIzaSyAK-fLJRaxrzL1S6_2TuM9BARw9Ehb2rP8';
?>

<!-- Google Font: Plus Jakarta Sans for modern typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Google Maps JavaScript API (Primary Real Map) -->
<script src="https://maps.googleapis.com/maps/api/js?key=<?= esc($google_maps_key) ?>&callback=onGoogleMapsApiLoaded" async defer></script>

<!-- Leaflet Interactive Map Library (Free Maps / OpenStreetMap Fallback) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
  :root {
    --sp-font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    --sp-bg: #f8fafc;
    --sp-card-bg: #ffffff;
    --sp-border: #e2e8f0;
    --sp-border-subtle: #f1f5f9;
    --sp-text-main: #0f172a;
    --sp-text-muted: #64748b;
    --sp-text-light: #94a3b8;
    --sp-primary-purple: #7c3aed;
    --sp-primary-purple-hover: #6d28d9;
    --sp-red: #ef4444;
    --sp-orange: #f59e0b;
    --sp-green: #10b981;
    --sp-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
    --sp-shadow-md: 0 4px 15px -2px rgba(15, 23, 42, 0.06);
    --sp-shadow-lg: 0 10px 25px -4px rgba(15, 23, 42, 0.08);
  }

  .sp-container {
    font-family: var(--sp-font);
    background: var(--sp-bg);
    color: var(--sp-text-main);
    padding: 1.5rem 1.75rem 3rem 1.75rem;
    min-height: 100vh;
  }

  /* Header Section */
  .sp-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.25rem;
    margin-bottom: 1.75rem;
  }

  .sp-header-title {
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--sp-text-main);
    margin: 0;
    letter-spacing: -0.025em;
  }

  .sp-header-subtitle {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--sp-text-muted);
    margin: 0.25rem 0 0 0;
  }

  .sp-header-controls {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.65rem;
  }

  .sp-search-wrapper {
    position: relative;
    min-width: 260px;
  }

  .sp-search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--sp-text-light);
    font-size: 0.85rem;
    pointer-events: none;
  }

  .sp-search-input {
    width: 100%;
    padding: 0.5rem 0.85rem 0.5rem 2.25rem;
    font-size: 0.82rem;
    font-weight: 500;
    color: var(--sp-text-main);
    background: #ffffff;
    border: 1px solid var(--sp-border);
    border-radius: 10px;
    outline: none;
    transition: all 0.2s ease;
  }

  .sp-search-input:focus {
    border-color: var(--sp-primary-purple);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
  }

  .sp-select {
    padding: 0.5rem 2rem 0.5rem 0.85rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    background-color: #ffffff;
    border: 1px solid var(--sp-border);
    border-radius: 10px;
    cursor: pointer;
    outline: none;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.65rem center;
    background-size: 0.9rem;
    transition: all 0.2s ease;
  }

  .sp-select:focus {
    border-color: var(--sp-primary-purple);
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
  }

  .sp-btn-date {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.85rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: #334155;
    background: #ffffff;
    border: 1px solid var(--sp-border);
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .sp-btn-date:hover {
    background: #f8fafc;
  }

  .sp-btn-export {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1.15rem;
    font-size: 0.82rem;
    font-weight: 600;
    color: #ffffff;
    background: var(--sp-primary-purple);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
  }

  .sp-btn-export:hover {
    background: var(--sp-primary-purple-hover);
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.25);
  }

  /* Card Layouts */
  .sp-card {
    background: var(--sp-card-bg);
    border: 1px solid var(--sp-border);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: var(--sp-shadow);
  }

  .sp-card-header {
    margin-bottom: 1.25rem;
  }

  .sp-card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--sp-text-main);
    margin: 0;
  }

  .sp-card-subtitle {
    font-size: 0.8rem;
    color: var(--sp-text-muted);
    margin: 0.2rem 0 0 0;
  }

  /* Real Map Container (Google Maps / Leaflet) */
  .map-stage-container {
    position: relative;
    width: 100%;
    height: 420px;
    border-radius: 14px;
    overflow: hidden;
    background: #f8fafc;
    border: 1px solid var(--sp-border);
  }

  #spMapContainer {
    width: 100%;
    height: 100%;
    border-radius: 12px;
    z-index: 1;
    background: #f8fafc;
  }

  /* Map Provider Switcher Controls */
  .map-provider-toggle {
    display: inline-flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 9px;
    gap: 4px;
    border: 1px solid var(--sp-border);
  }

  .btn-provider {
    border: none;
    background: transparent;
    padding: 4px 11px;
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--sp-text-muted);
    border-radius: 7px;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
  }

  .btn-provider:hover {
    color: var(--sp-text-main);
  }

  .btn-provider.active {
    background: #ffffff;
    color: var(--sp-primary-purple);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
  }

  /* Custom Pin Overlays (Both Google Maps & Leaflet) */
  .custom-real-pin,
  .custom-gmap-pin {
    background: transparent !important;
    border: none !important;
  }

  .custom-gmap-pin {
    position: absolute;
    cursor: pointer;
    z-index: 50;
  }

  .real-pin-inner {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    cursor: pointer;
  }

  .real-pin-halo {
    position: absolute;
    top: -4px;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    opacity: 0.25;
    animation: pin-halo-pulse 2.2s infinite ease-in-out;
    pointer-events: none;
  }

  @keyframes pin-halo-pulse {

    0%,
    100% {
      transform: scale(1);
      opacity: 0.2;
    }

    50% {
      transform: scale(1.35);
      opacity: 0.45;
    }
  }

  .real-pin-inner.shortage .real-pin-halo {
    background: #ef4444;
  }

  .real-pin-inner.tight .real-pin-halo {
    background: #f59e0b;
  }

  .real-pin-inner.balanced .real-pin-halo {
    background: #10b981;
  }

  .real-pin-bubble {
    position: relative;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 800;
    font-size: 0.78rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    border: 2.5px solid #ffffff;
    transition: transform 0.18s ease, box-shadow 0.18s ease;
  }

  .real-pin-inner.shortage .real-pin-bubble {
    background: #ef4444;
  }

  .real-pin-inner.tight .real-pin-bubble {
    background: #f59e0b;
  }

  .real-pin-inner.balanced .real-pin-bubble {
    background: #10b981;
  }

  .real-pin-inner:hover .real-pin-bubble,
  .real-pin-inner.is-active .real-pin-bubble {
    transform: scale(1.22);
    box-shadow: 0 6px 18px rgba(124, 58, 237, 0.45);
    border-color: #ffffff;
  }

  .real-pin-alert {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    background: #ffffff;
    border: 1.5px solid #ef4444;
    color: #ef4444;
    font-size: 0.62rem;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .real-pin-label {
    margin-top: 4px;
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(4px);
    color: #ffffff;
    font-size: 0.68rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 6px;
    white-space: nowrap;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
    pointer-events: none;
  }

  /* Leaflet Popups */
  .leaflet-popup-content-wrapper {
    background: #0f172a !important;
    color: #ffffff !important;
    border-radius: 12px !important;
    padding: 4px 6px !important;
    box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.4) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
  }

  .leaflet-popup-tip {
    background: #0f172a !important;
  }

  .leaflet-popup-content {
    margin: 8px 10px !important;
    line-height: 1.4 !important;
  }

  /* Google Maps InfoWindow Modern Dark Theme */
  .gm-style .gm-style-iw-c {
    background-color: #0f172a !important;
    color: #ffffff !important;
    border-radius: 12px !important;
    padding: 8px 12px !important;
    box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.45) !important;
    border: 1px solid rgba(255, 255, 255, 0.12) !important;
  }

  .gm-style .gm-style-iw-d {
    overflow: hidden !important;
    color: #ffffff !important;
  }

  .gm-style .gm-style-iw-tc::after {
    background: #0f172a !important;
  }

  .gm-ui-hover-effect {
    filter: invert(1);
    opacity: 0.8;
  }

  .map-legend {
    position: absolute;
    bottom: 16px;
    left: 18px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    border: 1px solid var(--sp-border);
    border-radius: 10px;
    padding: 6px 14px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    z-index: 1000;
  }

  .legend-title {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--sp-text-muted);
    text-transform: capitalize;
  }

  .legend-item {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.74rem;
    font-weight: 600;
    color: #475569;
  }

  .legend-dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
  }

  .legend-dot.shortage {
    background-color: var(--sp-red);
  }

  .legend-dot.tight {
    background-color: var(--sp-orange);
  }

  .legend-dot.balanced {
    background-color: var(--sp-green);
  }

  /* Map Floating Tooltip */
  .node-tooltip {
    position: absolute;
    display: none;
    background: #0f172a;
    color: #ffffff;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.75rem;
    pointer-events: none !important;
    z-index: 1000;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
    line-height: 1.4;
    white-space: nowrap;
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  /* Right Side: AI Recommendation Card */
  .ai-recommendation-card {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #db2777 100%);
    border-radius: 16px;
    padding: 1.6rem 1.6rem 1.75rem 1.6rem;
    color: #ffffff;
    box-shadow: 0 10px 25px -4px rgba(124, 58, 237, 0.35);
    margin-bottom: 1.25rem;
    position: relative;
    overflow: hidden;
  }

  .ai-recommendation-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0) 70%);
    border-radius: 50%;
    pointer-events: none;
  }

  .ai-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    color: #ffffff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 11px;
    border-radius: 20px;
    margin-bottom: 0.85rem;
    text-transform: capitalize;
  }

  .ai-headline {
    font-size: 1.18rem;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.35;
    margin: 0 0 0.5rem 0;
    letter-spacing: -0.015em;
  }

  .ai-subtext {
    font-size: 0.82rem;
    color: rgba(255, 255, 255, 0.88);
    line-height: 1.45;
    margin: 0;
    font-weight: 400;
  }

  /* Right Side: SP Shortage Areas Card */
  .shortage-list {
    display: flex;
    flex-direction: column;
  }

  .shortage-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.85rem 0;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease;
  }

  .shortage-item:last-child {
    border-bottom: none;
    padding-bottom: 0.2rem;
  }

  .shortage-left {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .shortage-icon {
    font-size: 0.95rem;
    width: 20px;
    text-align: center;
  }

  .shortage-name {
    font-size: 0.86rem;
    font-weight: 600;
    color: #1e293b;
  }

  .shortage-right {
    text-align: right;
  }

  .shortage-gap-num {
    font-size: 0.95rem;
    font-weight: 800;
    color: var(--sp-red);
    line-height: 1.1;
  }

  .shortage-gap-sub {
    font-size: 0.68rem;
    font-weight: 600;
    color: var(--sp-text-light);
    display: block;
  }

  /* High Demand Zones Table Card */
  .table-card {
    margin-top: 1.5rem;
    padding: 1.6rem 1.75rem;
  }

  .sp-table-wrapper {
    overflow-x: auto;
  }

  .sp-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
  }

  .sp-table thead,
  .sp-table thead tr,
  .sp-table thead th,
  .sp-table th {
    background-color: #f1f5f9 !important;
    background: #f1f5f9 !important;
    color: #0f172a !important;
    box-shadow: none !important;
    -webkit-box-shadow: none !important;
    font-size: 0.76rem !important;
    font-weight: 800 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.05em !important;
    padding: 0.95rem 1.15rem !important;
    border-top: none !important;
    border-bottom: 2px solid #cbd5e1 !important;
    border-left: none !important;
    border-right: none !important;
    vertical-align: middle !important;
  }

  .sp-table td {
    padding: 1rem 1rem;
    font-size: 0.84rem;
    color: #334155;
    border-bottom: 1px solid #f8fafc;
    vertical-align: middle;
    transition: background 0.15s ease;
  }

  .sp-table tr:hover td {
    background-color: #f8fafc;
  }

  .sp-table tr.highlighted td {
    background-color: #f1f5f9;
  }

  .zone-cell {
    font-weight: 700;
    color: #0f172a;
  }

  .coverage-bar-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
    min-width: 140px;
    max-width: 200px;
  }

  .coverage-track {
    flex: 1;
    height: 6px;
    background: #e2e8f0;
    border-radius: 9999px;
    overflow: hidden;
    position: relative;
  }

  .coverage-fill {
    height: 100%;
    border-radius: 9999px;
    transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  }

  .coverage-fill.shortage {
    background-color: var(--sp-red);
  }

  .coverage-fill.tight {
    background-color: var(--sp-orange);
  }

  .coverage-fill.balanced {
    background-color: var(--sp-green);
  }

  .coverage-pct {
    font-size: 0.75rem;
    font-weight: 700;
    color: #475569;
    min-width: 38px;
  }

  .gap-badge {
    font-weight: 800;
    font-size: 0.85rem;
  }

  .gap-badge.negative {
    color: var(--sp-red);
  }

  .gap-badge.positive {
    color: var(--sp-green);
  }

  .status-tag {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 6px;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: lowercase;
  }

  .status-tag.shortage {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fee2e2;
  }

  .status-tag.tight {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fef3c7;
  }

  .status-tag.balanced {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #d1fae5;
  }
</style>

<div class="sp-container">
  <!-- Top Navigation / Page Header -->
  <div class="sp-header">
    <div>
      <h1 class="sp-header-title">City Demand vs Supply</h1>
      <p class="sp-header-subtitle">Geo-distribution of demand and SP availability</p>
    </div>

    <div class="sp-header-controls">
      <!-- Search Input -->
      <div class="sp-search-wrapper">
        <i class="fas fa-search sp-search-icon"></i>
        <input type="text" id="spSearchInput" class="sp-search-input" placeholder="Search zones, areas, SPs..." oninput="filterDashboardZones(this.value)">
      </div>

      <!-- City Selector -->
      <select id="spCitySelect" class="sp-select" onchange="changeCityFilter(this.value)">
        <?php foreach (($city_demand_supply['available_cities'] ?? ['Hyderabad', 'Bengaluru', 'Mumbai', 'Secunderabad']) as $cOpt): ?>
          <option value="<?= esc($cOpt) ?>" <?= (($city_demand_supply['selected_city'] ?? 'Hyderabad') === $cOpt) ? 'selected' : '' ?>><?= esc($cOpt) ?></option>
        <?php endforeach; ?>
        <option value="All Cities" <?= (($city_demand_supply['selected_city'] ?? '') === 'All Cities') ? 'selected' : '' ?>>All Cities</option>
      </select>

      <!-- Service Selector -->
      <select id="spServiceSelect" class="sp-select" onchange="changeServiceFilter(this.value)">
        <option value="All Services" <?= (($city_demand_supply['selected_service'] ?? 'All Services') === 'All Services') ? 'selected' : '' ?>>All Services</option>
        <option value="Walking" <?= (($city_demand_supply['selected_service'] ?? '') === 'Walking') ? 'selected' : '' ?>>Walking</option>
        <option value="Grooming" <?= (($city_demand_supply['selected_service'] ?? '') === 'Grooming') ? 'selected' : '' ?>>Grooming</option>
        <option value="Training" <?= (($city_demand_supply['selected_service'] ?? '') === 'Training') ? 'selected' : '' ?>>Training</option>
        <option value="Sitting" <?= (($city_demand_supply['selected_service'] ?? '') === 'Sitting') ? 'selected' : '' ?>>Sitting</option>
      </select>

      <!-- Date Range Selector -->
      <select id="spDateSelect" class="sp-select" onchange="changeDateFilter(this.value)">
        <option value="30" <?= (($city_demand_supply['date_days'] ?? 30) == 30) ? 'selected' : '' ?>>Last 30 days</option>
        <option value="90" <?= (($city_demand_supply['date_days'] ?? 30) == 90) ? 'selected' : '' ?>>Last 90 days</option>
        <option value="180" <?= (($city_demand_supply['date_days'] ?? 30) == 180) ? 'selected' : '' ?>>Last 6 months</option>
        <option value="0" <?= (($city_demand_supply['date_days'] ?? 30) == 0) ? 'selected' : '' ?>>All Time</option>
      </select>

      <!-- Export Button -->
      <button class="sp-btn-export" onclick="exportDemandSupplyCSV()">
        <i class="fas fa-download"></i>
        <span>Export CSV</span>
      </button>
    </div>
  </div>

  <!-- Primary 2-Column Grid -->
  <div class="row g-4">
    <!-- Left Column: Booking Density Map (col-lg-8) -->
    <div class="col-lg-8">
      <div class="sp-card h-100" id="mapCardContainer">
        <div class="sp-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
          <div>
            <h3 class="sp-card-title">Booking Density Map</h3>
            <p class="sp-card-subtitle" id="mapSubtitle"><?= esc($city_demand_supply['subtitle'] ?? 'Hyderabad - Active SPs vs Demand') ?></p>
          </div>
          <div class="d-flex align-items-center gap-2">
            <!-- Map Provider Switcher -->
            <div class="map-provider-toggle">
              <button type="button" class="btn-provider active" id="btnGoogleMap" onclick="switchMapProvider('google')">
                <i class="fab fa-google"></i> Google Maps
              </button>
              <button type="button" class="btn-provider" id="btnFreeMap" onclick="switchMapProvider('free')">
                <i class="fas fa-map-marked-alt"></i> Free Maps
              </button>
            </div>
            <div id="mapLoadingIndicator" style="display: none; font-size: 0.8rem; color: var(--sp-primary-purple);">
              <i class="fas fa-circle-notch fa-spin"></i> Updating...
            </div>
          </div>
        </div>

        <div class="map-stage-container" id="mapStage">
          <!-- Real Interactive Geographic Map Container (Google Maps / Leaflet) -->
          <div id="spMapContainer" style="width: 100%; height: 100%;"></div>

          <!-- Bottom Left Map Legend -->
          <div class="map-legend">
            <span class="legend-title">Demand Level</span>
            <div class="legend-item">
              <span class="legend-dot shortage"></span>
              <span>Shortage</span>
            </div>
            <div class="legend-item">
              <span class="legend-dot tight"></span>
              <span>Tight</span>
            </div>
            <div class="legend-item">
              <span class="legend-dot balanced"></span>
              <span>Balanced</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Column: AI Recommendation & SP Shortage Areas (col-lg-4) -->
    <div class="col-lg-4">
      <!-- Card 1: AI Recommendation (Clean, No recruitment button) -->
      <div class="ai-recommendation-card">
        <div class="ai-badge">
          <i class="fas fa-sparkles"></i>
          <span>✦ AI Recommendation</span>
        </div>
        <h4 class="ai-headline" id="aiHeadline"><?= esc($city_demand_supply['recommendation']['headline'] ?? 'Live Coverage Analysis') ?></h4>
        <p class="ai-subtext" id="aiSubtext"><?= esc($city_demand_supply['recommendation']['subtext'] ?? 'Analyzing active supply and booking density across active operational zones.') ?></p>
        <!-- Notice: "Trigger Recruitment Drive" button has been completely removed as requested -->
      </div>

      <!-- Card 2: SP Shortage Areas -->
      <div class="sp-card">
        <div class="sp-card-header">
          <h3 class="sp-card-title">SP Shortage Areas</h3>
          <p class="sp-card-subtitle">Immediate hiring needed</p>
        </div>

        <div class="shortage-list" id="shortageListContainer">
          <?php if (empty($city_demand_supply['shortage_areas'])): ?>
            <div class="text-muted text-center py-3" style="font-size: 0.8rem;">No shortage areas found for this filter</div>
          <?php else: ?>
            <?php foreach ($city_demand_supply['shortage_areas'] as $shortage): ?>
              <?php
              $isCritical = abs($shortage['gap']) >= 10;
              $pinColor = $isCritical ? 'text-danger' : 'text-warning';
              ?>
              <div class="shortage-item" style="cursor: pointer;" onclick="panToZone('<?= esc($shortage['zone']) ?>')">
                <div class="shortage-left">
                  <i class="fas fa-map-marker-alt <?= $pinColor ?> shortage-icon"></i>
                  <span class="shortage-name"><?= esc($shortage['zone']) ?></span>
                </div>
                <div class="shortage-right">
                  <span class="shortage-gap-num"><?= esc($shortage['gap']) ?></span>
                  <span class="shortage-gap-sub">SP gap</span>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom Row: High Demand Zones Table Card (col-12) -->
  <div class="row mt-2">
    <div class="col-12">
      <div class="sp-card table-card">
        <div class="sp-card-header">
          <h3 class="sp-card-title">High Demand Zones</h3>
          <p class="sp-card-subtitle">City-wise demand vs SP supply</p>
        </div>

        <div class="sp-table-wrapper">
          <table class="sp-table" id="zonesTable">
            <thead>
              <tr style="background-color: #f1f5f9 !important; box-shadow: none !important;">
                <th style="width: 22%; background: #f1f5f9 !important; color: #0f172a !important; font-weight: 800 !important; box-shadow: none !important;">Zone</th>
                <th style="width: 14%; background: #f1f5f9 !important; color: #0f172a !important; font-weight: 800 !important; box-shadow: none !important;">Demand</th>
                <th style="width: 14%; background: #f1f5f9 !important; color: #0f172a !important; font-weight: 800 !important; box-shadow: none !important;">SP Supply</th>
                <th style="width: 26%; background: #f1f5f9 !important; color: #0f172a !important; font-weight: 800 !important; box-shadow: none !important;">Coverage</th>
                <th style="width: 12%; background: #f1f5f9 !important; color: #0f172a !important; font-weight: 800 !important; box-shadow: none !important;">Gap</th>
                <th style="width: 12%; background: #f1f5f9 !important; color: #0f172a !important; font-weight: 800 !important; box-shadow: none !important;">Status</th>
              </tr>
            </thead>
            <tbody id="zonesTableBody">
              <?php if (empty($city_demand_supply['zones'])): ?>
                <tr>
                  <td colspan="6" class="text-center py-4 text-muted" style="font-size:0.85rem;">No matching zones found</td>
                </tr>
              <?php else: ?>
                <?php foreach ($city_demand_supply['zones'] as $row): ?>
                  <?php
                  $isNegative = $row['gap'] < 0;
                  $gapDisplay = $isNegative ? $row['gap'] : ('+' . $row['gap']);
                  $gapClass = $isNegative ? 'negative' : 'positive';
                  $statusClass = strtolower($row['status']);
                  $coverageFillWidth = min($row['coverage'], 100);
                  ?>
                  <tr data-zone-name="<?= esc($row['zone']) ?>" style="cursor: pointer;" onclick="panToZone('<?= esc($row['zone']) ?>')">
                    <td class="zone-cell"><?= esc($row['zone']) ?></td>
                    <td class="fw-semibold"><?= (int)$row['demand'] ?></td>
                    <td class="fw-semibold"><?= (int)$row['supply'] ?></td>
                    <td>
                      <div class="coverage-bar-wrapper">
                        <div class="coverage-track">
                          <div class="coverage-fill <?= $statusClass ?>" style="width: <?= $coverageFillWidth ?>%;"></div>
                        </div>
                        <span class="coverage-pct"><?= (int)$row['coverage'] ?>%</span>
                      </div>
                    </td>
                    <td>
                      <span class="gap-badge <?= $gapClass ?>"><?= $gapDisplay ?></span>
                    </td>
                    <td>
                      <span class="status-tag <?= $statusClass ?>"><?= esc($row['status']) ?></span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Initial real dynamic data injected straight from PHP controller
  let currentCityData = <?= json_encode($city_demand_supply, JSON_UNESCAPED_SLASHES) ?>;

  let currentCity = '<?= esc($city_demand_supply['selected_city'] ?? 'Hyderabad') ?>';
  let currentService = '<?= esc($city_demand_supply['selected_service'] ?? 'All Services') ?>';
  let currentDays = <?= (int)($city_demand_supply['date_days'] ?? 30) ?>;
  let currentSearchQuery = '';

  // Dual Map Engine Support: Google Maps & Free Maps (Leaflet / OSM)
  let currentMapProvider = 'google'; // 'google' | 'free'
  let googleMap = null;
  let googleInfoWindow = null;
  let googleOverlays = [];
  let googleMapLoaded = false;

  let leafletMap = null;
  let leafletMarkersLayer = null;

  // Custom Google Maps Pin Overlay Class
  class CustomGMapPinOverlay {
    constructor(item, map, onHover, onClick) {
      this.item = item;
      this.map = map;
      this.onHover = onHover;
      this.onClick = onClick;
      this.div = null;
      this.overlayView = null;
      this.initOverlay();
    }

    initOverlay() {
      if (typeof google === 'undefined' || !google.maps || !google.maps.OverlayView) return;
      const self = this;
      this.overlayView = new google.maps.OverlayView();

      this.overlayView.onAdd = function() {
        self.div = document.createElement('div');
        self.div.className = 'custom-gmap-pin';
        self.div.setAttribute('data-zone', self.item.zone);

        const isShortage = self.item.status === 'shortage';
        const isTight = self.item.status === 'tight';
        const statusClass = isShortage ? 'shortage' : (isTight ? 'tight' : 'balanced');
        const alertHtml = isShortage ? '<span class="real-pin-alert">!</span>' : '';

        self.div.innerHTML = `
          <div class="real-pin-inner ${statusClass}">
            <div class="real-pin-halo"></div>
            <div class="real-pin-bubble">
              <span>${self.item.demand}</span>
              ${alertHtml}
            </div>
            <div class="real-pin-label">${escapeHtml(self.item.zone)}</div>
          </div>
        `;

        self.div.addEventListener('mouseenter', () => {
          if (self.onHover) self.onHover(self.item, self.div, true);
        });
        self.div.addEventListener('mouseleave', () => {
          if (self.onHover) self.onHover(self.item, self.div, false);
        });
        self.div.addEventListener('click', (e) => {
          e.stopPropagation();
          if (self.onClick) self.onClick(self.item, self.div);
        });

        const panes = this.getPanes();
        if (panes && panes.overlayMouseTarget) {
          panes.overlayMouseTarget.appendChild(self.div);
        }
      };

      this.overlayView.draw = function() {
        if (!self.div) return;
        const projection = this.getProjection();
        if (!projection) return;
        const latLng = new google.maps.LatLng(self.item.lat, self.item.lng);
        const pos = projection.fromLatLngToDivPixel(latLng);
        if (pos) {
          self.div.style.left = (pos.x - 40) + 'px';
          self.div.style.top = (pos.y - 20) + 'px';
        }
      };

      this.overlayView.onRemove = function() {
        if (self.div && self.div.parentNode) {
          self.div.parentNode.removeChild(self.div);
          self.div = null;
        }
      };

      this.overlayView.setMap(this.map);
    }

    setMap(map) {
      if (this.overlayView) {
        this.overlayView.setMap(map);
      }
    }
  }

  // Google Maps Callback handler
  window.onGoogleMapsApiLoaded = function() {
    googleMapLoaded = true;
    if (currentMapProvider === 'google') {
      initGoogleMap();
    }
  };

  // Google Maps Authentication error handler (seamlessly falls back to free maps)
  window.gm_authFailure = function() {
    console.warn('Google Maps API key authorization restricted. Auto-switching to Free Maps (OSM).');
    switchMapProvider('free', true);
  };

  document.addEventListener('DOMContentLoaded', function() {
    // If google maps is already ready in browser cache
    if (typeof google !== 'undefined' && google.maps) {
      googleMapLoaded = true;
      initGoogleMap();
    } else {
      // Safety timeout: if Google script has not loaded within 3s, init Free Map as fallback
      setTimeout(function() {
        if (!googleMap && currentMapProvider === 'google' && (!window.google || !window.google.maps)) {
          console.info('Google Maps script did not load in time. Initializing Free Maps view.');
          switchMapProvider('free');
        }
      }, 3000);
    }
  });

  // Switch between Google Maps and Free Maps
  function switchMapProvider(provider, isForcedFallback = false) {
    currentMapProvider = provider;

    const btnG = document.getElementById('btnGoogleMap');
    const btnF = document.getElementById('btnFreeMap');

    if (provider === 'google') {
      if (btnG) btnG.classList.add('active');
      if (btnF) btnF.classList.remove('active');
      if (typeof google !== 'undefined' && google.maps) {
        initGoogleMap();
      } else {
        if (googleMapLoaded) initGoogleMap();
      }
    } else {
      if (btnF) btnF.classList.add('active');
      if (btnG) {
        btnG.classList.remove('active');
        if (isForcedFallback) {
          btnG.title = 'Google Maps API auth restricted. Using Free Maps.';
          btnG.style.opacity = '0.5';
        }
      }
      initFreeMap();
    }
  }

  // 1. Google Maps Initialization
  function initGoogleMap() {
    const mapEl = document.getElementById('spMapContainer');
    if (!mapEl || typeof google === 'undefined' || !google.maps) return;

    // Destroy Leaflet if active
    destroyLeafletMap();
    mapEl.innerHTML = '';

    const center = (currentCityData && currentCityData.city_center) ?
      currentCityData.city_center : {
        lat: 17.3850,
        lng: 78.4867,
        zoom: 11
      };

    googleMap = new google.maps.Map(mapEl, {
      center: {
        lat: center.lat,
        lng: center.lng
      },
      zoom: center.zoom || 11,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      mapTypeControl: true,
      mapTypeControlOptions: {
        position: google.maps.ControlPosition.TOP_RIGHT
      },
      streetViewControl: true,
      fullscreenControl: true,
      zoomControl: true,
      styles: [{
          featureType: 'poi.business',
          stylers: [{
            visibility: 'off'
          }]
        },
        {
          featureType: 'transit',
          elementType: 'labels.icon',
          stylers: [{
            visibility: 'off'
          }]
        }
      ]
    });

    googleInfoWindow = new google.maps.InfoWindow();

    renderGoogleMapPins(currentCityData.markers || currentCityData.zones || []);
  }

  function renderGoogleMapPins(markers) {
    if (!googleMap || typeof google === 'undefined' || !google.maps) return;

    // Clear previous overlays
    googleOverlays.forEach(ov => ov.setMap(null));
    googleOverlays = [];

    if (!markers || markers.length === 0) return;

    const bounds = new google.maps.LatLngBounds();
    let validPins = 0;

    markers.forEach(item => {
      if (!item.lat || !item.lng) return;

      const latLng = new google.maps.LatLng(item.lat, item.lng);
      bounds.extend(latLng);
      validPins++;

      const overlay = new CustomGMapPinOverlay(
        item,
        googleMap,
        (data, div, isEnter) => {
          highlightTableRow(data.zone, isEnter);
          if (isEnter) {
            openGMapInfoWindow(data);
          }
        },
        (data) => {
          highlightTableRow(data.zone, true);
          openGMapInfoWindow(data);
        }
      );

      googleOverlays.push(overlay);
    });

    if (validPins > 1) {
      googleMap.fitBounds(bounds, {
        top: 45,
        right: 45,
        bottom: 45,
        left: 45
      });
    } else if (validPins === 1) {
      googleMap.setCenter(bounds.getCenter());
      googleMap.setZoom(12);
    }
  }

  function openGMapInfoWindow(item) {
    if (!googleMap || !googleInfoWindow) return;
    const gapDisplay = item.gap < 0 ? item.gap : `+${item.gap}`;
    const gapColor = item.gap < 0 ? '#ef4444' : '#10b981';

    const content = `
      <div style="font-family: var(--sp-font); min-width: 175px; padding: 2px;">
        <div style="font-weight: 800; font-size: 0.95rem; color: #ffffff; margin-bottom: 2px;">${escapeHtml(item.zone)}</div>
        <div style="font-size: 0.74rem; color: #94a3b8; margin-bottom: 8px;">${escapeHtml(item.city || currentCity)} &bull; ${escapeHtml(item.top_service || 'walkers')}</div>
        <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px; color: #cbd5e1;">
          <span>Demand: <strong style="color: #fff;">${item.demand}</strong></span>
          <span>Supply: <strong style="color: #fff;">${item.supply}</strong></span>
        </div>
        <div style="font-size: 0.8rem; color: #cbd5e1;">
          Coverage: <strong style="color: #fff;">${item.coverage}%</strong>
          (Gap: <strong style="color: ${gapColor};">${gapDisplay}</strong>)
        </div>
      </div>
    `;

    googleInfoWindow.setContent(content);
    googleInfoWindow.setPosition({
      lat: item.lat,
      lng: item.lng
    });
    googleInfoWindow.open(googleMap);
  }

  // 2. Free Maps (Leaflet / OSM) Initialization
  function initFreeMap() {
    const mapEl = document.getElementById('spMapContainer');
    if (!mapEl) return;

    // Clear any Google overlays
    if (googleOverlays) {
      googleOverlays.forEach(ov => ov.setMap(null));
      googleOverlays = [];
    }
    if (googleInfoWindow) googleInfoWindow.close();
    googleMap = null;

    destroyLeafletMap();
    mapEl.innerHTML = '';

    const center = (currentCityData && currentCityData.city_center) ?
      currentCityData.city_center : {
        lat: 17.3850,
        lng: 78.4867,
        zoom: 11
      };

    leafletMap = L.map('spMapContainer', {
      center: [center.lat, center.lng],
      zoom: center.zoom || 11,
      zoomControl: true,
      scrollWheelZoom: false
    });

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
      attribution: '&copy; OpenStreetMap contributors &copy; CARTO',
      subdomains: 'abcd',
      maxZoom: 19
    }).addTo(leafletMap);

    leafletMarkersLayer = L.layerGroup().addTo(leafletMap);
    renderLeafletMapPins(currentCityData.markers || currentCityData.zones || []);
  }

  function destroyLeafletMap() {
    if (leafletMap) {
      try {
        leafletMap.off();
        leafletMap.remove();
      } catch (e) {}
      leafletMap = null;
      leafletMarkersLayer = null;
    }
  }

  function renderLeafletMapPins(markers) {
    if (!leafletMarkersLayer || !leafletMap) return;
    leafletMarkersLayer.clearLayers();

    if (!markers || markers.length === 0) return;

    const latLngs = [];

    markers.forEach(item => {
      if (!item.lat || !item.lng) return;

      latLngs.push([item.lat, item.lng]);

      const isShortage = item.status === 'shortage';
      const isTight = item.status === 'tight';
      const statusClass = isShortage ? 'shortage' : (isTight ? 'tight' : 'balanced');
      const alertHtml = isShortage ? '<span class="real-pin-alert">!</span>' : '';
      const gapDisplay = item.gap < 0 ? item.gap : `+${item.gap}`;
      const gapColor = item.gap < 0 ? '#ef4444' : '#10b981';

      const iconHtml = `
        <div class="real-pin-inner ${statusClass}" data-zone="${escapeHtml(item.zone)}">
          <div class="real-pin-halo"></div>
          <div class="real-pin-bubble">
            <span>${item.demand}</span>
            ${alertHtml}
          </div>
          <div class="real-pin-label">${escapeHtml(item.zone)}</div>
        </div>
      `;

      const customIcon = L.divIcon({
        html: iconHtml,
        className: 'custom-real-pin',
        iconSize: [80, 56],
        iconAnchor: [40, 20]
      });

      const marker = L.marker([item.lat, item.lng], {
        icon: customIcon
      });

      const popupHtml = `
        <div style="font-family: var(--sp-font); min-width: 175px;">
          <div style="font-weight: 800; font-size: 0.95rem; color: #ffffff; margin-bottom: 2px;">${escapeHtml(item.zone)}</div>
          <div style="font-size: 0.74rem; color: #94a3b8; margin-bottom: 8px;">${escapeHtml(item.city || currentCity)} &bull; ${escapeHtml(item.top_service || 'walkers')}</div>
          <div style="display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px; color: #cbd5e1;">
            <span>Demand: <strong style="color: #fff;">${item.demand}</strong></span>
            <span>Supply: <strong style="color: #fff;">${item.supply}</strong></span>
          </div>
          <div style="font-size: 0.8rem; color: #cbd5e1;">
            Coverage: <strong style="color: #fff;">${item.coverage}%</strong>
            (Gap: <strong style="color: ${gapColor};">${gapDisplay}</strong>)
          </div>
        </div>
      `;

      marker.bindPopup(popupHtml, {
        closeButton: false,
        offset: [0, -10]
      });

      marker.on('mouseover', function() {
        this.openPopup();
        highlightTableRow(item.zone, true);
      });

      marker.on('mouseout', function() {
        this.closePopup();
        highlightTableRow(item.zone, false);
      });

      marker.on('click', function() {
        highlightTableRow(item.zone, true);
      });

      marker.zoneName = item.zone;
      leafletMarkersLayer.addLayer(marker);
    });

    if (latLngs.length > 1) {
      const bounds = L.latLngBounds(latLngs);
      leafletMap.fitBounds(bounds, {
        padding: [45, 45],
        maxZoom: 13
      });
    } else if (latLngs.length === 1) {
      leafletMap.setView(latLngs[0], 12);
    }
  }

  function changeCityFilter(selectedCity) {
    currentCity = selectedCity;
    fetchDynamicData();
  }

  function changeServiceFilter(selectedService) {
    currentService = selectedService;
    fetchDynamicData();
  }

  function changeDateFilter(selectedDays) {
    currentDays = parseInt(selectedDays, 10);
    fetchDynamicData();
  }

  function filterDashboardZones(query) {
    currentSearchQuery = query.toLowerCase().trim();
    if (currentCityData) {
      renderShortageList(currentCityData.shortage_areas || []);
      renderZonesTable(currentCityData.zones || []);
    }
  }

  function fetchDynamicData() {
    const indicator = document.getElementById('mapLoadingIndicator');
    if (indicator) indicator.style.display = 'block';

    const url = `<?= base_url('sp-management/city-data') ?>?city=${encodeURIComponent(currentCity)}&service=${encodeURIComponent(currentService)}&days=${currentDays}`;

    fetch(url)
      .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return response.json();
      })
      .then(res => {
        if (indicator) indicator.style.display = 'none';
        if (res.status === 'success' && res.data) {
          currentCityData = res.data;
          renderDashboardComponents(currentCityData);
        }
      })
      .catch(err => {
        if (indicator) indicator.style.display = 'none';
        console.error('Error fetching city dynamic data:', err);
      });
  }

  function renderDashboardComponents(data) {
    if (!data) return;

    // 1. Update Subtitle
    const subtitleEl = document.getElementById('mapSubtitle');
    if (subtitleEl) {
      subtitleEl.textContent = data.subtitle || `${currentCity} - Active SPs vs Demand`;
    }

    // 2. Update AI Recommendation (Zero trigger button)
    const aiHeadline = document.getElementById('aiHeadline');
    const aiSubtext = document.getElementById('aiSubtext');
    if (aiHeadline && data.recommendation) {
      aiHeadline.textContent = data.recommendation.headline;
    }
    if (aiSubtext && data.recommendation) {
      aiSubtext.textContent = data.recommendation.subtext;
    }

    // 3. Render Map Pins & Center Map according to active provider
    if (currentMapProvider === 'google' && googleMap && data.city_center) {
      googleMap.panTo({
        lat: data.city_center.lat,
        lng: data.city_center.lng
      });
      renderGoogleMapPins(data.markers || data.zones || []);
    } else if (currentMapProvider === 'free' && leafletMap && data.city_center) {
      leafletMap.flyTo([data.city_center.lat, data.city_center.lng], data.city_center.zoom || 11, {
        duration: 1.2
      });
      renderLeafletMapPins(data.markers || data.zones || []);
    }

    // 4. Render Shortage Areas List
    renderShortageList(data.shortage_areas || []);

    // 5. Render High Demand Zones Table
    renderZonesTable(data.zones || []);
  }

  function renderShortageList(shortageAreas) {
    const container = document.getElementById('shortageListContainer');
    if (!container) return;
    container.innerHTML = '';

    const filtered = shortageAreas.filter(item => {
      return !currentSearchQuery || item.zone.toLowerCase().includes(currentSearchQuery);
    });

    if (filtered.length === 0) {
      container.innerHTML = '<div class="text-muted text-center py-3" style="font-size: 0.8rem;">No shortage areas found for this filter</div>';
      return;
    }

    filtered.forEach(item => {
      const isCritical = Math.abs(item.gap) >= 10;
      const pinColor = isCritical ? 'text-danger' : 'text-warning';

      const row = document.createElement('div');
      row.className = 'shortage-item';
      row.style.cursor = 'pointer';
      row.onclick = () => panToZone(item.zone);
      row.innerHTML = `
        <div class="shortage-left">
          <i class="fas fa-map-marker-alt ${pinColor} shortage-icon"></i>
          <span class="shortage-name">${escapeHtml(item.zone)}</span>
        </div>
        <div class="shortage-right">
          <span class="shortage-gap-num">${item.gap}</span>
          <span class="shortage-gap-sub">SP gap</span>
        </div>
      `;
      container.appendChild(row);
    });
  }

  function renderZonesTable(zones) {
    const tbody = document.getElementById('zonesTableBody');
    if (!tbody) return;
    tbody.innerHTML = '';

    const filtered = zones.filter(row => {
      return !currentSearchQuery || row.zone.toLowerCase().includes(currentSearchQuery);
    });

    if (filtered.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted" style="font-size:0.85rem;">No matching zones found</td></tr>';
      return;
    }

    filtered.forEach(row => {
      const isNegative = row.gap < 0;
      const gapDisplay = isNegative ? row.gap : `+${row.gap}`;
      const gapClass = isNegative ? 'negative' : 'positive';
      const statusClass = row.status.toLowerCase();
      const coverageFillWidth = Math.min(row.coverage, 100);

      const tr = document.createElement('tr');
      tr.setAttribute('data-zone-name', row.zone);
      tr.style.cursor = 'pointer';
      tr.onclick = () => panToZone(row.zone);
      tr.innerHTML = `
        <td class="zone-cell">${escapeHtml(row.zone)}</td>
        <td class="fw-semibold">${row.demand}</td>
        <td class="fw-semibold">${row.supply}</td>
        <td>
          <div class="coverage-bar-wrapper">
            <div class="coverage-track">
              <div class="coverage-fill ${statusClass}" style="width: ${coverageFillWidth}%;"></div>
            </div>
            <span class="coverage-pct">${row.coverage}%</span>
          </div>
        </td>
        <td>
          <span class="gap-badge ${gapClass}">${gapDisplay}</span>
        </td>
        <td>
          <span class="status-tag ${statusClass}">${escapeHtml(row.status)}</span>
        </td>
      `;

      tr.addEventListener('mouseenter', function() {
        highlightMapNode(row.zone, true);
      });
      tr.addEventListener('mouseleave', function() {
        highlightMapNode(row.zone, false);
      });

      tbody.appendChild(tr);
    });
  }

  // Cross-Highlighting & Real Map Functions
  function highlightTableRow(zoneName, highlight) {
    const rows = document.querySelectorAll('#zonesTableBody tr');
    rows.forEach(r => {
      if (r.getAttribute('data-zone-name') === zoneName) {
        if (highlight) {
          r.classList.add('highlighted');
        } else {
          r.classList.remove('highlighted');
        }
      }
    });
  }

  function highlightMapNode(zoneName, highlight) {
    // 1. Google Maps Highlight
    if (currentMapProvider === 'google') {
      const gPin = document.querySelector(`.custom-gmap-pin[data-zone="${CSS.escape(zoneName)}"]`);
      if (gPin) {
        const inner = gPin.querySelector('.real-pin-inner');
        if (inner) {
          if (highlight) inner.classList.add('is-active');
          else inner.classList.remove('is-active');
        }
      }
      if (highlight && currentCityData && currentCityData.zones) {
        const target = currentCityData.zones.find(z => z.zone.toLowerCase() === zoneName.toLowerCase());
        if (target) openGMapInfoWindow(target);
      } else if (!highlight && googleInfoWindow) {
        googleInfoWindow.close();
      }
    }

    // 2. Leaflet Highlight
    if (currentMapProvider === 'free' && leafletMarkersLayer) {
      leafletMarkersLayer.eachLayer(marker => {
        const el = marker.getElement();
        if (!el) return;
        const inner = el.querySelector('.real-pin-inner');
        if (marker.zoneName === zoneName || (inner && inner.getAttribute('data-zone') === zoneName)) {
          if (highlight) {
            if (inner) inner.classList.add('is-active');
            marker.openPopup();
          } else {
            if (inner) inner.classList.remove('is-active');
            marker.closePopup();
          }
        }
      });
    }
  }

  function panToZone(zoneName) {
    if (!currentCityData || !currentCityData.zones) return;
    const target = currentCityData.zones.find(z => z.zone.toLowerCase() === zoneName.toLowerCase());
    if (!target || !target.lat || !target.lng) return;

    if (currentMapProvider === 'google' && googleMap) {
      googleMap.panTo({
        lat: target.lat,
        lng: target.lng
      });
      googleMap.setZoom(14);
      openGMapInfoWindow(target);
      highlightTableRow(target.zone, true);
      highlightMapNode(target.zone, true);
    } else if (currentMapProvider === 'free' && leafletMap) {
      leafletMap.flyTo([target.lat, target.lng], 14, {
        duration: 1.0
      });
      setTimeout(() => {
        highlightMapNode(target.zone, true);
      }, 500);
    }
  }

  // Export CSV Function
  function exportDemandSupplyCSV() {
    if (!currentCityData || !currentCityData.zones) return;

    let csvContent = 'data:text/csv;charset=utf-8,';
    csvContent += 'Zone,Demand,SP Supply,Coverage %,Gap,Status\n';

    currentCityData.zones.forEach(row => {
      csvContent += `"${row.zone}",${row.demand},${row.supply},${row.coverage}%,${row.gap},"${row.status}"\n`;
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `City_Demand_Supply_${currentCity}_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  }

  function toastInfo(msg) {
    if (typeof Lobibox !== 'undefined' && Lobibox.notify) {
      Lobibox.notify('info', {
        size: 'mini',
        rounded: true,
        delay: 3000,
        delayIndicator: false,
        msg: msg
      });
    } else {
      alert(msg);
    }
  }

  function escapeHtml(text) {
    if (!text) return '';
    return String(text)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }
</script>

>>>>>>> origin/main
<?php echo $this->endSection(); ?>