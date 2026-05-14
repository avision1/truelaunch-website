<?php

require __DIR__ . '/../app/bootstrap.php';

$baseUrl = $config['site']['base_url'] ?? '';
$contact = $content['contact'] ?? [];

$pageTitle = 'Team - True Launch';
$pageDesc = 'Meet the team behind True Launch, building rocket manufacturing systems for 500 kg to LEO.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <meta name="description" content="<?php echo e($pageDesc); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(url_for('assets/css/style.css', $baseUrl)); ?>">
    <style>
        .team-hero {
            padding: 160px 0 80px;
        }
        .team-hero .section-kicker {
            margin-bottom: 16px;
        }
        .team-hero .section-title {
            max-width: 640px;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            padding: 60px 0 120px;
        }
        .team-card {
            background: var(--bg-2);
            border: 1px solid var(--border);
            border-radius: 4px;
            overflow: hidden;
        }
        .team-card-photo {
            width: 100%;
            aspect-ratio: 3 / 4;
            object-fit: cover;
            object-position: top;
            display: block;
            background: #1a1a1a;
        }
        .team-card-photo-placeholder {
            width: 100%;
            aspect-ratio: 3 / 4;
            background: #1a1a1a;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--muted-2);
            font-size: 48px;
            font-family: 'Chakra Petch', sans-serif;
            letter-spacing: 0.05em;
        }
        .team-card-body {
            padding: 24px 24px 28px;
        }
        .team-card-name {
            font-family: 'Chakra Petch', sans-serif;
            font-size: 17px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: 0.03em;
            margin-bottom: 4px;
        }
        .team-card-role {
            font-size: 13px;
            color: var(--muted-2);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .team-card-bio {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.65;
        }
        .team-card-linkedin {
            display: inline-block;
            margin-top: 6px;
            font-size: 11px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted-2);
            transition: color 0.2s;
        }
        .team-card-linkedin:hover {
            color: var(--accent);
        }
        @media (max-width: 900px) {
            .team-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 700px) {
            .team-hero { padding: 120px 0 60px; }
        }
        @media (max-width: 500px) {
            .team-grid { grid-template-columns: 1fr; }
            .team-hero { padding: 110px 0 48px; }
            .team-card-body { padding: 20px 20px 24px; }
        }
    </style>
</head>
<body>
    <canvas id="starfield"></canvas>

    <nav class="nav" id="nav">
        <div class="nav-inner">
            <a class="brand" href="<?php echo e($baseUrl === '' ? '/' : $baseUrl . '/'); ?>">
                <img class="brand-logo" src="<?php echo e(url_for('assets/images/logo-white.png', $baseUrl)); ?>" alt="True Launch">
            </a>
            <a class="nav-cta" href="<?php echo e($baseUrl === '' ? '/' : $baseUrl . '/'); ?>#contact">Plan Mission</a>
        </div>
    </nav>

    <div class="team-hero">
        <div class="container">
            <p class="section-kicker reveal">Team</p>
            <h1 class="section-title reveal">The people building True Launch.</h1>
        </div>
    </div>

    <div class="container">
        <div class="team-grid">

            <div class="team-card reveal">
                <img class="team-card-photo" src="<?php echo e(url_for('assets/images/mohan.png', $baseUrl)); ?>" alt="Mohan Tamang">
                <div class="team-card-body">
                    <div class="team-card-name">Mohan Tamang</div>
                    <div class="team-card-role">Founder<br>CEO/CTO</div>
                    <a class="team-card-linkedin" href="https://www.linkedin.com/in/mohantamangg/" target="_blank" rel="noopener">LinkedIn</a>
                </div>
            </div>

            <div class="team-card reveal">
                <img class="team-card-photo" src="<?php echo e(url_for('assets/images/rubita.png', $baseUrl)); ?>" alt="Rubita Magar">
                <div class="team-card-body">
                    <div class="team-card-name">Rubita Magar</div>
                    <div class="team-card-role">Co-Founder &amp; Chief Business Officer</div>
                    <a class="team-card-linkedin" href="https://np.linkedin.com/in/rubitamagar" target="_blank" rel="noopener">LinkedIn</a>
                </div>
            </div>

            <div class="team-card reveal">
                <img class="team-card-photo" src="<?php echo e(url_for('assets/images/abhishek.png', $baseUrl)); ?>" alt="Abhishek Raju">
                <div class="team-card-body">
                    <div class="team-card-name">Abhishek Raju</div>
                    <div class="team-card-role">Non-Executive Founder &amp; Director</div>
                    <a class="team-card-linkedin" href="https://www.linkedin.com/in/abhishekraju/" target="_blank" rel="noopener">LinkedIn</a>
                </div>
            </div>

            <div class="team-card reveal">
                <img class="team-card-photo" src="<?php echo e(url_for('assets/images/sunil.png', $baseUrl)); ?>" alt="Sunil Bista">
                <div class="team-card-body">
                    <div class="team-card-name">Sunil Bista</div>
                    <div class="team-card-role">Chief Propulsion Engineer</div>
                    <a class="team-card-linkedin" href="https://www.linkedin.com/in/sunil-bista-5608bb257/" target="_blank" rel="noopener">LinkedIn</a>
                </div>
            </div>

        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <div class="footer-brand">
                    <div><a href="<?php echo e($baseUrl === '' ? '/' : $baseUrl . '/'); ?>">Home</a></div>
                    <a class="footer-linkedin" href="https://www.linkedin.com/company/truelaunch" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <span class="footer-linkedin-text">in</span>
                    </a>
                </div>
                <div class="footer-contact">
                    <div>&copy; 2026 True Launch.</div>
                    <div><a href="mailto:<?php echo e($contact['email'] ?? 'connect@truelaunch.space'); ?>"><?php echo e($contact['email'] ?? 'connect@truelaunch.space'); ?></a></div>
                    <div>Bengaluru, INDIA</div>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?php echo e(url_for('assets/js/main.js', $baseUrl)); ?>"></script>
</body>
</html>
