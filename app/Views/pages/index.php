<?php // Render the index page using the existing Bootstrap layout. ?>
<!-- Introduce the marketplace and direct visitors to browse or register. -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-7">
                <span class="eyebrow">GROWN IN JAMAICA • CONNECTED BY AGRIREACH</span>
                <h1>Fresh from the farm.<br><span>Closer to you.</span></h1>
                <p class="lead">Discover quality local produce, connect directly with Jamaican farmers, and support our agricultural communities.</p>
                <div class="d-flex flex-wrap gap-3">
                    <a class="btn btn-success btn-lg" href="/marketplace.php">Explore Marketplace →</a>
                    <a class="btn btn-light btn-lg" href="/register.php">Join as a Farmer</a>
                </div>
                <div class="hero-trust">✓ Local farmers &nbsp; ✓ Direct connections &nbsp; ✓ Fresh produce</div>
            </div>

            <div class="col-lg-5 text-center">
                <img src="/assets/img/container1_image.png" alt="Fresh vegetables from Jamaican farms" class="hero-image">
                <div class="hero-art-label">Fresh • Local • Jamaican 🇯🇲</div>
            </div>

        </div>
    </div>
</section>

<!-- Show the produce categories that buyers can browse. -->
<section class="section-space">
    <div class="container">

        <div class="section-heading">
            <div>
                <span class="eyebrow">SHOP BY CATEGORY</span>
                <h2>What are you looking for?</h2>
            </div>
            <a href="/marketplace.php" class="text-success fw-semibold">View all produce →</a>
        </div>

        <div class="row g-3">
            <div class="col-md-6"><a class="category-card" href="/marketplace.php?category=Vegetables"><span>🥦</span><strong>Vegetables</strong><small>Fresh greens and more</small></a></div>
            <div class="col-md-6"><a class="category-card" href="/marketplace.php?category=Fruits"><span>🍌</span><strong>Fruits</strong><small>Sweet seasonal harvests</small></a></div>
            <div class="col-md-6"><a class="category-card" href="/marketplace.php?category=Ground+Provisions"><span>🍠</span><strong>Ground Provisions</strong><small>Traditional Jamaican staples</small></a></div>
            <div class="col-md-6"><a class="category-card" href="/marketplace.php?category=Eggs"><span>🥚</span><strong>Eggs</strong><small>Fresh from local farms</small></a></div>
        </div>

    </div>
</section>

<!-- Display a selection of product cards using the reusable card component. -->
<section class="section-space bg-soft">
    <div class="container">

        <div class="section-heading">
            <div>
                <span class="eyebrow">FROM LOCAL FARMS</span>
                <h2>Featured Produce</h2>
                <p class="text-muted mb-0">Newest listings from local farmers.</p>
            </div>
            <a href="/marketplace.php" class="btn btn-outline-success">Browse Everything</a>
        </div>

        <div class="row g-4">
            <?php foreach (array_slice($products, 0, 3) as $product): include dirname(__DIR__) . '/components/product-card.php'; endforeach; ?>
        </div>

    </div>
</section>

<!-- Encourage farmers to join the platform. -->
<section class="section-space">
    <div class="container">
        <div class="join-banner">
            <div>
                <span class="eyebrow">FOR JAMAICAN FARMERS</span>
                <h2>Grow your reach, not just your crops.</h2>
                <p>Show buyers what you have available and connect with interested customers directly.</p>
                <a href="/register.php" class="btn btn-light">Create Farmer Account →</a>
            </div>
            <div class="join-icon" aria-hidden="true">🌾</div>
        </div>
    </div>
</section>