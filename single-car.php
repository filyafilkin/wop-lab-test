<?php get_header() ?>
<div class="single-car">
    <div class="single-car__inner container">
        <h1 class="single-car__title">
            <?php the_title() ?>
        </h1>

        <div class="single-car__img">
            <?php if (has_post_thumbnail()) {
                the_post_thumbnail();
            } else { ?>
                <img src="/wp-content/uploads/logo-1.png" alt="WOP LAB TEST">
            <?php } ?>
        </div>

        <div class="single-car__content">
            <?php $country_arr = get_the_terms(get_the_ID(), 'country');
            if (!empty($country_arr)) {
                foreach ($country_arr as $country) {
                    echo '<div class="single-car__field"><span class="single-car__subtitle"> Country: </span> ' . $country->name . '</div>';
                }
            } ?>

            <?php $brand_arr = get_the_terms(get_the_ID(), 'brand');
            if (!empty($brand_arr)) {
                foreach ($brand_arr as $brand) {
                    echo '<div class="single-car__field"><span class="single-car__subtitle"> Brand: </span>' . $brand->name . '</div>';
                }
            } ?>

            <?php $power = get_post_meta( get_the_ID(), 'power', true );
            if ($power) {
                    echo '<div class="single-car__field"><span class="single-car__subtitle"> Power: </span>' .  esc_html($power) . '</div>';
            } ?>

            <?php $price = get_post_meta( get_the_ID(), 'price', true );
            if ($price) {
                echo '<div class="single-car__field"><span class="single-car__subtitle"> Price: </span>' .  esc_html($price) . '</div>';
            } ?>

            <?php $color = get_post_meta( get_the_ID(), 'color', true );
            if ($color) {
                echo '<div class="single-car__field">
                            <span class="single-car__subtitle"> Color: </span><div class="single-car__color" style="background-color: ' . esc_html($color) . ' " ></div>
                       </div>';
            } ?>

            <?php $fuel = get_post_meta( get_the_ID(), 'fuel', true );
            if ($fuel) {
                echo '<div class="single-car__field"><span class="single-car__subtitle"> Fuel: </span>' .  esc_html($fuel) . '</div>';
            } ?>
        </div>
    </div>
</div>
