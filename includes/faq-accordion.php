<?php
// Usage: Set $faqItem with 'q' and 'a' keys before including
?>
<div class="faq-item">
    <button class="faq-question" type="button">
        <span><?= htmlspecialchars($faqItem['q']) ?></span>
        <svg class="faq-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    </button>
    <div class="faq-answer">
        <div class="faq-answer-inner"><?= htmlspecialchars($faqItem['a']) ?></div>
    </div>
</div>
