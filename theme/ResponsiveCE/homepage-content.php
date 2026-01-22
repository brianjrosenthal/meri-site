<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-image-container">
        <img src="/data/uploads/hero_image.png" alt="Peaceful landscape" class="hero-image">
        <div class="hero-overlay">
            <h1 class="hero-title">RITES OF PASSAGE</h1>
        </div>
    </div>
</section>

<!-- Services Grid Section -->
<section class="services-grid-section">
    <div class="services-header">
        <h2>Services</h2>
    </div>
    
    <div class="container services-container">
        <div class="services-grid">
            <a href="#rites-of-passage" class="service-card">
                <div class="service-image">
                    <img src="/data/uploads/rites_of_passage_sheep_square.png" alt="Rites of Passage">
                    <div class="service-title-overlay">
                        <h3>Rites of Passage</h3>
                    </div>
                </div>
            </a>
            
            <a href="#cranio-sacral" class="service-card">
                <div class="service-image">
                    <img src="/data/uploads/cranio_sacral_therapy_square.png" alt="Cranio-Sacral Therapy">
                    <div class="service-title-overlay">
                        <h3>Cranio-Sacral Therapy</h3>
                    </div>
                </div>
            </a>
            
            <a href="#somatic-emotional" class="service-card">
                <div class="service-image">
                    <img src="/data/uploads/somatic_emotional_release.png" alt="Somatic-Emotional Release">
                    <div class="service-title-overlay">
                        <h3>Somatic-Emotional Release</h3>
                    </div>
                </div>
            </a>
            
            <a href="#shamanic-work" class="service-card">
                <div class="service-image">
                    <img src="/data/uploads/fire_without_marshmallows.jpeg" alt="Shamanic Work">
                    <div class="service-title-overlay">
                        <h3>Shamanic Work</h3>
                    </div>
                </div>
            </a>
            
            <a href="#dynamic-body" class="service-card">
                <div class="service-image">
                    <img src="/data/uploads/dynamic_body_rebalancing_square.png" alt="Dynamic Body Balancing">
                    <div class="service-title-overlay">
                        <h3>Dynamic Body Balancing</h3>
                    </div>
                </div>
            </a>
            
            <a href="#reiki" class="service-card">
                <div class="service-image">
                    <img src="/data/uploads/reiki.png" alt="Reiki">
                    <div class="service-title-overlay">
                        <h3>Reiki</h3>
                    </div>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Service Details Sections -->
<div class="service-details-wrapper">
    <div class="container">
        
        <!-- Rites of Passage -->
        <section id="rites-of-passage" class="service-detail">
            <div class="service-detail-content">
                <h2>Rites of Passage</h2>
                <?php get_html_component('rites-of-passage-desc'); ?>
            </div>
        </section>
        
        <!-- Cranio-Sacral Therapy -->
        <section id="cranio-sacral" class="service-detail">
            <div class="service-detail-content">
                <h2>Cranio-Sacral Therapy</h2>
                <?php get_html_component('cranio-sacral-desc'); ?>
            </div>
        </section>
        
        <!-- Somatic-Emotional Release -->
        <section id="somatic-emotional" class="service-detail">
            <div class="service-detail-content">
                <h2>Somatic-Emotional Release</h2>
                <?php get_html_component('somatic-emotional-desc'); ?>
            </div>
        </section>
        
        <!-- Shamanic Work -->
        <section id="shamanic-work" class="service-detail">
            <div class="service-detail-content">
                <h2>Shamanic Work</h2>
                <?php get_html_component('shamanic-work-desc'); ?>
            </div>
        </section>
        
        <!-- Dynamic Body Balancing -->
        <section id="dynamic-body" class="service-detail">
            <div class="service-detail-content">
                <h2>Dynamic Body Balancing</h2>
                <?php get_html_component('dynamic-body-desc'); ?>
            </div>
        </section>
        
        <!-- Reiki -->
        <section id="reiki" class="service-detail">
            <div class="service-detail-content">
                <h2>Reiki</h2>
                <?php get_html_component('reiki-desc'); ?>
            </div>
        </section>
        
    </div>
</div>

<!-- TEMP: Color Picker Tool - Remove this line when done -->
<? // php include(GSDATAOTHERPATH.'../../theme/ResponsiveCE/color-picker-tool.html'); 
?>
