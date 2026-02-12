<?php
// Usage: Set $breadcrumbs array before including
// Example: $breadcrumbs = ['Home' => '/', 'Services' => '/services', 'Floor Installation' => ''];
$bcBasePath = $basePath ?? '';
if (isset($breadcrumbs) && is_array($breadcrumbs)):
?>
<nav class="mb-6" aria-label="Breadcrumb">
    <ol class="flex flex-wrap items-center gap-2 text-sm">
        <?php $i = 0; $total = count($breadcrumbs); ?>
        <?php foreach ($breadcrumbs as $label => $url): ?>
            <?php $i++; ?>
            <?php if ($i < $total && $url): ?>
                <li>
                    <a href="<?= $bcBasePath . ltrim($url, '/') ?>" class="text-white/40 hover:text-primary transition-colors"><?= htmlspecialchars($label) ?></a>
                </li>
                <li class="text-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
            <?php else: ?>
                <li class="text-primary font-medium"><?= htmlspecialchars($label) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ol>
</nav>
<?php endif; ?>
