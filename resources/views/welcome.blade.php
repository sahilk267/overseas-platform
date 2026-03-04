<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UMAEP | India's First Unified Ad & Event Platform</title>
    
    <!-- SEO Best Practices -->
    <meta name="description" content="UMAEP is India's first unified advertising and event platform, connecting clients with verified local agencies for Print, Digital, Outdoor, and Broadcast ads.">
    <meta name="keywords" content="advertising platform india, media planning tool, billboard ads mumbai, newspaper advertising india, unified ad network, event management platform">
    <meta name="author" content="UMAEP Team">
    
    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://umaep.aaditechs.in/">
    <meta property="og:title" content="UMAEP | India's First Unified Advertising Ecosystem">
    <meta property="og:description" content="Bridge the gap between your brand and the best marketing agencies across India. Verified, Local, and Transparent.">
    <meta property="og:image" content="{{ asset('hero.png') }}">

    <!-- Google Analytics (Placeholder) -->
    <!-- Paste your Google Analytics GTAG here -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-XXXXXXXXXX');
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --dark-bg: #0f172a;
            --glass-bg: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: #f8fafc;
            overflow-x: hidden;
        }

        h1, h2, h3, .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
        }

        p {
            font-family: 'Inter', sans-serif;
            color: #94a3b8;
        }

        /* Glassmorphism Classes */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 20px rgba(99, 102, 241, 0.1);
        }

        /* Navigation */
        .navbar {
            padding: 1.5rem 0;
            background: transparent;
            position: absolute;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.8rem;
            color: #fff !important;
            letter-spacing: -1px;
        }

        .nav-link {
            color: #94a3b8 !important;
            font-weight: 600;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #fff !important;
        }

        /* Hero Section */
        .hero-section {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding-top: 100px;
            background: url('{{ asset('hero.png') }}') no-repeat center center;
            background-size: cover;
            background-color: var(--dark-bg); /* Fallback */
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 60% 50%, rgba(15, 23, 42, 0.4) 0%, rgba(15, 23, 42, 0.95) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid rgba(99, 102, 241, 0.3);
            border-radius: 50px;
            color: #818cf8;
            font-weight: 600;
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease;
        }

        .display-1 {
            font-size: 4.5rem;
            letter-spacing: -2px;
            background: linear-gradient(to right, #fff 30%, #94a3b8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: fadeInUp 1s ease;
        }

        /* Services Grid */
        .service-icon {
            font-size: 2.5rem;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
        }

        /* Buttons */
        .btn-primary-umaep {
            background: var(--primary-gradient);
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 14px;
            font-weight: 600;
            color: #fff;
            transition: all 0.3s;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-primary-umaep:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.5);
            color: #fff;
        }

        .btn-outline-umaep {
            border: 1px solid var(--glass-border);
            padding: 1rem 2.5rem;
            border-radius: 14px;
            font-weight: 600;
            color: #fff;
            background: var(--glass-bg);
            transition: all 0.3s;
        }

        .btn-outline-umaep:hover {
            background: #fff;
            color: var(--dark-bg);
        }

        /* Section Spacing */
        .section-padding {
            padding: 100px 0;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .floating-anim {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Mobile Adjustments */
        @media (max-width: 768px) {
            .display-1 { font-size: 3rem; }
            .hero-section { text-align: center; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">
                UMA<span style="color: #6366f1">EP</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-3" href="#how-it-works">How it Works</a>
                    </li>
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item ms-lg-4">
                                <a href="{{ url('/dashboard') }}" class="btn btn-primary-umaep">Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item ms-lg-4">
                                <a href="{{ route('login') }}" class="nav-link">Log in</a>
                            </li>
                            <li class="nav-item ms-lg-2">
                                <a href="{{ route('register') }}" class="btn btn-primary-umaep">Sign Up</a>
                            </li>
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <span class="hero-badge">🇮🇳 India's First Unified Platform</span>
                    <h1 class="display-1 mb-4">Transforming How India <span style="background: var(--primary-gradient); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Advertises.</span></h1>
                    <p class="fs-5 mb-5 lh-lg">A single common platform for the entire media ecosystem. From Print & Outdoor to Digital & Events—we bridge the gap between clients and elite agencies across India.</p>
                    
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}?type=advertiser" class="btn btn-primary-umaep px-5">Launch Campaign</a>
                        <a href="{{ route('register') }}?type=vendor" class="btn btn-outline-umaep px-5">Join as Agency</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Stats -->
    <section class="section-padding" id="about">
        <div class="container text-center">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 glass-card h-100">
                        <h2 class="display-5 fw-bold mb-2">50+</h2>
                        <p class="mb-0">Ad Categories</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 glass-card h-100">
                        <h2 class="display-5 fw-bold mb-2">100%</h2>
                        <p class="mb-0">Verified Agencies</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 glass-card h-100">
                        <h2 class="display-5 fw-bold mb-2">Proximity</h2>
                        <p class="mb-0">Location-based Routing</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- India's First Story -->
    <section class="section-padding overflow-hidden">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="p-2 border border-primary rounded-4 d-inline-block mb-4" style="--bs-border-opacity: .2">
                        <span class="text-primary fw-bold small text-uppercase tracking-wider px-2">The Mission</span>
                    </div>
                    <h2 class="display-4 fw-bold mb-4">India's First Unified <br><span class="text-primary">Ad-Tech Bridge.</span></h2>
                    <p class="lead mb-4">Pehle, marketing aur events ke liye alag alag vendors dhoondna ek nightmare tha. UMAEP ne use badal diya hai.</p>
                    <p class="mb-5">Humne India ke unorganized advertising sector ko digital connectivity se joda hai. Ab client ko 50 jagah bhatakne ki zaroorat nahi—ek platform par saari services, verified vendors, aur guaranteed execution.</p>
                    
                    <div class="row g-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 glass-card rounded-3"><i class="bi bi-shield-check text-primary"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Verified Partners</h6>
                                    <small class="text-muted">No more fake agencies.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 glass-card rounded-3"><i class="bi bi-geo-alt text-primary"></i></div>
                                <div>
                                    <h6 class="mb-0 fw-bold">Local Proximity</h6>
                                    <small class="text-muted">Closest vendor wins.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <img src="{{ asset('story.png') }}" alt="UMAEP Mission" class="img-fluid rounded-5 shadow-2xl border border-secondary" style="border-width: 0.5px">
                        <div class="position-absolute bottom-0 start-0 translate-middle-y glass-card p-4 m-4 d-none d-md-block" style="width: 250px">
                            <h6 class="fw-bold text-primary">Bridge the Gap</h6>
                            <p class="small mb-0 text-white-50">Connecting 500+ small vendors to national brands.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Smart Lead Routing (Phase 10 Deep Dive) -->
    <section class="section-padding" style="background: linear-gradient(to bottom, var(--dark-bg), #1e293b);">
        <div class="container">
            <div class="row align-items-center g-5 flex-row-reverse">
                <div class="col-lg-6">
                    <div class="p-2 border border-info rounded-4 d-inline-block mb-4" style="--bs-border-opacity: .2">
                        <span class="text-info fw-bold small text-uppercase tracking-wider px-2">Smart Technology</span>
                    </div>
                    <h2 class="display-5 fw-bold mb-4">Precision Lead Routing <br><span class="text-info">Based on Proximity.</span></h2>
                    <p class="mb-4">Humaara platform sirf ek directory nahi hai. Ye ek intelligent engine hai. Jab aap campaign launch karte hain, humaara **Haversine Algorithm** sabse paas wali verified agency ko identify karta hai.</p>
                    
                    <div class="glass-card p-4 mb-4 border-info" style="--bs-border-opacity: 0.1">
                        <div class="d-flex gap-4">
                            <i class="bi bi-geo-fill fs-2 text-info"></i>
                            <div>
                                <h6 class="fw-bold">GPS-Based Matching</h6>
                                <p class="small mb-0">Clients and Agencies are matched via real-time coordinates for zero transportation delays.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('routing.png') }}" alt="Smart Routing" class="img-fluid rounded-5 shadow-2xl floating-anim border border-secondary" style="border-width: 0.5px">
                </div>
            </div>
        </div>
    </section>

    <!-- The UMAEP Advantage (Table) -->
    <section class="section-padding">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">The UMAEP Advantage</h2>
                <p>Why businesses are switching to the unified model.</p>
            </div>
            <div class="table-responsive glass-card p-2 border-primary" style="--bs-border-opacity: 0.1">
                <table class="table table-dark table-hover mb-0">
                    <thead>
                        <tr class="border-bottom border-secondary">
                            <th class="p-4">Feature</th>
                            <th class="p-4 text-center">Traditional Way</th>
                            <th class="p-4 text-center text-primary">UMAEP Way</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="p-4 fw-bold">Vendor Discovery</td>
                            <td class="p-4 text-center">Days of calling & searching</td>
                            <td class="p-4 text-center text-primary"><i class="bi bi-lightning-fill me-2"></i> Instant Routing</td>
                        </tr>
                        <tr>
                            <td class="p-4 fw-bold">Trust & Verification</td>
                            <td class="p-4 text-center">Word of mouth (Risky)</td>
                            <td class="p-4 text-center text-primary"><i class="bi bi-check-shield-fill me-2"></i> Verified Profiles</td>
                        </tr>
                        <tr>
                            <td class="p-4 fw-bold">Progress Tracking</td>
                            <td class="p-4 text-center">Unclear phone updates</td>
                            <td class="p-4 text-center text-primary"><i class="bi bi-graph-up me-2"></i> Real-time Dashboard</td>
                        </tr>
                        <tr>
                            <td class="p-4 fw-bold">Unified Billing</td>
                            <td class="p-4 text-center">Multiple invoices/Cash</td>
                            <td class="p-4 text-center text-primary"><i class="bi bi-wallet2 me-2"></i> Single Digital Wallet</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Trust & Security -->
    <section class="section-padding bg-darker">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <img src="{{ asset('trust.png') }}" alt="Trust & Security" class="img-fluid rounded-5 shadow-lg border border-secondary" style="border-width: 0.5px">
                </div>
                <div class="col-lg-7">
                    <h2 class="display-5 fw-bold mb-4">Secure, Transparent & <br><span class="text-primary">Globally Aligned.</span></h2>
                    <p class="lead mb-4">Aapki security humari priority hai. UMAEP har transaction aur execution ko record karta hai.</p>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h5 class="fw-bold"><i class="bi bi-lock-fill text-primary me-2"></i> In-Platform Security</h5>
                            <p class="small">Aapka data aur campaigns end-to-end encrypted hain.</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold"><i class="bi bi-patch-check-fill text-primary me-2"></i> Quality Guarantee</h5>
                            <p class="small">Agencies are only paid once the campaign progress is verified.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Grid -->
    <section class="section-padding bg-darker" id="services">
        <div class="container">
            <div class="row mb-5 text-center">
                <div class="col-lg-6 mx-auto">
                    <h2 class="display-5 mb-3">Our Unified Ecosystem</h2>
                    <p>Every marketing medium, managed from one sophisticated dashboard.</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4 col-lg-3">
                    <div class="p-5 glass-card text-center h-100">
                        <i class="bi bi-megaphone service-icon"></i>
                        <h5>Broadcast</h5>
                        <p class="small">Television & Radio</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="p-5 glass-card text-center h-100">
                        <i class="bi bi-display service-icon"></i>
                        <h5>Outdoor</h5>
                        <p class="small">Billboards & Airports</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="p-5 glass-card text-center h-100">
                        <i class="bi bi-newspaper service-icon"></i>
                        <h5>Print</h5>
                        <p class="small">Newspapers & Magazines</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="p-5 glass-card text-center h-100">
                        <i class="bi bi-cpu service-icon"></i>
                        <h5>Digital</h5>
                        <p class="small">Social & Web Ads</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="p-5 glass-card text-center h-100">
                        <i class="bi bi-calendar-event service-icon"></i>
                        <h5>Events</h5>
                        <p class="small">Activations & Stalls</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="p-5 glass-card text-center h-100">
                        <i class="bi bi-people service-icon"></i>
                        <h5>Influencer</h5>
                        <p class="small">Celebs & Bloggers</p>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="p-4 glass-card text-center h-100 d-flex flex-column justify-content-center align-items-center">
                        <h4 class="fw-bold">+40 More</h4>
                        <p class="small">Search our catalog</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dual Role Benefits -->
    <section class="section-padding bg-light-subtle text-dark py-5" style="background: #f8fafc !important;">
        <div class="container text-center py-5">
            <h2 class="display-5 fw-bold mb-5">Built for Both Sides of the Market</h2>
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100">
                        <div class="card-body p-5">
                            <div class="mb-4 d-inline-block p-3 bg-primary bg-opacity-10 rounded-circle">
                                <i class="bi bi-person-workspace fs-1 text-primary"></i>
                            </div>
                            <h3 class="fw-bold mb-4">For Agencies (Vendors)</h3>
                            <ul class="text-start list-unstyled mb-5">
                                <li class="mb-3"><i class="bi bi-arrow-right-short text-primary fs-4 me-2"></i> Get high-quality leads directly from your city.</li>
                                <li class="mb-3"><i class="bi bi-arrow-right-short text-primary fs-4 me-2"></i> Showcase your specialized child-categories.</li>
                                <li class="mb-3"><i class="bi bi-arrow-right-short text-primary fs-4 me-2"></i> Build a digital portfolio with client ratings.</li>
                                <li><i class="bi bi-arrow-right-short text-primary fs-4 me-2"></i> No cold calling—clients come to you.</li>
                            </ul>
                            <a href="{{ route('register') }}?type=vendor" class="btn btn-outline-primary btn-lg rounded-pill w-100">Join as Partner</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card border-0 shadow-lg rounded-4 overflow-hidden h-100 bg-dark text-white">
                        <div class="card-body p-5">
                            <div class="mb-4 d-inline-block p-3 bg-light bg-opacity-10 rounded-circle">
                                <i class="bi bi-rocket-takeoff fs-1 text-light"></i>
                            </div>
                            <h3 class="fw-bold mb-4">For Advertisers (Clients)</h3>
                            <ul class="text-start list-unstyled mb-5">
                                <li class="mb-3"><i class="bi bi-arrow-right-short text-light fs-4 me-2"></i> Access 50+ advertising categories instantly.</li>
                                <li class="mb-3"><i class="bi bi-arrow-right-short text-light fs-4 me-2"></i> Real-time campaign tracking and execution.</li>
                                <li class="mb-3"><i class="bi bi-arrow-right-short text-light fs-4 me-2"></i> One unified wallet for all marketing expenses.</li>
                                <li><i class="bi bi-arrow-right-short text-light fs-4 me-2"></i> Direct communication with verified local experts.</li>
                            </ul>
                            <a href="{{ route('register') }}?type=advertiser" class="btn btn-primary btn-lg rounded-pill w-100">Start Your Campaign</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="section-padding" id="faq">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Common Questions</h2>
                <p>Everything you need to know about India's largest ad-network.</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-8 mx-auto">
                    <div class="accordion accordion-flush glass-card p-4" id="faqAccordion">
                        <div class="accordion-item bg-transparent text-white border-bottom border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-transparent text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#q1">
                                    How does UMAEP verify agencies?
                                </button>
                            </h2>
                            <div id="q1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Hum har agency ka profile manually review karte hain. Unke past work, GST details aur locality verification ke baad hi unhe "Active" status milta hai.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-transparent text-white border-bottom border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-transparent text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#q2">
                                    Is there any platform fee?
                                </button>
                            </h2>
                            <div id="q2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    UMAEP basic usage ke liye free hai. Hum sirf successful campaigns par ek minimal service charge late hain jo platform development aur support mein use hota hai.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item bg-transparent text-white border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-transparent text-white shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#q3">
                                    Can I manage multiple ad mediums together?
                                </button>
                            </h2>
                            <div id="q3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Yes! Yahi humari sabse badi khasiyat hai. Aap ek hi dashboard se Billboard, Social Media Ads aur Newspaper Ads manage kar sakte hain.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-5 border-top border-secondary">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <h4 class="fw-bold mb-0">UMA<span style="color: #6366f1">EP</span></h4>
                    <p class="small mb-0 mt-2">© 2026 United Media, Advertising & Event Platform. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-4 mt-md-0">
                    <div class="d-flex justify-content-center justify-content-md-end gap-4">
                        <a href="#" class="text-muted text-decoration-none">Privacy Policy</a>
                        <a href="#" class="text-muted text-decoration-none">Terms of Service</a>
                        <a href="#" class="text-muted text-decoration-none">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
