<?php
require_once 'includes/content-loader.php';
$pageTitle = 'Get Instant Quote | ' . SITE_NAME;
$pageDescription = 'Get an instant quote for your stair or flooring renovation project. Choose your options and receive a detailed estimate by email.';
$currentPage = 'quote';
$heroTitle = 'Get Instant Quote';
$heroSubtitle = 'Select your options and receive a detailed estimate by email';
$heroBg = IMG . '/hero/custom-stairs.jpg';
$breadcrumbs = ['Home' => '/', 'Get Instant Quote' => ''];
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'includes/head.php'; ?>
<body class="bg-dark text-white font-body overflow-x-hidden loading">
<?php include 'includes/loader.php'; ?>
<?php include 'includes/header.php'; ?>

<main>
    <!-- ============ PAGE HERO ============ -->
    <?php include 'includes/page-hero.php'; ?>

    <!-- ============ QUOTE FORM ============ -->
    <section class="section-padding bg-dark">
        <div class="container-narrow">

            <form id="quoteForm" novalidate>
                <!-- Step 1: Category -->
                <div class="mb-10" data-animate="fade-up">
                    <h2 class="font-heading text-xl font-bold mb-1">Step 1 <span class="text-white/40 font-normal text-base">— Select Category</span></h2>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <button type="button" id="catStairs" data-category="stairs" class="quote-card quote-card--active">
                            <svg class="w-8 h-8 mb-2 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                            <span class="font-heading font-bold">Stairs</span>
                        </button>
                        <button type="button" id="catFloors" data-category="floors" class="quote-card">
                            <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                            <span class="font-heading font-bold">Floors</span>
                        </button>
                    </div>
                </div>

                <!-- ==================== STAIRS FLOW ==================== -->

                <!-- Step 2: Stairs Service Type -->
                <div id="stepService" class="mb-10" data-animate="fade-up">
                    <h2 class="font-heading text-xl font-bold mb-1">Step 2 <span class="text-white/40 font-normal text-base">— Select Service Type</span></h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                        <button type="button" data-service="sanding" class="quote-card service-card">
                            <span class="font-heading font-bold text-lg">Sanding & Re-Staining</span>
                            <span class="text-white/50 text-sm mt-1">Restore your existing stairs with fresh sanding and staining</span>
                        </button>
                        <button type="button" data-service="recapping" class="quote-card service-card">
                            <span class="font-heading font-bold text-lg">Recapping</span>
                            <span class="text-white/50 text-sm mt-1">Install new hardwood caps over your existing stairs</span>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Sanding & Re-Staining Options -->
                <div id="sandingOptions" class="mb-10" hidden>
                    <h2 class="font-heading text-xl font-bold mb-1">Step 3 <span class="text-white/40 font-normal text-base">— Configure Your Project</span></h2>
                    <div class="space-y-6 mt-6">
                        <!-- Staircases -->
                        <div class="form-group">
                            <label for="sanding_staircases" class="form-label">Number of Staircases <button type="button" class="quote-info-btn" data-info="staircases"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <input type="number" id="sanding_staircases" name="sanding_staircases" class="form-input" value="1" min="1" max="20">
                        </div>

                        <!-- Railing & Posts Toggle -->
                        <div>
                            <label class="form-label">Do you have railing and posts? <button type="button" class="quote-info-btn" data-info="has_railing"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <div class="grid grid-cols-2 gap-3 mt-2">
                                <button type="button" data-toggle="sanding_has_railing" data-value="yes" class="quote-toggle">Yes</button>
                                <button type="button" data-toggle="sanding_has_railing" data-value="no" class="quote-toggle quote-toggle--active">No</button>
                            </div>
                            <input type="hidden" name="sanding_has_railing" id="sanding_has_railing" value="no">
                        </div>

                        <!-- Railing/Posts sub-options -->
                        <div id="sandingRailingDetails" class="space-y-6" hidden>
                            <div class="form-group">
                                <label for="sanding_posts_qty" class="form-label">
                                    Number of Posts
                                    <button type="button" class="quote-info-btn" data-info="posts"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                </label>
                                <input type="number" id="sanding_posts_qty" name="sanding_posts_qty" class="form-input" value="0" min="0" max="50">
                            </div>
                            <div>
                                <label class="form-label">
                                    Railing present?
                                    <button type="button" class="quote-info-btn" data-info="railing"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                </label>
                                <div class="grid grid-cols-2 gap-3 mt-2">
                                    <button type="button" data-toggle="sanding_railing_present" data-value="yes" class="quote-toggle">Yes</button>
                                    <button type="button" data-toggle="sanding_railing_present" data-value="no" class="quote-toggle quote-toggle--active">No</button>
                                </div>
                                <input type="hidden" name="sanding_railing_present" id="sanding_railing_present" value="no">
                            </div>
                            <div>
                                <label class="form-label">
                                    Spindle Type
                                    <button type="button" class="quote-info-btn" data-info="spindles"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                </label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-2">
                                    <button type="button" data-radio="sanding_spindle_type" data-value="replace_metal" class="quote-radio">Replace with Metal</button>
                                    <button type="button" data-radio="sanding_spindle_type" data-value="paint_white" class="quote-radio">Paint White</button>
                                    <button type="button" data-radio="sanding_spindle_type" data-value="keep_metal" class="quote-radio">Keep Existing Metal</button>
                                </div>
                                <input type="hidden" name="sanding_spindle_type" id="sanding_spindle_type" value="">
                            </div>
                            <div id="sandingSpindlesQty" class="form-group" hidden>
                                <label for="sanding_spindles_qty" class="form-label">Number of Spindles <button type="button" class="quote-info-btn" data-info="spindles_qty"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                                <input type="number" id="sanding_spindles_qty" name="sanding_spindles_qty" class="form-input" value="0" min="0" max="200">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Recapping Options -->
                <div id="recappingOptions" class="mb-10" hidden>
                    <h2 class="font-heading text-xl font-bold mb-1">Step 3 <span class="text-white/40 font-normal text-base">— Configure Your Project</span></h2>
                    <div class="space-y-6 mt-6">
                        <div class="form-group">
                            <label for="recap_box_steps" class="form-label">
                                Number of Box Steps
                                <button type="button" class="quote-info-btn" data-info="box_step"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                            </label>
                            <input type="number" id="recap_box_steps" name="recap_box_steps" class="form-input" value="0" min="0" max="50">
                        </div>
                        <div class="form-group">
                            <label for="recap_open_steps" class="form-label">
                                Number of Open Steps (left/right)
                                <button type="button" class="quote-info-btn" data-info="open_step"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                            </label>
                            <input type="number" id="recap_open_steps" name="recap_open_steps" class="form-input" value="0" min="0" max="50">
                        </div>
                        <div>
                            <label class="form-label">
                                Spindle Type
                                <button type="button" class="quote-info-btn" data-info="spindles"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                                <button type="button" data-radio="recap_spindle_type" data-value="new_metal" class="quote-radio">New Plain Metal</button>
                                <button type="button" data-radio="recap_spindle_type" data-value="reuse_wood" class="quote-radio">Reuse Wood, Paint White</button>
                                <button type="button" data-radio="recap_spindle_type" data-value="reuse_metal" class="quote-radio">Reuse Metal</button>
                                <button type="button" data-radio="recap_spindle_type" data-value="none" class="quote-radio">None</button>
                            </div>
                            <input type="hidden" name="recap_spindle_type" id="recap_spindle_type" value="">
                        </div>
                        <div id="recapSpindlesQty" class="form-group" hidden>
                            <label for="recap_spindles_qty" class="form-label">Number of Spindles <button type="button" class="quote-info-btn" data-info="spindles_qty"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <input type="number" id="recap_spindles_qty" name="recap_spindles_qty" class="form-input" value="0" min="0" max="200">
                        </div>
                        <div>
                            <label class="form-label">
                                New Railing?
                                <button type="button" class="quote-info-btn" data-info="railing"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                            </label>
                            <div class="grid grid-cols-2 gap-3 mt-2">
                                <button type="button" data-toggle="recap_new_railing" data-value="yes" class="quote-toggle">Yes</button>
                                <button type="button" data-toggle="recap_new_railing" data-value="no" class="quote-toggle quote-toggle--active">No</button>
                            </div>
                            <input type="hidden" name="recap_new_railing" id="recap_new_railing" value="no">
                        </div>
                        <div id="recapRailingLf" class="form-group" hidden>
                            <label for="recap_railing_lf" class="form-label">Railing Linear Feet <button type="button" class="quote-info-btn" data-info="railing_lf"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <input type="number" id="recap_railing_lf" name="recap_railing_lf" class="form-input" value="0" min="0" max="500">
                        </div>
                        <div>
                            <label class="form-label">Staining? <button type="button" class="quote-info-btn" data-info="staining"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <div class="grid grid-cols-2 gap-3 mt-2">
                                <button type="button" data-toggle="recap_staining" data-value="yes" class="quote-toggle">Yes</button>
                                <button type="button" data-toggle="recap_staining" data-value="no" class="quote-toggle quote-toggle--active">No</button>
                            </div>
                            <input type="hidden" name="recap_staining" id="recap_staining" value="no">
                        </div>
                        <div id="recapStainingQty" class="form-group" hidden>
                            <label for="recap_staining_staircases" class="form-label">Number of Staircases to Stain <button type="button" class="quote-info-btn" data-info="staining_staircases"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <input type="number" id="recap_staining_staircases" name="recap_staining_staircases" class="form-input" value="1" min="1" max="20">
                        </div>
                        <div>
                            <label class="form-label">
                                Posts
                                <button type="button" class="quote-info-btn" data-info="posts"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-2">
                                <button type="button" data-radio="recap_posts_type" data-value="existing" class="quote-radio">Existing to Stain</button>
                                <button type="button" data-radio="recap_posts_type" data-value="new" class="quote-radio">New Posts</button>
                            </div>
                            <input type="hidden" name="recap_posts_type" id="recap_posts_type" value="">
                        </div>
                        <div id="recapPostsQty" class="form-group" hidden>
                            <label for="recap_posts_qty" class="form-label">Number of Posts <button type="button" class="quote-info-btn" data-info="posts"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <input type="number" id="recap_posts_qty" name="recap_posts_qty" class="form-input" value="0" min="0" max="50">
                        </div>
                    </div>
                </div>

                <!-- ==================== FLOORS FLOW ==================== -->

                <!-- Step 2: Floor Configuration -->
                <div id="stepFloorConfig" class="mb-10" hidden>
                    <h2 class="font-heading text-xl font-bold mb-1">Step 2 <span class="text-white/40 font-normal text-base">— Configure Your Floor Project</span></h2>
                    <div class="space-y-6 mt-6">

                        <!-- Total Square Footage -->
                        <div class="form-group">
                            <label for="floor_total_sqft" class="form-label">Total Square Footage <button type="button" class="quote-info-btn" data-info="floor_sqft"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <input type="number" id="floor_total_sqft" name="floor_total_sqft" class="form-input" value="" min="1" max="50000" placeholder="e.g. 800">
                        </div>

                        <!-- Existing Floor Demolition -->
                        <div>
                            <label class="form-label">Existing Floor Demolition <button type="button" class="quote-info-btn" data-info="floor_demo"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-2">
                                <button type="button" data-radio="floor_demo_type" data-value="carpet" class="quote-radio">Carpet</button>
                                <button type="button" data-radio="floor_demo_type" data-value="hardwood" class="quote-radio">Hardwood</button>
                                <button type="button" data-radio="floor_demo_type" data-value="tiles" class="quote-radio">Tiles</button>
                                <button type="button" data-radio="floor_demo_type" data-value="none" class="quote-radio">None</button>
                            </div>
                            <input type="hidden" name="floor_demo_type" id="floor_demo_type" value="">
                        </div>

                        <!-- Include Material? -->
                        <div>
                            <label class="form-label">Include Material? <button type="button" class="quote-info-btn" data-info="floor_material"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button></label>
                            <div class="grid grid-cols-2 gap-3 mt-2">
                                <button type="button" data-toggle="floor_include_material" data-value="yes" class="quote-toggle">Yes</button>
                                <button type="button" data-toggle="floor_include_material" data-value="no" class="quote-toggle quote-toggle--active">No</button>
                            </div>
                            <input type="hidden" name="floor_include_material" id="floor_include_material" value="no">
                        </div>

                        <!-- Material Selection (shown when include_material = yes) -->
                        <div id="floorMaterialSection" hidden>
                            <label class="form-label mb-3">Select Material(s) <span class="text-white/30 text-xs font-normal">— you can pick multiple</span></label>
                            <div class="space-y-4">

                                <!-- Hardwood -->
                                <div class="floor-material-item relative">
                                    <button type="button" data-material="hardwood" class="quote-card floor-mat-card w-full text-left flex-row !flex items-center gap-3 !p-4 !pr-10">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"/></svg>
                                        </div>
                                        <span class="font-heading font-bold">Hardwood</span>
                                    </button>
                                    <button type="button" class="quote-info-btn absolute right-3 top-5 z-10" data-info="mat_hardwood"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                    <input type="hidden" name="floor_mat_hardwood" id="floor_mat_hardwood" value="0">
                                    <div id="floorMatHardwoodSqft" class="form-group mt-3 ml-11" hidden>
                                        <label for="floor_mat_hardwood_sqft" class="form-label">Square Footage</label>
                                        <input type="number" id="floor_mat_hardwood_sqft" name="floor_mat_hardwood_sqft" class="form-input" value="" min="1" max="50000" placeholder="sqft">
                                    </div>
                                </div>

                                <!-- Engineered Hardwood -->
                                <div class="floor-material-item relative">
                                    <button type="button" data-material="engineered" class="quote-card floor-mat-card w-full text-left flex-row !flex items-center gap-3 !p-4 !pr-10">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"/></svg>
                                        </div>
                                        <span class="font-heading font-bold">Engineered Hardwood</span>
                                    </button>
                                    <button type="button" class="quote-info-btn absolute right-3 top-5 z-10" data-info="mat_engineered"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                    <input type="hidden" name="floor_mat_engineered" id="floor_mat_engineered" value="0">
                                    <div id="floorMatEngineeredSqft" class="form-group mt-3 ml-11" hidden>
                                        <label for="floor_mat_engineered_sqft" class="form-label">Square Footage</label>
                                        <input type="number" id="floor_mat_engineered_sqft" name="floor_mat_engineered_sqft" class="form-input" value="" min="1" max="50000" placeholder="sqft">
                                    </div>
                                    <!-- Installation method (shown when engineered selected) -->
                                    <div id="floorEngMethodSection" class="mt-3 ml-11" hidden>
                                        <label class="form-label">
                                            Installation Method
                                            <button type="button" class="quote-info-btn" data-info="eng_method"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                        </label>
                                        <div class="grid grid-cols-2 gap-3 mt-2">
                                            <button type="button" data-radio="floor_eng_method" data-value="glue_nail" class="quote-radio">Glue & Nail</button>
                                            <button type="button" data-radio="floor_eng_method" data-value="nails_only" class="quote-radio">Nails Only</button>
                                        </div>
                                        <input type="hidden" name="floor_eng_method" id="floor_eng_method" value="">
                                    </div>
                                </div>

                                <!-- Vinyl -->
                                <div class="floor-material-item relative">
                                    <button type="button" data-material="vinyl" class="quote-card floor-mat-card w-full text-left flex-row !flex items-center gap-3 !p-4 !pr-10">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"/></svg>
                                        </div>
                                        <span class="font-heading font-bold">Vinyl</span>
                                    </button>
                                    <button type="button" class="quote-info-btn absolute right-3 top-5 z-10" data-info="mat_vinyl"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                    <input type="hidden" name="floor_mat_vinyl" id="floor_mat_vinyl" value="0">
                                    <div id="floorMatVinylSqft" class="form-group mt-3 ml-11" hidden>
                                        <label for="floor_mat_vinyl_sqft" class="form-label">Square Footage</label>
                                        <input type="number" id="floor_mat_vinyl_sqft" name="floor_mat_vinyl_sqft" class="form-input" value="" min="1" max="50000" placeholder="sqft">
                                    </div>
                                </div>

                                <!-- Laminate -->
                                <div class="floor-material-item relative">
                                    <button type="button" data-material="laminate" class="quote-card floor-mat-card w-full text-left flex-row !flex items-center gap-3 !p-4 !pr-10">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2z"/></svg>
                                        </div>
                                        <span class="font-heading font-bold">Laminate</span>
                                    </button>
                                    <button type="button" class="quote-info-btn absolute right-3 top-5 z-10" data-info="mat_laminate"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                                    <input type="hidden" name="floor_mat_laminate" id="floor_mat_laminate" value="0">
                                    <div id="floorMatLaminateSqft" class="form-group mt-3 ml-11" hidden>
                                        <label for="floor_mat_laminate_sqft" class="form-label">Square Footage</label>
                                        <input type="number" id="floor_mat_laminate_sqft" name="floor_mat_laminate_sqft" class="form-input" value="" min="1" max="50000" placeholder="sqft">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Baseboard -->
                        <div>
                            <label class="form-label">
                                Baseboard needed?
                                <button type="button" class="quote-info-btn" data-info="baseboard"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                            </label>
                            <div class="grid grid-cols-2 gap-3 mt-2">
                                <button type="button" data-toggle="floor_baseboard" data-value="yes" class="quote-toggle">Yes</button>
                                <button type="button" data-toggle="floor_baseboard" data-value="no" class="quote-toggle quote-toggle--active">No</button>
                            </div>
                            <input type="hidden" name="floor_baseboard" id="floor_baseboard" value="no">
                        </div>
                        <div id="floorBaseboardInfo" class="rounded-xl bg-white/5 border border-white/10 p-4" hidden>
                            <p class="text-white/50 text-sm">Estimated linear feet: <span id="floorBaseboardLf" class="text-primary font-bold">0</span> LF <span class="text-white/30">(total sqft &div; 3)</span></p>
                        </div>

                        <!-- Shoe Molding -->
                        <div>
                            <label class="form-label">
                                Shoe Molding needed?
                                <button type="button" class="quote-info-btn" data-info="shoe_molding"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></button>
                            </label>
                            <div class="grid grid-cols-2 gap-3 mt-2">
                                <button type="button" data-toggle="floor_shoe_molding" data-value="yes" class="quote-toggle">Yes</button>
                                <button type="button" data-toggle="floor_shoe_molding" data-value="no" class="quote-toggle quote-toggle--active">No</button>
                            </div>
                            <input type="hidden" name="floor_shoe_molding" id="floor_shoe_molding" value="no">
                        </div>
                        <div id="floorShoeMoldingInfo" class="rounded-xl bg-white/5 border border-white/10 p-4" hidden>
                            <p class="text-white/50 text-sm">Estimated linear feet: <span id="floorShoeMoldingLf" class="text-primary font-bold">0</span> LF <span class="text-white/30">(total sqft &div; 3)</span></p>
                        </div>

                    </div>
                </div>

                <!-- ==================== SHARED ==================== -->

                <!-- Get Quote Button -->
                <div id="getQuoteBtn" class="mb-10" hidden>
                    <button type="button" id="openContactModal" class="btn btn-primary btn-lg w-full sm:w-auto">
                        Get My Quote
                        <svg class="w-5 h-5 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </button>
                    <p class="text-white/30 text-sm mt-3">Your quote will be sent to your email</p>
                </div>

                <input type="hidden" name="service_type" id="service_type" value="">
            </form>

        </div>
    </section>

    <!-- ============ CTA SECTION ============ -->
    <?php include 'includes/cta-section.php'; ?>
</main>

<!-- Contact Info Modal -->
<div id="contactModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/70" id="contactModalOverlay"></div>
    <div class="relative bg-dark-50 border border-white/10 rounded-2xl max-w-lg w-full p-8 transform scale-95 transition-transform duration-300" id="contactModalContent">
        <button type="button" id="contactModalClose" class="absolute top-4 right-4 text-white/40 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h3 class="font-heading text-xl font-bold mb-1">Almost there!</h3>
        <p class="text-white/50 text-sm mb-6">Enter your details and we'll email you a detailed quote.</p>

        <div class="space-y-4">
            <div class="form-group mb-0">
                <label for="modal_name" class="form-label">Full Name <span class="text-primary">*</span></label>
                <input type="text" id="modal_name" name="name" class="form-input" placeholder="John Doe">
                <span class="form-error">Please enter your full name</span>
            </div>
            <div class="form-group mb-0">
                <label for="modal_email" class="form-label">Email Address <span class="text-primary">*</span></label>
                <input type="email" id="modal_email" name="email" class="form-input" placeholder="john@example.com">
                <span class="form-error">Please enter a valid email address</span>
            </div>
            <div class="form-group mb-0">
                <label for="modal_phone" class="form-label">Phone Number <span class="text-primary">*</span></label>
                <input type="tel" id="modal_phone" name="phone" class="form-input" placeholder="(416) 555-0123">
                <span class="form-error">Please enter a valid phone number</span>
            </div>
            <div class="form-group mb-0">
                <label for="modal_address" class="form-label">Address <span class="text-white/30 text-xs font-normal">(Optional)</span></label>
                <input type="text" id="modal_address" name="address" class="form-input" placeholder="123 Main St, Brampton, ON">
            </div>
            <div class="form-group mb-0">
                <label for="modal_notes" class="form-label">Additional Notes <span class="text-white/30 text-xs font-normal">(Optional)</span></label>
                <textarea id="modal_notes" name="notes" rows="2" class="form-input resize-none" placeholder="Any additional details..."></textarea>
            </div>

            <button type="button" id="submitQuoteBtn" class="btn btn-primary btn-lg w-full mt-2">
                Send My Quote
                <svg class="w-5 h-5 btn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </button>
        </div>
    </div>
</div>

<!-- Info Modal -->
<div id="infoModal" class="fixed inset-0 z-[100] flex items-center justify-center p-4 opacity-0 pointer-events-none transition-opacity duration-300">
    <div class="absolute inset-0 bg-black/70" id="infoModalOverlay"></div>
    <div class="relative bg-dark-50 border border-white/10 rounded-2xl max-w-md w-full p-6 transform scale-95 transition-transform duration-300" id="infoModalContent">
        <button type="button" id="infoModalClose" class="absolute top-4 right-4 text-white/40 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div id="infoModalBody">
            <div class="rounded-xl overflow-hidden mb-4 bg-white/5">
                <img id="infoModalImg" src="" alt="" class="w-full h-48 object-cover">
            </div>
            <h3 id="infoModalTitle" class="font-heading text-lg font-bold mb-2"></h3>
            <p id="infoModalDesc" class="text-white/60 text-sm"></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<?php include 'includes/scripts.php'; ?>
</body>
</html>
