<?php
/**
 * Public - Blog Post Detail Page
 * Accessed via /blog/{slug} (nginx rewrite)
 */
require_once 'includes/content-loader.php';

$slug = $_GET['slug'] ?? '';
if (empty($slug) || !$pdo) {
    header('HTTP/1.1 404 Not Found');
    include '404.php';
    exit;
}

// Fetch published post
$stmt = $pdo->prepare("SELECT * FROM ml_cms_blog_posts WHERE slug = ? AND status = 'published' LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    header('HTTP/1.1 404 Not Found');
    include '404.php';
    exit;
}

$pageTitle = $post['title'] . ' - ' . SITE_NAME;
$pageDescription = $post['excerpt'] ?: substr(strip_tags($post['content']), 0, 160);
$currentPage = 'blog';
$heroTitle = $post['title'];
$heroSubtitle = $post['category'] ? $post['category'] . ' &bull; ' . date('F j, Y', strtotime($post['published_at'])) : date('F j, Y', strtotime($post['published_at']));
$heroBg = $post['featured_image_url'] ?: IMG . '/hero/blog-page.jpg';
$breadcrumbs = ['Home' => '/', 'Blog' => '/blog', $post['title'] => ''];

// Fetch related posts
$relatedStmt = $pdo->prepare("SELECT id, title, slug, excerpt, featured_image_url, category, published_at FROM ml_cms_blog_posts WHERE status = 'published' AND id != ? ORDER BY published_at DESC LIMIT 3");
$relatedStmt->execute([$post['id']]);
$relatedPosts = $relatedStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body class="bg-dark text-white font-body overflow-x-hidden loading">
<?php include 'includes/loader.php'; ?>
<?php include 'includes/header.php'; ?>

<main>
    <?php include 'includes/page-hero.php'; ?>

    <!-- Blog Post Content -->
    <section class="section-padding bg-dark">
        <div class="max-w-3xl mx-auto px-6">
            <!-- Post Meta -->
            <div class="flex items-center gap-4 mb-8" data-animate="fade-up">
                <?php if ($post['category']): ?>
                    <span class="badge"><?= htmlspecialchars($post['category']) ?></span>
                <?php endif; ?>
                <span class="text-white/40 text-sm"><?= date('F j, Y', strtotime($post['published_at'])) ?></span>
                <?php if ($post['author_name']): ?>
                    <span class="text-white/40 text-sm">by <?= htmlspecialchars($post['author_name']) ?></span>
                <?php endif; ?>
            </div>

            <!-- Featured Image -->
            <?php if ($post['featured_image_url']): ?>
                <div class="mb-10 rounded-2xl overflow-hidden" data-animate="fade-up">
                    <img src="<?= htmlspecialchars($post['featured_image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-auto object-cover">
                </div>
            <?php endif; ?>

            <!-- Content -->
            <article class="prose prose-invert prose-lg max-w-none" data-animate="fade-up">
                <style>
                    .prose { color: rgba(255,255,255,0.75); line-height: 1.8; }
                    .prose h2 { color: white; font-family: 'Syne', sans-serif; font-size: 1.5rem; font-weight: 700; margin-top: 2.5rem; margin-bottom: 1rem; }
                    .prose h3 { color: white; font-family: 'Syne', sans-serif; font-size: 1.25rem; font-weight: 600; margin-top: 2rem; margin-bottom: 0.75rem; }
                    .prose p { margin-bottom: 1.25rem; }
                    .prose a { color: #FAA416; text-decoration: underline; }
                    .prose ul, .prose ol { margin-bottom: 1.25rem; padding-left: 1.5rem; }
                    .prose li { margin-bottom: 0.5rem; }
                    .prose img { border-radius: 1rem; margin: 2rem 0; }
                    .prose blockquote { border-left: 3px solid #FAA416; padding-left: 1rem; color: rgba(255,255,255,0.6); font-style: italic; }
                    .prose hr { border-color: rgba(255,255,255,0.1); margin: 2rem 0; }
                    .prose code { background: rgba(255,255,255,0.05); padding: 0.2em 0.4em; border-radius: 0.25rem; font-size: 0.875em; }
                    .prose table { width: 100%; border-collapse: collapse; }
                    .prose th, .prose td { border: 1px solid rgba(255,255,255,0.1); padding: 0.5rem 0.75rem; }
                </style>
                <?= $post['content'] ?>
            </article>

            <!-- Share / Back -->
            <div class="flex items-center justify-between mt-12 pt-8 border-t border-white/10" data-animate="fade-up">
                <a href="/blog" class="text-white/40 hover:text-primary transition flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Blog
                </a>
            </div>
        </div>
    </section>

    <!-- Related Posts -->
    <?php if (!empty($relatedPosts)): ?>
    <section class="section-padding bg-dark-100">
        <div class="container-wide">
            <h2 class="font-heading text-2xl font-bold text-white mb-8 text-center" data-animate="text-reveal">More Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8" data-animate="stagger-up">
                <?php foreach ($relatedPosts as $related): ?>
                    <a href="/blog/<?= htmlspecialchars($related['slug']) ?>" class="group bg-dark-50 border border-white/5 rounded-2xl overflow-hidden hover:border-primary/20 transition-all duration-500">
                        <div class="overflow-hidden h-48">
                            <?php if ($related['featured_image_url']): ?>
                                <img src="<?= htmlspecialchars($related['featured_image_url']) ?>" alt="<?= htmlspecialchars($related['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full bg-dark-300 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <?php if ($related['category']): ?>
                                <span class="badge mb-2"><?= htmlspecialchars($related['category']) ?></span>
                            <?php endif; ?>
                            <h3 class="font-heading text-lg font-bold group-hover:text-primary transition-colors"><?= htmlspecialchars($related['title']) ?></h3>
                            <?php if ($related['excerpt']): ?>
                                <p class="text-white/50 text-sm mt-2 line-clamp-2"><?= htmlspecialchars($related['excerpt']) ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php include 'includes/cta-section.php'; ?>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
