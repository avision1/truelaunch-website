<?php

require __DIR__ . '/../../app/bootstrap.php';
require_login();

function dashboard_time_ago(?string $timestamp): string
{
    if (!$timestamp) {
        return 'n/a';
    }

    $ts = strtotime($timestamp);
    if (!$ts) {
        return 'n/a';
    }

    $diff = time() - $ts;
    if ($diff < 60) {
        return 'just now';
    }

    $mins = (int) floor($diff / 60);
    if ($mins < 60) {
        return $mins . ' min' . ($mins === 1 ? '' : 's') . ' ago';
    }

    $hours = (int) floor($mins / 60);
    if ($hours < 24) {
        return $hours . ' hour' . ($hours === 1 ? '' : 's') . ' ago';
    }

    $days = (int) floor($hours / 24);
    if ($days < 30) {
        return $days . ' day' . ($days === 1 ? '' : 's') . ' ago';
    }

    $months = (int) floor($days / 30);
    if ($months < 12) {
        return $months . ' month' . ($months === 1 ? '' : 's') . ' ago';
    }

    $years = (int) floor($months / 12);
    return $years . ' year' . ($years === 1 ? '' : 's') . ' ago';
}

$status     = '';
$statusType = 'success';
$activeSection = $_GET['section'] ?? 'overview';

if (!$pdo) {
    $status     = 'No database connection. Update app/config.php with your cPanel credentials to enable saving.';
    $statusType = 'error';
}

// ── Handle POST saves ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $pdo) {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $status = 'Invalid session token.';
        $statusType = 'error';
    } else {
        $section = $_POST['_section'] ?? '';

        if ($section === 'hero') {
            save_site_content($pdo, 'hero', [
                'eyebrow'      => trim($_POST['hero_eyebrow'] ?? ''),
                'title_line1'  => trim($_POST['hero_title_line1'] ?? ''),
                'title_line2'  => trim($_POST['hero_title_line2'] ?? ''),
                'title_sub'    => trim($_POST['hero_title_sub'] ?? ''),
                'subtitle'     => trim($_POST['hero_subtitle'] ?? ''),
                'cta_primary'  => trim($_POST['hero_cta_primary'] ?? ''),
                'cta_secondary'=> trim($_POST['hero_cta_secondary'] ?? ''),
                'metrics' => [
                    ['value' => trim($_POST['hero_m1_val'] ?? ''), 'label' => trim($_POST['hero_m1_lbl'] ?? '')],
                    ['value' => trim($_POST['hero_m2_val'] ?? ''), 'label' => trim($_POST['hero_m2_lbl'] ?? '')],
                    ['value' => trim($_POST['hero_m3_val'] ?? ''), 'label' => trim($_POST['hero_m3_lbl'] ?? '')],
                ],
            ]);
        }

        if ($section === 'manifesto') {
            save_site_content($pdo, 'manifesto', [
                'title' => trim($_POST['manifesto_title'] ?? ''),
                'body'  => trim($_POST['manifesto_body'] ?? ''),
                'pills' => clean_list($_POST['manifesto_pills'] ?? ''),
            ]);
        }

        if ($section === 'platform') {
            save_site_content($pdo, 'platform', [
                'kicker'       => trim($_POST['platform_kicker'] ?? ''),
                'title'        => trim($_POST['platform_title'] ?? ''),
                'lead'         => trim($_POST['platform_lead'] ?? ''),
                'vehicle_name' => trim($_POST['platform_vehicle_name'] ?? ''),
                'vehicle_sub'  => trim($_POST['platform_vehicle_sub'] ?? ''),
                'specs' => [
                    ['value' => trim($_POST['spec_1_val'] ?? ''), 'label' => trim($_POST['spec_1_lbl'] ?? '')],
                    ['value' => trim($_POST['spec_2_val'] ?? ''), 'label' => trim($_POST['spec_2_lbl'] ?? '')],
                    ['value' => trim($_POST['spec_3_val'] ?? ''), 'label' => trim($_POST['spec_3_lbl'] ?? '')],
                    ['value' => trim($_POST['spec_4_val'] ?? ''), 'label' => trim($_POST['spec_4_lbl'] ?? '')],
                    ['value' => trim($_POST['spec_5_val'] ?? ''), 'label' => trim($_POST['spec_5_lbl'] ?? '')],
                    ['value' => trim($_POST['spec_6_val'] ?? ''), 'label' => trim($_POST['spec_6_lbl'] ?? '')],
                ],
            ]);
        }

        if ($section === 'capabilities') {
            save_site_content($pdo, 'capabilities', [
                ['title' => trim($_POST['cap_1_title'] ?? ''), 'desc' => trim($_POST['cap_1_desc'] ?? '')],
                ['title' => trim($_POST['cap_2_title'] ?? ''), 'desc' => trim($_POST['cap_2_desc'] ?? '')],
                ['title' => trim($_POST['cap_3_title'] ?? ''), 'desc' => trim($_POST['cap_3_desc'] ?? '')],
            ]);
        }

        if ($section === 'roadmap') {
            save_site_content($pdo, 'roadmap', [
                ['title' => trim($_POST['roadmap_1_title'] ?? ''), 'desc' => trim($_POST['roadmap_1_desc'] ?? '')],
                ['title' => trim($_POST['roadmap_2_title'] ?? ''), 'desc' => trim($_POST['roadmap_2_desc'] ?? '')],
                ['title' => trim($_POST['roadmap_3_title'] ?? ''), 'desc' => trim($_POST['roadmap_3_desc'] ?? '')],
            ]);
        }

        if ($section === 'cta') {
            save_site_content($pdo, 'cta', [
                'title'     => trim($_POST['cta_title'] ?? ''),
                'sub'       => trim($_POST['cta_sub'] ?? ''),
                'primary'   => trim($_POST['cta_primary'] ?? ''),
                'secondary' => trim($_POST['cta_secondary'] ?? ''),
            ]);
        }

        if ($section === 'contact') {
            save_site_content($pdo, 'contact', [
                'email' => trim($_POST['contact_email'] ?? ''),
                'phone' => trim($_POST['contact_phone'] ?? ''),
                'focus' => trim($_POST['contact_focus'] ?? ''),
            ]);
        }

        if ($section === 'quick_draft') {
            $draftTitle = trim($_POST['draft_title'] ?? '');
            $draftBody = trim($_POST['draft_body'] ?? '');

            if ($draftTitle === '' && $draftBody === '') {
                $status = 'Draft cannot be empty.';
                $statusType = 'error';
            } else {
                $current = get_site_content($pdo, $defaults);
                $drafts = $current['quick_drafts'] ?? [];
                if (!is_array($drafts)) {
                    $drafts = [];
                }

                $drafts[] = [
                    'title' => $draftTitle,
                    'body' => $draftBody,
                    'created_at' => date('Y-m-d H:i:s'),
                ];

                $drafts = array_slice($drafts, -10);
                save_site_content($pdo, 'quick_drafts', $drafts);

                $status = 'Draft saved.';
                $statusType = 'success';
                $activeSection = 'overview';
            }
        }

        if ($status === '') {
            $status     = 'Changes saved successfully.';
            $statusType = 'success';
            $activeSection = $section;
        }
    }
}

// ── Mark inquiry as read ──────────────────────────────────────────────────
if (isset($_GET['read']) && $pdo) {
    $pdo->prepare('UPDATE inquiries SET is_read = 1 WHERE id = ?')->execute([(int)$_GET['read']]);
    header('Location: dashboard.php?section=inquiries');
    exit;
}

// ── Load content ──────────────────────────────────────────────────────────
if ($pdo) {
    $content = get_site_content($pdo, $defaults);
}

$hero         = $content['hero'] ?? [];
$manifesto    = $content['manifesto'] ?? [];
$platform     = $content['platform'] ?? [];
$capabilities = $content['capabilities'] ?? [];
$roadmap      = $content['roadmap'] ?? [];
$cta          = $content['cta'] ?? [];
$contact      = $content['contact'] ?? [];
$drafts       = $content['quick_drafts'] ?? [];

if (!is_array($drafts)) {
    $drafts = [];
}

$sections = [
    'hero'         => ['icon' => '&#9650;', 'label' => 'Hero'],
    'manifesto'    => ['icon' => '&#9670;', 'label' => 'Manifesto'],
    'platform'     => ['icon' => '&#9711;', 'label' => 'Platform'],
    'capabilities' => ['icon' => '&#9635;', 'label' => 'Capabilities'],
    'roadmap'      => ['icon' => '&#9654;', 'label' => 'Roadmap'],
    'cta'          => ['icon' => '&#9733;', 'label' => 'CTA Banner'],
    'contact'      => ['icon' => '&#9743;', 'label' => 'Contact Info'],
];

// ── Load inquiries ────────────────────────────────────────────────────────
$inquiries   = [];
$unreadCount = 0;
$totalCount  = 0;

if ($pdo) {
    try {
        $inquiries   = $pdo->query('SELECT * FROM inquiries ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
        $totalCount  = count($inquiries);
        $unreadCount = count(array_filter($inquiries, fn($r) => !$r['is_read']));
    } catch (Throwable $e) {
        // Table may not exist yet
    }
}

$contentUpdates = [];
if ($pdo) {
    try {
        $contentUpdates = $pdo->query('SELECT content_key, updated_at FROM site_content ORDER BY updated_at DESC LIMIT 8')
            ->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        // Table may not exist yet
    }
}

if (!empty($contentUpdates)) {
    $contentUpdates = array_values(array_filter(
        $contentUpdates,
        fn($row) => isset($sections[$row['content_key']])
    ));
}

$recentInquiries = array_slice($inquiries, 0, 5);
$recentDrafts = array_slice(array_reverse($drafts), 0, 5);
$lastUpdate = $contentUpdates[0]['updated_at'] ?? null;

$activity = [];
foreach (array_slice($inquiries, 0, 4) as $row) {
    $activity[] = [
        'title' => 'Inquiry from ' . $row['name'],
        'meta' => $row['inquiry'] !== '' ? $row['inquiry'] : 'General',
        'time' => $row['created_at'] ?? '',
        'tag' => $row['is_read'] ? 'Read' : 'New',
    ];
}

foreach (array_slice($contentUpdates, 0, 4) as $row) {
    $label = $sections[$row['content_key']]['label'] ?? ucwords(str_replace('_', ' ', $row['content_key']));
    $activity[] = [
        'title' => 'Updated ' . $label,
        'meta' => 'Content section',
        'time' => $row['updated_at'] ?? '',
        'tag' => 'Update',
    ];
}

usort($activity, function (array $a, array $b): int {
    return strtotime($b['time'] ?? '') <=> strtotime($a['time'] ?? '');
});

$activity = array_slice($activity, 0, 6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — True Launch</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chakra+Petch:wght@400;600&family=Sora:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<!-- ── SIDEBAR ─────────────────────────────────────────── -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="../assets/images/logo-white.png" alt="True Launch">
    </div>

    <div class="sidebar-label">Overview</div>
    <ul class="sidebar-nav">
        <li>
            <a href="#" data-panel="overview" class="<?php echo $activeSection === 'overview' ? 'active' : ''; ?>">
                <span class="nav-icon">&#9632;</span> Dashboard
            </a>
        </li>
        <li>
            <a href="#" data-panel="inquiries" class="<?php echo $activeSection === 'inquiries' ? 'active' : ''; ?>">
                <span class="nav-icon">&#9993;</span> Inquiries
                <?php if ($unreadCount > 0) : ?>
                    <span class="sidebar-badge"><?php echo $unreadCount; ?></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>

    <div class="sidebar-label">Content</div>
    <ul class="sidebar-nav">
        <?php foreach ($sections as $key => $s) : ?>
        <li>
            <a href="#" data-panel="<?php echo $key; ?>" class="<?php echo $activeSection === $key ? 'active' : ''; ?>">
                <span class="nav-icon"><?php echo $s['icon']; ?></span> <?php echo $s['label']; ?>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="sidebar-footer">
        <a href="../index.php" target="_blank">&#8599; View site</a>
        <a href="logout.php" style="margin-top:2px;">&#8592; Sign out</a>
    </div>
</aside>

<!-- ── MAIN ────────────────────────────────────────────── -->
<div class="main">
    <div class="topbar">
        <div class="topbar-title" id="topbar-title">Dashboard</div>
        <div class="topbar-actions">
            <a class="topbar-btn" href="../index.php" target="_blank">View Site</a>
            <a class="topbar-btn" href="logout.php">Sign Out</a>
        </div>
    </div>

    <div class="content">

        <?php if ($status) : ?>
            <div class="status-bar <?php echo e($statusType); ?>"><?php echo e($status); ?></div>
        <?php endif; ?>

        <?php if (!$pdo) : ?>
            <div class="db-warning">
                &#9888; No database connection. Configure <code>app/config.php</code> to enable content saving.
            </div>
        <?php endif; ?>

        <!-- OVERVIEW -->
        <div class="panel <?php echo $activeSection === 'overview' ? 'active' : ''; ?>" id="panel-overview">
            <div class="overview-header">
                <div>
                    <div class="overview-greeting">Welcome back.</div>
                    <div class="overview-sub">A quick snapshot of your site and recent activity.</div>
                </div>
                <div class="overview-actions">
                    <a class="overview-action-btn" href="../index.php" target="_blank">View site</a>
                    <button class="overview-action-btn" data-panel="inquiries">View inquiries</button>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-label">Total Inquiries</div>
                    <div class="stat-card-value"><?php echo $totalCount; ?></div>
                    <div class="stat-card-sub">All time</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-label">Unread</div>
                    <div class="stat-card-value"><?php echo $unreadCount; ?></div>
                    <div class="stat-card-sub">Needs attention</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-label">Content Sections</div>
                    <div class="stat-card-value"><?php echo count($sections); ?></div>
                    <div class="stat-card-sub">Editable</div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-label">Last Update</div>
                    <div class="stat-card-value" style="font-size:18px;">
                        <?php echo e(dashboard_time_ago($lastUpdate)); ?>
                    </div>
                    <div class="stat-card-sub">Site content</div>
                </div>
            </div>

            <div class="dashboard-layout">
                <div class="dashboard-main">
                    <div class="widget">
                        <div class="widget-head">
                            <div class="widget-title">At a Glance</div>
                        </div>
                        <div class="glance-grid">
                            <div class="glance-item">
                                <div class="glance-label">Content sections</div>
                                <div class="glance-value"><?php echo count($sections); ?></div>
                            </div>
                            <div class="glance-item">
                                <div class="glance-label">Total inquiries</div>
                                <div class="glance-value"><?php echo $totalCount; ?></div>
                            </div>
                            <div class="glance-item">
                                <div class="glance-label">Unread inquiries</div>
                                <div class="glance-value"><?php echo $unreadCount; ?></div>
                            </div>
                            <div class="glance-item">
                                <div class="glance-label">Database</div>
                                <div class="glance-value <?php echo $pdo ? 'glance-good' : 'glance-bad'; ?>">
                                    <?php echo $pdo ? 'Connected' : 'Not connected'; ?>
                                </div>
                            </div>
                        </div>
                        <div class="glance-meta">Last update: <?php echo $lastUpdate ? date('d M Y', strtotime($lastUpdate)) : 'n/a'; ?></div>
                    </div>

                    <div class="widget">
                        <div class="widget-head">
                            <div class="widget-title">Activity</div>
                        </div>
                        <?php if (empty($activity)) : ?>
                            <div class="empty-state compact">No activity yet.</div>
                        <?php else : ?>
                            <div class="activity-list">
                                <?php foreach ($activity as $item) : ?>
                                    <div class="activity-item">
                                        <div>
                                            <div class="activity-title"><?php echo e($item['title']); ?></div>
                                            <div class="activity-meta"><?php echo e($item['meta']); ?> &middot; <?php echo e(dashboard_time_ago($item['time'])); ?></div>
                                        </div>
                                        <span class="activity-tag"><?php echo e($item['tag']); ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="widget">
                        <div class="widget-head">
                            <div class="widget-title">Recent Inquiries</div>
                            <button class="widget-link" data-panel="inquiries">View all</button>
                        </div>
                        <?php if (empty($recentInquiries)) : ?>
                            <div class="empty-state compact">No inquiries yet.</div>
                        <?php else : ?>
                            <div class="list-rows">
                                <?php foreach ($recentInquiries as $row) : ?>
                                    <div class="list-row static">
                                        <div class="list-row-main">
                                            <div class="list-row-title"><?php echo e($row['name']); ?></div>
                                            <div class="list-row-sub"><?php echo e($row['inquiry'] !== '' ? $row['inquiry'] : 'General'); ?></div>
                                        </div>
                                        <div class="list-row-meta"><?php echo e(dashboard_time_ago($row['created_at'] ?? null)); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <aside class="dashboard-side">
                    <div class="widget">
                        <div class="widget-head">
                            <div class="widget-title">Quick Draft</div>
                        </div>
                        <?php if (!$pdo) : ?>
                            <div class="widget-note">Connect the database to save drafts.</div>
                        <?php endif; ?>
                        <form method="post" class="quick-draft-form">
                            <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                            <input type="hidden" name="_section" value="quick_draft">
                            <div class="form-grid">
                                <div class="field">
                                    <label>Title</label>
                                    <input type="text" name="draft_title" placeholder="Draft title" <?php echo $pdo ? '' : 'disabled'; ?>>
                                </div>
                                <div class="field">
                                    <label>Content</label>
                                    <textarea name="draft_body" placeholder="Write a quick note..." <?php echo $pdo ? '' : 'disabled'; ?>></textarea>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button class="btn-save" type="submit" <?php echo $pdo ? '' : 'disabled'; ?>>Save Draft</button>
                            </div>
                        </form>
                        <div class="widget-subtitle">Recent drafts</div>
                        <?php if (empty($recentDrafts)) : ?>
                            <div class="empty-state compact">No drafts yet.</div>
                        <?php else : ?>
                            <div class="list-rows">
                                <?php foreach ($recentDrafts as $draft) : ?>
                                    <?php
                                        $draftTitle = trim($draft['title'] ?? '');
                                        $draftBody = trim($draft['body'] ?? '');
                                        $draftSnippet = $draftBody === '' ? 'Draft note' : (strlen($draftBody) > 42 ? substr($draftBody, 0, 42) . '...' : $draftBody);
                                    ?>
                                    <div class="list-row static">
                                        <div class="list-row-main">
                                            <div class="list-row-title"><?php echo e($draftTitle !== '' ? $draftTitle : 'Untitled'); ?></div>
                                            <div class="list-row-sub"><?php echo e($draftSnippet); ?></div>
                                        </div>
                                        <div class="list-row-meta"><?php echo e(dashboard_time_ago($draft['created_at'] ?? null)); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="widget">
                        <div class="widget-head">
                            <div class="widget-title">Recent Pages</div>
                        </div>
                        <?php if (empty($contentUpdates)) : ?>
                            <div class="empty-state compact">No page updates yet.</div>
                        <?php else : ?>
                            <div class="list-rows">
                                <?php foreach ($contentUpdates as $row) : ?>
                                    <?php $key = $row['content_key']; $label = $sections[$key]['label'] ?? ucwords(str_replace('_', ' ', $key)); ?>
                                    <button class="list-row" data-panel="<?php echo e($key); ?>">
                                        <div class="list-row-main">
                                            <div class="list-row-title"><?php echo e($label); ?></div>
                                            <div class="list-row-sub">Content section</div>
                                        </div>
                                        <div class="list-row-meta"><?php echo e(dashboard_time_ago($row['updated_at'] ?? null)); ?></div>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="widget">
                        <div class="widget-head">
                            <div class="widget-title">Quick Links</div>
                        </div>
                        <div class="quick-links-list">
                            <?php foreach ($sections as $key => $s) : ?>
                                <button class="quick-link-btn" data-panel="<?php echo $key; ?>"><?php echo $s['label']; ?></button>
                            <?php endforeach; ?>
                            <button class="quick-link-btn" data-panel="inquiries">Inquiries</button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>

        <!-- INQUIRIES -->
        <div class="panel <?php echo $activeSection === 'inquiries' ? 'active' : ''; ?>" id="panel-inquiries">
            <div class="section-hd">
                <div>
                    <div class="section-hd-title">Inquiries</div>
                    <div class="section-hd-sub"><?php echo $totalCount; ?> total &mdash; <?php echo $unreadCount; ?> unread</div>
                </div>
            </div>
            <div class="card" style="padding:0;overflow:hidden;">
                <?php if (empty($inquiries)) : ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">&#9993;</div>
                        No inquiries yet. They will appear here when visitors submit the contact form.
                    </div>
                <?php else : ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Inquiry</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inquiries as $row) : ?>
                                    <tr>
                                        <td class="td-name"><?php echo e($row['name']); ?></td>
                                        <td><a href="mailto:<?php echo e($row['email']); ?>" style="color:var(--muted)"><?php echo e($row['email']); ?></a></td>
                                        <td><?php echo e($row['inquiry'] ?: '—'); ?></td>
                                        <td class="td-msg"><p><?php echo e($row['message']); ?></p></td>
                                        <td style="white-space:nowrap;"><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <?php if ($row['is_read']) : ?>
                                                <span class="badge badge-read">Read</span>
                                            <?php else : ?>
                                                <span class="badge badge-new">New</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$row['is_read']) : ?>
                                                <a href="?read=<?php echo (int)$row['id']; ?>" style="font-size:11px;color:var(--muted-2);text-transform:uppercase;letter-spacing:.1em;">Mark read</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- HERO -->
        <div class="panel <?php echo $activeSection === 'hero' ? 'active' : ''; ?>" id="panel-hero">
            <div class="section-hd">
                <div><div class="section-hd-title">Hero</div><div class="section-hd-sub">Main headline and metrics</div></div>
            </div>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="_section" value="hero">
                <div class="card">
                    <div class="card-title">Headline</div>
                    <div class="form-grid">
                        <div class="field">
                            <label>Eyebrow</label>
                            <input type="text" name="hero_eyebrow" value="<?php echo e($hero['eyebrow'] ?? ''); ?>">
                        </div>
                        <div class="form-grid grid-3">
                            <div class="field">
                                <label>Title line 1</label>
                                <input type="text" name="hero_title_line1" value="<?php echo e($hero['title_line1'] ?? ''); ?>">
                            </div>
                            <div class="field">
                                <label>Title line 2</label>
                                <input type="text" name="hero_title_line2" value="<?php echo e($hero['title_line2'] ?? ''); ?>">
                            </div>
                            <div class="field">
                                <label>Title sub</label>
                                <input type="text" name="hero_title_sub" value="<?php echo e($hero['title_sub'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="field">
                            <label>Subtitle</label>
                            <textarea name="hero_subtitle"><?php echo e($hero['subtitle'] ?? ''); ?></textarea>
                        </div>
                        <div class="form-grid grid-2">
                            <div class="field"><label>Primary CTA</label><input type="text" name="hero_cta_primary" value="<?php echo e($hero['cta_primary'] ?? ''); ?>"></div>
                            <div class="field"><label>Secondary CTA</label><input type="text" name="hero_cta_secondary" value="<?php echo e($hero['cta_secondary'] ?? ''); ?>"></div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">Metrics</div>
                    <div class="form-grid grid-3">
                        <?php for ($i = 1; $i <= 3; $i++) : $m = $hero['metrics'][$i-1] ?? []; ?>
                            <div class="field"><label>Metric <?php echo $i; ?> value</label><input type="text" name="hero_m<?php echo $i; ?>_val" value="<?php echo e($m['value'] ?? ''); ?>"></div>
                            <div class="field"><label>Metric <?php echo $i; ?> label</label><input type="text" name="hero_m<?php echo $i; ?>_lbl" value="<?php echo e($m['label'] ?? ''); ?>"></div>
                            <?php if ($i < 3) : ?><div></div><?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="form-actions"><button class="btn-save" type="submit">Save Hero</button></div>
            </form>
        </div>

        <!-- MANIFESTO -->
        <div class="panel <?php echo $activeSection === 'manifesto' ? 'active' : ''; ?>" id="panel-manifesto">
            <div class="section-hd">
                <div><div class="section-hd-title">Manifesto</div><div class="section-hd-sub">Mission statement section</div></div>
            </div>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="_section" value="manifesto">
                <div class="card">
                    <div class="card-title">Content</div>
                    <div class="form-grid">
                        <div class="field"><label>Title</label><textarea name="manifesto_title"><?php echo e($manifesto['title'] ?? ''); ?></textarea></div>
                        <div class="field"><label>Body</label><textarea name="manifesto_body"><?php echo e($manifesto['body'] ?? ''); ?></textarea></div>
                        <div class="field"><label>Pills (comma separated)</label><input type="text" name="manifesto_pills" value="<?php echo e(implode(', ', $manifesto['pills'] ?? [])); ?>"></div>
                    </div>
                </div>
                <div class="form-actions"><button class="btn-save" type="submit">Save Manifesto</button></div>
            </form>
        </div>

        <!-- PLATFORM -->
        <div class="panel <?php echo $activeSection === 'platform' ? 'active' : ''; ?>" id="panel-platform">
            <div class="section-hd">
                <div><div class="section-hd-title">Platform</div><div class="section-hd-sub">ROCKET ONE vehicle section</div></div>
            </div>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="_section" value="platform">
                <div class="card">
                    <div class="card-title">Section copy</div>
                    <div class="form-grid">
                        <div class="form-grid grid-2">
                            <div class="field"><label>Kicker</label><input type="text" name="platform_kicker" value="<?php echo e($platform['kicker'] ?? ''); ?>"></div>
                            <div class="field"><label>Vehicle name</label><input type="text" name="platform_vehicle_name" value="<?php echo e($platform['vehicle_name'] ?? ''); ?>"></div>
                        </div>
                        <div class="field"><label>Title</label><input type="text" name="platform_title" value="<?php echo e($platform['title'] ?? ''); ?>"></div>
                        <div class="field"><label>Lead</label><textarea name="platform_lead"><?php echo e($platform['lead'] ?? ''); ?></textarea></div>
                        <div class="field"><label>Vehicle subline</label><input type="text" name="platform_vehicle_sub" value="<?php echo e($platform['vehicle_sub'] ?? ''); ?>"></div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-title">Specs</div>
                    <div class="form-grid grid-2">
                        <?php for ($i = 1; $i <= 6; $i++) : $sp = $platform['specs'][$i-1] ?? []; ?>
                            <div class="field"><label>Spec <?php echo $i; ?> value</label><input type="text" name="spec_<?php echo $i; ?>_val" value="<?php echo e($sp['value'] ?? ''); ?>"></div>
                            <div class="field"><label>Spec <?php echo $i; ?> label</label><input type="text" name="spec_<?php echo $i; ?>_lbl" value="<?php echo e($sp['label'] ?? ''); ?>"></div>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="form-actions"><button class="btn-save" type="submit">Save Platform</button></div>
            </form>
        </div>

        <!-- CAPABILITIES -->
        <div class="panel <?php echo $activeSection === 'capabilities' ? 'active' : ''; ?>" id="panel-capabilities">
            <div class="section-hd">
                <div><div class="section-hd-title">Capabilities</div><div class="section-hd-sub">Three capability cards</div></div>
            </div>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="_section" value="capabilities">
                <?php for ($i = 1; $i <= 3; $i++) : $cap = $capabilities[$i-1] ?? []; ?>
                    <div class="card">
                        <div class="card-title">Capability <?php echo $i; ?></div>
                        <div class="form-grid">
                            <div class="field"><label>Title</label><input type="text" name="cap_<?php echo $i; ?>_title" value="<?php echo e($cap['title'] ?? ''); ?>"></div>
                            <div class="field"><label>Description</label><textarea name="cap_<?php echo $i; ?>_desc"><?php echo e($cap['desc'] ?? ''); ?></textarea></div>
                        </div>
                    </div>
                <?php endfor; ?>
                <div class="form-actions"><button class="btn-save" type="submit">Save Capabilities</button></div>
            </form>
        </div>

        <!-- ROADMAP -->
        <div class="panel <?php echo $activeSection === 'roadmap' ? 'active' : ''; ?>" id="panel-roadmap">
            <div class="section-hd">
                <div><div class="section-hd-title">Roadmap</div><div class="section-hd-sub">Three timeline phases</div></div>
            </div>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="_section" value="roadmap">
                <?php for ($i = 1; $i <= 3; $i++) : $rm = $roadmap[$i-1] ?? []; ?>
                    <div class="card">
                        <div class="card-title">Phase <?php echo $i; ?></div>
                        <div class="form-grid">
                            <div class="field"><label>Title</label><input type="text" name="roadmap_<?php echo $i; ?>_title" value="<?php echo e($rm['title'] ?? ''); ?>"></div>
                            <div class="field"><label>Description</label><textarea name="roadmap_<?php echo $i; ?>_desc"><?php echo e($rm['desc'] ?? ''); ?></textarea></div>
                        </div>
                    </div>
                <?php endfor; ?>
                <div class="form-actions"><button class="btn-save" type="submit">Save Roadmap</button></div>
            </form>
        </div>

        <!-- CTA -->
        <div class="panel <?php echo $activeSection === 'cta' ? 'active' : ''; ?>" id="panel-cta">
            <div class="section-hd">
                <div><div class="section-hd-title">CTA Banner</div><div class="section-hd-sub">Call-to-action section above footer</div></div>
            </div>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="_section" value="cta">
                <div class="card">
                    <div class="card-title">Content</div>
                    <div class="form-grid">
                        <div class="field"><label>Title</label><input type="text" name="cta_title" value="<?php echo e($cta['title'] ?? ''); ?>"></div>
                        <div class="field"><label>Sub copy</label><textarea name="cta_sub"><?php echo e($cta['sub'] ?? ''); ?></textarea></div>
                        <div class="form-grid grid-2">
                            <div class="field"><label>Primary button</label><input type="text" name="cta_primary" value="<?php echo e($cta['primary'] ?? ''); ?>"></div>
                            <div class="field"><label>Secondary button</label><input type="text" name="cta_secondary" value="<?php echo e($cta['secondary'] ?? ''); ?>"></div>
                        </div>
                    </div>
                </div>
                <div class="form-actions"><button class="btn-save" type="submit">Save CTA</button></div>
            </form>
        </div>

        <!-- CONTACT -->
        <div class="panel <?php echo $activeSection === 'contact' ? 'active' : ''; ?>" id="panel-contact">
            <div class="section-hd">
                <div><div class="section-hd-title">Contact Info</div><div class="section-hd-sub">Displayed in the contact modal</div></div>
            </div>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?php echo e(csrf_token()); ?>">
                <input type="hidden" name="_section" value="contact">
                <div class="card">
                    <div class="card-title">Details</div>
                    <div class="form-grid">
                        <div class="form-grid grid-2">
                            <div class="field"><label>Email</label><input type="email" name="contact_email" value="<?php echo e($contact['email'] ?? ''); ?>"></div>
                            <div class="field"><label>Phone</label><input type="text" name="contact_phone" value="<?php echo e($contact['phone'] ?? ''); ?>"></div>
                        </div>
                        <div class="field"><label>Focus line</label><input type="text" name="contact_focus" value="<?php echo e($contact['focus'] ?? ''); ?>"></div>
                    </div>
                </div>
                <div class="form-actions"><button class="btn-save" type="submit">Save Contact</button></div>
            </form>
        </div>

    </div><!-- /content -->
</div><!-- /main -->

<script>
(function () {
    const titles = {
        overview: 'Dashboard', inquiries: 'Inquiries',
        hero: 'Hero', manifesto: 'Manifesto', platform: 'Platform',
        capabilities: 'Capabilities', roadmap: 'Roadmap',
        cta: 'CTA Banner', contact: 'Contact Info'
    };

    function activate(key) {
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('[data-panel]').forEach(a => a.classList.remove('active'));
        const panel = document.getElementById('panel-' + key);
        if (panel) panel.classList.add('active');
        document.querySelectorAll('[data-panel="' + key + '"]').forEach(a => a.classList.add('active'));
        const tb = document.getElementById('topbar-title');
        if (tb) tb.textContent = titles[key] || key;
    }

    document.querySelectorAll('[data-panel]').forEach(el => {
        el.addEventListener('click', function (e) {
            e.preventDefault();
            activate(this.dataset.panel);
        });
    });
})();
</script>
</body>
</html>
