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

<?php echo $this->endSection(); ?>