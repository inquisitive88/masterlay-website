<?php
/**
 * CMS Admin - Seed Service Detail Sections
 * Migrates hardcoded content from the 7 static service pages into ml_cms_service_detail_sections
 * Run once from admin panel or CLI: php admin/seed-service-sections.php
 */
$isCLI = php_sapi_name() === 'cli';

if ($isCLI) {
    // In CLI mode, load DB directly without auth
    require_once __DIR__ . '/includes/admin-db.php';
    require_once __DIR__ . '/includes/admin-db-tables.php';
    require_once __DIR__ . '/includes/admin-functions.php';
    if (!defined('CMS_IMG')) define('CMS_IMG', 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images');
    // Also need IMG constant for image URLs
    if (!defined('IMG')) define('IMG', 'https://pub-579b6a2a6c454fd28b41d057a14d45f0.r2.dev/images');
} else {
    require_once __DIR__ . '/includes/admin-bootstrap.php';
}

if (!$isCLI && !is_ajax()) {
    // Render a simple UI
    $adminPageTitle = 'Seed Service Sections';
    $adminCurrentPage = 'services';
    $adminBreadcrumb = ['Services' => '/admin/services', 'Seed Page Content' => ''];
    include __DIR__ . '/includes/admin-layout-top.php';
}

// Check if sections already exist
$count = (int)$pdo->query("SELECT COUNT(*) FROM ml_cms_service_detail_sections")->fetchColumn();

if ($isCLI || ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if (!$isCLI) require_csrf();

    // Clear existing sections
    $pdo->exec("TRUNCATE TABLE ml_cms_service_detail_sections");

    // Service slug => detail sections data
    $servicesContent = [

        // ============ FLOOR INSTALLATION ============
        'floor-installation' => [
            'overview' => [
                'title' => 'Premium Vinyl Plank Flooring, Expertly Installed',
                'content' => "Vinyl plank flooring offers the perfect combination of stunning aesthetics, exceptional durability, and water resistance. Whether you are renovating a single room or transforming your entire home, our team ensures every plank is laid with precision and care.\n\nWe guide you through every step, from selecting the right material and color to preparing your subfloor and completing a flawless installation. The result is a beautiful, long-lasting floor that stands up to daily life.",
            ],
            'features' => [
                'title' => "What's Included",
                'items' => [
                    'Material selection guidance tailored to your lifestyle and budget',
                    'Subfloor assessment and preparation',
                    'Professional installation by experienced craftsmen',
                    'Transitions and trim finishing for a seamless look',
                    'Post-installation cleanup and final walkthrough',
                ],
            ],
            'process' => [
                ['title' => 'Assessment', 'content' => 'We visit your home, evaluate the existing subfloor condition, take precise measurements, and discuss your design preferences.'],
                ['title' => 'Material Selection', 'content' => 'We bring samples to your home so you can see colors and textures in your own lighting. We help you choose the perfect vinyl plank product.'],
                ['title' => 'Subfloor Prep', 'content' => 'We level, clean, and prepare the subfloor to manufacturer specifications, ensuring a stable foundation for your new flooring.'],
                ['title' => 'Installation', 'content' => 'Every plank is carefully placed with precision cuts, proper expansion gaps, and expert transitions. We finish with a detailed walkthrough.'],
            ],
            'gallery' => [
                'title' => 'Floor Installation Gallery',
                'subtitle' => 'See the quality and craftsmanship we bring to every flooring project.',
                'images' => [
                    ['src' => IMG . '/general/vinyl-plank-living.jpg', 'alt' => 'Vinyl plank flooring in living room'],
                    ['src' => IMG . '/general/modern-floor-detail.jpg', 'alt' => 'Modern floor installation detail'],
                    ['src' => IMG . '/general/completed-floor.jpg', 'alt' => 'Completed floor installation project'],
                ],
            ],
            'faq' => [
                'title' => 'Common Questions About Floor Installation',
                'items' => [
                    ['q' => 'How durable is vinyl plank flooring?', 'a' => 'Vinyl plank flooring is extremely durable and designed for high-traffic areas. It resists scratches, dents, and moisture, making it an ideal choice for kitchens, bathrooms, basements, and living areas. Most quality vinyl plank comes with a wear layer rated for 15-25 years of residential use.'],
                    ['q' => 'How long does a typical floor installation take?', 'a' => 'Most single-room installations are completed in one day. Whole-home installations typically take 2-4 days depending on square footage, the amount of subfloor preparation needed, and the complexity of the layout. We provide a detailed timeline before work begins.'],
                    ['q' => 'Do I need to move my furniture before installation?', 'a' => 'We recommend clearing the rooms before our crew arrives. For larger or heavier items, we can assist with furniture moving as part of the project. Just let us know during the consultation so we can plan accordingly.'],
                    ['q' => 'Can vinyl plank be installed over existing flooring?', 'a' => 'In many cases, yes. Vinyl plank can often be installed over existing hard surfaces like tile, hardwood, or concrete, as long as the subfloor is level and in good condition. We assess the existing floor during our consultation to determine the best approach.'],
                ],
            ],
        ],

        // ============ FLOOR REFINISHING ============
        'floor-refinishing' => [
            'overview' => [
                'title' => 'Breathe New Life Into Your Hardwood Floors',
                'content' => "Years of foot traffic, furniture scratches, and sun exposure can leave hardwood floors looking tired and worn. Our refinishing service restores them to their original beauty, or transforms them with a completely new look through custom staining.\n\nUsing advanced dustless sanding technology, we minimize mess and disruption to your household. Our multi-coat finishing process delivers a surface that is not only visually stunning but also protected against daily wear for years to come.",
            ],
            'features' => [
                'title' => "What's Included",
                'items' => [
                    'Thorough floor assessment and condition report',
                    'Dustless sanding with advanced containment systems',
                    'Custom stain color matching to your design vision',
                    'Multiple coats of premium polyurethane for lasting protection',
                    'Final buffing, inspection, and walkthrough',
                ],
            ],
            'process' => [
                ['title' => 'Inspection', 'content' => 'We evaluate your floors for structural integrity, damage, and finish condition. We identify any repairs needed before sanding begins.'],
                ['title' => 'Sanding', 'content' => 'Using dustless sanding equipment, we strip away old finish, scratches, and imperfections to reveal fresh, clean wood beneath.'],
                ['title' => 'Staining', 'content' => 'We apply your chosen stain color evenly across the entire floor. We test colors on-site first to ensure it matches your vision perfectly.'],
                ['title' => 'Sealing', 'content' => 'Multiple coats of high-grade polyurethane are applied, sanded between coats, and finished with a final buff for a flawless, protective surface.'],
            ],
            'gallery' => [
                'title' => 'Floor Refinishing Gallery',
                'subtitle' => 'See the transformation our refinishing service delivers.',
                'images' => [
                    ['src' => IMG . '/general/refinished-hardwood.jpg', 'alt' => 'Refinished hardwood floor'],
                    ['src' => IMG . '/general/stained-hardwood.jpg', 'alt' => 'Stained hardwood floor detail'],
                    ['src' => IMG . '/general/polished-hardwood.jpg', 'alt' => 'Polished hardwood flooring'],
                ],
            ],
            'faq' => [
                'title' => 'Common Questions About Floor Refinishing',
                'items' => [
                    ['q' => 'What is dustless sanding and how does it work?', 'a' => 'Dustless sanding uses specialized equipment with built-in vacuum systems that capture up to 99% of the dust generated during the sanding process. This means far less mess in your home, better air quality, and a cleaner result overall. It is especially beneficial for families with allergies or respiratory sensitivities.'],
                    ['q' => 'How long does the finish take to fully cure?', 'a' => 'Water-based polyurethane is dry to the touch within 2-4 hours between coats. After the final coat, light foot traffic is typically safe within 24 hours, but we recommend waiting 3-5 days before placing furniture and 7 days before placing area rugs to allow the finish to fully harden.'],
                    ['q' => 'How many coats of polyurethane do you apply?', 'a' => 'We apply a minimum of three coats of polyurethane for optimal protection and appearance. Between each coat, we lightly sand the surface to ensure perfect adhesion. For high-traffic areas, we may recommend an additional coat for extra durability.'],
                    ['q' => 'Can you change the color of my existing hardwood floors?', 'a' => 'Absolutely. Once the old finish is sanded away, we can apply any stain color you choose, from light natural tones to rich dark espressos. We always test the stain on an inconspicuous area first so you can approve the color before we proceed with the full floor.'],
                ],
            ],
        ],

        // ============ FLOOR REPAIR ============
        'floor-repair' => [
            'overview' => [
                'title' => 'Expert Solutions for Damaged Floors',
                'content' => "Whether it is water damage, deep scratches, cracked tiles, or squeaky boards, our floor repair service addresses every issue with precision and care. We go beyond surface fixes to diagnose the root cause of the problem and deliver lasting solutions.\n\nOur team carefully matches existing materials, textures, and colors so that repairs blend seamlessly with the rest of your floor. The goal is always a repair that is invisible to the eye and built to last.",
            ],
            'features' => [
                'title' => "What's Included",
                'items' => [
                    'Comprehensive damage assessment and diagnosis',
                    'Material matching for seamless blending with existing flooring',
                    'Structural repair of subfloor and support systems',
                    'Surface restoration including sanding and refinishing',
                    'Preventive recommendations to avoid future damage',
                ],
            ],
            'process' => [
                ['title' => 'Diagnosis', 'content' => 'We thoroughly inspect the damaged area to understand the full extent of the issue, including any hidden subfloor or moisture problems beneath the surface.'],
                ['title' => 'Material Sourcing', 'content' => 'We locate and source matching materials, whether hardwood planks, vinyl, tile, or specialty products, to ensure a seamless repair that blends perfectly.'],
                ['title' => 'Repair', 'content' => 'Our team performs the repair with expert precision, whether it involves replacing boards, patching subfloor, fixing squeaks, or restoring the surface finish.'],
                ['title' => 'Quality Check', 'content' => 'We conduct a final inspection to verify the repair is structurally sound, visually seamless, and meets our rigorous quality standards before handover.'],
            ],
            'gallery' => [
                'title' => 'Floor Repair Gallery',
                'subtitle' => 'See how we restore damaged floors to perfection.',
                'images' => [
                    ['src' => IMG . '/gallery/floor-repair.jpg', 'alt' => 'Repaired hardwood floor'],
                    ['src' => IMG . '/general/floor-damage-restore.jpg', 'alt' => 'Floor damage restoration'],
                    ['src' => IMG . '/general/completed-repair.jpg', 'alt' => 'Completed floor repair'],
                ],
            ],
            'faq' => [
                'title' => 'Common Questions About Floor Repair',
                'items' => [
                    ['q' => 'What types of floor damage can you fix?', 'a' => 'We handle a wide range of floor issues including water damage, warping, buckling, deep scratches, cracked or chipped tiles, squeaky boards, loose planks, gaps between boards, and subfloor deterioration. If the damage is isolated, we can often repair the affected section without replacing the entire floor.'],
                    ['q' => 'Can you match my existing flooring for a seamless repair?', 'a' => 'Yes, matching existing flooring is one of our specialties. We source identical or closely matching materials and use staining and finishing techniques to blend the repaired section with the surrounding floor. We bring samples to your home for comparison before proceeding.'],
                    ['q' => 'When should I repair versus replace my floors?', 'a' => 'If the damage is localized to a specific area and the rest of the floor is in good condition, repair is usually the more cost-effective choice. If damage is widespread, or if the flooring is very old and worn throughout, replacement may be the better long-term investment. We provide honest recommendations during our assessment.'],
                    ['q' => 'How long does a typical floor repair take?', 'a' => 'Most repairs are completed within one to two days. Simple fixes like eliminating squeaks or replacing a few boards can be done in a few hours. More extensive water damage or subfloor repairs may take longer. We provide a clear timeline after our initial assessment.'],
                ],
            ],
        ],

        // ============ CUSTOM STAIRS ============
        'custom-stairs' => [
            'overview' => [
                'title' => 'Staircases That Make a Statement',
                'content' => "Your staircase is one of the most prominent architectural features in your home. Whether you envision a grand traditional hardwood staircase, sleek modern floating treads, or a contemporary mix of metal and glass, we design and build each system with meticulous precision.\n\nEvery project begins with a detailed design consultation where we understand your vision, lifestyle, and home architecture. From material selection to final finishing, we handle every detail to deliver a staircase that becomes the centerpiece of your home.",
            ],
            'features' => [
                'title' => "What's Included",
                'items' => [
                    'Comprehensive design consultation and 3D visualization',
                    'Material selection including hardwood, metal, and glass options',
                    'Custom fabrication of treads, risers, and stringers',
                    'Professional installation with structural reinforcement',
                    'Railing systems including wood, metal, cable, and glass',
                ],
            ],
            'process' => [
                ['title' => 'Design', 'content' => 'We collaborate on style, materials, and layout. We take precise measurements and present design options that complement your home\'s architecture and your personal taste.'],
                ['title' => 'Fabrication', 'content' => 'Each component is custom-built to exact specifications. Treads, risers, stringers, and railing parts are crafted with precision to ensure a perfect fit during installation.'],
                ['title' => 'Installation', 'content' => 'Our experienced crew handles structural preparation, assembly, and secure mounting. Every joint is tight, every tread is level, and every railing is solid and safe.'],
                ['title' => 'Finishing', 'content' => 'We apply stains, sealants, and protective finishes to bring out the natural beauty of the materials. A final walkthrough ensures every detail meets our exacting standards.'],
            ],
            'gallery' => [
                'title' => 'Custom Stairs Gallery',
                'subtitle' => 'Explore our portfolio of custom staircase designs.',
                'images' => [
                    ['src' => IMG . '/general/modern-staircase.jpg', 'alt' => 'Modern custom staircase'],
                    ['src' => IMG . '/general/wood-metal-stairs.jpg', 'alt' => 'Wood and metal staircase'],
                    ['src' => IMG . '/general/traditional-hardwood-stairs.jpg', 'alt' => 'Traditional hardwood staircase'],
                ],
            ],
            'faq' => [
                'title' => 'Common Questions About Custom Stairs',
                'items' => [
                    ['q' => 'What staircase styles do you offer?', 'a' => 'We build a full range of staircase styles including traditional closed-riser designs, open-riser contemporary stairs, floating staircases, L-shaped and U-shaped configurations, and spiral staircases. We work with hardwood, metal, glass, and cable railing systems to achieve the exact look you want.'],
                    ['q' => 'Do custom stairs need to meet building code requirements?', 'a' => 'Yes, all staircases must comply with Ontario Building Code requirements for safety. This includes specifications for tread depth, riser height, railing height, and baluster spacing. We design every staircase to meet or exceed these codes, and we coordinate any required inspections.'],
                    ['q' => 'How long does a custom staircase project take?', 'a' => 'A typical custom staircase project takes 3-6 weeks from design approval to completion. This includes 1-2 weeks for design finalization, 1-2 weeks for fabrication and material sourcing, and 1-2 weeks for installation and finishing. Complex designs with specialty materials may require additional time.'],
                    ['q' => 'Can you renovate my existing staircase instead of building a new one?', 'a' => 'Absolutely. If your existing staircase is structurally sound, we can transform it with new treads, risers, railings, and finishes. This is often a cost-effective way to dramatically update the look of your stairs without a full rebuild. We assess the structure first to determine the best approach.'],
                ],
            ],
        ],

        // ============ DOOR INSTALLATION ============
        'door-installation' => [
            'overview' => [
                'title' => 'Doors That Define Your Home',
                'content' => "From a grand front entry that sets the tone for your entire home to interior doors that complete your design vision, every door installation we handle is executed with precision and care. We work with all door types including front entry doors, patio doors, French doors, barn doors, and interior passage doors.\n\nProper door installation goes beyond aesthetics. It affects energy efficiency, security, sound insulation, and the overall feel of your home. Our team ensures every door is perfectly plumb, level, and sealed for optimal performance.",
            ],
            'features' => [
                'title' => "What's Included",
                'items' => [
                    'Door selection guidance for style, material, and function',
                    'Precise measurements to ensure a perfect fit',
                    'Frame preparation, shimming, and leveling',
                    'Hardware installation including handles, locks, and hinges',
                    'Weather sealing and insulation for exterior doors',
                ],
            ],
            'process' => [
                ['title' => 'Consultation', 'content' => 'We discuss your needs, style preferences, and functional requirements. We help you choose the right door type, material, and hardware for each opening.'],
                ['title' => 'Measurement', 'content' => 'We take precise measurements of each opening, checking for square, plumb, and level. This ensures every door is ordered to the exact dimensions needed.'],
                ['title' => 'Installation', 'content' => 'Old doors are carefully removed, frames are prepared and shimmed to perfection, and new doors are hung with expert precision for smooth, gap-free operation.'],
                ['title' => 'Hardware & Finishing', 'content' => 'We install all hardware, apply weather stripping to exterior doors, add casing trim, and ensure everything operates perfectly. A final walkthrough confirms your satisfaction.'],
            ],
            'gallery' => [
                'title' => 'Door Installation Gallery',
                'subtitle' => 'See our door installations across the Greater Toronto Area.',
                'images' => [
                    ['src' => IMG . '/general/front-entry-door.jpg', 'alt' => 'Front entry door installation'],
                    ['src' => IMG . '/general/interior-door-hardware.jpg', 'alt' => 'Interior door with modern hardware'],
                    ['src' => IMG . '/general/french-door-install.jpg', 'alt' => 'French door installation'],
                ],
            ],
            'faq' => [
                'title' => 'Common Questions About Door Installation',
                'items' => [
                    ['q' => 'What types of doors do you install?', 'a' => 'We install all types of residential doors including front entry doors, interior passage and privacy doors, patio sliding doors, French doors, barn doors, bi-fold closet doors, and custom decorative doors. We work with wood, fiberglass, steel, and glass materials depending on the application.'],
                    ['q' => 'How do new doors improve home security?', 'a' => 'A properly installed exterior door with a quality deadbolt and reinforced strike plate significantly improves your home security. We install doors with multi-point locking systems, solid cores, and reinforced frames. We can also recommend smart lock options for added convenience and security.'],
                    ['q' => 'Will new doors help with energy efficiency?', 'a' => 'Absolutely. Old or poorly sealed doors are one of the biggest sources of energy loss in a home. We install energy-efficient doors with proper insulation cores, weather stripping, and threshold seals that dramatically reduce drafts and lower your heating and cooling costs.'],
                    ['q' => 'How long does it take to install a door?', 'a' => 'A single interior door replacement typically takes 1-2 hours. Exterior door installations take 3-5 hours depending on the type and whether frame modifications are needed. For whole-home door replacements, we can typically complete 4-6 interior doors per day.'],
                ],
            ],
        ],

        // ============ BATHROOM RENOVATIONS ============
        'bathroom-renovations' => [
            'overview' => [
                'title' => 'Complete Bathroom Transformations From Concept to Completion',
                'content' => "A bathroom renovation is one of the most impactful upgrades you can make to your home. It improves daily comfort, increases property value, and creates a personal sanctuary in your own home. At Masterlay, we deliver complete bathroom overhauls that combine thoughtful design with expert construction.\n\nFrom the initial design consultation through demolition, construction, tiling, plumbing, and final finishing, we manage every detail of the process. Our goal is to create a space that feels like a private spa, built with the quality materials and precision craftsmanship that define every Masterlay project.\n\nWhether you are updating a powder room, renovating a family bathroom, or creating a luxury ensuite, we bring the same level of dedication and attention to every project, regardless of size.",
            ],
            'features' => [
                'title' => "What's Included",
                'items' => [
                    'Complete demolition and disposal of existing fixtures and finishes',
                    'Flooring and wall tiling with premium tile selection',
                    'Custom vanity and cabinetry design and installation',
                    'Fixture upgrades including toilets, faucets, shower heads, and tubs',
                    'Modern lighting design and ventilation upgrades',
                    'Plumbing coordination and code-compliant rough-in work',
                ],
            ],
            'process' => [
                ['title' => 'Design Consultation', 'content' => 'We meet at your home to understand your vision, assess the existing space, and discuss layout options, material preferences, fixtures, and budget. We present a detailed proposal with a clear timeline and transparent pricing.'],
                ['title' => 'Demolition & Prep', 'content' => 'We carefully demolish and dispose of all existing fixtures, tile, and finishes. We inspect and address any underlying issues like water damage, mold, or structural concerns. Plumbing and electrical rough-in work is completed.'],
                ['title' => 'Construction & Tiling', 'content' => 'Waterproofing membranes are applied, followed by precision tile installation on floors and walls. The vanity, cabinetry, and shower enclosures are installed. Every surface is finished with expert care.'],
                ['title' => 'Fixtures & Finishing', 'content' => 'All fixtures are connected and tested, lighting and ventilation are installed, mirrors and accessories are mounted, and a thorough cleaning is performed. We do a final walkthrough to ensure every detail is perfect.'],
            ],
            'gallery' => [
                'title' => 'Bathroom Renovations Gallery',
                'subtitle' => 'Browse our recent bathroom transformations.',
                'images' => [
                    ['src' => IMG . '/general/freestanding-tub.jpg', 'alt' => 'Modern bathroom with freestanding tub'],
                    ['src' => IMG . '/general/walk-in-shower.jpg', 'alt' => 'Walk-in shower with tile work'],
                    ['src' => IMG . '/general/custom-vanity.jpg', 'alt' => 'Custom vanity and mirror'],
                ],
            ],
            'faq' => [
                'title' => 'Common Questions About Bathroom Renovations',
                'items' => [
                    ['q' => 'How long does a bathroom renovation typically take?', 'a' => 'A standard bathroom renovation takes 2-4 weeks depending on the scope. A simple cosmetic update with new fixtures and paint can be completed in under 2 weeks. A full gut renovation involving layout changes, new plumbing, and custom tile work typically takes 3-4 weeks. We provide a detailed schedule at the start of every project so you know exactly what to expect.'],
                    ['q' => 'Do I need permits for a bathroom renovation?', 'a' => 'Permits are typically required when the renovation involves plumbing changes, electrical work, or structural modifications. Simple cosmetic updates like replacing fixtures and re-tiling usually do not require permits. We handle all permit applications and coordinate inspections on your behalf so you do not have to deal with the bureaucracy.'],
                    ['q' => 'How do I use the bathroom during the renovation?', 'a' => 'If you have a second bathroom in your home, we recommend using that during the renovation. For homes with a single bathroom, we prioritize scheduling to minimize downtime and can often maintain basic functionality during certain phases. We discuss logistics during the planning stage so there are no surprises.'],
                    ['q' => 'What does a bathroom renovation cost?', 'a' => 'Costs vary widely based on the scope, size, and material selections. A basic cosmetic refresh may start in the range of several thousand dollars, while a full custom renovation with premium materials can be a significant investment. We provide detailed, transparent quotes during the consultation so you understand exactly what is included. We also offer financing through our Financeit partnership for flexible monthly payments.'],
                ],
            ],
        ],

        // ============ BASEMENT RENOVATION ============
        'basement-renovation' => [
            'overview' => [
                'title' => 'Unlock Your Home\'s Full Potential',
                'content' => "Your basement is one of the largest untapped spaces in your home. Whether it's completely unfinished or just outdated, a professional basement renovation can add hundreds of square feet of functional living space \xe2\x80\x94 increasing both your comfort and your property value.\n\nAt Masterlay, we handle every aspect of the basement renovation from start to finish. Our team manages framing, insulation, electrical coordination, plumbing coordination, drywall, flooring, trim, lighting, and final finishes. Whether you envision a home theatre, a guest suite, a home office, a rec room, or a rental unit, we bring your vision to life with precision craftsmanship.",
            ],
            'features' => [
                'title' => "What's Included",
                'items' => [
                    'Full framing, insulation, and vapour barrier installation',
                    'Drywall installation, taping, and finishing',
                    'Flooring installation (vinyl plank, tile, laminate, or carpet)',
                    'Pot light and recessed lighting design and installation',
                    'Basement bathroom and kitchenette rough-ins and builds',
                    'Trim, doors, paint, and final finishing touches',
                ],
            ],
            'process' => [
                ['title' => 'Design & Planning', 'content' => 'We visit your home to assess the basement space, discuss your goals, and create a custom layout. We handle permit applications and provide a detailed proposal with a clear timeline and transparent pricing.'],
                ['title' => 'Framing & Rough-In', 'content' => 'The structural framework goes up, including partition walls, bulkheads, and doorways. Electrical wiring, plumbing rough-ins, and HVAC ducting are completed and inspected before we close the walls.'],
                ['title' => 'Insulation & Drywall', 'content' => 'Insulation and vapour barriers are installed for comfort and moisture control. Drywall is hung, taped, mudded, and sanded smooth. Ceilings are finished with drywall or drop ceiling depending on your preference.'],
                ['title' => 'Finishing & Handover', 'content' => 'Flooring is installed, trim and doors go in, painting is completed, and fixtures are connected. We do a thorough cleanup and a final walkthrough to ensure every detail meets your expectations.'],
            ],
            'gallery' => [
                'title' => 'Basement Renovation Gallery',
                'subtitle' => 'See our basement transformations.',
                'images' => [
                    ['src' => IMG . '/general/finished-basement.jpg', 'alt' => 'Finished basement living room'],
                    ['src' => IMG . '/general/modern-basement-living.jpg', 'alt' => 'Basement home theatre'],
                    ['src' => IMG . '/general/vinyl-plank-living.jpg', 'alt' => 'Modern basement bar area'],
                ],
            ],
            'faq' => [
                'title' => 'Common Questions About Basement Renovation',
                'items' => [
                    ['q' => 'How long does a basement renovation take?', 'a' => 'A typical basement renovation takes 4-8 weeks depending on the size and complexity. A basic open-concept finish may take 4-5 weeks, while a full build with a bathroom, kitchenette, and multiple rooms can take 6-8 weeks. We provide a detailed schedule during the planning phase so you know exactly what to expect.'],
                    ['q' => 'Do I need a permit for a basement renovation?', 'a' => 'Yes, most basement renovations in Ontario require a building permit, especially if you are adding bedrooms, bathrooms, a kitchen, or making structural changes. We handle all permit applications, inspections, and code compliance on your behalf so you do not have to worry about the process.'],
                    ['q' => 'What about moisture and waterproofing?', 'a' => 'Moisture control is a critical part of every basement renovation. We assess your basement for signs of water intrusion before starting. Our builds include proper vapour barriers, insulation rated for below-grade applications, and drainage solutions where needed. If significant waterproofing is required, we coordinate with specialists before finishing the space.'],
                    ['q' => 'How much does a basement renovation cost?', 'a' => 'Costs depend on the size of the space, the scope of work, and your material selections. A basic open-concept finish is a different investment than a full build with a bathroom, bedroom, and kitchenette. We provide detailed, transparent quotes during the consultation so you understand exactly what is included. We also offer financing through our Financeit partnership for flexible monthly payments.'],
                ],
            ],
        ],
    ];

    $inserted = 0;
    $sectionStmt = $pdo->prepare("INSERT INTO ml_cms_service_detail_sections (service_id, section_type, title, subtitle, content, sort_order) VALUES (?, ?, ?, ?, ?, ?)");
    $serviceIdStmt = $pdo->prepare("SELECT id FROM ml_cms_services WHERE slug = ? LIMIT 1");

    foreach ($servicesContent as $slug => $sections) {
        $serviceIdStmt->execute([$slug]);
        $row = $serviceIdStmt->fetch();
        if (!$row) {
            $msg = "Service '{$slug}' not found in database. Skipping.";
            if ($isCLI) echo "  SKIP: {$msg}\n";
            continue;
        }
        $serviceId = (int)$row['id'];
        $sortOrder = 0;

        // Overview
        if (!empty($sections['overview'])) {
            $sectionStmt->execute([$serviceId, 'overview', $sections['overview']['title'], null, $sections['overview']['content'], $sortOrder++]);
            $inserted++;
        }

        // Features
        if (!empty($sections['features'])) {
            $sectionStmt->execute([$serviceId, 'features', $sections['features']['title'], null, json_encode($sections['features']['items']), $sortOrder++]);
            $inserted++;
        }

        // Process steps
        if (!empty($sections['process'])) {
            foreach ($sections['process'] as $step) {
                $sectionStmt->execute([$serviceId, 'process', $step['title'], null, $step['content'], $sortOrder++]);
                $inserted++;
            }
        }

        // Gallery
        if (!empty($sections['gallery'])) {
            $sectionStmt->execute([$serviceId, 'gallery', $sections['gallery']['title'], $sections['gallery']['subtitle'] ?? null, json_encode($sections['gallery']['images']), $sortOrder++]);
            $inserted++;
        }

        // FAQ
        if (!empty($sections['faq'])) {
            $sectionStmt->execute([$serviceId, 'faq', $sections['faq']['title'], null, json_encode($sections['faq']['items']), $sortOrder++]);
            $inserted++;
        }

        $msg = "Seeded sections for '{$slug}'";
        if ($isCLI) echo "  OK: {$msg}\n";
    }

    $totalMsg = "Successfully seeded {$inserted} service detail sections for " . count($servicesContent) . " services.";

    if ($isCLI) {
        echo "\n{$totalMsg}\n";
        exit(0);
    }

    if (is_ajax()) {
        json_response(['success' => true, 'message' => $totalMsg, 'inserted' => $inserted]);
    }

    set_flash('success', $totalMsg);
    redirect('/admin/services');
}

// Render UI
if (!$isCLI):
?>

<div class="admin-card max-w-2xl">
    <h1 class="font-heading text-2xl font-bold text-white mb-4">Seed Service Page Content</h1>
    <p class="text-white/50 mb-4">
        This will populate the <code class="text-primary text-xs">ml_cms_service_detail_sections</code> table with the hardcoded content from the existing 7 service pages (overview, features, process, gallery, FAQs).
    </p>

    <?php if ($count > 0): ?>
        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-4 mb-6">
            <p class="text-yellow-400 text-sm">
                <strong>Warning:</strong> There are already <?= $count ?> section records in the database. Running this seed will <strong>replace all existing data</strong> in the service detail sections table.
            </p>
        </div>
    <?php endif; ?>

    <form method="POST">
        <?= csrf_field() ?>
        <button type="submit" class="admin-btn admin-btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            <?= $count > 0 ? 'Replace & Seed Service Sections' : 'Seed Service Sections' ?>
        </button>
    </form>
</div>

<?php
include __DIR__ . '/includes/admin-layout-bottom.php';
endif;
?>
