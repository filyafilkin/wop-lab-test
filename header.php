<?php ?>
<html>
<head>
    <meta charset="utf-8"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;800&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>

<body>
    <header class="header">
        <div class="header__inner container">
            <?php the_custom_logo(); ?>

            <a class="header__number" href="tel:<?php echo get_theme_mod("wop_company-name"); ?>">
                <?php echo get_theme_mod("wop_company-name"); ?>
            </a>
        </div>
    </header>


