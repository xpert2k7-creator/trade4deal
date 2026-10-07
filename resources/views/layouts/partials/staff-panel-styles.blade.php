<style>
    :root {
        --t4d-primary: #0B3A6E;
        --t4d-primary-dark: #082F58;
        --t4d-accent: #0E7490;
        --t4d-orange: #F58220;
        --t4d-dark: #0F172A;
        --t4d-muted: #64748B;
        --t4d-border: #E2E8F0;
        --t4d-bg: #F7F8FA;
        --t4d-card: #FFFFFF;
        --t4d-sidebar: #0B3A6E;
        --t4d-radius: 14px;
        --t4d-font: 'Open Sans', system-ui, sans-serif;
    }

    * { box-sizing: border-box; }

    body {
        font-family: var(--t4d-font);
        background: var(--t4d-bg);
        color: var(--t4d-dark);
        min-height: 100vh;
        margin: 0;
    }

    .emp-shell {
        display: grid;
        grid-template-columns: 260px 1fr;
        min-height: 100vh;
    }

    .emp-sidebar {
        background: linear-gradient(180deg, #0B3A6E 0%, #082F58 100%);
        color: #fff;
        padding: 1.5rem 1.15rem;
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        height: 100vh;
    }

    .emp-sidebar .logo-wrap {
        padding: 0.25rem 0.5rem 1.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.12);
        margin-bottom: 1.25rem;
    }

    .emp-sidebar .logo-wrap img {
        display: block;
        width: auto;
        max-width: 100%;
        max-height: 76px;
        object-fit: contain;
        background: #fff;
        border-radius: 8px;
        padding: 0.35rem 0.5rem;
    }

    .emp-nav {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        flex: 1;
    }

    .emp-nav a {
        display: flex;
        align-items: center;
        gap: 0.7rem;
        padding: 0.7rem 0.9rem;
        border-radius: 10px;
        color: rgba(255,255,255,0.78);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.92rem;
        transition: background 0.15s ease, color 0.15s ease;
    }

    .emp-nav a:hover {
        background: rgba(255,255,255,0.1);
        color: #fff;
    }

    .emp-nav a.active {
        background: rgba(255,255,255,0.16);
        color: #fff;
    }

    .emp-nav a i { font-size: 1.1rem; opacity: 0.9; }

    .emp-user {
        border-top: 1px solid rgba(255,255,255,0.12);
        padding-top: 1rem;
        margin-top: 1rem;
    }

    .emp-user .name { font-weight: 700; font-size: 0.9rem; }
    .emp-user .role { font-size: 0.75rem; color: rgba(255,255,255,0.65); }

    .emp-main { min-width: 0; }

    .emp-topbar {
        background: rgba(255,255,255,0.92);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--t4d-border);
        padding: 1rem 1.75rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        position: sticky;
        top: 0;
        z-index: 20;
    }

    .emp-topbar h1 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--t4d-dark);
    }

    .emp-content { padding: 1.5rem 1.75rem 2.5rem; }

    .kpi-card {
        background: var(--t4d-card);
        border: 1px solid var(--t4d-border);
        border-radius: var(--t4d-radius);
        padding: 1.15rem 1.25rem;
        height: 100%;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
    }

    .kpi-card .label {
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: var(--t4d-muted);
        margin-bottom: 0.35rem;
    }

    .kpi-card .value {
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        color: var(--t4d-primary);
        line-height: 1.1;
    }

    .kpi-card.pending .value { color: #B45309; }
    .kpi-card.active .value { color: #059669; }
    .kpi-card.rejected .value { color: #64748B; }

    .panel {
        background: var(--t4d-card);
        border: 1px solid var(--t4d-border);
        border-radius: var(--t4d-radius);
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .panel-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--t4d-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .panel-header h2 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
    }

    .panel-body { padding: 1.25rem; }

    .table-emp {
        --bs-table-bg: transparent;
        margin: 0;
    }

    .table-emp thead th {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: var(--t4d-muted);
        font-weight: 700;
        border-bottom-color: var(--t4d-border);
        white-space: nowrap;
    }

    .table-emp td {
        vertical-align: middle;
        border-color: var(--t4d-border);
        font-size: 0.92rem;
    }

    .btn-primary-t4d {
        background: var(--t4d-primary);
        border: none;
        border-radius: 10px;
        font-weight: 600;
        color: #fff;
    }

    .btn-primary-t4d:hover { background: var(--t4d-primary-dark); color: #fff; }

    .status-tabs .nav-link {
        color: var(--t4d-muted);
        font-weight: 600;
        border: none;
        border-bottom: 2px solid transparent;
        border-radius: 0;
        padding: 0.75rem 1rem;
    }

    .status-tabs .nav-link.active {
        color: var(--t4d-primary);
        border-bottom-color: var(--t4d-primary);
        background: transparent;
    }

    .form-control, .form-select {
        border-radius: 10px;
        border: 1.5px solid #CBD5E1;
        background-color: #E8EEF5 !important;
        color: #0F172A !important;
        padding: 0.65rem 0.85rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0B3A6E !important;
        box-shadow: 0 0 0 3px rgba(11, 58, 110, 0.18) !important;
        background-color: #E8EEF5 !important;
        color: #0F172A !important;
    }

    .form-label { font-weight: 600; color: var(--t4d-dark); }

    @media (max-width: 991px) {
        .emp-shell { grid-template-columns: 1fr; }
        .emp-sidebar {
            position: relative;
            height: auto;
            padding-bottom: 1rem;
        }
        .emp-nav { flex-direction: row; flex-wrap: wrap; }
        .emp-content { padding: 1rem; }
        .emp-topbar { padding: 0.85rem 1rem; }
    }
</style>
