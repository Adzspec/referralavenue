<template>
    <section class="features-section">
        <div class="features-container">
            <!-- Left side image -->
            <div class="features-img">
                <img src="logo.jpg" alt="Features Illustration">
            </div>

            <!-- Right side cards -->
            <div>
                <h2 class="section-title">Key Features at a Glance</h2>
                <p class="section-subtitle">
                    Discover the core advantages that make our platform powerful, flexible,
                    and easy to use for businesses of all sizes.
                </p>

                <div class="features-cards">
                    <div class="feature-card">
                        <div class="feature-title">Dynamic Web Pages</div>
                        <div class="feature-benefit">Build offer pages fast, optimized for search and mobile.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-title">Publisher Portal</div>
                        <div class="feature-benefit">Self-serve sign-up, approval, and affiliate tools.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-title">Real-Time Performance Data</div>
                        <div class="feature-benefit">Make smarter decisions with live insights.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-title">Custom Domains & Branding</div>
                        <div class="feature-benefit">Launch under your name, logo, and style.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-title">Offer Management</div>
                        <div class="feature-benefit">Organize and tag offers by niche, payout, or performance.</div>
                    </div>
                    <div class="feature-card">
                        <div class="feature-title">Multi-Network Access</div>
                        <div class="feature-benefit">Aggregate and manage programs from across providers.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style>
:root {
    --brand-400:#56a4ff; --brand-500:#2a82ff; --brand-600:#1f67db;
    --muted:#64748b; --dark:#0b1220;
    --soft-shadow:0 10px 25px -10px rgba(0,0,0,0.15);
}
.features-section { padding:4rem 1rem; background: linear-gradient(to right, #eff6ff, #eef2ff); }
.features-container { max-width:1200px; margin:0 auto; display:grid; grid-template-columns:1fr; gap:2rem; align-items:start; }
@media(min-width:1024px){ .features-container{ grid-template-columns:1fr 1fr; gap:3rem; } }
.features-img { display:flex; align-items:center; justify-content:center; }
.features-img img { max-width:100%; border-radius:20px; box-shadow:var(--soft-shadow); }
.features-cards { display:grid; gap:1.25rem; }
@media(min-width:768px){ .features-cards{ grid-template-columns:1fr 1fr; } }
.feature-card {
    background:#f9fafb;
    border:1px solid #e5e7eb;
    border-radius:16px;
    padding:1.5rem;
    box-shadow:0 2px 8px rgba(0,0,0,0.05);
    transition:.25s;
    position:relative;
    overflow:hidden;
}
.feature-card::before {
    content:""; position:absolute; top:0; left:0; width:100%; height:4px;
    background:linear-gradient(to right,var(--brand-500),var(--brand-400));
    border-radius:16px 16px 0 0;
}
.feature-card:hover { transform:translateY(-6px); box-shadow:var(--soft-shadow); border-color:var(--brand-400); }
.feature-title { font-weight:700; font-size:1.1rem; color:var(--brand-600); margin-bottom:.4rem; }
.feature-benefit { font-size:.9rem; color:var(--muted); line-height:1.6; }
.section-title { font-size:2rem; font-weight:800; margin-bottom:.75rem; color:var(--dark); }
.section-subtitle { font-size:1rem; color:var(--muted); margin-bottom:1.75rem; line-height:1.6; }
</style>
