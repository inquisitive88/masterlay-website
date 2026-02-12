<?php
// ============================================
// Masterlay Renovations - Site Configuration
// ============================================

// Company Information
define('SITE_NAME', 'Masterlay Renovations Inc.');
define('SITE_TAGLINE', 'Elevating Homes Through Precision Craftsmanship');
define('SITE_URL', 'https://masterlayrenovations.ca');
define('PHONE', '+14318877709');
define('PHONE_DISPLAY', '(431) 887-7709');
define('EMAIL', 'inquiry@masterlayrenovations.ca');
define('ADDRESS', 'Brampton, ON, Canada');
define('HOURS', 'Sun - Sat: 7:00 AM - 8:00 PM');
define('YEAR_ESTABLISHED', 2018);

// Social Media
define('INSTAGRAM', 'https://instagram.com/masterlay_renovations');
define('FACEBOOK', 'https://facebook.com/61559863490775');
define('TIKTOK', 'https://tiktok.com/@masterlay_renovations');

// Brand Colors
define('COLOR_PRIMARY', '#FAA416');
define('COLOR_PRIMARY_LIGHT', '#FDB844');
define('COLOR_PRIMARY_DARK', '#D88A0A');
define('COLOR_DARK', '#0A0A0A');
define('COLOR_DARK_SURFACE', '#141414');
define('COLOR_DARK_CARD', '#1A1A1A');

// Services Data
$services = [
    [
        'slug' => 'floor-installation',
        'title' => 'Floor Installation',
        'short' => 'Premium vinyl plank flooring installed with expert precision and lasting durability.',
        'description' => 'We specialize in vinyl plank flooring installations that combine design flexibility with unmatched durability. From selecting the perfect material to flawless installation, we handle every detail.',
        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
        'image' => 'images/services/floor-installation.jpg',
        'hero_image' => 'images/hero/floor-installation.jpg',
    ],
    [
        'slug' => 'floor-refinishing',
        'title' => 'Floor Refinishing',
        'short' => 'Restore the natural beauty of your hardwood floors with advanced refinishing techniques.',
        'description' => 'Our hardwood floor refinishing service breathes new life into worn, scratched, or faded floors. Using dustless sanding technology and premium stains, we deliver stunning results.',
        'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',
        'image' => 'images/services/floor-refinishing.jpg',
        'hero_image' => 'images/hero/floor-refinishing.jpg',
    ],
    [
        'slug' => 'floor-repair',
        'title' => 'Floor Repair',
        'short' => 'Expert solutions for scratches, cracks, water damage, and damaged tiles.',
        'description' => 'From squeaky boards to water-damaged sections, our floor repair service addresses every issue. We diagnose the root cause and provide lasting fixes that blend seamlessly with your existing flooring.',
        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
        'image' => 'images/services/floor-repair.jpg',
        'hero_image' => 'images/hero/floor-repair.jpg',
    ],
    [
        'slug' => 'custom-stairs',
        'title' => 'Custom Stairs',
        'short' => 'Tailored staircase designs from traditional elegance to contemporary minimalism.',
        'description' => 'Our custom staircase services transform one of your home\'s most prominent features. Whether you prefer traditional hardwood, modern floating stairs, or contemporary metal-and-glass combinations, we craft each system with precision.',
        'icon' => 'M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12',
        'image' => 'images/services/custom-stairs.jpg',
        'hero_image' => 'images/hero/custom-stairs.jpg',
    ],
    [
        'slug' => 'door-installation',
        'title' => 'Door Installation',
        'short' => 'Front, interior, and decorative doors installed with flawless precision.',
        'description' => 'From grand entry doors that make a statement to interior doors that complete your design vision, we handle every installation with meticulous care. Our selection includes front doors, patio doors, French doors, and custom decorative options.',
        'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z',
        'image' => 'images/services/door-installation.jpg',
        'hero_image' => 'images/hero/door-installation.jpg',
    ],
    [
        'slug' => 'basement-renovation',
        'title' => 'Basement Renovation',
        'short' => 'Complete basement transformations from unfinished space to stunning living areas.',
        'description' => 'Our basement renovation service turns underused space into beautiful, functional living areas. From framing and insulation to flooring, lighting, and finishing — we handle every aspect of the build to create home theatres, rec rooms, guest suites, home offices, and more.',
        'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1',
        'image' => 'images/services/basement-renovation.jpg',
        'hero_image' => 'images/hero/basement-renovation.jpg',
    ],
    [
        'slug' => 'bathroom-renovations',
        'title' => 'Bathroom Renovations',
        'short' => 'Complete bathroom transformations with flawless finish and premium fixtures.',
        'description' => 'Our bathroom renovation service delivers complete overhauls from concept to completion. We handle flooring, wall upgrades, custom vanities, fixture installation, lighting, and ventilation — creating spa-like spaces in your home.',
        'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
        'image' => 'images/services/bathroom-renovations.jpg',
        'hero_image' => 'images/hero/bathroom-renovations.jpg',
    ],
];

// Testimonials Data
$testimonials = [
    [
        'name' => 'Sarah M.',
        'location' => 'Brampton, ON',
        'rating' => 5,
        'text' => 'Masterlay completely transformed our living room with beautiful vinyl plank flooring. The team was professional, punctual, and left our home spotless. Couldn\'t be happier with the results!',
        'project' => 'Floor Installation',
    ],
    [
        'name' => 'James K.',
        'location' => 'Mississauga, ON',
        'rating' => 5,
        'text' => 'Our staircase went from dated to stunning. The craftsmanship is incredible — every joint is perfect, and the finish is flawless. Highly recommend their custom stair work.',
        'project' => 'Custom Stairs',
    ],
    [
        'name' => 'Priya D.',
        'location' => 'Toronto, ON',
        'rating' => 5,
        'text' => 'The bathroom renovation exceeded our expectations. From the initial design consultation to the final walkthrough, every step was handled with care and professionalism.',
        'project' => 'Bathroom Renovation',
    ],
    [
        'name' => 'Michael R.',
        'location' => 'Brampton, ON',
        'rating' => 5,
        'text' => 'Had all our interior doors replaced and a complete basement renovation done. The attention to detail is outstanding — perfectly finished from framing to flooring. True craftsmen.',
        'project' => 'Door Installation & Basement Renovation',
    ],
    [
        'name' => 'Lisa T.',
        'location' => 'Oakville, ON',
        'rating' => 5,
        'text' => 'Our hardwood floors look brand new after refinishing. The team used dustless sanding which made the process so much cleaner. The stain color is exactly what we wanted.',
        'project' => 'Floor Refinishing',
    ],
    [
        'name' => 'David & Anna W.',
        'location' => 'Hamilton, ON',
        'rating' => 5,
        'text' => 'We hired Masterlay for a complete main floor renovation — flooring, stairs, and trim. The transformation is unbelievable. Professional crew, transparent pricing, and delivered on time.',
        'project' => 'Full Renovation',
    ],
];

// FAQ Data
$faqs = [
    'general' => [
        ['q' => 'What areas do you serve?', 'a' => 'We serve the Greater Toronto Area including Brampton, Mississauga, Toronto, Oakville, Hamilton, Burlington, and surrounding communities across Ontario.'],
        ['q' => 'Are you licensed and insured?', 'a' => 'Yes, Masterlay Renovations is fully licensed and insured. We carry comprehensive liability insurance and WSIB coverage for your complete peace of mind.'],
        ['q' => 'Do you offer warranties on your work?', 'a' => 'Absolutely. All our installations come with a workmanship warranty. The length varies by service — flooring installations include a 5-year warranty, and bathroom renovations include a 2-year comprehensive warranty.'],
        ['q' => 'How long has Masterlay been in business?', 'a' => 'Masterlay Renovations has been delivering premium renovation services since 2018. With over 500 completed projects, we have the experience and expertise to handle any job.'],
    ],
    'services' => [
        ['q' => 'What types of flooring do you install?', 'a' => 'We specialize in vinyl plank flooring, which offers exceptional durability, water resistance, and design flexibility. We also work with hardwood, laminate, and tile depending on project requirements.'],
        ['q' => 'How long does a bathroom renovation take?', 'a' => 'A typical bathroom renovation takes 2-4 weeks depending on the scope. Simple updates may be completed in under 2 weeks, while full gut-and-rebuild renovations may take up to 4 weeks.'],
        ['q' => 'Can you match my existing flooring or trim?', 'a' => 'Yes, we take great care to match existing materials as closely as possible. We bring samples to your home for side-by-side comparison before any work begins.'],
        ['q' => 'Do you handle permits for bathroom renovations?', 'a' => 'Yes, we manage all necessary permits for renovations that require them. We handle the paperwork and inspections so you don\'t have to worry about compliance.'],
    ],
    'pricing' => [
        ['q' => 'Do you offer free estimates?', 'a' => 'Yes! We provide free, no-obligation estimates for all projects. Contact us to schedule an in-home consultation where we\'ll assess your needs and provide a detailed quote.'],
        ['q' => 'What factors affect the cost?', 'a' => 'The main factors are the scope of work, materials selected, room size, and any preparation needed (subfloor repair, demolition, etc.). We provide transparent, itemized quotes so you know exactly what you\'re paying for.'],
        ['q' => 'Do you offer financing?', 'a' => 'Yes, we\'ve partnered with Financeit to offer flexible payment plans. You can finance your renovation with affordable monthly payments and get started on your project right away.'],
        ['q' => 'Is there a deposit required?', 'a' => 'Yes, we require a deposit to secure your project date and order materials. The deposit amount is typically 30% of the project total, with the balance due upon completion.'],
    ],
    'process' => [
        ['q' => 'What happens after I contact you?', 'a' => 'After your initial contact, we schedule a free in-home consultation within 48 hours. We\'ll discuss your vision, take measurements, and provide a detailed quote within 2-3 business days.'],
        ['q' => 'Do I need to be home during the work?', 'a' => 'While it\'s not required, we recommend being available for the start and end of each work day. Many clients provide a key or access code and go about their day while we work.'],
        ['q' => 'How do you protect my home during work?', 'a' => 'We take extensive precautions: floor coverings, wall protectors, dust barriers, and daily cleanup. We treat your home as if it were our own and leave the workspace clean at the end of each day.'],
        ['q' => 'What if I want to make changes during the project?', 'a' => 'We understand that ideas evolve. Minor changes can often be accommodated easily. For significant scope changes, we\'ll discuss the impact on timeline and cost before proceeding.'],
    ],
];

// Navigation
$navigation = [
    ['label' => 'Home', 'url' => '/', 'page' => 'index'],
    ['label' => 'About', 'url' => '/about', 'page' => 'about'],
    ['label' => 'Services', 'url' => '/services', 'page' => 'services', 'dropdown' => true],
    ['label' => 'Gallery', 'url' => '/gallery', 'page' => 'gallery'],
    ['label' => 'Financing', 'url' => '/financing', 'page' => 'financing'],
    ['label' => 'Testimonials', 'url' => '/testimonials', 'page' => 'testimonials'],
    ['label' => 'Contact', 'url' => '/contact', 'page' => 'contact'],
];

// Helper: Check if current page matches
function isActivePage($page) {
    $current = basename($_SERVER['PHP_SELF'], '.php');
    if ($current === 'index' && $page === 'index') return true;
    return $current === $page;
}

// Helper: Get base path (handles subdirectory pages)
function getBasePath() {
    $depth = substr_count(str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']), '/') - 1;
    return str_repeat('../', $depth);
}

// PHPMailer Setup
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function get_masterlay_mailer() {
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = 0;
    $mail->Debugoutput = 'error_log';
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.zohocloud.ca';
        $mail->SMTPAuth = true;
        $mail->Username = 'inquiry@masterlayrenovations.ca';
        $mail->Password = 'X72TL6xNFuTp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->setFrom('inquiry@masterlayrenovations.ca', 'Masterlay Renovations');
    } catch (Exception $e) {
        error_log('Mailer setup error: ' . $e->getMessage());
    }
    return $mail;
}
