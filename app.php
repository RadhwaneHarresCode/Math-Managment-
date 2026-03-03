<?php
// All non-API routes serve the SPA
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MathPlan — 30-Day Study Planner</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=JetBrains+Mono:wght@400;500&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
<style>
/* ============================================================
   DESIGN SYSTEM
   ============================================================ */
:root {
  --bg:       #0d0f14;
  --bg2:      #141720;
  --bg3:      #1c2030;
  --border:   #252a3a;
  --accent:   #4f7cff;
  --gold:     #f0b429;
  --green:    #22c55e;
  --red:      #ef4444;
  --text:     #e2e8f0;
  --muted:    #64748b;
  --dim:      #94a3b8;
  --week1:    #4f7cff;
  --week2:    #22c55e;
  --week3:    #a855f7;
  --week4:    #f0b429;
}

* { margin:0; padding:0; box-sizing:border-box; }

body {
  background: var(--bg);
  color: var(--text);
  font-family: 'Lato', sans-serif;
  font-weight: 300;
  min-height: 100vh;
  overflow-x: hidden;
}

/* ── SCROLLBAR ── */
::-webkit-scrollbar { width:6px; }
::-webkit-scrollbar-track { background: var(--bg2); }
::-webkit-scrollbar-thumb { background: var(--border); border-radius:3px; }

/* ── LAYOUT ── */
#app { min-height:100vh; }

.page { display:none; animation: fadeIn .3s ease; }
.page.active { display:block; }

@keyframes fadeIn { from{opacity:0;transform:translateY(8px)} to{opacity:1;transform:translateY(0)} }

/* ── NAV ── */
nav {
  position: sticky; top:0; z-index:100;
  background: rgba(13,15,20,.92);
  backdrop-filter: blur(16px);
  border-bottom: 1px solid var(--border);
  padding: 0 32px;
  display: flex; align-items:center; justify-content:space-between;
  height: 62px;
}

.nav-logo {
  font-family: 'Playfair Display', serif;
  font-size: 20px;
  color: var(--text);
  text-decoration:none;
  display:flex; align-items:center; gap:10px;
}
.nav-logo span { color:var(--accent); }

.nav-links { display:flex; align-items:center; gap:6px; }

.nav-btn {
  background:none; border:none; cursor:pointer;
  font-family:'Lato',sans-serif; font-size:13px; font-weight:400;
  color:var(--muted); padding:8px 14px; border-radius:6px;
  transition: all .15s; letter-spacing:.3px;
}
.nav-btn:hover { color:var(--text); background:var(--bg3); }
.nav-btn.active { color:var(--accent); }

.nav-right { display:flex; align-items:center; gap:12px; }

#user-info {
  font-family:'JetBrains Mono',monospace; font-size:11px;
  color:var(--muted); display:none; align-items:center; gap:8px;
}
#user-info.show { display:flex; }
#user-role-badge {
  background:var(--bg3); border:1px solid var(--border);
  padding:2px 8px; border-radius:20px; font-size:10px;
  color: var(--accent);
}

/* ── BUTTONS ── */
.btn {
  display:inline-flex; align-items:center; gap:8px;
  padding:10px 20px; border-radius:8px; border:none; cursor:pointer;
  font-family:'Lato',sans-serif; font-size:14px; font-weight:700;
  letter-spacing:.3px; transition: all .15s;
}
.btn-primary   { background:var(--accent); color:#fff; }
.btn-primary:hover { background:#3d6ae8; transform:translateY(-1px); box-shadow:0 4px 16px rgba(79,124,255,.3); }
.btn-ghost     { background:transparent; color:var(--dim); border:1px solid var(--border); }
.btn-ghost:hover { border-color:var(--dim); color:var(--text); }
.btn-danger    { background:var(--red); color:#fff; }
.btn-danger:hover { background:#dc2626; }
.btn-sm { padding:6px 14px; font-size:12px; }

/* ── FORMS ── */
.form-group { margin-bottom:18px; }
.form-group label { display:block; font-size:12px; color:var(--muted); margin-bottom:6px; letter-spacing:1px; text-transform:uppercase; font-family:'JetBrains Mono',monospace; }
.form-group input, .form-group textarea, .form-group select {
  width:100%; background:var(--bg3); border:1px solid var(--border);
  color:var(--text); padding:12px 16px; border-radius:8px;
  font-family:'Lato',sans-serif; font-size:14px; font-weight:300;
  transition: border-color .15s; outline:none;
}
.form-group input:focus, .form-group textarea:focus { border-color:var(--accent); }
.form-group textarea { resize:vertical; min-height:80px; }
.form-error { color:var(--red); font-size:12px; margin-top:4px; }

/* ── CARDS ── */
.card {
  background:var(--bg2); border:1px solid var(--border);
  border-radius:12px; padding:24px;
}

/* ── TOAST ── */
#toast {
  position:fixed; bottom:28px; right:28px; z-index:9999;
  background:var(--bg3); border:1px solid var(--border);
  border-radius:10px; padding:14px 20px;
  font-size:14px; color:var(--text);
  display:none; align-items:center; gap:10px;
  box-shadow: 0 8px 32px rgba(0,0,0,.5);
  animation: slideUp .2s ease;
  max-width:320px;
}
#toast.show { display:flex; }
#toast.success { border-color:var(--green); }
#toast.error   { border-color:var(--red); }
@keyframes slideUp { from{transform:translateY(16px);opacity:0} to{transform:translateY(0);opacity:1} }

/* ── MODAL ── */
.modal-overlay {
  position:fixed; inset:0; background:rgba(0,0,0,.7);
  backdrop-filter:blur(4px); z-index:200;
  display:none; align-items:center; justify-content:center; padding:24px;
}
.modal-overlay.show { display:flex; }
.modal {
  background:var(--bg2); border:1px solid var(--border);
  border-radius:16px; padding:32px; width:100%; max-width:440px;
  animation: fadeIn .2s ease;
}
.modal h2 { font-family:'Playfair Display',serif; font-size:22px; margin-bottom:6px; }
.modal .modal-sub { color:var(--muted); font-size:13px; margin-bottom:24px; }
.modal-footer { display:flex; gap:10px; justify-content:flex-end; margin-top:24px; }

/* ============================================================
   PAGE: LANDING
   ============================================================ */
#page-landing {
  min-height: 100vh;
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  padding:60px 24px;
  position:relative; overflow:hidden;
}

.landing-glow {
  position:absolute; top:-200px; left:50%; transform:translateX(-50%);
  width:600px; height:600px;
  background: radial-gradient(circle, rgba(79,124,255,.12) 0%, transparent 70%);
  pointer-events:none;
}

.landing-badge {
  font-family:'JetBrains Mono',monospace; font-size:11px;
  letter-spacing:2px; text-transform:uppercase;
  color:var(--accent); border:1px solid rgba(79,124,255,.3);
  padding:6px 16px; border-radius:20px; margin-bottom:32px;
}

.landing-title {
  font-family:'Playfair Display',serif;
  font-size:clamp(42px,8vw,80px);
  line-height:1.05; text-align:center; margin-bottom:24px;
  max-width:700px;
}
.landing-title em { font-style:italic; color:var(--accent); }

.landing-sub {
  font-size:17px; color:var(--dim); text-align:center;
  max-width:480px; line-height:1.7; margin-bottom:48px;
}

.landing-stats {
  display:flex; gap:40px; margin-bottom:56px; flex-wrap:wrap; justify-content:center;
}
.landing-stat { text-align:center; }
.landing-stat-num {
  font-family:'Playfair Display',serif; font-size:36px;
  display:block; color:var(--text);
}
.landing-stat-label { font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:1px; }

.landing-actions { display:flex; gap:12px; }

.week-pills { display:flex; gap:8px; margin-top:64px; flex-wrap:wrap; justify-content:center; }
.week-pill {
  border-radius:20px; padding:6px 16px; font-size:12px; font-weight:700;
  font-family:'JetBrains Mono',monospace; letter-spacing:.5px;
}
.pill-1 { background:rgba(79,124,255,.15); color:var(--week1); border:1px solid rgba(79,124,255,.3); }
.pill-2 { background:rgba(34,197,94,.15);  color:var(--week2); border:1px solid rgba(34,197,94,.3); }
.pill-3 { background:rgba(168,85,247,.15); color:var(--week3); border:1px solid rgba(168,85,247,.3); }
.pill-4 { background:rgba(240,180,41,.15); color:var(--week4); border:1px solid rgba(240,180,41,.3); }

/* ============================================================
   PAGE: AUTH
   ============================================================ */
#page-auth {
  min-height:100vh; display:flex; align-items:center; justify-content:center; padding:24px;
}
.auth-box { width:100%; max-width:400px; }
.auth-box h1 { font-family:'Playfair Display',serif; font-size:30px; margin-bottom:6px; }
.auth-box p  { color:var(--muted); margin-bottom:32px; font-size:14px; }
.auth-tabs { display:flex; gap:4px; background:var(--bg3); border-radius:8px; padding:4px; margin-bottom:28px; }
.auth-tab {
  flex:1; padding:8px; border:none; background:none; cursor:pointer;
  font-family:'Lato',sans-serif; font-size:13px; font-weight:700;
  color:var(--muted); border-radius:6px; transition: all .15s;
}
.auth-tab.active { background:var(--bg2); color:var(--text); }

/* ============================================================
   PAGE: DASHBOARD (Study Plan)
   ============================================================ */
#page-dashboard { padding:32px 24px; max-width:1100px; margin:0 auto; }

.dash-header { margin-bottom:32px; }
.dash-header h1 { font-family:'Playfair Display',serif; font-size:32px; margin-bottom:4px; }
.dash-header p { color:var(--muted); font-size:14px; }

/* Progress bar */
.progress-bar-wrap { background:var(--bg2); border:1px solid var(--border); border-radius:12px; padding:20px 24px; margin-bottom:28px; }
.progress-top { display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; }
.progress-label { font-family:'JetBrains Mono',monospace; font-size:12px; color:var(--muted); text-transform:uppercase; letter-spacing:1px; }
.progress-pct { font-family:'Playfair Display',serif; font-size:22px; color:var(--accent); }
.pbar { height:8px; background:var(--bg3); border-radius:4px; overflow:hidden; }
.pbar-fill { height:100%; background:linear-gradient(90deg,var(--accent),var(--week3)); border-radius:4px; transition:width .6s ease; width:0%; }
.progress-stats { display:flex; gap:24px; margin-top:12px; }
.prog-stat { font-size:12px; color:var(--muted); }
.prog-stat strong { color:var(--text); }

/* Filters */
.filters { display:flex; gap:8px; margin-bottom:24px; flex-wrap:wrap; }
.filter-btn {
  background:var(--bg3); border:1px solid var(--border);
  color:var(--muted); padding:7px 16px; border-radius:6px;
  font-size:12px; font-family:'JetBrains Mono',monospace; letter-spacing:.5px;
  cursor:pointer; transition:all .15s;
}
.filter-btn:hover { border-color:var(--dim); color:var(--text); }
.filter-btn.active { background:var(--accent); border-color:var(--accent); color:#fff; }

/* Week section */
.week-section { margin-bottom:36px; }
.week-header {
  display:flex; align-items:center; justify-content:space-between;
  padding-bottom:12px; margin-bottom:14px;
  border-bottom:1px solid var(--border);
}
.week-title { font-family:'Playfair Display',serif; font-size:18px; }
.week-badge { font-size:11px; font-family:'JetBrains Mono',monospace; padding:3px 10px; border-radius:20px; }

/* Day cards grid */
.days-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:10px; }

.day-card {
  background:var(--bg2); border:1px solid var(--border);
  border-radius:10px; padding:16px; cursor:pointer;
  transition:all .15s; position:relative;
  animation: fadeIn .3s ease both;
}
.day-card:hover { border-color:var(--accent); transform:translateY(-2px); box-shadow:0 4px 20px rgba(79,124,255,.1); }
.day-card.done { border-color:var(--green); background:rgba(34,197,94,.04); }
.day-card.done::after { content:'✓'; position:absolute; top:12px; right:14px; color:var(--green); font-weight:700; font-size:13px; }

.day-num { font-family:'JetBrains Mono',monospace; font-size:10px; color:var(--muted); text-transform:uppercase; letter-spacing:1.5px; margin-bottom:6px; }
.day-title { font-family:'Playfair Display',serif; font-size:15px; line-height:1.25; margin-bottom:8px; }
.day-topics { border-top:1px solid var(--border); padding-top:8px; margin-top:8px; }
.day-topic { font-size:11.5px; color:var(--muted); padding:2px 0; line-height:1.4; }
.day-topic::before { content:'→ '; color:var(--accent); font-family:'JetBrains Mono',monospace; }

.day-footer { display:flex; align-items:center; justify-content:space-between; margin-top:12px; }
.day-time { font-size:10px; color:var(--muted); font-family:'JetBrains Mono',monospace; }
.mark-btn {
  background:none; border:1px solid var(--border); color:var(--muted);
  padding:4px 10px; border-radius:4px; font-size:10px;
  font-family:'JetBrains Mono',monospace; letter-spacing:.5px; text-transform:uppercase;
  cursor:pointer; transition:all .15s;
}
.mark-btn:hover { background:var(--accent); border-color:var(--accent); color:#fff; }
.day-card.done .mark-btn { color:var(--green); border-color:var(--green); }
.day-card.done .mark-btn:hover { background:var(--green); color:#fff; }

/* ============================================================
   PAGE: ADMIN
   ============================================================ */
#page-admin { padding:32px 24px; max-width:1100px; margin:0 auto; }

.admin-header { margin-bottom:32px; }
.admin-header h1 { font-family:'Playfair Display',serif; font-size:32px; margin-bottom:4px; }
.admin-header p { color:var(--muted); font-size:14px; }

.admin-tabs { display:flex; gap:4px; background:var(--bg2); border:1px solid var(--border); border-radius:10px; padding:4px; margin-bottom:32px; width:fit-content; }
.admin-tab {
  padding:8px 20px; border:none; border-radius:8px; cursor:pointer;
  font-family:'Lato',sans-serif; font-size:13px; font-weight:700;
  color:var(--muted); background:none; transition:all .15s;
}
.admin-tab.active { background:var(--bg3); color:var(--text); }

.stat-cards { display:grid; grid-template-columns:repeat(auto-fill,minmax(180px,1fr)); gap:14px; margin-bottom:32px; }
.stat-card { background:var(--bg2); border:1px solid var(--border); border-radius:10px; padding:20px; }
.stat-card-num { font-family:'Playfair Display',serif; font-size:32px; display:block; }
.stat-card-label { font-size:11px; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-top:4px; font-family:'JetBrains Mono',monospace; }

.section-title { font-family:'Playfair Display',serif; font-size:20px; margin-bottom:16px; }

/* Tables */
table { width:100%; border-collapse:collapse; margin-bottom:32px; }
th { text-align:left; font-family:'JetBrains Mono',monospace; font-size:10px; text-transform:uppercase; letter-spacing:1.5px; color:var(--muted); padding:10px 14px; border-bottom:1px solid var(--border); }
td { padding:12px 14px; border-bottom:1px solid rgba(37,42,58,.5); font-size:13px; color:var(--dim); }
tr:hover td { background:var(--bg3); color:var(--text); }

.badge { display:inline-block; padding:2px 8px; border-radius:4px; font-size:11px; font-family:'JetBrains Mono',monospace; }
.badge-green { background:rgba(34,197,94,.15); color:var(--green); }
.badge-blue  { background:rgba(79,124,255,.15); color:var(--accent); }
.badge-gold  { background:rgba(240,180,41,.15); color:var(--gold); }

/* Progress mini bar */
.mini-bar { background:var(--bg3); border-radius:3px; height:4px; width:80px; overflow:hidden; }
.mini-fill { height:100%; background:var(--accent); border-radius:3px; }

.admin-view { display:none; }
.admin-view.active { display:block; }

/* ============================================================
   LOADING
   ============================================================ */
.spinner {
  width:24px; height:24px; border:2px solid var(--border);
  border-top-color:var(--accent); border-radius:50%;
  animation:spin .6s linear infinite; display:inline-block;
}
@keyframes spin { to{transform:rotate(360deg)} }

.loading-center { display:flex; justify-content:center; padding:60px; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media(max-width:600px){
  nav { padding:0 16px; }
  .nav-btn { padding:6px 10px; font-size:12px; }
  #page-dashboard, #page-admin { padding:20px 16px; }
  .landing-actions { flex-direction:column; align-items:center; }
  .landing-stats { gap:24px; }
}
</style>
</head>
<body>

<!-- NAV -->
<nav id="main-nav" style="display:none">
  <a class="nav-logo" href="#" onclick="navigate('dashboard')">Math<span>Plan</span></a>
  <div class="nav-links">
    <button class="nav-btn" id="nav-plan"  onclick="navigate('dashboard')">Study Plan</button>
    <button class="nav-btn" id="nav-admin" onclick="navigate('admin')" style="display:none">Admin</button>
  </div>
  <div class="nav-right">
    <div id="user-info">
      <span id="user-name-display"></span>
      <span id="user-role-badge"></span>
    </div>
    <button class="btn btn-ghost btn-sm" onclick="logout()">Sign out</button>
  </div>
</nav>

<!-- LANDING -->
<div id="page-landing" class="page active">
  <div class="landing-glow"></div>
  <div class="landing-badge">Portfolio Project · Full-Stack PHP</div>
  <h1 class="landing-title">Master Math in<br><em>30 Days</em></h1>
  <p class="landing-sub">A structured study plan with progress tracking, personal notes, and an admin dashboard. 2 hours per day — from algebra to exam readiness.</p>
  <div class="landing-stats">
    <div class="landing-stat"><span class="landing-stat-num">30</span><span class="landing-stat-label">Days</span></div>
    <div class="landing-stat"><span class="landing-stat-num">2h</span><span class="landing-stat-label">Daily</span></div>
    <div class="landing-stat"><span class="landing-stat-num">60h</span><span class="landing-stat-label">Total</span></div>
    <div class="landing-stat"><span class="landing-stat-num">4</span><span class="landing-stat-label">Phases</span></div>
  </div>
  <div class="landing-actions">
    <button class="btn btn-primary" onclick="navigate('auth')">Get Started →</button>
    <button class="btn btn-ghost"   onclick="previewPlan()">Preview Plan</button>
  </div>
  <div class="week-pills">
    <div class="week-pill pill-1">Week 1 · Algebra</div>
    <div class="week-pill pill-2">Week 2 · Geometry</div>
    <div class="week-pill pill-3">Week 3 · Functions</div>
    <div class="week-pill pill-4">Week 4 · Exam Prep</div>
  </div>
</div>

<!-- AUTH -->
<div id="page-auth" class="page">
  <div class="auth-box card">
    <h1>Welcome back</h1>
    <p>Sign in to track your progress</p>
    <div class="auth-tabs">
      <button class="auth-tab active" onclick="switchAuthTab('login')">Sign In</button>
      <button class="auth-tab" onclick="switchAuthTab('register')">Register</button>
    </div>
    <!-- Login -->
    <div id="auth-login">
      <div class="form-group"><label>Email</label><input type="email" id="login-email" placeholder="you@example.com"></div>
      <div class="form-group"><label>Password</label><input type="password" id="login-pass" placeholder="Your password"></div>
      <div id="login-err" class="form-error"></div>
      <button class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px" onclick="doLogin()">Sign In →</button>
      <p style="text-align:center;margin-top:16px;font-size:12px;color:var(--muted)">Demo admin: admin@mathplan.dev / Admin1234!</p>
    </div>
    <!-- Register -->
    <div id="auth-register" style="display:none">
      <div class="form-group"><label>Name</label><input type="text" id="reg-name" placeholder="Your name"></div>
      <div class="form-group"><label>Email</label><input type="email" id="reg-email" placeholder="you@example.com"></div>
      <div class="form-group"><label>Password</label><input type="password" id="reg-pass" placeholder="Min. 8 characters"></div>
      <div id="reg-err" class="form-error"></div>
      <button class="btn btn-primary" style="width:100%;justify-content:center;margin-top:8px" onclick="doRegister()">Create Account →</button>
    </div>
    <div style="text-align:center;margin-top:20px">
      <button class="btn btn-ghost btn-sm" onclick="navigate('landing')">← Back to home</button>
    </div>
  </div>
</div>

<!-- DASHBOARD -->
<div id="page-dashboard" class="page">
  <div class="dash-header">
    <h1 id="dash-greeting">Your Study Plan</h1>
    <p>Track your daily progress — 2 hours per session</p>
  </div>

  <div class="progress-bar-wrap">
    <div class="progress-top">
      <span class="progress-label">Overall Progress</span>
      <span class="progress-pct" id="prog-pct">0%</span>
    </div>
    <div class="pbar"><div class="pbar-fill" id="prog-fill"></div></div>
    <div class="progress-stats">
      <div class="prog-stat"><strong id="prog-done">0</strong> / 30 days completed</div>
      <div class="prog-stat"><strong id="prog-hrs">0h</strong> studied</div>
      <div class="prog-stat"><strong id="prog-left">60h</strong> remaining</div>
    </div>
  </div>

  <div class="filters">
    <button class="filter-btn active" onclick="applyFilter('all',this)">All Days</button>
    <button class="filter-btn" onclick="applyFilter('done',this)">✓ Completed</button>
    <button class="filter-btn" onclick="applyFilter('todo',this)">Remaining</button>
    <button class="filter-btn" onclick="applyFilter('w1',this)">Week 1</button>
    <button class="filter-btn" onclick="applyFilter('w2',this)">Week 2</button>
    <button class="filter-btn" onclick="applyFilter('w3',this)">Week 3</button>
    <button class="filter-btn" onclick="applyFilter('w4',this)">Week 4</button>
  </div>

  <div id="plan-container"><div class="loading-center"><div class="spinner"></div></div></div>
</div>

<!-- ADMIN -->
<div id="page-admin" class="page">
  <div class="admin-header">
    <h1>Admin Dashboard</h1>
    <p>Monitor student progress and manage accounts</p>
  </div>

  <div class="admin-tabs">
    <button class="admin-tab active" onclick="switchAdminTab('overview')">Overview</button>
    <button class="admin-tab" onclick="switchAdminTab('users')">Users</button>
    <button class="admin-tab" onclick="switchAdminTab('days')">Day Stats</button>
  </div>

  <!-- OVERVIEW -->
  <div id="admin-overview" class="admin-view active">
    <div class="stat-cards" id="admin-stat-cards"><div class="loading-center"><div class="spinner"></div></div></div>
    <h2 class="section-title">Top Students</h2>
    <table id="admin-top-table"><thead><tr><th>Name</th><th>Email</th><th>Days Done</th><th>Progress</th></tr></thead><tbody></tbody></table>
    <h2 class="section-title">Recent Activity</h2>
    <table id="admin-activity-table"><thead><tr><th>Student</th><th>Day</th><th>Status</th><th>Time</th></tr></thead><tbody></tbody></table>
  </div>

  <!-- USERS -->
  <div id="admin-users" class="admin-view">
    <table id="admin-users-table">
      <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Days Done</th><th>Joined</th><th>Action</th></tr></thead>
      <tbody></tbody>
    </table>
  </div>

  <!-- DAYS -->
  <div id="admin-days" class="admin-view">
    <table id="admin-days-table">
      <thead><tr><th>Day</th><th>Title</th><th>Attempts</th><th>Completions</th><th>Rate</th></tr></thead>
      <tbody></tbody>
    </table>
  </div>
</div>

<!-- NOTES MODAL -->
<div class="modal-overlay" id="notes-modal">
  <div class="modal">
    <h2>Day <span id="modal-day-num"></span></h2>
    <p class="modal-sub" id="modal-day-title"></p>
    <div class="form-group">
      <label>Your Notes</label>
      <textarea id="modal-notes" placeholder="What did you learn? What needs review?"></textarea>
    </div>
    <div class="modal-footer">
      <button class="btn btn-ghost" onclick="closeModal()">Cancel</button>
      <button class="btn btn-primary" onclick="saveNotes()">Save Notes</button>
    </div>
  </div>
</div>

<!-- TOAST -->
<div id="toast"></div>

<script>
/* ============================================================
   STATE
   ============================================================ */
const API = '/api';
let state = {
  user:        null,
  token:       localStorage.getItem('token'),
  days:        [],
  filter:      'all',
  currentDay:  null,
  adminData:   null,
  adminUsers:  null,
};

/* ============================================================
   BOOT
   ============================================================ */
window.addEventListener('DOMContentLoaded', async () => {
  if (state.token) {
    const ok = await loadMe();
    if (ok) { navigate('dashboard'); return; }
    localStorage.removeItem('token');
    state.token = null;
  }
  navigate('landing');
});

/* ============================================================
   NAVIGATION
   ============================================================ */
function navigate(page) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.getElementById('page-' + page).classList.add('active');

  const nav = document.getElementById('main-nav');
  nav.style.display = page === 'landing' || page === 'auth' ? 'none' : '';

  if (page === 'dashboard') { loadPlan(); updateNavActive('plan'); }
  if (page === 'admin')     { loadAdminStats(); updateNavActive('admin'); }
}

function updateNavActive(tab) {
  document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
  const el = document.getElementById('nav-' + tab);
  if (el) el.classList.add('active');
}

function previewPlan() {
  navigate('dashboard');
  loadPlan(true);
}

/* ============================================================
   AUTH
   ============================================================ */
function switchAuthTab(tab) {
  document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
  event.target.classList.add('active');
  document.getElementById('auth-login').style.display    = tab === 'login'    ? '' : 'none';
  document.getElementById('auth-register').style.display = tab === 'register' ? '' : 'none';
}

async function doLogin() {
  const email = document.getElementById('login-email').value.trim();
  const pass  = document.getElementById('login-pass').value;
  document.getElementById('login-err').textContent = '';

  const res = await post('/auth?action=login', { email, password: pass });
  if (!res.ok) { document.getElementById('login-err').textContent = res.msg; return; }

  setSession(res.data.token, res.data.user);
  navigate('dashboard');
}

async function doRegister() {
  const name  = document.getElementById('reg-name').value.trim();
  const email = document.getElementById('reg-email').value.trim();
  const pass  = document.getElementById('reg-pass').value;
  document.getElementById('reg-err').textContent = '';

  const res = await post('/auth?action=register', { name, email, password: pass });
  if (!res.ok) {
    const e = res.errors ? Object.values(res.errors).join(', ') : res.msg;
    document.getElementById('reg-err').textContent = e;
    return;
  }
  setSession(res.data.token, res.data.user);
  navigate('dashboard');
}

async function loadMe() {
  const res = await get('/auth?action=me');
  if (!res.ok) return false;
  setSession(state.token, res.data.user);
  return true;
}

function setSession(token, user) {
  state.token = token;
  state.user  = user;
  localStorage.setItem('token', token);

  document.getElementById('user-name-display').textContent = user.name;
  document.getElementById('user-role-badge').textContent   = user.role;
  document.getElementById('user-info').classList.add('show');

  const adminBtn = document.getElementById('nav-admin');
  adminBtn.style.display = user.role === 'admin' ? '' : 'none';
}

async function logout() {
  await post('/auth?action=logout', {});
  localStorage.removeItem('token');
  state.token = null; state.user = null; state.days = [];
  navigate('landing');
}

/* ============================================================
   STUDY PLAN
   ============================================================ */
async function loadPlan(preview = false) {
  if (!preview && !state.token) { navigate('auth'); return; }

  document.getElementById('plan-container').innerHTML = '<div class="loading-center"><div class="spinner"></div></div>';
  document.getElementById('main-nav').style.display = preview ? 'none' : '';

  const res = await get('/plan?action=all');
  if (!res.ok) { showToast('Failed to load plan', 'error'); return; }

  state.days = res.data.days;
  renderPlan();
  updateProgress();

  if (preview) {
    document.getElementById('dash-greeting').textContent = 'Plan Preview';
  }
}

const WEEK_META = {
  1: { label:'Week 1 — Algebra & Arithmetic',  cls:'pill-1', color:'var(--week1)' },
  2: { label:'Week 2 — Geometry',               cls:'pill-2', color:'var(--week2)' },
  3: { label:'Week 3 — Functions & Graphs',     cls:'pill-3', color:'var(--week3)' },
  4: { label:'Week 4 — Final Exam Prep',        cls:'pill-4', color:'var(--week4)' },
};

function renderPlan() {
  const container = document.getElementById('plan-container');
  const byWeek = {};
  state.days.forEach(d => { (byWeek[d.week] = byWeek[d.week] || []).push(d); });

  container.innerHTML = Object.entries(byWeek).map(([w, days]) => {
    const meta = WEEK_META[w];
    const dayCards = days.map((d, i) => {
      const done = d.progress?.completed || false;
      return `
        <div class="day-card${done?' done':''}" data-day="${d.day_number}" data-week="${d.week}"
             style="animation-delay:${i*0.03}s">
          <div class="day-num">Day ${d.day_number} of 30</div>
          <div class="day-title">${d.title}</div>
          <div class="day-topics">
            ${d.topics.map(t=>`<div class="day-topic">${t}</div>`).join('')}
          </div>
          <div class="day-footer">
            <span class="day-time">⏱ 2 hours</span>
            <button class="mark-btn" onclick="toggleDay(${d.day_number},event)">
              ${done ? 'Undo' : 'Done'}
            </button>
          </div>
          ${d.progress?.notes ? `<div style="margin-top:10px;font-size:11px;color:var(--muted);border-top:1px solid var(--border);padding-top:8px;line-height:1.5">📝 ${d.progress.notes}</div>` : ''}
        </div>
      `;
    }).join('');

    return `
      <div class="week-section" data-week="${w}">
        <div class="week-header">
          <span class="week-title">${meta.label}</span>
          <span class="week-badge ${meta.cls}" style="border:1px solid;border-color:${meta.color}30;background:${meta.color}15;color:${meta.color}">
            W${w}
          </span>
        </div>
        <div class="days-grid">${dayCards}</div>
      </div>
    `;
  }).join('');

  applyFilterNoButton(state.filter);
}

function updateProgress() {
  const done = state.days.filter(d => d.progress?.completed).length;
  const pct  = Math.round(done / 30 * 100);
  document.getElementById('prog-pct').textContent  = pct + '%';
  document.getElementById('prog-fill').style.width = pct + '%';
  document.getElementById('prog-done').textContent = done;
  document.getElementById('prog-hrs').textContent  = (done * 2) + 'h';
  document.getElementById('prog-left').textContent = ((30 - done) * 2) + 'h';

  if (state.user) {
    document.getElementById('dash-greeting').textContent =
      `Welcome back, ${state.user.name.split(' ')[0]} 👋`;
  }
}

async function toggleDay(dayNum, e) {
  e.stopPropagation();
  if (!state.token) { navigate('auth'); return; }

  const day  = state.days.find(d => d.day_number === dayNum);
  if (!day) return;
  const done = !(day.progress?.completed);

  const res = await post(`/plan?action=progress&day=${dayNum}`, { completed: done });
  if (!res.ok) { showToast('Could not save progress', 'error'); return; }

  if (!day.progress) day.progress = {};
  day.progress.completed = done;
  day.progress.completed_at = done ? new Date().toISOString() : null;

  showToast(done ? `Day ${dayNum} completed! 🎉` : `Day ${dayNum} marked incomplete`, done?'success':'');

  // Update card in DOM without full re-render
  const card = document.querySelector(`.day-card[data-day="${dayNum}"]`);
  if (card) {
    card.classList.toggle('done', done);
    card.querySelector('.mark-btn').textContent = done ? 'Undo' : 'Done';
  }
  updateProgress();
}

/* ── FILTERS ── */
function applyFilter(f, btn) {
  state.filter = f;
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  applyFilterNoButton(f);
}

function applyFilterNoButton(f) {
  document.querySelectorAll('.day-card').forEach(card => {
    const week = card.dataset.week;
    const done = card.classList.contains('done');
    let show = true;
    if (f === 'done') show = done;
    else if (f === 'todo') show = !done;
    else if (f === 'w1') show = week === '1';
    else if (f === 'w2') show = week === '2';
    else if (f === 'w3') show = week === '3';
    else if (f === 'w4') show = week === '4';
    card.style.display = show ? '' : 'none';
  });
  document.querySelectorAll('.week-section').forEach(ws => {
    const anyVisible = [...ws.querySelectorAll('.day-card')].some(c => c.style.display !== 'none');
    ws.style.display = anyVisible ? '' : 'none';
  });
}

/* ── NOTES MODAL ── */
let notesDayNum = null;
function openNotes(dayNum) {
  notesDayNum = dayNum;
  const day = state.days.find(d => d.day_number === dayNum);
  document.getElementById('modal-day-num').textContent   = dayNum;
  document.getElementById('modal-day-title').textContent = day?.title || '';
  document.getElementById('modal-notes').value           = day?.progress?.notes || '';
  document.getElementById('notes-modal').classList.add('show');
}
function closeModal() { document.getElementById('notes-modal').classList.remove('show'); }
async function saveNotes() {
  const notes = document.getElementById('modal-notes').value.trim();
  if (!notesDayNum) return;
  const res = await post(`/plan?action=progress&day=${notesDayNum}`, { notes });
  if (!res.ok) { showToast('Could not save notes','error'); return; }
  const day = state.days.find(d => d.day_number === notesDayNum);
  if (day) { if(!day.progress) day.progress={}; day.progress.notes = notes; }
  closeModal();
  showToast('Notes saved!','success');
  renderPlan();
}

/* ============================================================
   ADMIN
   ============================================================ */
async function loadAdminStats() {
  if (!state.adminData) {
    const res = await get('/admin?action=stats');
    if (!res.ok) return;
    state.adminData = res.data;
  }
  renderAdminOverview();
}

function renderAdminOverview() {
  const d = state.adminData;
  // Stat cards
  document.getElementById('admin-stat-cards').innerHTML = `
    <div class="stat-card"><span class="stat-card-num">${d.summary.total_students}</span><span class="stat-card-label">Students</span></div>
    <div class="stat-card"><span class="stat-card-num">${d.summary.active_sessions}</span><span class="stat-card-label">Active Sessions</span></div>
    <div class="stat-card"><span class="stat-card-num">30</span><span class="stat-card-label">Plan Days</span></div>
    <div class="stat-card"><span class="stat-card-num">${d.day_stats.length>0?d.day_stats.reduce((a,b)=>a+(+b.completions),0):0}</span><span class="stat-card-label">Total Completions</span></div>
  `;
  // Top students
  document.querySelector('#admin-top-table tbody').innerHTML = d.top_students.map(u=>`
    <tr>
      <td style="color:var(--text)">${u.name}</td>
      <td>${u.email}</td>
      <td><span class="badge badge-green">${u.completed}/30</span></td>
      <td>
        <div class="mini-bar"><div class="mini-fill" style="width:${Math.round(u.completed/30*100)}%"></div></div>
      </td>
    </tr>
  `).join('') || '<tr><td colspan="4" style="text-align:center;color:var(--muted)">No students yet</td></tr>';

  // Recent activity
  document.querySelector('#admin-activity-table tbody').innerHTML = d.recent_activity.map(r=>`
    <tr>
      <td style="color:var(--text)">${r.name}</td>
      <td>Day ${r.day_number} — ${r.title}</td>
      <td><span class="badge ${r.completed?'badge-green':'badge-blue'}">${r.completed?'Done':'Viewed'}</span></td>
      <td style="color:var(--muted)">${timeAgo(r.updated_at)}</td>
    </tr>
  `).join('') || '<tr><td colspan="4" style="text-align:center;color:var(--muted)">No activity yet</td></tr>';
}

async function loadAdminUsers() {
  if (!state.adminUsers) {
    const res = await get('/admin?action=users');
    if (!res.ok) return;
    state.adminUsers = res.data;
  }
  document.querySelector('#admin-users-table tbody').innerHTML = state.adminUsers.users.map(u=>`
    <tr>
      <td style="color:var(--text)">${u.name}</td>
      <td>${u.email}</td>
      <td><span class="badge ${u.role==='admin'?'badge-gold':'badge-blue'}">${u.role}</span></td>
      <td>${u.days_done||0}/30</td>
      <td style="color:var(--muted)">${u.created_at?.split('T')[0]||u.created_at?.split(' ')[0]}</td>
      <td>${u.role!=='admin'?`<button class="btn btn-danger btn-sm" onclick="deleteUser(${u.id},'${u.name}')">Delete</button>`:''}</td>
    </tr>
  `).join('');
}

function loadAdminDays() {
  if (!state.adminData) return;
  document.querySelector('#admin-days-table tbody').innerHTML = state.adminData.day_stats.map(d=>`
    <tr>
      <td style="color:var(--text)">Day ${d.day_number}</td>
      <td>${d.title}</td>
      <td>${d.attempts||0}</td>
      <td>${d.completions||0}</td>
      <td>
        ${d.attempts>0?`<span class="badge badge-green">${Math.round(d.completions/d.attempts*100)}%</span>`:'<span class="badge">—</span>'}
      </td>
    </tr>
  `).join('');
}

async function deleteUser(id, name) {
  if (!confirm(`Delete user "${name}"? This cannot be undone.`)) return;
  const res = await post(`/admin?action=delete&id=${id}`, {});
  if (!res.ok) { showToast('Could not delete user','error'); return; }
  state.adminUsers = null;
  loadAdminUsers();
  showToast(`User "${name}" deleted`,'success');
}

function switchAdminTab(tab) {
  document.querySelectorAll('.admin-tab').forEach(t=>t.classList.remove('active'));
  event.target.classList.add('active');
  document.querySelectorAll('.admin-view').forEach(v=>v.classList.remove('active'));
  document.getElementById('admin-'+tab).classList.add('active');
  if (tab==='users') loadAdminUsers();
  if (tab==='days')  loadAdminDays();
}

/* ============================================================
   HELPERS
   ============================================================ */
async function get(path) {
  const headers = {};
  if (state.token) headers['Authorization'] = 'Bearer '+state.token;
  const r = await fetch(API+path, { headers });
  return r.json().catch(()=>({ok:false,msg:'Network error'}));
}

async function post(path, data) {
  const headers = { 'Content-Type':'application/json' };
  if (state.token) headers['Authorization'] = 'Bearer '+state.token;
  const r = await fetch(API+path, { method:'POST', headers, body:JSON.stringify(data) });
  return r.json().catch(()=>({ok:false,msg:'Network error'}));
}

function showToast(msg, type='') {
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.className = 'show' + (type?' '+type:'');
  setTimeout(()=>{ t.classList.remove('show'); }, 3000);
}

function timeAgo(dateStr) {
  if (!dateStr) return '—';
  const d = new Date(dateStr.replace(' ','T'));
  const sec = Math.floor((Date.now()-d)/1000);
  if (sec<60) return 'just now';
  if (sec<3600) return Math.floor(sec/60)+'m ago';
  if (sec<86400) return Math.floor(sec/3600)+'h ago';
  return Math.floor(sec/86400)+'d ago';
}

// Close modal on overlay click
document.getElementById('notes-modal').addEventListener('click', function(e) {
  if (e.target === this) closeModal();
});

// Enter key on auth forms
document.addEventListener('keydown', e => {
  if (e.key === 'Enter') {
    if (document.getElementById('page-auth').classList.contains('active')) {
      if (document.getElementById('auth-login').style.display !== 'none') doLogin();
      else doRegister();
    }
  }
});
</script>
</body>
</html>
