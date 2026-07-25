<style>
/* ===================================================================
   TAF WEDDING — Design System v3 (Enterprise Widescreen Optimized)
   =================================================================== */
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root{
  --serif:'Cormorant Garamond',Georgia,serif;
  --sans:'Plus Jakarta Sans',system-ui,-apple-system,sans-serif;

  /* surfaces */
  --bg:#E8DFCB;
  --bg2:#F3ECDC;
  --bg3:#E0D4BA;
  --card:#FFFFFF;
  --border:#D2C2A1;
  --border2:#C3B083;

  /* typography */
  --ink:#1B1710;
  --ink2:#332B1E;
  --ink3:#564A37;
  --ink4:#6F6149;
  --muted:#897A60;

  /* accents */
  --gold:#9C6F22; --gold2:#875F1C; --gold3:#E7C879; --goldDeep:#6E4F18;
  --gold-grad:linear-gradient(135deg,#E9CD7E 0%,#C49A3D 50%,#9C6F22 100%);
  --goldBg:#F4E7C7;

  --hero:#14110B;
  --green:#1F7A4D; --greenBg:#DCEFE3;
  --red:#BE3850;   --redBg:#FAE2E6;
  --orange:#B5781C;--orangeBg:#FBEBCF;
  --blue:#2E5E96;  --blueBg:#E1EAF5;

  --r1:20px; --r2:15px; --r3:11px;
  --sh1:0 2px 6px rgba(55,40,12,.08);
  --sh2:0 8px 22px rgba(55,40,12,.12);
  --sh3:0 16px 40px rgba(55,40,12,.16);
  --hd:64px;
  --maxw:1180px; /* Batas landing page publik */
}

*{box-sizing:border-box;margin:0;padding:0}
[x-cloak]{display:none!important}
html{-webkit-text-size-adjust:100%}
body{font-family:var(--sans);background:var(--bg);color:var(--ink2);font-size:15px;line-height:1.6;
  -webkit-font-smoothing:antialiased;overflow-x:hidden}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}
button{font-family:inherit;cursor:pointer}
table{border-collapse:collapse;width:100%}
code{background:var(--bg2);padding:1px 6px;border-radius:5px;font-size:.88em;color:var(--gold2)}
.st-val,.o-mv,.totval,.bar-v,.val-line{font-variant-numeric:tabular-nums}

.fade{animation:fade .45s ease both}
@keyframes fade{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:none}}
@keyframes rise{from{opacity:0;transform:translateY(14px)}to{opacity:1;transform:none}}

/* ============ BUTTONS ============ */
.btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;font-weight:700;font-size:14px;
  line-height:1;padding:11px 18px;border-radius:999px;border:1.5px solid transparent;
  transition:transform .16s,box-shadow .16s,background .16s,color .16s,border-color .16s;white-space:nowrap}
.btn:active{transform:scale(.97)}
.btn-sm{padding:8px 14px;font-size:13px}
.btn-full{width:100%}

.btn-gold{background:var(--gold-grad);color:#2A1E06;box-shadow:0 6px 15px rgba(156,111,34,.35);border-color:#C49A3D}
.btn-gold:hover{box-shadow:0 9px 22px rgba(156,111,34,.45);transform:translateY(-1px);border-color:#9C6F22}
.btn-dark{background:var(--ink);color:#F3E6C9;border-color:var(--ink)}
.btn-dark:hover{background:#000;border-color:#000}
.btn-outline{background:var(--card);border-color:var(--border2);color:var(--ink2)}
.btn-outline:hover{border-color:var(--gold);color:var(--gold2)}
.btn-ghost{background:transparent;border-color:transparent;color:var(--ink4)}
.btn-ghost:hover{color:var(--ink);background:var(--bg2);border-color:var(--bg2)}
.btn-green{background:var(--green);color:#fff;border-color:var(--green)}
.btn-green:hover{background:#19623d;border-color:#19623d}
.btn-red{background:var(--red);color:#fff;border-color:var(--red)}
.btn-red:hover{background:#a32f44;border-color:#a32f44}
.btn-wa{background:#1f9d57;color:#fff;border-color:#1f9d57}
.btn-wa:hover{background:#198047;border-color:#198047}

.badge{display:inline-flex;align-items:center;gap:5px;font-size:11.5px;font-weight:700;
  padding:5px 11px;border-radius:999px;white-space:nowrap;border:1px solid transparent}
.b-gold{background:var(--goldBg);color:var(--gold2);border-color:#E6D2A2}
.b-green{background:var(--greenBg);color:var(--green);border-color:#b6dcc4}
.b-red{background:var(--redBg);color:var(--red);border-color:#f0c2cb}
.b-orange{background:var(--orangeBg);color:var(--orange);border-color:#ecd09a}
.b-blue{background:var(--blueBg);color:var(--blue);border-color:#c2d4ea}

.muted{color:var(--muted)}
.text-center{text-align:center}
.mt-2{margin-top:12px}.mb-2{margin-bottom:12px}
.flex-gap{display:flex;gap:8px;align-items:center}
.flex-wrap{flex-wrap:wrap}
.flex-between{display:flex;justify-content:space-between;align-items:center;gap:10px}
.pill-link{background:none;border:none;color:var(--gold2);font-weight:700;font-size:13px;font-family:var(--sans)}
.pill-link:hover{color:var(--gold)}
.divider{height:1px;background:var(--border);margin:16px 0}
.divider-sm{height:1px;background:var(--border);margin:10px 0}

/* ============ CARDS ============ */
.card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r2);
  box-shadow:var(--sh1);margin-bottom:15px;overflow:hidden}
.card-h{display:flex;justify-content:space-between;align-items:center;gap:10px;
  padding:12px 16px;border-bottom:1.5px solid var(--border);background:var(--bg2)}
.card-t{font-weight:800;font-size:15px;color:var(--ink)}
.card-b{padding:16px}

/* ============ ALERTS ============ */
.alert{display:flex;gap:11px;align-items:flex-start;padding:12px 15px;border-radius:var(--r3);
  margin-bottom:14px;font-size:13.5px;line-height:1.5;border:1.5px solid}
.alert svg{width:19px;height:19px;flex:none;margin-top:1px}
.alert ul{margin:0}
.alert-success{background:var(--greenBg);border-color:#a9d6bb;color:#185c39}
.alert-error{background:var(--redBg);border-color:#eeb6c0;color:#94283c}
.alert-warn{background:var(--orangeBg);border-color:#e8c98c;color:#825414}
.alert-info{background:var(--blueBg);border-color:#b6cae6;color:#284f78}

/* ============ FORMS ============ */
.field{margin-bottom:13px}
.field label{display:block;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.6px;
  color:var(--ink3);margin-bottom:6px}
.input{width:100%;font-family:var(--sans);font-size:15px;color:var(--ink);background:var(--card);
  border:1.5px solid var(--border2);border-radius:var(--r3);padding:10px 13px;
  transition:border .16s,box-shadow .16s}
.input:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 3px rgba(156,111,34,.18)}
textarea.input{min-height:84px;resize:vertical}
.row{display:grid;grid-template-columns:1fr;gap:0 16px}
.err-msg{font-size:12px;margin-top:6px}

/* ============ TABLES ============ */
.tbl-wrap{width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch;}
table thead th{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:var(--ink3);font-weight:800;
  text-align:left;padding:10px 14px;border-bottom:2px solid var(--border);background:var(--bg2)}
table tbody td{padding:11px 14px;border-bottom:1px solid var(--border);font-size:13.5px;color:var(--ink2);
  vertical-align:middle; word-wrap:break-word; overflow-wrap:break-word;}
table tbody tr:last-child td{border-bottom:none}
table tbody tr:hover{background:var(--bg2)}

.summary-line{display:flex;justify-content:space-between;gap:14px;padding:8px 0;font-size:14px;
  border-bottom:1px dashed var(--border)}
.summary-line:last-child{border-bottom:none}
.summary-line.total{font-weight:800;color:var(--ink);font-size:16.5px;border-top:2px solid var(--border);
  border-bottom:none;margin-top:4px;padding-top:12px}

/* RESPONSIVE TABLE → KARTU (mobile) */
.rtable,.rtable tbody,.rtable tr,.rtable td{display:block;width:100%}
.rtable thead{display:none}
.rtable tr{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r3);
  margin-bottom:14px;padding:5px 0;box-shadow:var(--sh1)}
.rtable td{border:none;display:flex;justify-content:space-between;align-items:center;gap:14px;
  padding:10px 16px;text-align:right;font-size:13.5px}
.rtable td::before{content:attr(data-label);font-weight:700;font-size:11px;text-transform:uppercase;
  letter-spacing:.4px;color:var(--muted);text-align:left;flex:none}
.rtable td:empty{display:none}
.rtable td.cell-actions{justify-content:flex-end;border-top:1px dashed var(--border);margin-top:3px;padding-top:12px}
.rtable td.cell-actions::before{display:none}

@media (min-width:880px){
  /* table-layout fixed memaksa tabel membagi lebarnya ADIL ke semua kolom */
  .rtable{display:table; width:100%; table-layout:fixed;}
  .rtable thead{display:table-header-group}
  .rtable tbody{display:table-row-group}
  .rtable tr{display:table-row;background:transparent;border:none;border-radius:0;margin:0;padding:0;box-shadow:none}
  .rtable tr:hover{background:var(--bg2)}
  .rtable td{display:table-cell;text-align:left;padding:13px 16px;border-bottom:1px solid var(--border);justify-content:flex-start}
  .rtable td::before{display:none}
  .rtable td.cell-actions{border-top:none;margin:0;padding:13px 16px}
}

/* ===================================================================
   LANDING
   =================================================================== */
.site-header{position:fixed;top:0;left:0;right:0;z-index:100;height:var(--hd);display:flex;align-items:center;
  background:transparent;border-bottom:1px solid transparent;
  transition:background .3s ease,border-color .3s ease,backdrop-filter .3s ease}
.site-header.scrolled{background:rgba(18,14,9,.8);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);
  border-bottom-color:rgba(231,200,121,.22)}
.site-header .wrap{max-width:var(--maxw);margin:0 auto;padding:0 20px;width:100%;
  display:flex;align-items:center;justify-content:space-between;gap:10px}
.logo{display:flex;align-items:center;gap:9px;font-family:var(--serif);font-weight:700;font-size:20px;color:#F4E8CC;min-width:0}
.logo em{font-style:italic;color:var(--gold3)}
.logo-emblem{width:28px;height:28px;object-fit:contain;flex:none}
.topnav-r{display:flex;gap:8px;align-items:center;flex:none}

.hero { position: relative; display: flex; flex-direction: column; justify-content: center; min-height: 85vh; }
.hero-bg-layer {
  position: absolute; top: 0; left: 0; width: 100%; height: 100vh; overflow: hidden;
  background:
    radial-gradient(42% 46% at 16% 22%,rgba(201,162,75,.22),transparent 70%),
    radial-gradient(44% 50% at 84% 16%,rgba(231,200,121,.15),transparent 72%),
    radial-gradient(72% 60% at 50% 110%,rgba(156,111,34,.26),transparent 72%),
    linear-gradient(165deg,#13100A 0%,#1E1810 48%,#100D08 100%);
}
.hero-bg-layer::before, .hero-bg-layer::after { content:''; position:absolute; border-radius:50%; filter:blur(64px); opacity:.5; pointer-events:none; }
.hero-bg-layer::before { width:300px; height:300px; background:radial-gradient(circle,#c79e44,transparent 64%); top:-40px; left:-50px; animation:orb1 15s ease-in-out infinite; }
.hero-bg-layer::after { width:360px; height:360px; background:radial-gradient(circle,#7d5e28,transparent 64%); bottom:-120px; right:-70px; animation:orb2 19s ease-in-out infinite; }
@keyframes orb1{0%,100%{transform:translate(0,0)}50%{transform:translate(34px,42px)}}
@keyframes orb2{0%,100%{transform:translate(0,0)}50%{transform:translate(-42px,-32px)}}

#hero-bg-base { z-index: -2; } 
#hero-bg-fade { z-index: 5; transition: opacity 0.6s ease; pointer-events: none; }
#hero-bg-fade.scrolled-out { opacity: 0; }

.hero-center{position:relative;z-index:10;display:flex;justify-content:center;text-align:center;padding:0 20px;}
.hero-inner{max-width:680px; margin-top:var(--hd);}
.hero-badge{width:120px;height:120px;margin:0 auto 22px;position:relative;display:flex;align-items:center;justify-content:center}
.hero-badge::after{content:'';position:absolute;inset:-16%;border-radius:50%;background:radial-gradient(circle,rgba(231,200,121,.3),transparent 62%);filter:blur(8px);z-index:-1}
.hero-badge img{width:112px;height:112px;object-fit:contain;filter:drop-shadow(0 6px 18px rgba(0,0,0,.55))}
.hero-eyebrow{display:inline-block;font-size:11.5px;font-weight:700;letter-spacing:2.2px;text-transform:uppercase;color:#EBDBAE;padding:8px 17px;border-radius:999px;margin-bottom:18px;background:rgba(255,255,255,.06);border:1px solid rgba(231,200,121,.3);backdrop-filter:blur(8px)}
.hero h1{font-family:var(--serif);font-weight:600;color:#FCF7EB;line-height:1.08;font-size:40px;letter-spacing:.3px}
.hero h1 em{font-style:italic;background:linear-gradient(120deg,#F2DEA2,#D9B458 55%,#E7C879);-webkit-background-clip:text;background-clip:text;color:transparent}
.hero-line{width:64px;height:2px;margin:20px auto;border-radius:2px;background:linear-gradient(90deg,transparent,var(--gold3),transparent);position:relative;overflow:hidden}
.hero-line::after{content:'';position:absolute;inset:0;width:40%;background:linear-gradient(90deg,transparent,#fff,transparent);animation:shimmer 3.2s linear infinite}
@keyframes shimmer{0%{transform:translateX(-120%)}100%{transform:translateX(320%)}}
.hero-desc{font-size:16px;color:#DCD0B6;line-height:1.7;max-width:520px;margin:0 auto}
.hero-ctas{display:flex;gap:12px;justify-content:center;flex-wrap:wrap;margin-top:28px}
.hero-ctas .btn-outline{border-color:rgba(231,200,121,.45);color:#EFE2C4;background:rgba(255,255,255,.05)}
.hero-ctas .btn-outline:hover{border-color:var(--gold3);color:#fff;background:rgba(255,255,255,.1)}

@media (prefers-reduced-motion:reduce){.hero-bg-layer::before,.hero-bg-layer::after,.hero-line::after{animation:none}}

.section{max-width:var(--maxw);margin:0 auto;padding:60px 20px}
.sec-head{text-align:center;max-width:600px;margin:0 auto 36px}
.sec-eyebrow{font-size:11px;font-weight:800;letter-spacing:2px;text-transform:uppercase;color:var(--gold);margin-bottom:10px}
.sec-title{font-family:var(--serif);font-weight:600;font-size:31px;color:var(--ink);line-height:1.16}
.sec-title em{font-style:italic;color:var(--gold)}
.sec-desc{font-size:15px;color:var(--ink3);margin-top:11px;line-height:1.65}

.steps-row{display:grid;grid-template-columns:1fr;gap:15px;max-width:560px;margin:0 auto}
.step-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r2);padding:24px 20px;box-shadow:var(--sh1);text-align:center}
.step-badge{width:46px;height:46px;border-radius:50%;background:var(--gold-grad);color:#2A1E06;font-family:var(--serif);font-weight:700;font-size:22px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;box-shadow:0 5px 13px rgba(156,111,34,.32)}
.step-card h4{font-family:var(--serif);font-size:20px;font-weight:600;color:var(--ink);margin-bottom:7px}
.step-card p{font-size:13.5px;color:var(--ink3);line-height:1.6}

.gallery-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
.gal-item{position:relative;border-radius:var(--r2);overflow:hidden;aspect-ratio:1/1;box-shadow:var(--sh1);border:1.5px solid var(--border)}
.gal-inner{width:100%;height:100%;background:var(--bg3)}
.gal-inner img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.gal-item:hover .gal-inner img{transform:scale(1.06)}
.gal-overlay{position:absolute;inset:0;display:flex;align-items:flex-end;padding:16px;background:linear-gradient(to top,rgba(14,10,6,.8),transparent 55%)}
.gal-label{color:#F3E6C9;font-family:var(--serif);font-size:17px;font-weight:600}

.testi-grid{display:grid;grid-template-columns:1fr;gap:16px}
.testi-card{position:relative;background:var(--card);border:1.5px solid var(--border);border-radius:var(--r2);padding:26px 22px;box-shadow:var(--sh1)}
.testi-quote{position:absolute;top:6px;right:18px;font-family:var(--serif);font-size:62px;color:var(--bg3);line-height:1}
.testi-stars{display:flex;gap:3px;margin-bottom:11px}
.testi-star{color:var(--gold);font-size:16px}
.testi-text{font-family:var(--serif);font-style:italic;font-size:17px;color:var(--ink2);line-height:1.55;position:relative}
.testi-author{margin-top:13px;font-size:13px;font-weight:700;color:var(--gold2)}

.why-grid{display:grid;grid-template-columns:1fr;gap:16px;max-width:760px;margin:0 auto}
.why-card{display:flex;gap:16px;background:var(--card);border:1.5px solid var(--border);border-radius:var(--r2);padding:22px;box-shadow:var(--sh1)}
.why-icon{width:48px;height:48px;border-radius:12px;background:var(--gold-grad);color:#2A1E06;display:flex;align-items:center;justify-content:center;flex:none}
.why-icon svg{width:24px;height:24px}
.why-title{font-weight:800;font-size:16px;color:var(--ink);margin-bottom:5px}
.why-desc{font-size:13.5px;color:var(--ink3);line-height:1.6}

.cta-banner{position:relative;overflow:hidden;border-radius:var(--r1);text-align:center;padding:54px 26px;color:#F8F1E3;background:radial-gradient(60% 80% at 50% 0%,rgba(201,162,75,.3),transparent 70%),linear-gradient(160deg,#1C1710,#110E08)}
.cta-banner h2{font-family:var(--serif);font-weight:600;font-size:32px;line-height:1.16;margin-bottom:12px}
.cta-banner h2 em{font-style:italic;color:var(--gold3)}
.cta-banner p{font-size:15.5px;color:#DCD0B6;max-width:440px;margin:0 auto 26px;line-height:1.6}

.footer{background:var(--hero);color:#CFC1A6;text-align:center;padding:46px 20px 32px;margin-top:10px}
.footer .logo{justify-content:center;margin-bottom:16px}
.footer-contact{font-size:13.5px;color:#A99B82;line-height:1.8;max-width:520px;margin:0 auto}
.footer-line{height:1px;background:rgba(231,200,121,.2);max-width:340px;margin:24px auto}
.footer p{font-size:12.5px;color:#897C66}

/* ===================================================================
   ADMIN LAYOUT
   =================================================================== */
.topbar{display:flex;align-items:center;justify-content:space-between;gap:10px;position:sticky;top:0;z-index:60;background:var(--hero);padding:11px 16px;border-bottom:1px solid rgba(231,200,121,.16)}
.topbar .logo{font-size:18px}
.hamburger{width:42px;height:42px;border-radius:11px;border:1px solid rgba(231,200,121,.25);background:rgba(255,255,255,.06);color:#EFE2C4;display:flex;align-items:center;justify-content:center}
.hamburger svg{width:21px;height:21px}
.backdrop{position:fixed;inset:0;background:rgba(12,9,6,.55);backdrop-filter:blur(2px);z-index:70;opacity:0;visibility:hidden;transition:opacity .25s}
.backdrop.show{opacity:1;visibility:visible}

.layout{display:block;min-height:100vh}
.side{position:fixed;top:0;left:0;bottom:0;width:264px;z-index:80;background:linear-gradient(180deg,#1A150E,#110E08);color:#E9DBBF;display:flex;flex-direction:column;padding:22px 18px;transform:translateX(-100%);transition:transform .28s ease;overflow-y:auto}
.side.open{transform:translateX(0)}
.side-top{display:flex;align-items:center;gap:12px;padding:4px 6px 20px;border-bottom:1px solid rgba(231,200,121,.14);margin-bottom:16px}
.side-emblem{width:40px;height:40px;flex:none}
.side-emblem img{width:40px;height:40px;object-fit:contain}
.side-logo{font-family:var(--serif);font-weight:700;font-size:19.5px;color:#F4E8CC}
.side-logo em{font-style:italic;color:var(--gold3)}
.side-nav{display:flex;flex-direction:column;gap:4px;flex:1}
.nav-i{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:12px;font-size:14.5px;font-weight:600;color:rgba(233,219,191,.72);transition:background .16s,color .16s}
.nav-i:hover{background:rgba(231,200,121,.1);color:#F4E8CC}
.nav-i.on{background:var(--gold-grad);color:#2A1E06}
.nav-i .ico{display:flex;width:22px;height:22px;flex:none}
.nav-i .ico svg{width:22px;height:22px}
.side-foot{border-top:1px solid rgba(231,200,121,.14);padding-top:16px;margin-top:12px}
.side-user{display:flex;align-items:center;gap:12px}
.av{width:42px;height:42px;border-radius:12px;background:var(--gold-grad);color:#2A1E06;font-weight:800;font-size:14px;display:flex;align-items:center;justify-content:center;flex:none}
.side-name{font-size:13.5px;font-weight:700;color:#F4E8CC}
.side-role{font-size:11px;color:rgba(233,219,191,.55)}

.main{min-height:100vh}
.main-inner{margin:0 auto;padding:16px 14px 40px}

.pg-head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:16px}
.pg-head h1{font-family:var(--serif);font-weight:600;font-size:27px;color:var(--ink);line-height:1.15}
.pg-head p{font-size:13.5px;color:var(--ink4);margin-top:3px}

/* ============ KARTU STATISTIK (MOBILE KEMBALI PADAT 2x2) ============ */
.stats{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:14px}
.st{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r2);padding:14px;box-shadow:var(--sh1);display:block}
.st-ico{width:38px;height:38px;border-radius:10px;display:none;align-items:center;justify-content:center;margin-bottom:9px;background:var(--bg2);color:var(--ink3)}
.st-ico svg{width:20px;height:20px}
.st-lbl{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--muted);line-height:1.3}
.st-val{font-family:var(--sans);font-weight:800;font-size:21px;color:var(--ink);margin:3px 0 2px;letter-spacing:-.3px;line-height:1.15}
.st-sub{font-size:11.5px;font-weight:600}

/* Grid Dasar Mobile */
.grid2{display:grid;grid-template-columns:1fr;gap:14px}
.grid-jadwal{display:grid;grid-template-columns:1fr;gap:14px}

/* OPTIMASI KARTU PEMESANAN AGAR MULTI-KOLOM OTOMATIS */
.ogrid{display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:14px}

.pager{display:flex;justify-content:center;align-items:center;gap:6px;margin-top:20px;flex-wrap:wrap}
.pg-btn{min-width:40px;height:40px;display:inline-flex;align-items:center;justify-content:center;border-radius:10px;border:1.5px solid var(--border2);background:var(--card);font-weight:700;font-size:13.5px;color:var(--ink2)}
.pg-btn:hover{border-color:var(--gold);color:var(--gold2)}
.pg-btn.on{background:var(--gold-grad);border-color:transparent;color:#2A1E06}
.pg-btn:disabled{opacity:.4;cursor:default}

/* ============ OPTIMASI CALENDAR AGAR PRESISI ============ */
.cal{display:grid;grid-template-columns:repeat(7,minmax(0,1fr));gap:6px}
.cal-hd{text-align:center;font-size:10.5px;font-weight:800;text-transform:uppercase;color:var(--muted);padding:6px 0}
.cal-d{position:relative;aspect-ratio:1/1;max-height:48px;display:flex;align-items:center;justify-content:center;border-radius:10px;font-size:13.5px;font-weight:700;color:var(--ink3);background:var(--bg2);border:1.5px solid var(--border)}
.cal-d.nil{background:transparent;border:none}
.cal-d.ev{background:var(--gold-grad);color:#2A1E06;font-weight:800;border-color:transparent;box-shadow:0 3px 9px rgba(156,111,34,.32)}
.cal-d.today{background:var(--ink);color:var(--gold3);border:2px solid var(--gold);font-weight:800}
.cal-count{position:absolute;top:3px;right:3px;min-width:16px;height:16px;padding:0 4px;border-radius:9px;background:var(--card);color:var(--gold2);font-size:9.5px;font-weight:800;display:flex;align-items:center;justify-content:center;box-shadow:var(--sh1);border:1px solid var(--border)}
.cal-legend{display:flex;flex-wrap:wrap;gap:14px;align-items:center;margin-top:18px}
.ci{display:flex;align-items:center;gap:7px;font-size:12.5px;color:var(--ink3)}
.cal-sw{width:18px;height:18px;border-radius:6px;flex:none}
.cal-sw.empty{background:var(--bg2);border:1.5px solid var(--border)}
.cal-sw.book{background:var(--gold-grad)}
.cal-sw.now{background:var(--ink);border:2px solid var(--gold)}

/* ============ BARS ============ */
.bars{display:flex;align-items:flex-end;gap:5px;height:188px;padding-top:18px}
.bar-c{flex:1;display:flex;flex-direction:column;align-items:center;height:100%;justify-content:flex-end;gap:5px}
.bar-v{font-size:9.5px;font-weight:700;color:var(--ink4);white-space:nowrap}
.bar{width:100%;max-width:32px;border-radius:6px 6px 0 0;background:var(--gold-grad);min-height:3px;transition:height .6s ease}
.bar-l{font-size:10px;color:var(--muted);font-weight:700}

/* ===================================================================
   PORTAL KLIEN
   =================================================================== */
.cl-page{margin:0 auto;padding:24px 16px 44px}

.cl-head{display:flex;justify-content:space-between;align-items:flex-start;gap:12px;flex-wrap:wrap;margin-bottom:20px}
.cl-head h1{font-family:var(--serif);font-weight:600;color:var(--ink)}

.wa-bar{display:flex;gap:14px;align-items:flex-start;background:var(--greenBg);border:1.5px solid #a9d6bb;border-radius:var(--r2);padding:16px 18px;margin-bottom:20px}
.wa-live{width:10px;height:10px;border-radius:50%;background:var(--green);margin-top:5px;flex:none;animation:pulse 2s infinite}
@keyframes pulse{0%{box-shadow:0 0 0 0 rgba(31,122,77,.45)}70%{box-shadow:0 0 0 9px rgba(31,122,77,0)}100%{box-shadow:0 0 0 0 rgba(31,122,77,0)}}
.wa-bar p{font-size:13px;color:#185c39;line-height:1.55}

.order-card{position:relative;overflow:hidden;border-radius:var(--r1);padding:26px 24px;margin-bottom:20px;color:#F4E8CC;background:radial-gradient(70% 90% at 100% 0%,rgba(201,162,75,.26),transparent 65%),linear-gradient(155deg,#1C1710,#110E08)}
.o-id{font-size:11px;font-weight:700;letter-spacing:1.6px;text-transform:uppercase;color:var(--gold3)}
.o-name{font-family:var(--serif);font-size:28px;font-weight:600;margin:5px 0 6px}
.o-date{font-size:13.5px;color:#C9BBA0}
.o-meta{display:grid;grid-template-columns:1fr 1fr;gap:14px 18px;margin-top:20px;padding-top:18px;border-top:1px solid rgba(231,200,121,.2)}
.o-ml{font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#A99B82;margin-bottom:4px}
.o-mv{font-size:16.5px;font-weight:800;color:#F4E8CC}

.tl{position:relative;padding-left:8px}
.tl-i{display:flex;gap:15px;padding-bottom:22px;position:relative}
.tl-i:not(:last-child)::before{content:'';position:absolute;left:13px;top:30px;bottom:0;width:2px;background:var(--border)}
.tl-dot{width:28px;height:28px;border-radius:50%;flex:none;display:flex;align-items:center;justify-content:center;z-index:1}
.tl-dot svg{width:15px;height:15px}
.tl-ok{background:var(--green);color:#fff}
.tl-now{background:var(--gold-grad);color:#2A1E06}
.tl-wait{background:var(--bg3);color:var(--muted)}
.tl-t{font-weight:700;font-size:15px;color:var(--ink)}
.tl-s{font-size:13px;color:var(--ink3);margin-top:3px}

.upload{border:2px dashed var(--border2);border-radius:var(--r2);padding:26px 20px;text-align:center;cursor:pointer;transition:border .16s,background .16s;background:var(--bg2)}
.upload:hover{border-color:var(--gold);background:var(--goldBg)}
.upload svg{width:32px;height:32px;color:var(--gold2);margin:0 auto 10px}

/* ===================================================================
   FORM PEMESANAN
   =================================================================== */
.form-page{max-width:680px;margin:0 auto;padding:24px 16px 44px}

/* ============ AUTH (login / daftar) ============ */
.login-page{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:30px 20px;background:radial-gradient(60% 50% at 50% 0%,rgba(201,162,75,.12),transparent 70%),var(--bg)}
.login-box{width:100%;max-width:440px;background:var(--card);border:1.5px solid var(--border);border-radius:var(--r1);box-shadow:var(--sh3);padding:34px 28px}
.login-box h2{font-family:var(--serif);font-weight:600;font-size:32px;color:var(--ink);line-height:1.1}
.login-box h2 em{font-style:italic;color:var(--gold)}
.login-sub{font-size:14px;color:var(--ink3);margin:8px 0 24px}
.back-link{font-size:13.5px;font-weight:600;color:var(--ink4)}
.back-link a:hover{color:var(--gold2)}

.form-card{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r1);padding:28px 22px;box-shadow:var(--sh2)}
.form-card h2{font-family:var(--serif);font-weight:600;font-size:28px;color:var(--ink)}
.form-sub{font-size:14px;color:var(--ink3);margin:8px 0 24px;line-height:1.6}
.step{display:flex;align-items:center;gap:12px;margin-bottom:18px}
.step-n{width:32px;height:32px;border-radius:50%;background:var(--ink);color:var(--gold3);font-weight:800;font-size:14px;display:flex;align-items:center;justify-content:center;flex:none}
.step-t{font-family:var(--serif);font-size:21px;font-weight:600;color:var(--ink)}

.chk{display:flex;align-items:center;gap:14px;border:1.5px solid var(--border2);border-radius:var(--r2);padding:16px 18px;cursor:pointer;transition:border .16s,background .16s;-webkit-tap-highlight-color:transparent}
.chk:hover{border-color:var(--gold3)}
.chk.on{border-color:var(--gold);background:var(--goldBg)}
.chk-box{width:26px;height:26px;border-radius:7px;border:2px solid var(--border2);flex:none;display:flex;align-items:center;justify-content:center;color:#fff;transition:background .16s,border .16s}
.chk-box svg{width:16px;height:16px;opacity:0;transition:opacity .16s}
.chk.on .chk-box{background:var(--gold);border-color:var(--gold)}
.chk.on .chk-box svg{opacity:1}
.chk-body{display:flex;justify-content:space-between;align-items:center;gap:12px;width:100%;flex-wrap:wrap}
.chk-name{font-weight:600;font-size:15px;color:var(--ink)}
.chk-price{font-size:14px;font-weight:700;color:var(--gold2);font-variant-numeric:tabular-nums}

.empty{text-align:center;padding:44px 20px}
.empty-ico{width:68px;height:68px;border-radius:18px;background:var(--bg2);color:var(--muted);display:flex;align-items:center;justify-content:center;margin:0 auto 16px}
.empty-ico svg{width:34px;height:34px}
.empty p{color:var(--muted);font-size:14.5px}

/* =================================================================
   BREAKPOINTS (OPTIMASI DESAIN & REFACTORING LAYOUT)
   ================================================================= */
@media (max-width:430px){
  .site-header .wrap{padding:0 14px;gap:7px}
  .logo{font-size:16px;gap:7px}
  .logo-emblem{width:24px;height:24px}
  .site-header .btn-sm{padding:8px 11px;font-size:12px}
  .topnav-r{gap:6px}
}
@media (min-width:560px){
  .row{grid-template-columns:1fr 1fr}
  .steps-row{grid-template-columns:repeat(3,1fr);max-width:none}
  .gallery-grid{grid-template-columns:repeat(3,1fr)}
  .testi-grid{grid-template-columns:repeat(3,1fr)}
  .why-grid{grid-template-columns:repeat(2,1fr)}
  .main-inner{padding:26px 24px 60px}
  .hero h1{font-size:46px}
}
@media (min-width:768px){
  .cl-page,.form-page{padding:36px 24px 60px}
  .hero-center{padding:52px 24px 70px}
  .hero-badge{width:142px;height:142px}
  .hero-badge img{width:132px;height:132px}
  .section{padding:70px 24px}
  .sec-title{font-size:35px}
}
@media (min-width:880px){
  .ogrid{grid-template-columns:repeat(auto-fill, minmax(320px, 1fr))}
}
@media (min-width:1024px){
  .topbar{display:none}
  .backdrop{display:none}
  .side{transform:translateX(0)}
  .layout{padding-left:264px}

  .cl-page, .main-inner { 
      max-width: 1360px; 
      margin: 0 auto; 
      padding: 26px 32px 50px; 
  }

  /* KARTU STATISTIK: Horizontal di Desktop */
  .stats{grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:16px;}
  .st{
      display: grid;
      grid-template-columns: 46px 1fr;
      grid-template-rows: auto auto auto;
      column-gap: 14px;
      align-items: center;
      padding: 17px 18px;
  }
  .st-ico{grid-column:1; grid-row:1 / 4; width:46px; height:46px; margin:0; flex:none; display:flex;}
  .st-ico svg{width:23px; height:23px;}
  .st-lbl{grid-column:2; grid-row:1; line-height:1.2; margin-bottom:3px;}
  .st-val{grid-column:2; grid-row:2; font-size:22px; line-height:1.2; letter-spacing:-.5px;}
  .st-sub{grid-column:2; grid-row:3; line-height:1.2; margin-top:3px;}
  
  .grid2 { grid-template-columns: 1.5fr 1fr; gap: 20px; align-items: start; }
  .grid-jadwal { grid-template-columns: 340px 1fr; gap: 20px; align-items: start; }

  .pg-head h1{font-size:28px}
  .hero h1{font-size:54px}
}
@media (min-width:1180px){
  .hero h1{font-size:60px}
}

/* ============ TOAST ============ */
.toast-container{position:fixed;top:20px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;max-width:360px}
.toast{display:flex;gap:10px;align-items:flex-start;padding:14px 16px;border-radius:var(--r3);
  border:1.5px solid;font-size:13.5px;line-height:1.5;box-shadow:var(--sh3);opacity:0;transform:translateX(24px);transition:all .3s ease}
.toast.show{opacity:1;transform:translateX(0)}
.toast svg{width:19px;height:19px;flex:none;margin-top:1px}
.toast-success{background:var(--greenBg);border-color:#a9d6bb;color:#185c39}
.toast-error{background:var(--redBg);border-color:#eeb6c0;color:#94283c}
.toast-warn{background:var(--orangeBg);border-color:#e8c98c;color:#825414}
.toast-info{background:var(--blueBg);border-color:#b6cae6;color:#284f78}
@media(max-width:480px){
  .toast-container{left:12px;right:12px;top:12px;max-width:none}
}

/* ============ WIZARD PEMESANAN ============ */

.wiz-layout{display:grid;grid-template-columns:1fr;gap:24px}
@media(min-width:1024px){ .wiz-layout{grid-template-columns:1fr 340px;align-items:start} }

.wiz-panel{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r1);padding:clamp(20px,4vw,32px);box-shadow:var(--sh1)}
.wiz-panel-eyebrow{font-size:11px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--gold2)}
.wiz-panel-title{font-family:var(--serif);font-size:24px;font-weight:600;color:var(--ink);margin-top:4px}
.wiz-panel-desc{font-size:13.5px;color:var(--muted);margin-top:6px;margin-bottom:22px;line-height:1.5}
.wiz-nav{display:flex;justify-content:space-between;gap:12px;margin-top:28px;padding-top:22px;border-top:1.5px dashed var(--border)}

/* Kartu layanan */

/* Ringkasan (sticky, gaya tiket) — desktop */
.wiz-rail{position:sticky;top:76px}
.wiz-ticket{background:linear-gradient(155deg,#1C1710,#110E08);border:1.5px solid rgba(231,200,121,.2);border-radius:var(--r1);color:#F4E8CC;box-shadow:var(--sh2)}
.wiz-ticket-head{padding:18px 20px;display:flex;align-items:center;gap:8px;color:#D2C2A1}
.wiz-ticket-title{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.06em;color:#EAD9AF}
.wiz-ticket-body{padding:16px 20px;display:flex;flex-direction:column;gap:10px;max-height:280px;overflow-y:auto}
.wiz-ticket-item{display:flex;justify-content:space-between;gap:10px;font-size:13px}
.wiz-ticket-item .n{color:#D2C2A1}
.wiz-ticket-item .v{font-weight:600;color:#F7EFDA;font-variant-numeric:tabular-nums;white-space:nowrap}
.wiz-ticket-empty{font-size:12.5px;color:#9C8C6C;text-align:center;padding:14px 0}
.wiz-ticket-perf{position:relative;height:0;border-top:1.5px dashed rgba(231,200,121,.3);margin:0 20px}
.wiz-ticket-perf::before,.wiz-ticket-perf::after{content:'';position:absolute;top:50%;transform:translateY(-50%);width:16px;height:16px;border-radius:50%;background:var(--bg)}
.wiz-ticket-perf::before{left:-28px}
.wiz-ticket-perf::after{right:-28px}
.wiz-ticket-total{padding:16px 20px;display:flex;justify-content:space-between;align-items:baseline}
.wiz-ticket-total .lbl{font-size:11.5px;text-transform:uppercase;letter-spacing:.05em;color:#EAD9AF}
.wiz-ticket-total .val{:var(--serif);font-size:20px;font-weight:700;color:var(--gold3)}
.wiz-ticket-actions{padding:0 20px 20px;display:flex;flex-direction:column;gap:8px}
.wiz-ticket-note{padding:0 20px 18px;font-size:11px;color:#9C8C6C;line-height:1.5;font-style:italic}

/* Bar ringkasan mobile (collapsible) */
.wiz-mbar{display:none;position:fixed;left:0;right:0;bottom:0;z-index:80;background:linear-gradient(155deg,#1C1710,#110E08);border-top:1.5px solid rgba(231,200,121,.25);box-shadow:0 -8px 24px rgba(0,0,0,.25)}
.wiz-mbar-toggle{width:100%;display:flex;justify-content:space-between;align-items:center;padding:12px 18px;background:none;border:none;color:#F4E8CC;cursor:pointer}
.wiz-mbar-detail{max-height:0;overflow:hidden;transition:max-height .25s ease}
.wiz-mbar.open .wiz-mbar-detail{max-height:220px;overflow-y:auto;padding:4px 18px}
.wiz-mbar-btns{display:flex;gap:10px;padding:10px 18px 16px}
.wiz-mbar-btns .btn{flex:1}
@media(max-width:1023px){ .wiz-mbar{display:block} }

/* Step review */
.wiz-review-block{margin-bottom:22px;padding-bottom:22px;border-bottom:1.5px dashed var(--border)}
.wiz-review-block:last-child{border-bottom:none;margin-bottom:0;padding-bottom:0}
.wiz-review-head{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.wiz-review-label{font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:.05em;color:var(--muted)}
.wiz-review-edit{font-size:12px;font-weight:700;color:var(--gold2);background:none;border:none;cursor:pointer}
.wiz-review-row{display:flex;justify-content:space-between;gap:12px;font-size:14px;padding:6px 0}
.wiz-review-row .k{color:var(--ink3)}
.wiz-review-row .v{color:var(--ink);font-weight:600;text-align:right}

/* ============ STAT ROW + TICKET CARD (Portal Klien) ============ */
.stat-row{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-bottom:20px}
@media(min-width:860px){ .stat-row{grid-template-columns:repeat(4,1fr)} }
.stat-chip{background:var(--card);border:1.5px solid var(--border);border-radius:var(--r2);padding:13px 15px;display:flex;align-items:center;gap:11px}
.stat-chip .ico{width:36px;height:36px;border-radius:9px;background:var(--bg2);color:var(--gold2);display:flex;align-items:center;justify-content:center;flex:none}
.stat-chip .lbl{font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--ink4)}
.stat-chip .val{font-size:15.5px;font-weight:800;color:var(--ink);margin-top:1px}

.notice-bar{display:flex;gap:10px;align-items:center;background:var(--goldBg);border:1.5px solid #E3CB8F;border-radius:var(--r3);padding:10px 14px;font-size:12px;color:var(--goldDeep);margin-bottom:18px;font-weight:600}
.notice-bar svg{width:16px;height:16px;flex:none}

.booking-grid{display:grid;grid-template-columns:1fr;gap:16px}
@media(min-width:720px){ .booking-grid{grid-template-columns:repeat(3,1fr)} }

.ticket{position:relative;background:var(--card);border:1.5px solid var(--border2);border-radius:var(--r1);box-shadow:var(--sh1);overflow:hidden;transition:.18s;display:block;text-decoration:none}
.ticket:hover{transform:translateY(-3px);box-shadow:var(--sh2)}
.ticket-accent{height:4px;width:100%}
.ticket-stub{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:11px 18px}
.ticket-kode{font-size:12px;font-weight:800;letter-spacing:1.2px;color:var(--gold2)}
.ticket-perf{position:relative;height:0;border-top:1.5px dashed var(--border2)}
.ticket-perf::before,.ticket-perf::after{content:'';position:absolute;top:50%;transform:translateY(-50%);width:16px;height:16px;border-radius:50%;background:var(--bg)}
.ticket-perf::before{left:-9px}
.ticket-perf::after{right:-9px}
.ticket-body{padding:16px 18px}
.ticket-foot{padding:13px 18px;background:var(--bg2);display:flex;justify-content:space-between;align-items:center;gap:10px}
.booking-card-name{font-family:var(--serif);font-size:19px;font-weight:600;color:var(--ink);margin-bottom:8px}
.booking-meta{display:flex;flex-direction:column;gap:6px}
.booking-meta .row{display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--ink3);font-weight:500}
.booking-meta svg{width:13px;height:13px;color:var(--gold);flex:none}
.booking-total-lbl{font-size:10px;text-transform:uppercase;letter-spacing:.4px;color:var(--ink4);font-weight:700}
.booking-total-val{font-size:15px;font-weight:800;color:var(--ink);margin-top:1px}

.empty-state{text-align:center;padding:52px 20px;background:var(--card);border:1.5px dashed var(--border2);border-radius:var(--r1)}
.empty-state .ico{width:56px;height:56px;border-radius:14px;background:var(--bg2);color:var(--ink4);display:flex;align-items:center;justify-content:center;margin:0 auto 14px}
.empty-state .ico svg{width:26px;height:26px}
.empty-state h3{font-family:var(--serif);font-size:18px;margin-bottom:5px;color:var(--ink)}
.empty-state p{font-size:13px;color:var(--ink4);max-width:340px;margin:0 auto 18px}

/* ============ STEPPER 3 LANGKAH (versi ringkas) ============ */
.stepper3{display:flex;align-items:center;gap:0;margin-bottom:22px;overflow-x:auto;padding-bottom:2px}
.step-item{display:flex;align-items:center;gap:8px;flex:none}
.step-node{display:flex;align-items:center;gap:8px;cursor:pointer}
.step-circle{width:28px;height:28px;border-radius:50%;background:var(--bg2);border:1.5px solid var(--border2);display:flex;align-items:center;justify-content:center;font-size:12.5px;font-weight:800;color:var(--muted);flex:none;transition:.2s}
.step-node.done .step-circle{background:var(--gold);border-color:var(--gold);color:#2A1E06}
.step-node.active .step-circle{background:var(--gold-grad);border-color:transparent;color:#2A1E06;box-shadow:0 0 0 4px var(--goldBg)}
.step-label{font-size:12.5px;font-weight:700;color:var(--ink4);white-space:nowrap}
.step-node.active .step-label{color:var(--ink)}
.step-node.done .step-label{color:var(--ink3)}
.step-line{width:32px;height:1.5px;background:var(--border2);margin:0 6px;flex:none}
.step-line.done{background:var(--gold)}

/* Mobile: sembunyikan label teks, cukup nomor + garis, supaya 3 tahap selalu muat tanpa kepotong/discroll */
@media (max-width:480px){
  .stepper3{overflow-x:visible}
  .step-label{display:none}
  .step-line{flex:1;width:auto;margin:0 6px}
}

/* ============ KARTU LAYANAN BERGAMBAR (dipakai bersama: landing "Layanan Kami" & wizard "Pilih Layanan") ============ */
.svc-grid{display:grid;grid-template-columns:repeat(2,1fr);gap:14px}
@media (max-width:480px){
  .svc-grid{grid-template-columns:1fr}
}
@media (min-width:768px){
  .svc-grid{grid-template-columns:repeat(3,1fr)}
}
@media (min-width:1024px){
  .svc-grid{grid-template-columns:repeat(4,1fr)}
}
.svc-card{position:relative;background:var(--card);border:1.5px solid var(--border2);border-radius:var(--r2);overflow:hidden;box-shadow:var(--sh1);cursor:pointer;transition:.15s;display:flex;flex-direction:column}
.svc-card:hover{border-color:var(--gold);transform:translateY(-2px);box-shadow:var(--sh2)}
.svc-card.on{border-color:var(--gold);background:linear-gradient(180deg,#FFFDF7,#FBF3DE)}
.svc-photo{position:relative;height:120px;background:linear-gradient(145deg,#1C1710,#110E08);background-size:cover;background-position:center}
.svc-photo-empty{display:flex;align-items:center;justify-content:center;height:100%}
.svc-photo-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.05),rgba(0,0,0,.6) 100%)}
.svc-tag-on{position:absolute;top:10px;left:10px;font-size:10px;font-weight:800;color:#2A1E06;background:var(--gold);padding:3px 9px;border-radius:99px;text-transform:uppercase;letter-spacing:.3px;z-index:2}
.svc-info-btn{position:absolute;top:8px;right:8px;width:26px;height:26px;border-radius:50%;background:rgba(0,0,0,.45);border:1px solid rgba(255,255,255,.3);color:#fff;display:flex;align-items:center;justify-content:center;z-index:2;cursor:pointer}
.svc-name-on-photo{position:absolute;bottom:8px;left:12px;right:44px;color:#fff;font-family:var(--serif);font-size:15.5px;font-weight:600;line-height:1.25;z-index:2}
.svc-body{padding:12px 14px 14px;display:flex;flex-direction:column;flex:1}
.svc-desc{font-size:11.5px;color:var(--ink3);line-height:1.5;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin-bottom:10px}
.svc-foot{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-top:auto;padding-top:10px;border-top:1px dashed var(--border)}
.svc-price{font-weight:800;font-size:13px;color:var(--ink)}
.svc-add{display:flex;align-items:center;gap:5px;font-size:11px;font-weight:800;padding:5px 10px;border-radius:99px;border:1.5px solid var(--gold);color:var(--goldDeep);background:transparent;flex:none}
.svc-card.on .svc-add{background:var(--ink);border-color:var(--ink);color:var(--gold3)}
.svc-kategori-tag{display:inline-block;font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.3px;color:var(--goldDeep);background:var(--goldBg);padding:3px 9px;border-radius:99px;margin-bottom:8px}

/* ============ LAYAR SUKSES (thread status tracker) ============ */
.success-shell{max-width:520px;margin:0 auto;padding:40px 20px 20px;text-align:center}
.success-mark{width:72px;height:72px;border-radius:50%;background:var(--gold-grad);color:#2B1E06;display:flex;align-items:center;justify-content:center;margin:0 auto 18px;box-shadow:0 8px 24px rgba(169,122,37,.35)}
.success-code{display:inline-block;margin:14px 0 22px;font-family:var(--sans);font-weight:800;font-size:15px;letter-spacing:1px;color:var(--goldDeep);background:var(--goldBg);padding:8px 18px;border-radius:999px}
.success-actions{display:flex;gap:10px;justify-content:center;margin-top:26px;flex-wrap:wrap}

.thread-stepper{display:flex;align-items:flex-start;gap:0;max-width:480px;margin:0 auto}
.thread-node{display:flex;flex-direction:column;align-items:center;gap:8px;flex:none;width:70px}
.thread-node-dot{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12.5px;flex:none;position:relative;z-index:2}
.thread-node-label{font-size:10.5px;font-weight:700;color:var(--ink3);text-align:center;letter-spacing:.2px}
.thread-line{flex:1;height:30px;display:flex;align-items:center;margin:0 -2px}
.thread-line-in{width:100%;height:0;border-top:2px dashed var(--border2)}
.thread-node.done .thread-node-dot{background:var(--gold-grad);color:#2B1E06;box-shadow:0 3px 10px rgba(201,162,75,.4)}
.thread-node.now .thread-node-dot{background:var(--card);border:2px solid var(--gold);color:var(--goldDeep)}
.thread-node.wait .thread-node-dot{background:var(--bg2);border:2px solid var(--border2);color:var(--ink4)}
.thread-line.done .thread-line-in{border-top-style:solid;border-top-color:var(--gold)}

.chips{display:flex;gap:8px;flex-wrap:wrap}
.chip{padding:8px 15px;border-radius:999px;border:1.5px solid var(--border2);font-size:12.5px;font-weight:600;background:var(--card);color:var(--ink3);cursor:pointer;transition:.15s}
.chip:hover{border-color:var(--gold)}
.chip.active{background:var(--ink);color:var(--gold3);border-color:var(--ink)}

.hd-inner{max-width:1280px;margin:0 auto;padding:0 20px;width:100%}
</style>