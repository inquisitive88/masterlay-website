<?php
require_once 'includes/content-loader.php';
$pageTitle = 'Renovation Insights & Blog - ' . SITE_NAME;
$pageDescription = 'Tips, trends, and project inspiration from Masterlay Renovations. Stay updated with the latest in flooring, stairs, doors, and bathroom renovation ideas.';
$currentPage = 'blog';
$heroTitle = 'Renovation Insights';
$heroSubtitle = 'Tips, trends, and project inspiration';
$heroBg = IMG . '/hero/blog-page.jpg';
$breadcrumbs = ['Home' => '/', 'Blog' => ''];
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body class="bg-dark text-white font-body overflow-x-hidden loading">
<?php include 'includes/loader.php'; ?>
<?php include 'includes/header.php'; ?>

<main>
    <?php include 'includes/page-hero.php'; ?>

    <!-- Blog Grid -->
    <section class="section-padding bg-dark">
        <div class="container-wide">
            <?php
            // Load published blog posts from database
            $blogPosts = [];
            if ($pdo) {
                try {
                    $blogPosts = $pdo->query("SELECT * FROM ml_cms_blog_posts WHERE status = 'published' ORDER BY published_at DESC")->fetchAll();
                } catch (PDOException $e) {
                    $blogPosts = [];
                }
            }
            ?>

            <?php if (!empty($blogPosts)): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-animate="stagger-up">
                    <?php foreach ($blogPosts as $post): ?>
                    <a href="/blog/<?= htmlspecialchars($post['slug']) ?>" class="group bg-dark-50 border border-white/5 rounded-2xl overflow-hidden hover:border-primary/20 transition-all duration-500">
                        <div class="overflow-hidden h-52">
                            <?php if ($post['featured_image_url']): ?>
                                <img src="<?= htmlspecialchars($post['featured_image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full bg-dark-300 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <?php if ($post['category']): ?>
                                    <span class="badge"><?= htmlspecialchars($post['category']) ?></span>
                                <?php endif; ?>
                                <span class="text-white/30 text-xs"><?= date('M j, Y', strtotime($post['published_at'])) ?></span>
                            </div>
                            <h3 class="font-heading text-lg font-bold mb-3 group-hover:text-primary transition-colors"><?= htmlspecialchars($post['title']) ?></h3>
                            <?php if ($post['excerpt']): ?>
                                <p class="text-white/50 text-sm line-clamp-2"><?= htmlspecialchars($post['excerpt']) ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <!-- No published posts - show placeholders -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-animate="stagger-up">
                    <?php
                    $blogPlaceholders = [
                        ['title' => 'How to Choose the Right Flooring for Your Home', 'category' => 'Flooring', 'image' => IMG . '/services/floor-installation.jpg'],
                        ['title' => '5 Bathroom Renovation Trends for 2025', 'category' => 'Bathrooms', 'image' => IMG . '/services/bathroom-renovations.jpg'],
                        ['title' => 'The Benefits of Custom Staircase Design', 'category' => 'Stairs', 'image' => IMG . '/services/custom-stairs.jpg'],
                    ];
                    foreach ($blogPlaceholders as $placeholder):
                    ?>
                    <div class="group bg-dark-50 border border-white/5 rounded-2xl overflow-hidden hover:border-primary/20 transition-all duration-500">
                        <div class="overflow-hidden h-52">
                            <img src="<?= $placeholder['image'] ?>" alt="<?= $placeholder['title'] ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" loading="lazy">
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-3">
                                <span class="badge"><?= $placeholder['category'] ?></span>
                                <span class="text-white/30 text-xs">Coming Soon</span>
                            </div>
                            <h3 class="font-heading text-lg font-bold mb-3 group-hover:text-primary transition-colors"><?= $placeholder['title'] ?></h3>
                            <p class="text-white/50 text-sm line-clamp-2">Stay tuned — this article is coming soon.</p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Coming Soon Message -->
                <div class="text-center mt-16" data-animate="fade-up">
                    <div class="glass rounded-2xl p-10 max-w-lg mx-auto">
                        <svg class="w-12 h-12 text-primary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                        <h3 class="font-heading text-xl font-bold mb-2">More Articles Coming Soon</h3>
                        <p class="text-white/50 text-sm">We're working on expert guides, renovation tips, and project inspiration. Check back soon or follow us on social media for updates.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/cta-section.php'; ?>
</main>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
