<?php
// Usage: Set $service array before including this file
// Required keys: slug, title, short, icon, image
$cardBasePath = $basePath ?? '';
?>
<a href="<?= $cardBasePath ?>services/<?= $service['slug'] ?>" class="service-card block">
    <div class="card-image">
        <img src="<?= $cardBasePath ?><?= $service['image'] ?>" alt="<?= htmlspecialchars($service['title']) ?>" loading="lazy">
    </div>
    <div class="card-body">
        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center mb-3">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?= $service['icon'] ?>"/>
            </svg>
        </div>
        <h3 class="card-title"><?= $service['title'] ?></h3>
        <p class="card-text"><?= $service['short'] ?></p>
        <span class="card-link">
            Learn More
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </span>
    </div>
</a>
