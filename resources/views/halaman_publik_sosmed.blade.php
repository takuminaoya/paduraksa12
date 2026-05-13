<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resident Reports Portal</title>
<link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Mono:wght@400;500&family=Instrument+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --bg: #f5f0e8;
    --surface: #fffdf8;
    --surface2: #eee8d8;
    --ink: #1a1612;
    --ink2: #4a4035;
    --ink3: #8a7a65;
    --accent: #c0392b;
    --accent2: #e67e22;
    --green: #27ae60;
    --blue: #2980b9;
    --border: #d4c9b0;
    --shadow: rgba(26,22,18,0.08);
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'Instrument Sans', sans-serif;
    background: var(--bg);
    color: var(--ink);
    min-height: 100vh;
    background-image:
      radial-gradient(circle at 20% 20%, rgba(192,57,43,0.04) 0%, transparent 50%),
      radial-gradient(circle at 80% 80%, rgba(230,126,34,0.04) 0%, transparent 50%);
  }

  img {
    width: 100%;
  }

  /* HEADER */
  header {
    background: var(--ink);
    color: var(--bg);
    padding: 0 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 2px 12px rgba(0,0,0,0.3);
  }

  .header-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
  }

  .header-logo {
    width: 36px; height: 36px;
    background: var(--accent);
    border-radius: 6px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
  }

  .header-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.2rem;
    letter-spacing: 0.02em;
  }

  .header-subtitle {
    font-size: 0.7rem;
    opacity: 0.5;
    font-family: 'DM Mono', monospace;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .header-meta {
    font-family: 'DM Mono', monospace;
    font-size: 0.72rem;
    opacity: 0.5;
    letter-spacing: 0.05em;
  }

  /* LAYOUT */
  .container {
    max-width: 1300px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
  }

  /* STATS ROW */
  .stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
    animation: fadeUp 0.5s ease both;
  }

  .stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 1.2rem 1.4rem;
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    position: relative;
    overflow: hidden;
  }

  .stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: var(--accent);
  }

  .stat-card:nth-child(2)::before { background: var(--accent2); }
  .stat-card:nth-child(3)::before { background: var(--blue); }
  .stat-card:nth-child(4)::before { background: var(--green); }

  .stat-label {
    font-family: 'DM Mono', monospace;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--ink3);
  }

  .stat-value {
    font-family: 'DM Serif Display', serif;
    font-size: 2rem;
    color: var(--ink);
    line-height: 1;
  }

  .stat-sub {
    font-size: 0.72rem;
    color: var(--ink3);
  }

  /* CONTROLS */
  .controls {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    animation: fadeUp 0.5s ease 0.1s both;
  }

  .search-wrap {
    flex: 1;
    min-width: 220px;
    position: relative;
  }

  .search-wrap svg {
    position: absolute;
    left: 0.9rem; top: 50%;
    transform: translateY(-50%);
    opacity: 0.4;
    pointer-events: none;
  }

  .search-input {
    width: 100%;
    padding: 0.65rem 1rem 0.65rem 2.5rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--surface);
    font-family: 'Instrument Sans', sans-serif;
    font-size: 0.9rem;
    color: var(--ink);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
  }

  .search-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(192,57,43,0.1);
  }

  select, .btn-filter {
    padding: 0.65rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    background: var(--surface);
    font-family: 'Instrument Sans', sans-serif;
    font-size: 0.85rem;
    color: var(--ink);
    cursor: pointer;
    outline: none;
    transition: border-color 0.2s;
  }

  select:focus { border-color: var(--accent); }

  .view-toggle {
    display: flex;
    border: 1px solid var(--border);
    border-radius: 8px;
    overflow: hidden;
  }

  .view-btn {
    padding: 0.65rem 0.85rem;
    background: var(--surface);
    border: none;
    cursor: pointer;
    color: var(--ink3);
    transition: background 0.15s, color 0.15s;
    display: flex; align-items: center;
  }

  .view-btn.active {
    background: var(--ink);
    color: var(--bg);
  }

  /* REPORT GRID */
  .reports-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 1.25rem;
    animation: fadeUp 0.5s ease 0.2s both;
  }

  .reports-grid.list-view {
    grid-template-columns: 1fr;
  }

  /* REPORT CARD */
  .report-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
    position: relative;
  }

  .report-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px var(--shadow);
    border-color: var(--ink3);
  }

  .card-header {
    padding: 1rem 1.2rem 0.75rem;
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 0.75rem;
    border-bottom: 1px solid var(--surface2);
  }

  .card-id {
    font-family: 'DM Mono', monospace;
    font-size: 0.65rem;
    color: var(--ink3);
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .badge {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.2rem 0.6rem;
    border-radius: 100px;
    font-size: 0.68rem;
    font-weight: 600;
    letter-spacing: 0.04em;
    text-transform: uppercase;
    white-space: nowrap;
  }

  .badge-open { background: #fdecea; color: #c0392b; }
  .badge-progress { background: #fff3e0; color: #e67e22; }
  .badge-resolved { background: #e8f8ee; color: #27ae60; }
  .badge-review { background: #e3f2fd; color: #2980b9; }

  .badge::before {
    content: '';
    width: 6px; height: 6px;
    border-radius: 50%;
    background: currentColor;
    flex-shrink: 0;
  }

  .card-body { padding: 1rem 1.2rem; }

  .card-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.05rem;
    color: var(--ink);
    margin-bottom: 0.5rem;
    line-height: 1.3;
  }

  .card-desc {
    font-size: 0.82rem;
    color: var(--ink2);
    line-height: 1.55;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .card-footer {
    padding: 0.75rem 1.2rem;
    border-top: 1px solid var(--surface2);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.5rem;
  }

  .card-meta-row {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--ink3);
  }

  .card-meta-row svg { opacity: 0.6; }

  .cat-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    font-size: 0.68rem;
    font-family: 'DM Mono', monospace;
    background: var(--surface2);
    color: var(--ink2);
  }

  .priority-dot {
    width: 8px; height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
  }
  .p-high { background: #c0392b; }
  .p-medium { background: #e67e22; }
  .p-low { background: #27ae60; }

  /* LIST VIEW ADJUSTMENTS */
  .list-view .report-card {
    display: flex;
    align-items: stretch;
  }

  .list-view .card-header {
    flex-direction: column;
    justify-content: center;
    width: 140px;
    flex-shrink: 0;
    border-bottom: none;
    border-right: 1px solid var(--surface2);
  }

  .list-view .card-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 0.9rem 1.2rem;
  }

  .list-view .card-footer {
    width: 200px;
    flex-shrink: 0;
    flex-direction: column;
    align-items: flex-end;
    justify-content: center;
    border-top: none;
    border-left: 1px solid var(--surface2);
  }

  /* MODAL */
  .modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(26,22,18,0.6);
    backdrop-filter: blur(4px);
    z-index: 200;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1.5rem;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.25s;
  }

  .modal-overlay.open {
    opacity: 1;
    pointer-events: all;
  }

  .modal {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 16px;
    width: 100%;
    max-width: 640px;
    max-height: 90vh;
    overflow-y: auto;
    transform: translateY(16px) scale(0.98);
    transition: transform 0.25s;
    box-shadow: 0 24px 60px rgba(0,0,0,0.25);
  }

  .modal-overlay.open .modal {
    transform: translateY(0) scale(1);
  }

  .modal-header {
    padding: 1.5rem 1.75rem 1.25rem;
    border-bottom: 1px solid var(--border);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    position: sticky;
    top: 0;
    background: var(--surface);
    z-index: 1;
  }

  .modal-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.35rem;
    line-height: 1.2;
    color: var(--ink);
  }

  .modal-close {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid var(--border);
    background: var(--bg);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: var(--ink2);
    flex-shrink: 0;
    transition: background 0.15s;
  }

  .modal-close:hover { background: var(--surface2); }

  .modal-body { padding: 1.5rem 1.75rem; }

  .modal-section { margin-bottom: 1.5rem; }

  .modal-section-label {
    font-family: 'DM Mono', monospace;
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--ink3);
    margin-bottom: 0.5rem;
  }

  .modal-section-text {
    font-size: 0.9rem;
    color: var(--ink2);
    line-height: 1.65;
  }

  .modal-meta-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1.5rem;
  }

  .modal-meta-item {
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0.85rem 1rem;
  }

  .modal-meta-label {
    font-family: 'DM Mono', monospace;
    font-size: 0.6rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--ink3);
    margin-bottom: 0.3rem;
  }

  .modal-meta-value {
    font-size: 0.88rem;
    font-weight: 500;
    color: var(--ink);
  }

  .timeline {
    border-left: 2px solid var(--border);
    padding-left: 1.2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .timeline-item {
    position: relative;
  }

  .timeline-item::before {
    content: '';
    position: absolute;
    left: -1.45rem; top: 4px;
    width: 10px; height: 10px;
    border-radius: 50%;
    background: var(--surface2);
    border: 2px solid var(--border);
  }

  .timeline-item:first-child::before {
    background: var(--accent);
    border-color: var(--accent);
  }

  .timeline-time {
    font-family: 'DM Mono', monospace;
    font-size: 0.65rem;
    color: var(--ink3);
    letter-spacing: 0.05em;
    margin-bottom: 0.2rem;
  }

  .timeline-text {
    width: 100%;
    font-size: 0.84rem;
    color: var(--ink2);
  }

  /* EMPTY STATE */
  .empty-state {
    text-align: center;
    padding: 4rem 2rem;
    color: var(--ink3);
    grid-column: 1/-1;
  }

  .empty-state-icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.4;
  }

  .empty-title {
    font-family: 'DM Serif Display', serif;
    font-size: 1.3rem;
    color: var(--ink2);
    margin-bottom: 0.5rem;
  }

  /* ANIMATIONS */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(14px); }
    to { opacity: 1; transform: translateY(0); }
  }

  .report-card {
    animation: fadeUp 0.4s ease both;
  }

  /* SCROLLBAR */
  ::-webkit-scrollbar { width: 6px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

  @media (max-width: 768px) {
    .stats-row { grid-template-columns: repeat(2, 1fr); }
    .list-view .report-card { flex-direction: column; }
    .list-view .card-header, .list-view .card-footer {
      width: 100%; border: none; flex-direction: row;
      border-bottom: 1px solid var(--surface2);
    }
    .list-view .card-footer { border-top: 1px solid var(--surface2); border-bottom: none; justify-content: space-between; }
  }
</style>
</head>
<body>

<header>
  <div class="header-brand">
    <div class="header-logo">🏘</div>
    <div>
      <div class="header-title">PaduraksaWatch</div>
      <div class="header-subtitle">Report Portal For Team Social Media</div>
    </div>
  </div>
  <div class="header-meta" id="headerDate"></div>
</header>

<div class="container">

  <!-- STATS -->
  <div class="stats-row" id="statsRow">
    <div class="stat-card">
      <div class="stat-label">Total Reports</div>
      <div class="stat-value" id="statTotal">0</div>
      <div class="stat-sub">All submissions</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Open</div>
      <div class="stat-value" id="statOpen">0</div>
      <div class="stat-sub">Awaiting action</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">In Progress</div>
      <div class="stat-value" id="statProgress">0</div>
      <div class="stat-sub">Being handled</div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Resolved</div>
      <div class="stat-value" id="statResolved">0</div>
      <div class="stat-sub">Completed</div>
    </div>
  </div>

  <!-- CONTROLS -->
  <div class="controls">
    <div class="search-wrap">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
      </svg>
      <input class="search-input" type="text" placeholder="Search reports, residents, locations…" id="searchInput">
    </div>

    <select id="filterStatus">
      <option value="">All Statuses</option>
      @foreach ($statuses as $item)
          <option value="{{ $item }}">{{ $item }}</option>
      @endforeach
    </select>

    <select id="filterCategory">
      <option value="">All Categories</option>
      @foreach ($klass as $item)
          <option value="{{ $item }}">{{ $item }}</option>
      @endforeach
    </select>

    <select id="filterPriority">
      <option value="">All Priorities</option>
      <option value="High">High</option>
      <option value="Medium">Medium</option>
      <option value="Low">Low</option>
    </select>

    <div class="view-toggle">
      <button class="view-btn active" id="btnGrid" onclick="setView('grid')" title="Grid view">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
          <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
      </button>
      <button class="view-btn" id="btnList" onclick="setView('list')" title="List view">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
          <line x1="8" y1="18" x2="21" y2="18"/>
          <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/>
          <line x1="3" y1="18" x2="3.01" y2="18"/>
        </svg>
      </button>
    </div>
  </div>

  <!-- REPORTS GRID -->
  <div class="reports-grid" id="reportsGrid"></div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
  <div class="modal" id="modal">
    <div class="modal-header">
      <div>
        <div style="font-family:'DM Mono',monospace;font-size:0.65rem;color:var(--ink3);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:0.4rem;" id="modalId"></div>
        <div class="modal-title" id="modalTitle"></div>
      </div>
      <button class="modal-close" onclick="closeModalDirect()">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
        </svg>
      </button>
    </div>
    <div class="modal-body" id="modalBody"></div>
  </div>
</div>

<script>
const reports = {!! $datas !!}

// Computed stats
function updateStats() {
  document.getElementById('statTotal').textContent = reports.length;
  document.getElementById('statOpen').textContent = reports.filter(r => r.status === 'AKTIF').length;
  document.getElementById('statProgress').textContent = reports.filter(r => r.status === 'TINDAK_LANJUT').length;
  document.getElementById('statResolved').textContent = reports.filter(r => r.status === 'SELESAI').length;
}

// Badge class
function badgeClass(status) {
  if (status === 'Open') return 'badge-open';
  if (status === 'In Progress') return 'badge-progress';
  if (status === 'Resolved') return 'badge-resolved';
  if (status === 'Under Review') return 'badge-review';
  return '';
}

// Priority dot
function priorityClass(p) {
  if (p === 'High') return 'p-high';
  if (p === 'Medium') return 'p-medium';
  return 'p-low';
}

// Cat icon
function catIcon(c) {
  const map = { Infrastructure:'🔧', Safety:'🚨', Noise:'🔊', Sanitation:'🗑', Lighting:'💡', Parking:'🚗' };
  return map[c] || '📋';
}

let currentView = 'grid';
let activeReport = null;

function setView(v) {
  currentView = v;
  const grid = document.getElementById('reportsGrid');
  grid.classList.toggle('list-view', v === 'list');
  document.getElementById('btnGrid').classList.toggle('active', v === 'grid');
  document.getElementById('btnList').classList.toggle('active', v === 'list');
}

function renderCards(data) {
  const grid = document.getElementById('reportsGrid');
  if (!data.length) {
    grid.innerHTML = `<div class="empty-state">
      <div class="empty-state-icon">🔍</div>
      <div class="empty-title">No reports found</div>
      <div>Try adjusting your search or filters</div>
    </div>`;
    return;
  }
  grid.innerHTML = data.map((r, i) => `
    <div class="report-card" style="animation-delay:${i * 0.04}s" onclick="openModal('${r.id}')">
      <div class="card-header">
        <div>
          <div class="card-id">${r.id}</div>
        </div>
        <span class="badge ${badgeClass(r.status)}">${r.status}</span>
      </div>
      <div class="card-body">
        <div class="card-title">${r.title}</div>
        <div class="card-desc">${r.description}</div>
      </div>
      <div class="card-footer">
        <div class="card-meta-row">
          <span class="priority-dot ${priorityClass(r.priority)}"></span>
          <span>${r.priority} Priority</span>
        </div>
        <div class="card-meta-row">
          <span class="cat-tag">${catIcon(r.category)} ${r.category}</span>
        </div>
        <div class="card-meta-row" style="font-size:0.7rem;color:var(--ink3)">
          ${r.date}
        </div>
      </div>
    </div>
  `).join('');
}

function getFiltered() {
  const q = document.getElementById('searchInput').value.toLowerCase();
  const st = document.getElementById('filterStatus').value;
  const cat = document.getElementById('filterCategory').value;
  const pri = document.getElementById('filterPriority').value;
  return reports.filter(r => {
    const match = !q || r.title.toLowerCase().includes(q) || r.description.toLowerCase().includes(q)
      || r.resident.toLowerCase().includes(q) || r.location.toLowerCase().includes(q) || r.id.toLowerCase().includes(q);
    const matchSt = !st || r.status === st;
    const matchCat = !cat || r.category === cat;
    const matchPri = !pri || r.priority === pri;
    return match && matchSt && matchCat && matchPri;
  });
}

function refresh() { renderCards(getFiltered()); }

document.getElementById('searchInput').addEventListener('input', refresh);
document.getElementById('filterStatus').addEventListener('change', refresh);
document.getElementById('filterCategory').addEventListener('change', refresh);
document.getElementById('filterPriority').addEventListener('change', refresh);

// MODAL
function openModal(id) {
  const r = reports.find(x => x.id === id);
  if (!r) return;
  document.getElementById('modalId').textContent = r.id;
  document.getElementById('modalTitle').textContent = r.title;
  document.getElementById('modalBody').innerHTML = `
    <div class="modal-meta-grid">
      <div class="modal-meta-item">
        <div class="modal-meta-label">Status</div>
        <div class="modal-meta-value"><span class="badge ${badgeClass(r.status)}">${r.status}</span></div>
      </div>
      <div class="modal-meta-item">
        <div class="modal-meta-label">Priority</div>
        <div class="modal-meta-value" style="display:flex;align-items:center;gap:0.4rem">
          <span class="priority-dot ${priorityClass(r.priority)}"></span>${r.priority}
        </div>
      </div>
      <div class="modal-meta-item">
        <div class="modal-meta-label">Category</div>
        <div class="modal-meta-value">${catIcon(r.category)} ${r.category}</div>
      </div>
      <div class="modal-meta-item">
        <div class="modal-meta-label">Assignee</div>
        <div class="modal-meta-value">${r.assignee}</div>
      </div>
      <div class="modal-meta-item">
        <div class="modal-meta-label">Submitted By</div>
        <div class="modal-meta-value">${r.resident} · ${r.unit}</div>
      </div>
      <div class="modal-meta-item">
        <div class="modal-meta-label">Location</div>
        <div class="modal-meta-value">${r.location}</div>
      </div>
    </div>

    <div class="modal-section">
      <div class="modal-section-label">Description</div>
      <div class="modal-section-text">${r.description}</div>
    </div>

    <div class="modal-section">
      <div class="modal-section-label">Activity Timeline</div>
      <div class="timeline">
        ${r.timeline.map(t => `
          <div class="timeline-item">
            <div class="timeline-time">${t.time}</div>
            <div class="timeline-text">
                <img src="${t.text}"></img>
            </div>
          </div>
        `).join('')}
      </div>
    </div>

    <div style="text-align:right;padding-top:0.5rem;font-family:'DM Mono',monospace;font-size:0.65rem;color:var(--ink3)">
      Last updated: ${r.updated}
    </div>
  `;
  document.getElementById('modalOverlay').classList.add('open');
}

function closeModal(e) {
  if (e.target === document.getElementById('modalOverlay')) closeModalDirect();
}

function closeModalDirect() {
  document.getElementById('modalOverlay').classList.remove('open');
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModalDirect();
});

// Date
document.getElementById('headerDate').textContent = new Date().toLocaleDateString('en-GB', { weekday:'short', day:'numeric', month:'short', year:'numeric' });

updateStats();
renderCards(reports);
</script>
</body>
</html>
