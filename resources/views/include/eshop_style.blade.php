<style>
    .header.shop .header-inner {
        background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .footer {
        background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .header.sticky .header-inner .nav li a {
        color: #fff;
    }

    .header.shop .all-category {
        color: #fff;
        background: transparent;
        position: relative;
        background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .shop-newsletter .newsletter-inner .btn {
        background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .shop-newsletter .newsletter-inner .btn:hover {
        color: #fff;
        background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .midium-banner .single-banner a {
        background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .midium-banner .single-banner a:hover {
        background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .hero-slider .hero-text .btn {
        background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .hero-slider .hero-text .btn:hover {
        background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .header.shop .top-left .list-main li i {
        color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .header.shop .list-main li i {
        color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .header.shop .right-bar .sinlge-bar .single-icon .total-count {
        background: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .single-product .product-content h3 a:hover {
        color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .section-title h2::before {
        background: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .shopping-summery thead {
        background: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    #scrollUp i {
        background: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    #scrollUp i:hover {
        background: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .btn {
        background: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .btn:hover {
        background: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .quickview-content .add-to-cart .btn {
        background: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .header.shop .nav li.active a {
        color: #fff;
        background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .header.shop .nav li:hover a {
        color: #fff;
        background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .header.shop .nav li .dropdown li:hover a {
        color: #fff;
        background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .footer .links ul li a:hover {
        padding-left: 10px;
        color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .footer .about .call a {
        font-size: 20px;
        font-weight: 600;
        color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .page-item.active .page-link {
        z-index: 2;
        color: #fff;
        background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
        border-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .page-link:hover {
        color: #fff;
        background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
        border-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }

    .contact-us .single-info i {
        background-color: {{ SettingHelper::getSettingValueBySLug('site_secondary_color') }};
    }

    .accordion-button:not(.collapsed) {
        color: #ffffff;
        background-color: {{ SettingHelper::getSettingValueBySLug('site_primary_color') }};
    }
</style>
