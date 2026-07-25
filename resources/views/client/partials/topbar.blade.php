@php $current = $current ?? null; @endphp
<header style="position:sticky;top:0;z-index:150;height:60px;background:var(--hero);border-bottom:1px solid rgba(231,200,121,.14);display:flex;align-items:center;">
    <div class="hd-inner" style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
        @if ($current)
            <div style="display:flex;align-items:center;gap:8px;color:#D2C2A1;font-size:12.5px;font-weight:600;min-width:0;overflow:hidden;">
                <a href="{{ route('client.pemesanan.index') }}" style="color:#D2C2A1;text-decoration:none;">Portal Klien</a>
                <span style="opacity:.4;">/</span>
                <span style="color:#FCF7EB;font-weight:700;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $current }}</span>
            </div>
        @else
            <a href="{{ route('client.pemesanan.index') }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;">
                <span style="width:28px;height:28px;border-radius:50%;background:var(--gold-grad);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:11px;color:var(--ink);flex:none;">TW</span>
                <span style="font-family:var(--serif);font-size:15px;color:#FCF7EB;">Taf <em style="color:var(--gold3);font-style:italic;">Wedding</em></span>
            </a>
        @endif

        <div x-data="{ userMenu: false }" style="position:relative;flex:none;">
            <button type="button" @click="userMenu = !userMenu" @click.away="userMenu = false" style="background:rgba(255,255,255,.07);border:1px solid rgba(231,200,121,.22);padding:4px 10px 4px 4px;border-radius:999px;display:flex;align-items:center;gap:8px;cursor:pointer;">
                <span style="width:26px;height:26px;background:var(--gold-grad);border-radius:50%;display:flex;align-items:center;justify-content:center;color:var(--ink);font-weight:800;font-size:11px;flex:none;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </span>
                <span style="color:#FCF7EB;font-size:12.5px;font-weight:600;white-space:nowrap;">{{ auth()->user()->name }}</span>
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" :style="userMenu ? 'transform:rotate(180deg)' : ''" style="transition:transform .2s;"><polyline points="6 9 12 15 18 9"/></svg>
            </button>
            <div x-show="userMenu" x-transition x-cloak style="position:absolute;top:calc(100% + 8px);right:0;background:#FFFFFF;border:1.5px solid var(--border2);border-radius:11px;box-shadow:var(--sh2);min-width:170px;overflow:hidden;z-index:50;">
                @if ($current)
                    <a href="{{ route('client.pemesanan.index') }}" style="width:100%;text-align:left;background:transparent;border:none;padding:11px 15px;font-size:12.5px;font-weight:600;color:var(--ink3);display:flex;align-items:center;gap:8px;text-decoration:none;">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9.5 12 3l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V9.5Z"/></svg>
                        Portal Klien
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" style="width:100%;text-align:left;background:transparent;border:none;padding:11px 15px;font-size:12.5px;font-weight:600;color:var(--red);cursor:pointer;display:flex;align-items:center;gap:8px;">
                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>