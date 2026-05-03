/**
 * ==========================================
 * MARKETPULSE ANALYTICS v3.1 - COMPLETE
 * WDD Concepts Demonstrated:
 * - DOM Manipulation & Dynamic Content Injection
 * - Event Delegation & Multiple Event Listeners
 * - localStorage for State Persistence
 * - Chart.js Dynamic Chart Creation & Updates
 * - Responsive UI Logic
 * - Module Pattern for Code Organization
 * - API Simulation with setInterval
 * ==========================================
 * Developer: Tsireletso Thatho
 * Email: thathotsireletso@gmail.com
 * Module: WDD Portfolio 2026
 * ==========================================
 */

'use strict';

// ==========================================
// GLOBAL APPLICATION STATE
// ==========================================
const AppState = {
  preferences: {
    theme: 'dark',
    selectedAssets: ['GOLD', 'NASDAQ', 'SP500', 'BTC', 'ETH', 'AAPL', 'TSLA', 'MSFT'],
    watchlistAssets: ['AAPL', 'TSLA', 'MSFT', 'GOLD', 'BTC'],
    defaultChartType: 'line',
    defaultTimeRange: '1D',
    updateInterval: 3000,
    animations: true,
  },
  marketData: {
    GOLD: { price: 2342.50, change: 0.8, volume: '1.2M', name: 'Gold Spot (XAU/USD)' },
    NASDAQ: { price: 18432.10, change: 1.2, volume: '2.8B', name: 'NASDAQ Composite' },
    SP500: { price: 5321.80, change: 0.6, volume: '3.1B', name: 'S&P 500 Index' },
    BTC: { price: 67450.00, change: 2.1, volume: '28.5B', name: 'Bitcoin (BTC/USD)' },
    ETH: { price: 3521.40, change: 1.5, volume: '15.2B', name: 'Ethereum (ETH/USD)' },
    AAPL: { price: 187.32, change: 1.24, volume: '52.3M', name: 'Apple Inc.' },
    TSLA: { price: 248.50, change: -0.82, volume: '38.1M', name: 'Tesla Inc.' },
    MSFT: { price: 415.23, change: 0.45, volume: '28.7M', name: 'Microsoft Corp.' },
    AMZN: { price: 178.25, change: 0.67, volume: '35.4M', name: 'Amazon.com Inc.' },
    GOOGL: { price: 141.80, change: -0.30, volume: '22.1M', name: 'Alphabet Inc.' },
    NVDA: { price: 875.50, change: 3.20, volume: '45.8M', name: 'NVIDIA Corp.' },
    META: { price: 505.75, change: 1.10, volume: '18.9M', name: 'Meta Platforms Inc.' },
    PLTR: { price: 24.50, change: 5.23, volume: '65.2M', name: 'Palantir Technologies' },
    AMD: { price: 145.30, change: -1.50, volume: '42.1M', name: 'Advanced Micro Devices' },
    JPM: { price: 198.45, change: 0.35, volume: '12.8M', name: 'JPMorgan Chase & Co.' },
    V: { price: 280.15, change: 0.55, volume: '8.5M', name: 'Visa Inc.' },
    COIN: { price: 210.80, change: 4.20, volume: '22.7M', name: 'Coinbase Global Inc.' },
    SQ: { price: 82.40, change: 2.80, volume: '15.3M', name: 'Block Inc.' },
    SHOP: { price: 68.90, change: -0.90, volume: '18.2M', name: 'Shopify Inc.' },
    SNAP: { price: 12.35, change: 1.45, volume: '32.6M', name: 'Snap Inc.' },
  },
  charts: {
    main: null,
    allocation: null,
    performance: null,
    mini: {},
  },
  currentSection: 'dashboard',
  currentChartType: 'line',
  currentTimeRange: '1D',
  updateTimer: null,
};

// ==========================================
// UTILITY FUNCTIONS
// ==========================================

/**
 * Refreshes Lucide icons - called after any dynamic DOM changes
 * WDD Concept: Ensures icons render after DOM manipulation
 */
function refreshIcons() {
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  }
}

/**
 * Gets CSS custom property value for dynamic theming
 */
function getThemeColor(variable) {
  return getComputedStyle(document.documentElement).getPropertyValue(variable).trim();
}

// ==========================================
// LOCAL STORAGE MANAGEMENT
// ==========================================

/**
 * Loads user preferences from localStorage
 * WDD Concept: Client-side persistence without backend
 */
function loadPreferences() {
  const saved = localStorage.getItem('marketpulse_preferences_v31');
  if (saved) {
    try {
      const parsed = JSON.parse(saved);
      AppState.preferences = { ...AppState.preferences, ...parsed };
      console.log('📂 Preferences loaded from localStorage');
    } catch (e) {
      console.warn('⚠️ Could not parse saved preferences, using defaults');
    }
  }
}

/**
 * Saves current preferences to localStorage
 */
function savePreferences() {
  try {
    localStorage.setItem('marketpulse_preferences_v31', JSON.stringify(AppState.preferences));
  } catch (e) {
    console.warn('⚠️ Could not save preferences to localStorage');
  }
}

// ==========================================
// THEME MANAGEMENT
// ==========================================

/**
 * Applies the current theme (dark/light) to the document
 * WDD Concept: CSS custom properties enable dynamic theming
 */
function applyTheme() {
  const theme = AppState.preferences.theme;
  document.documentElement.setAttribute('data-theme', theme);
  
  // Update sidebar theme icons
  const sunIcon = document.getElementById('themeIconLight');
  const moonIcon = document.getElementById('themeIconDark');
  const mobileSun = document.querySelector('.mobile-sun-icon');
  const mobileMoon = document.querySelector('.mobile-moon-icon');
  
  if (theme === 'dark') {
    sunIcon?.classList.add('hidden');
    moonIcon?.classList.remove('hidden');
    mobileSun?.classList.add('hidden');
    mobileMoon?.classList.remove('hidden');
  } else {
    sunIcon?.classList.remove('hidden');
    moonIcon?.classList.add('hidden');
    mobileSun?.classList.remove('hidden');
    mobileMoon?.classList.add('hidden');
  }
  
  // Update settings toggle
  const darkModeToggle = document.getElementById('darkModeToggle');
  if (darkModeToggle) darkModeToggle.checked = theme === 'dark';
}

/**
 * Toggles between dark and light theme
 */
function toggleTheme() {
  AppState.preferences.theme = AppState.preferences.theme === 'dark' ? 'light' : 'dark';
  applyTheme();
  savePreferences();
  updateChartsTheme();
  console.log(`🎨 Theme switched to ${AppState.preferences.theme} mode`);
}

// ==========================================
// SIDEBAR & NAVIGATION
// ==========================================

/**
 * Initializes sidebar toggle functionality
 * WDD Concept: Responsive navigation with event listeners
 */
function initSidebar() {
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('sidebarOverlay');
  const menuToggle = document.getElementById('menuToggleBtn');
  const closeBtn = document.getElementById('closeSidebarBtn');
  
  function openSidebar() {
    sidebar?.classList.add('mobile-open');
    overlay?.classList.add('active');
    document.body.style.overflow = 'hidden';
  }
  
  function closeSidebar() {
    sidebar?.classList.remove('mobile-open');
    overlay?.classList.remove('active');
    document.body.style.overflow = '';
  }
  
  menuToggle?.addEventListener('click', openSidebar);
  closeBtn?.addEventListener('click', closeSidebar);
  overlay?.addEventListener('click', closeSidebar);
  
  // Keyboard accessibility
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && sidebar?.classList.contains('mobile-open')) {
      closeSidebar();
    }
  });
  
  // Auto-close on window resize to desktop
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1025) closeSidebar();
  });
}

/**
 * Switches between dashboard sections
 * WDD Concept: Single Page Application (SPA) style navigation
 */
function switchSection(sectionName) {
  // Hide all sections
  document.querySelectorAll('.section-panel').forEach(s => s.classList.remove('active'));
  
  // Show target section
  const target = document.getElementById(sectionName + 'Section');
  if (target) target.classList.add('active');
  
  // Update navigation active state
  document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.classList.remove('active');
    btn.removeAttribute('aria-current');
  });
  
  const navBtn = document.getElementById('nav-' + sectionName);
  if (navBtn) {
    navBtn.classList.add('active');
    navBtn.setAttribute('aria-current', 'page');
  }
  
  AppState.currentSection = sectionName;
  
  // Close mobile sidebar after navigation
  if (window.innerWidth < 1025) {
    document.getElementById('sidebar')?.classList.remove('mobile-open');
    document.getElementById('sidebarOverlay')?.classList.remove('active');
    document.body.style.overflow = '';
  }
  
  // Refresh section-specific content
  setTimeout(() => {
    if (sectionName === 'markets') updateMarketsSection();
    if (sectionName === 'portfolio') updatePortfolioSection();
    refreshIcons();
  }, 150);
  
  console.log(`📑 Navigated to ${sectionName} section`);
}

// ==========================================
// MODAL MANAGEMENT
// ==========================================

/**
 * Initializes all modal functionality
 * WDD Concept: Event-driven UI components with accessibility
 */
function initModals() {
  function openModal(modal) {
    if (!modal) return;
    modal.classList.add('show');
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    refreshIcons();
  }
  
  function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }
  
  // Contact Modal
  document.getElementById('contactBtn')?.addEventListener('click', (e) => {
    e.preventDefault();
    openModal(document.getElementById('contactModal'));
  });
  document.getElementById('closeContactModal')?.addEventListener('click', () => {
    closeModal(document.getElementById('contactModal'));
  });
  
  // Add Asset Modal
  document.getElementById('closeAddAssetModal')?.addEventListener('click', () => {
    closeModal(document.getElementById('addAssetModal'));
  });
  
  // Close modals on overlay click (event delegation)
  document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) closeModal(modal);
    });
  });
  
  // Close on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal.show').forEach(m => closeModal(m));
    }
  });
  
  // Theme toggle buttons
  document.getElementById('themeToggleBtn')?.addEventListener('click', toggleTheme);
  document.getElementById('mobileThemeToggle')?.addEventListener('click', toggleTheme);
}

/**
 * Opens the Add Asset modal with available assets
 */
function openAddAssetModal() {
  const modal = document.getElementById('addAssetModal');
  const container = document.getElementById('availableAssets');
  if (!modal || !container) return;
  
  const watchlist = AppState.preferences.watchlistAssets;
  const available = Object.keys(AppState.marketData).filter(s => !watchlist.includes(s));
  
  container.innerHTML = available.map(symbol => {
    const data = AppState.marketData[symbol];
    return `
      <div class="available-asset-item">
        <div>
          <strong>${symbol}</strong>
          <span style="color:var(--text-muted);font-size:0.75rem;display:block;">${data.name}</span>
        </div>
        <button onclick="addToWatchlist('${symbol}')">
          <i data-lucide="plus"></i> Add
        </button>
      </div>
    `;
  }).join('') || '<p style="color:var(--text-muted);text-align:center;padding:1rem;">🎉 All assets are in your watchlist!</p>';
  
  modal.classList.add('show');
  modal.setAttribute('aria-hidden', 'false');
  document.body.style.overflow = 'hidden';
  refreshIcons();
}

/**
 * Adds an asset to the watchlist
 */
function addToWatchlist(symbol) {
  if (!AppState.preferences.watchlistAssets.includes(symbol)) {
    AppState.preferences.watchlistAssets.push(symbol);
    savePreferences();
    updateWatchlistTable();
    updateAllUI();
  }
  const modal = document.getElementById('addAssetModal');
  if (modal) {
    modal.classList.remove('show');
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }
}

/**
 * Removes an asset from the watchlist
 */
function removeFromWatchlist(symbol) {
  AppState.preferences.watchlistAssets = AppState.preferences.watchlistAssets.filter(s => s !== symbol);
  savePreferences();
  updateWatchlistTable();
  updateAllUI();
}

// ==========================================
// CHART MANAGEMENT
// ==========================================

/**
 * Initializes all charts on page load
 */
function initCharts() {
  AppState.currentChartType = AppState.preferences.defaultChartType;
  AppState.currentTimeRange = AppState.preferences.defaultTimeRange;
  createMainChart();
  createAllocationChart();
  createPortfolioChart();
  updateWatchlistTable();
  updateChartControlsUI();
}

/**
 * Generates time labels based on selected range
 */
function generateTimeLabels(timeRange) {
  const now = new Date();
  const labels = [];
  switch(timeRange) {
    case '1D':
      for (let i = 23; i >= 0; i--) {
        const d = new Date(now - i * 3600000);
        labels.push(d.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false }));
      }
      break;
    case '1W':
      for (let i = 6; i >= 0; i--) {
        const d = new Date(now - i * 86400000);
        labels.push(d.toLocaleDateString('en-US', { weekday: 'short' }));
      }
      break;
    case '1M':
      for (let i = 29; i >= 0; i--) {
        const d = new Date(now - i * 86400000);
        labels.push(d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
      }
      break;
    case '1Y':
      for (let i = 11; i >= 0; i--) {
        const d = new Date(now.getFullYear(), now.getMonth() - i, 1);
        labels.push(d.toLocaleDateString('en-US', { month: 'short' }));
      }
      break;
  }
  return labels;
}

/**
 * Creates the main live market chart
 * WDD Concept: Dynamic Chart.js initialization
 */
function createMainChart() {
  const ctx = document.getElementById('mainChart')?.getContext('2d');
  if (!ctx) return;
  
  // Destroy existing chart instance
  if (AppState.charts.main) AppState.charts.main.destroy();
  
  const assets = AppState.preferences.selectedAssets;
  const colors = ['#10b981','#f59e0b','#3b82f6','#8b5cf6','#ef4444','#ec4899','#06b6d4','#84cc16','#f97316','#14b8a6'];
  
  const datasets = assets.map((symbol, i) => {
    const base = AppState.marketData[symbol]?.price || 100;
    const data = [];
    let price = base * 0.95;
    const points = { '1D': 24, '1W': 7, '1M': 30, '1Y': 12 }[AppState.currentTimeRange] || 24;
    for (let j = 0; j < points; j++) {
      price += (Math.random() * base * 0.02) - (base * 0.01);
      data.push(parseFloat(price.toFixed(2)));
    }
    return {
      label: symbol,
      data,
      borderColor: colors[i % colors.length],
      backgroundColor: 'transparent',
      borderWidth: 2.5,
      pointRadius: 0,
      pointHoverRadius: 5,
      tension: 0.4,
    };
  });
  
  const labels = generateTimeLabels(AppState.currentTimeRange);
  const chartType = AppState.currentChartType === 'area' ? 'line' : AppState.currentChartType;
  const fillArea = AppState.currentChartType === 'area';
  
  if (fillArea) {
    datasets.forEach(ds => {
      ds.backgroundColor = ds.borderColor + '20';
      ds.fill = true;
    });
  }
  
  AppState.charts.main = new Chart(ctx, {
    type: chartType,
    data: { labels, datasets },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            color: getThemeColor('--text-secondary'),
            usePointStyle: true,
            padding: 15,
            font: { size: 11 },
          }
        },
        tooltip: {
          callbacks: {
            label: (ctx) => `${ctx.dataset.label}: $${ctx.parsed.y.toLocaleString()}`,
          }
        }
      },
      scales: {
        x: {
          ticks: { color: getThemeColor('--text-muted'), maxTicksLimit: 8, font: { size: 10 } },
          grid: { color: 'rgba(128,128,128,0.1)' }
        },
        y: {
          ticks: { color: getThemeColor('--text-muted'), callback: v => '$' + v.toLocaleString(), font: { size: 10 } },
          grid: { color: 'rgba(128,128,128,0.1)' }
        }
      }
    }
  });
  
  // Update chart title
  const titleEl = document.getElementById('mainChartTitle');
  if (titleEl) {
    titleEl.textContent = assets.length > 1 ? 'Multi-Asset Comparison' : `${assets[0] || 'Market'} Price`;
  }
}

/**
 * Creates the asset allocation doughnut chart
 */
function createAllocationChart() {
  const ctx = document.getElementById('allocationChart')?.getContext('2d');
  if (!ctx) return;
  
  if (AppState.charts.allocation) AppState.charts.allocation.destroy();
  
  const watchlist = AppState.preferences.watchlistAssets;
  const colors = ['#10b981','#f59e0b','#3b82f6','#8b5cf6','#ef4444','#ec4899','#06b6d4','#84cc16','#f97316','#14b8a6'];
  
  AppState.charts.allocation = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: watchlist,
      datasets: [{
        data: watchlist.map(s => AppState.marketData[s]?.price || 1),
        backgroundColor: watchlist.map((_, i) => colors[i % colors.length]),
        borderWidth: 2,
        borderColor: getThemeColor('--bg-card') || '#18181b',
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            color: getThemeColor('--text-secondary'),
            padding: 15,
            usePointStyle: true,
            font: { size: 11 },
          }
        }
      }
    }
  });
}

/**
 * Creates the portfolio performance chart
 */
function createPortfolioChart() {
  const ctx = document.getElementById('portfolioPerformanceChart')?.getContext('2d');
  if (!ctx) return;
  
  if (AppState.charts.performance) AppState.charts.performance.destroy();
  
  const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
  const data = [100, 103, 107, 112, 109, 115, 120, 125, 130, 128, 135, 148];
  
  AppState.charts.performance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: months,
      datasets: [{
        label: 'Portfolio Growth %',
        data,
        borderColor: '#10b981',
        backgroundColor: 'rgba(16,185,129,0.1)',
        borderWidth: 2.5,
        tension: 0.4,
        fill: true,
        pointBackgroundColor: '#10b981',
        pointRadius: 4,
        pointHoverRadius: 7,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        x: {
          ticks: { color: getThemeColor('--text-muted'), font: { size: 11 } },
          grid: { display: false }
        },
        y: {
          ticks: { color: getThemeColor('--text-muted'), callback: v => v + '%', font: { size: 11 } },
          grid: { color: 'rgba(128,128,128,0.1)' }
        }
      }
    }
  });
}

/**
 * Sets the time range and recreates the chart
 */
function setTimeRange(range) {
  AppState.currentTimeRange = range;
  AppState.preferences.defaultTimeRange = range;
  savePreferences();
  updateChartControlsUI();
  createMainChart();
}

/**
 * Sets the chart type and recreates the chart
 */
function setChartType(type) {
  AppState.currentChartType = type;
  AppState.preferences.defaultChartType = type;
  savePreferences();
  updateChartControlsUI();
  createMainChart();
}

/**
 * Cycles through doughnut/pie/polarArea chart types
 */
function setAllocationChartType() {
  const chart = AppState.charts.allocation;
  if (!chart) return;
  const types = ['doughnut', 'pie', 'polarArea'];
  const idx = types.indexOf(chart.config.type);
  chart.config.type = types[(idx + 1) % types.length];
  chart.update();
}

/**
 * Updates the UI for chart control buttons
 */
function updateChartControlsUI() {
  document.querySelectorAll('.time-btn').forEach(b => {
    b.classList.toggle('active', b.dataset.range === AppState.currentTimeRange);
  });
  document.querySelectorAll('.chart-type-btn').forEach(b => {
    b.classList.toggle('active', b.dataset.type === AppState.currentChartType);
  });
}

/**
 * Updates chart colors when theme changes
 */
function updateChartsTheme() {
  if (AppState.charts.main) {
    AppState.charts.main.options.plugins.legend.labels.color = getThemeColor('--text-secondary');
    AppState.charts.main.options.scales.x.ticks.color = getThemeColor('--text-muted');
    AppState.charts.main.options.scales.y.ticks.color = getThemeColor('--text-muted');
    AppState.charts.main.update();
  }
  createAllocationChart();
  createPortfolioChart();
}

// ==========================================
// SETTINGS MANAGEMENT
// ==========================================

/**
 * Initializes the settings panel
 */
function initSettings() {
  // Build asset selection checkboxes
  const assetGrid = document.getElementById('assetSelectionGrid');
  if (assetGrid) {
    assetGrid.innerHTML = Object.keys(AppState.marketData).map(symbol => `
      <label class="asset-checkbox-item">
        <input type="checkbox" value="${symbol}" 
               ${AppState.preferences.selectedAssets.includes(symbol) ? 'checked' : ''}
               onchange="toggleAssetSelection('${symbol}', this.checked)">
        <span>${symbol}</span>
        <span style="color:var(--text-muted);font-size:0.65rem;margin-left:auto;">${AppState.marketData[symbol].name}</span>
      </label>
    `).join('');
  }
  
  // Set current values in form elements
  const defaultChartType = document.getElementById('defaultChartType');
  const defaultTimeRange = document.getElementById('defaultTimeRange');
  const updateInterval = document.getElementById('updateInterval');
  const darkModeToggle = document.getElementById('darkModeToggle');
  const animationsToggle = document.getElementById('animationsToggle');
  
  if (defaultChartType) defaultChartType.value = AppState.preferences.defaultChartType;
  if (defaultTimeRange) defaultTimeRange.value = AppState.preferences.defaultTimeRange;
  if (updateInterval) updateInterval.value = AppState.preferences.updateInterval;
  if (darkModeToggle) darkModeToggle.checked = AppState.preferences.theme === 'dark';
  if (animationsToggle) animationsToggle.checked = AppState.preferences.animations;
  
  // Event listeners for settings changes
  defaultChartType?.addEventListener('change', (e) => {
    AppState.preferences.defaultChartType = e.target.value;
    AppState.currentChartType = e.target.value;
    savePreferences();
    createMainChart();
    updateChartControlsUI();
  });
  
  defaultTimeRange?.addEventListener('change', (e) => setTimeRange(e.target.value));
  
  updateInterval?.addEventListener('change', (e) => {
    AppState.preferences.updateInterval = parseInt(e.target.value);
    savePreferences();
    restartLiveUpdates();
  });
  
  darkModeToggle?.addEventListener('change', (e) => {
    AppState.preferences.theme = e.target.checked ? 'dark' : 'light';
    applyTheme();
    savePreferences();
    updateChartsTheme();
  });
  
  animationsToggle?.addEventListener('change', (e) => {
    AppState.preferences.animations = e.target.checked;
    document.documentElement.setAttribute('data-animations', e.target.checked);
    savePreferences();
  });
}

/**
 * Toggles an asset in the selected assets list
 */
function toggleAssetSelection(symbol, checked) {
  if (checked) {
    if (!AppState.preferences.selectedAssets.includes(symbol)) {
      AppState.preferences.selectedAssets.push(symbol);
    }
  } else {
    AppState.preferences.selectedAssets = AppState.preferences.selectedAssets.filter(s => s !== symbol);
  }
  savePreferences();
  createMainChart();
  updateAllUI();
  console.log(`${checked ? '✅ Added' : '❌ Removed'} ${symbol} from tracking`);
}

// ==========================================
// SEARCH FUNCTIONALITY
// ==========================================

/**
 * Initializes search filtering for the watchlist table
 */
function initSearch() {
  document.getElementById('searchInput')?.addEventListener('input', (e) => {
    const query = e.target.value.toUpperCase();
    document.querySelectorAll('#watchlistBody tr').forEach(row => {
      const symbol = row.querySelector('.symbol-badge')?.textContent || '';
      const name = row.querySelector('td:nth-child(2)')?.textContent || '';
      row.style.display = (!query || symbol.toUpperCase().includes(query) || name.toUpperCase().includes(query)) ? '' : 'none';
    });
  });
}

// ==========================================
// LIVE DATA UPDATES
// ==========================================

/**
 * Starts the live data simulation
 */
function startLiveUpdates() {
  restartLiveUpdates();
}

/**
 * Restarts the update timer with current interval
 */
function restartLiveUpdates() {
  if (AppState.updateTimer) clearInterval(AppState.updateTimer);
  AppState.updateTimer = setInterval(simulateDataUpdate, AppState.preferences.updateInterval);
  console.log(`⏱️ Live updates running every ${AppState.preferences.updateInterval / 1000}s`);
}

/**
 * Simulates real-time market data updates
 * WDD Concept: Simulating API data with setInterval
 */
function simulateDataUpdate() {
  // Update all market prices with random walks
  Object.keys(AppState.marketData).forEach(symbol => {
    const d = AppState.marketData[symbol];
    const volatility = d.price > 1000 ? 50 : d.price > 100 ? 2 : 5;
    const change = (Math.random() * volatility * 2) - volatility;
    const newPrice = Math.max(d.price * 0.95, Math.min(d.price * 1.05, d.price + change));
    d.change = ((newPrice - d.price) / d.price) * 100;
    d.price = parseFloat(newPrice.toFixed(2));
  });
  
  // Update UI components
  updateTickerBar();
  updateStatsCards();
  updateWatchlistTable();
  
  // Update main chart with new data point
  if (AppState.charts.main && AppState.currentSection === 'dashboard') {
    const chart = AppState.charts.main;
    const now = new Date();
    const label = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
    chart.data.labels.push(label);
    chart.data.labels.shift();
    chart.data.datasets.forEach((ds, i) => {
      const sym = AppState.preferences.selectedAssets[i];
      if (sym && AppState.marketData[sym]) {
        ds.data.push(AppState.marketData[sym].price);
        ds.data.shift();
      }
    });
    chart.update('none');
  }
  
  // Update section-specific content if visible
  if (AppState.currentSection === 'markets') updateMarketsSection();
  if (AppState.currentSection === 'portfolio') updatePortfolioSection();
}

// ==========================================
// UI UPDATE FUNCTIONS
// ==========================================

/**
 * Updates all UI components
 */
function updateAllUI() {
  updateTickerBar();
  updateStatsCards();
  updateWatchlistTable();
  createAllocationChart();
  refreshIcons();
}

/**
 * Updates the scrolling ticker bar
 */
function updateTickerBar() {
  const bar = document.getElementById('tickerBar');
  if (!bar) return;
  
  const tickers = AppState.preferences.selectedAssets.slice(0, 10);
  const html = tickers.map(s => {
    const d = AppState.marketData[s];
    if (!d) return '';
    const cls = d.change >= 0 ? 'positive' : 'negative';
    const arrow = d.change >= 0 ? '▲' : '▼';
    return `<span>${s} <span class="${cls}">$${d.price.toLocaleString()} ${arrow}${Math.abs(d.change).toFixed(1)}%</span></span>`;
  }).join('<span style="color:var(--text-muted);margin:0 0.5rem;">|</span>');
  
  bar.innerHTML = html + html; // Duplicate for seamless infinite scroll
}

/**
 * Updates the statistics cards
 */
function updateStatsCards() {
  const pv = document.getElementById('portfolioValue');
  const dc = document.getElementById('dailyChange');
  const ta = document.getElementById('totalAssets');
  const py = document.getElementById('portfolioYield');
  
  if (pv) {
    const val = 148000 + (Math.random() * 4000 - 2000);
    pv.textContent = '$' + Math.round(val).toLocaleString();
  }
  if (dc) {
    const ch = (Math.random() * 4) - 1;
    dc.textContent = (ch >= 0 ? '+' : '') + ch.toFixed(2) + '%';
    dc.className = 'stat-value ' + (ch >= 0 ? 'positive' : 'negative');
  }
  if (ta) ta.textContent = AppState.preferences.selectedAssets.length;
  if (py) {
    const yieldVal = 6 + (Math.random() * 4);
    py.textContent = yieldVal.toFixed(1) + '%';
  }
}

/**
 * Updates the watchlist table with current data
 */
function updateWatchlistTable() {
  const tbody = document.getElementById('watchlistBody');
  if (!tbody) return;
  
  tbody.innerHTML = AppState.preferences.watchlistAssets.map(s => {
    const d = AppState.marketData[s];
    if (!d) return '';
    const cls = d.change >= 0 ? 'positive' : 'negative';
    const arrow = d.change >= 0 ? '▲' : '▼';
    const changeColor = d.change >= 0 ? 'var(--accent-primary)' : 'var(--danger)';
    return `
      <tr>
        <td><span class="symbol-badge">${s}</span></td>
        <td>${d.name}</td>
        <td style="font-family:monospace;">$${d.price.toLocaleString()}</td>
        <td style="color:${changeColor};">${arrow} ${Math.abs(d.change).toFixed(2)}%</td>
        <td style="color:var(--text-muted);">${d.volume}</td>
        <td>
          <button onclick="removeFromWatchlist('${s}')" class="remove-btn" aria-label="Remove ${s} from watchlist">
            <i data-lucide="trash-2"></i>
          </button>
        </td>
      </tr>
    `;
  }).join('');
  
  refreshIcons();
}

/**
 * Updates the markets section with cards and mini charts
 */
function updateMarketsSection() {
  const grid = document.getElementById('marketsGrid');
  if (!grid) return;
  
  const display = ['GOLD', 'NASDAQ', 'SP500', 'BTC', 'ETH', 'AAPL'];
  
  grid.innerHTML = display.map(s => {
    const d = AppState.marketData[s];
    if (!d) return '';
    const cls = d.change >= 0 ? 'up' : 'down';
    const arrow = d.change >= 0 ? '▲' : '▼';
    let tag = 'tag-index', tagName = 'Index';
    if (s === 'GOLD') { tag = 'tag-gold'; tagName = 'Commodity'; }
    if (s === 'BTC' || s === 'ETH') { tag = 'tag-crypto'; tagName = 'Crypto'; }
    
    return `
      <div class="market-card card-glass">
        <div class="market-card-header">
          <span class="market-symbol">${s}</span>
          <span class="market-tag ${tag}">${tagName}</span>
        </div>
        <div class="market-price">$${d.price.toLocaleString()}</div>
        <div class="market-change ${cls}">${arrow} ${Math.abs(d.change).toFixed(2)}%</div>
        <div class="mini-chart-container">
          <canvas id="mini-${s}" aria-label="${s} mini chart"></canvas>
        </div>
      </div>
    `;
  }).join('');
  
  // Create mini sparkline charts
  setTimeout(() => {
    display.forEach(s => {
      const canvas = document.getElementById('mini-' + s);
      if (!canvas || AppState.charts.mini[s]) return;
      
      const ctx = canvas.getContext('2d');
      const data = [];
      let price = AppState.marketData[s]?.price || 100;
      for (let i = 0; i < 20; i++) {
        price += (Math.random() * price * 0.01) - (price * 0.005);
        data.push(price);
      }
      
      // Destroy existing mini chart if any
      if (AppState.charts.mini[s]) AppState.charts.mini[s].destroy();
      
      AppState.charts.mini[s] = new Chart(ctx, {
        type: 'line',
        data: {
          labels: Array(20).fill(''),
          datasets: [{ 
            data, 
            borderColor: '#10b981', 
            borderWidth: 2, 
            pointRadius: 0, 
            tension: 0.4, 
            fill: false 
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false }, tooltip: { enabled: false } },
          scales: { x: { display: false }, y: { display: false } }
        }
      });
    });
  }, 200);
}

/**
 * Updates the portfolio holdings section
 */
function updatePortfolioSection() {
  const list = document.getElementById('holdingsList');
  if (!list) return;
  
  list.innerHTML = AppState.preferences.watchlistAssets.map(s => {
    const d = AppState.marketData[s];
    if (!d) return '';
    const shares = Math.floor(Math.random() * 50) + 5;
    const value = d.price * shares;
    const cls = d.change >= 0 ? 'positive' : 'negative';
    return `
      <div class="holding-item">
        <div class="holding-info">
          <h4>${s} - ${d.name}</h4>
          <p>${shares} shares</p>
        </div>
        <div class="holding-value">
          <p class="price">$${value.toLocaleString()}</p>
          <p class="change ${cls}">${d.change >= 0 ? '+' : ''}${d.change.toFixed(2)}%</p>
        </div>
      </div>
    `;
  }).join('');
}

// ==========================================
// APPLICATION INITIALIZATION
// ==========================================

/**
 * Main initialization - runs when DOM is fully loaded
 * WDD Concept: DOMContentLoaded ensures all elements exist before manipulation
 */
document.addEventListener('DOMContentLoaded', () => {
  console.log('%c🚀 MarketPulse Analytics v3.1 Initializing...', 'color: #10b981; font-weight: bold; font-size: 14px;');
  console.log('%c👨‍💻 Developed by Tsireletso Thatho | WDD Module 2026', 'color: #a1a1aa;');
  console.log('%c📧 thathotsireletso@gmail.com', 'color: #71717a;');
  
  // Load saved preferences
  loadPreferences();
  
  // Apply theme
  applyTheme();
  
  // Initialize Lucide icons
  refreshIcons();
  
  // Initialize all components
  initSidebar();
  initModals();
  initCharts();
  initSettings();
  initSearch();
  
  // Start live data simulation
  startLiveUpdates();
  
  // Update all UI elements
  updateAllUI();
  
  // Final icon refresh after dynamic content renders
  setTimeout(refreshIcons, 300);
  setTimeout(refreshIcons, 800);
  setTimeout(refreshIcons, 1500);
  
  console.log('%c✅ MarketPulse Analytics Ready', 'color: #10b981; font-weight: bold; font-size: 14px;');
  console.log('%c📊 Features: Multi-Asset Tracking | Dark/Light Mode | localStorage | Responsive | Live Updates', 'color: #a1a1aa;');
  console.log('%c🎨 Settings: Asset Selection | Chart Types | Time Ranges | Theme Toggle', 'color: #71717a;');
});

// ==========================================
// GLOBAL FUNCTION EXPORTS
// Required for onclick handlers in HTML
// ==========================================
window.switchSection = switchSection;
window.setTimeRange = setTimeRange;
window.setChartType = setChartType;
window.setAllocationChartType = setAllocationChartType;
window.openAddAssetModal = openAddAssetModal;
window.addToWatchlist = addToWatchlist;
window.removeFromWatchlist = removeFromWatchlist;
window.toggleAssetSelection = toggleAssetSelection;