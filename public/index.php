<?php

require __DIR__ . '/../app/bootstrap.php';

$baseUrl = $config['site']['base_url'] ?? '';
$sent = isset($_GET['sent']);
$error = isset($_GET['error']);
$form = $_GET['form'] ?? '';
$activeForm = in_array($form, ['contact', 'investor'], true) ? $form : 'contact';

$hero = $content['hero'] ?? [];
$manifesto = $content['manifesto'] ?? [];
$platform = $content['platform'] ?? [];
$capabilities = $content['capabilities'] ?? [];
$roadmap = $content['roadmap'] ?? [];
$cta = $content['cta'] ?? [];
$contact = $content['contact'] ?? [];
$footer = $content['footer'] ?? [];

$contactPhone = $contact['phone'] ?? '';
$contactPhoneDisplay = $contactPhone !== '' ? $contactPhone : '+1 (000) 000-0000';
$contactPhoneLink = preg_replace('/[^0-9+]/', '', $contactPhoneDisplay);

$pageTitle = 'True Launch - Rocket Manufacturing for 500 kg to LEO';
$pageDesc = 'True Launch is a rocket manufacturing company building launch vehicles aimed at delivering 500 kg payloads to LEO.';
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
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;500;600;700&family=DM+Serif+Display:ital@1&family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(url_for('assets/css/style.css', $baseUrl)); ?>">
</head>
<body>
    <nav class="nav" id="nav">
        <div class="nav-inner">
            <a class="brand" href="#home">
                <img class="brand-logo" src="<?php echo e(url_for('assets/images/logo-white.png', $baseUrl)); ?>" alt="True Launch">
            </a>
            <button class="nav-cta" data-open-modal="contact">Plan Mission</button>
        </div>
    </nav>

    <header class="hero" id="home">
        <video class="hero-bg-video" src="<?php echo e(url_for('assets/images/cover.mp4', $baseUrl)); ?>" autoplay muted loop playsinline></video>
        <div class="container hero-inner">
            <div class="hero-content">
                <h1 class="hero-title">Making Space<br>Accessible <em class="hero-for">for</em> ALL</h1>
                <p class="hero-tagline">One Stop Solution for Small Satellites</p>
            </div>

        </div>
    </header>

    <section class="section" id="manifesto">
        <div class="container">
            <div class="reveal">
                <h2 class="section-title"><?php echo nl2br(e($manifesto['title'] ?? '')); ?></h2>
                <p class="lead" style="margin-top: 28px;"><?php echo e($manifesto['body'] ?? ''); ?></p>
            </div>
            <div class="stats-row reveal">
                <div class="stat-block">
                    <div class="stat-num">Tailored</div>
                    <div class="stat-label">Orbits</div>
                </div>
                <div class="stat-block">
                    <div class="stat-num">3×</div>
                    <div class="stat-label">Faster turnaround</div>
                </div>
                <div class="stat-block">
                    <div class="stat-num">Weekly</div>
                    <div class="stat-label">Launch cadence</div>
                </div>
                <div class="stat-block">
                    <div class="stat-num">Dedicated</div>
                    <div class="stat-label">Mission launches</div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="platform">
        <div class="container">
            <div class="platform-layout reveal">
                <div class="platform-left">
                    <h2 class="section-title" style="margin-bottom:20px;font-size:clamp(18px,2.4vw,34px);">Launch<br>Anywhere, Anytime.</h2>
                    <div class="vehicle-name"><?php echo e($platform['vehicle_name'] ?? 'ROCKET ONE'); ?></div>
                    <p class="vehicle-sub"><?php echo e($platform['vehicle_sub'] ?? ''); ?></p>
                    <div class="vehicle-specs">
                        <?php foreach (($platform['specs'] ?? []) as $spec) : ?>
                            <div>
                                <div class="spec-value"><?php echo e($spec['value'] ?? ''); ?></div>
                                <div class="spec-label"><?php echo e($spec['label'] ?? ''); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="platform-right">
                    <img src="<?php echo e(url_for('assets/images/r1.png', $baseUrl)); ?>" alt="Rocket">
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="capabilities">
        <video class="cap-bg-video" src="<?php echo e(url_for('assets/images/ET.MOV', $baseUrl)); ?>" autoplay muted loop playsinline></video>
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <p class="section-kicker">Capabilities</p>
                    <h2 class="section-title">Engineering that scales with mission demand.</h2>
                </div>
            </div>
            <div class="cap-bar reveal">
                <?php foreach ($capabilities as $i => $capability) : ?>
                    <div class="cap-bar-item">
                        <div class="cap-num"><?php echo str_pad($i + 1, 2, '0', STR_PAD_LEFT); ?></div>
                        <div class="cap-bar-title"><?php echo $capability['title'] ?? ''; ?></div>
                        <p class="cap-desc"><?php echo e($capability['desc'] ?? ''); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="section" id="roadmap">
        <video class="roadmap-bg-video" src="<?php echo e(url_for('assets/images/sats.mp4', $baseUrl)); ?>" autoplay muted loop playsinline></video>
        <div class="container">
            <div class="section-head reveal">
                <div>
                    <p class="section-kicker">The Advantage</p>
                    <h2 class="section-title">Built to<br>Outperform.</h2>
                </div>
                <p class="lead">Hybrid propulsion. Dedicated launches.<br>Built for the pace of modern space.</p>
            </div>
            <div class="timeline">
                <?php foreach ($roadmap as $i => $item) : ?>
                    <div class="timeline-item reveal">
                        <div class="timeline-phase"><?php echo e($item['category'] ?? ''); ?></div>
                        <div class="timeline-title"><?php echo e($item['title'] ?? ''); ?></div>
                        <div class="timeline-desc"><?php echo e($item['desc'] ?? ''); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container cta-inner reveal">
            <h2 class="cta-title"><?php echo e($cta['title'] ?? 'Join us as we bring the future forward!'); ?></h2>
            <p class="cta-sub"><?php echo e($cta['sub'] ?? ''); ?></p>
            <div class="cta-actions">
                <button class="btn btn-primary" data-open-modal="investor"><?php echo e($cta['primary'] ?? 'Become an Investor'); ?></button>
            </div>
        </div>
    </section>

    <div class="modal-overlay" id="modal-contact" aria-hidden="true">
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-contact-title">
            <button class="modal-close" aria-label="Close">&times;</button>
            <p class="section-kicker" style="margin-bottom:12px;">Contact</p>
            <h2 class="modal-title" id="modal-contact-title">Plan your mission with True Launch.</h2>
            <?php if ($sent && $activeForm === 'contact') : ?>
                <div class="form-status success" style="margin-top:20px;">Thanks, your message has been sent.</div>
            <?php elseif ($error && $activeForm === 'contact') : ?>
                <div class="form-status error" style="margin-top:20px;">Message failed to send. Please email us directly.</div>
            <?php endif; ?>
            <form class="form" method="post" action="<?php echo e(url_for('api/contact.php', $baseUrl)); ?>" style="margin-top:28px;">
                <input class="hp-field" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="form" value="contact">
                <div class="field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" placeholder="Full Name" autocomplete="name" required>
                </div>
                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" placeholder="Your Work Email Preferred" autocomplete="email" required>
                </div>
                <div class="field">
                    <label for="inquiry">Inquiry</label>
                    <select id="inquiry" name="inquiry" required>
                        <option value="">Select one</option>
                        <option>Payload planning</option>
                        <option>Launch schedule</option>
                        <option>Partnerships</option>
                        <option>Investment</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="field">
                    <label for="message">Mission details</label>
                    <textarea id="message" name="message" placeholder="Tell us about your payload and timeline" required></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Send Inquiry</button>
            </form>
        </div>
    </div>

    <div class="modal-overlay" id="modal-investor" aria-hidden="true">
        <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="modal-investor-title">
            <button class="modal-close" aria-label="Close">&times;</button>
            <p class="section-kicker" style="margin-bottom:12px;">Investors</p>
            <h2 class="modal-title" id="modal-investor-title">Become an investor in True Launch.</h2>
            <?php if ($sent && $activeForm === 'investor') : ?>
                <div class="form-status success" style="margin-top:20px;">Thanks, your message has been sent.</div>
            <?php elseif ($error && $activeForm === 'investor') : ?>
                <div class="form-status error" style="margin-top:20px;">Message failed to send. Please email us directly.</div>
            <?php endif; ?>
            <form class="form" method="post" action="<?php echo e(url_for('api/contact.php', $baseUrl)); ?>" style="margin-top:28px;">
                <input class="hp-field" type="text" name="website" tabindex="-1" autocomplete="off" aria-hidden="true">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="form" value="investor">
                <input type="hidden" name="inquiry" value="Investment">
                <div class="field">
                    <label for="investor_name">Name</label>
                    <input id="investor_name" name="name" type="text" placeholder="Full Name" autocomplete="name" required>
                </div>
                <div class="field">
                    <label for="investor_email">Email</label>
                    <input id="investor_email" name="email" type="email" placeholder="Your Work Email Preferred" autocomplete="email" required>
                </div>
                <div class="field">
                    <label for="investor_type">Investor type</label>
                    <select id="investor_type" name="investor_type" required>
                        <option value="">Select one</option>
                        <option>Angel</option>
                        <option>Venture fund</option>
                        <option>Family office</option>
                        <option>Strategic partner</option>
                        <option>Other</option>
                    </select>
                </div>
                <div class="field">
                    <label for="investor_message">Investment details</label>
                    <textarea id="investor_message" name="message" placeholder="Tell us about your fund and ticket size" required></textarea>
                </div>
                <button class="btn btn-primary" type="submit">Submit Interest</button>
            </form>
        </div>
    </div>

    <footer class="footer footer-home">
        <div class="container">
            <div class="footer-bottom">
                <div class="footer-brand">
                    <div><a href="<?php echo e(url_for('team.php', $baseUrl)); ?>">About Us</a></div>
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
