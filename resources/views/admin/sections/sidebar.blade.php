<?php
$segment_2 = Request::segment(2);
$segment_3 = Request::segment(3);
?>

<div id="sidebar-nav" class="sidebar ad-pannel-sdbar-sty">
    <nav>
        <ul class="nav" id="sidebar-nav-menu">
            <li>
                <a href="{{route('admin.dashboard')}}" class="{{($segment_2 == 'dashboard') ? 'active' : ''}}">
                    <i class="fa fa-pie-chart"></i><span class="title">Dashboard</span>
                </a>
            </li>

            <?php
            if ($segment_2 == 'users' || $segment_2 == 'lawful-interception') {
                $resellers_active_class = 'active';
                $resellers_aria_expanded = 'true';
                $resellers_div_height = '';
                $resellers_div_collapse_class = 'collapse in';
            } else {
                $resellers_active_class = 'collapsed';
                $resellers_aria_expanded = 'false';
                $resellers_div_height = 'height: 0px';
                $resellers_div_collapse_class = 'collapse';
            }
            ?>

            @if(have_right(72) || have_right(97))
                <li class="panel">
                    <a href="#resellers" data-toggle="collapse" data-parent="#sidebar-nav-menu"
                       class="{{ $resellers_active_class }} drop-menu-links"
                       aria-expanded="{{ $resellers_aria_expanded }}">
                        <i class="fa fa-users"></i><span class="title">Users</span>
                    </a>

                    <div id="resellers" class="{{ $resellers_div_collapse_class }}"
                         aria-expanded="{{ $resellers_aria_expanded }}" style="{{ $resellers_div_height }}">
                        <ul class="submenu">
                            @if(have_right(72))
                                <li>
                                    <a href="{{ url('admin/users') }}"
                                       class="{{($segment_2 == 'users') ? 'active' : ''}}">
                                        <i class="fa fa-users"></i><span class="title">Users</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right(97))
                                <li>
                                    <a href="{{ url('admin/lawful-interception') }}"
                                        class="{{($segment_2 == 'lawful-interception') ? 'active' : ''}}">
                                        <i class="fa fa-user-secret"></i><span class="title">Lawful Interception</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            <?php
            if ($segment_2 == 'roles' || $segment_2 == 'admins') {
                $admins_active_class = 'active';
                $admins_aria_expanded = 'true';
                $admins_div_height = '';
                $admins_div_collapse_class = 'collapse in';
            } else {
                $admins_active_class = 'collapsed';
                $admins_aria_expanded = 'false';
                $admins_div_height = 'height: 0px';
                $admins_div_collapse_class = 'collapse';
            }
            ?>

            @if(have_right(73) || have_right(74))
                <li class="panel">
                    <a href="#admins" data-toggle="collapse" data-parent="#sidebar-nav-menu"
                       class="{{ $admins_active_class }} drop-menu-links" aria-expanded="{{ $admins_aria_expanded }}">
                        <i class="fa fa-users"></i><span class="title">Admins</span>
                    </a>

                    <div id="admins" class="{{ $admins_div_collapse_class }}"
                         aria-expanded="{{ $admins_aria_expanded }}"
                         style="{{ $admins_div_height }}">
                        <ul class="submenu">
                            @if(have_right(73))
                                <li>
                                    <a href="{{ url('admin/roles') }}"
                                       class="{{($segment_2 == 'roles') ? 'active' : ''}}">
                                        <i class="fa fa-user-secret"></i><span class="title">Roles</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right(74))
                                <li>
                                    <a href="{{ url('admin/admins') }}"
                                       class="{{($segment_2 == 'admins') ? 'active' : ''}}">
                                        <i class="fa fa-user"></i> <span class="title">Admin Users</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if(have_right(76))
                <li>
                    <a href="{{ url('admin/countries') }}" class="{{($segment_2 == 'countries') ? 'active' : ''}}">
                        <i class="fa fa-globe"></i><span class="title">Country wise VAT</span>
                    </a>
                </li>
            @endif


            <?php
            if ($segment_2 == 'shapes' || $segment_2 == 'logos' || $segment_2 == 'qr-code-templates' || $segment_2 == 'guest-qr-code') {
                $languages_active_class = 'active';
                $languages_aria_expanded = 'true';
                $languages_div_height = '';
                $languages_div_collapse_class = 'collapse in';
            } else {
                $languages_active_class = 'collapsed';
                $languages_aria_expanded = 'false';
                $languages_div_height = 'height: 0px';
                $languages_div_collapse_class = 'collapse';
            }
            ?>

            @if(have_right(77) || have_right(78) || have_right(79))
                <li class="panel">
                    <a href="#shapes" data-toggle="collapse" data-parent="#sidebar-nav-menu"
                       class="{{ $languages_active_class }} drop-menu-links"
                       aria-expanded="{{ $languages_aria_expanded }}">
                        <i class="fa fa-qrcode"></i><span class="title">QR Codes Management</span>
                    </a>

                    <div id="shapes" class="{{ $languages_div_collapse_class }}"
                         aria-expanded="{{ $languages_aria_expanded }}" style="{{ $languages_div_height }}">
                        <ul class="submenu">
                            @if(have_right(77))
                                <li>
                                    <a href="{{ url('admin/shapes') }}"
                                       class="{{($segment_2 == 'shapes') ? 'active' : ''}}">
                                        <i class="fa fa-tags"></i><span class="title">Shapes</span>
                                    </a>
                                </li>
                            @endif
                                @if(have_right(78))
                            <li>
                                <a href="{{ url('admin/logos') }}"
                                   class="{{($segment_2 == 'logos') ? 'active' : ''}}">
                                    <i class="fa fa-picture-o"></i><span class="title">Logos</span>
                                </a>
                            </li>
                                @endif
                                @if(have_right(79))
                            <li>
                                <a href="{{ url('admin/qr-code-templates') }}"
                                   class="{{($segment_2 == 'qr-code-templates') ? 'active' : ''}}">
                                    <i class="fa fa-tags"></i><span class="title">QR Code Templates</span>
                                </a>
                            </li>
                                    @endif

                                @if(have_right(107))
                                    <li>
                                        <a href="{{ url('admin/guest-qr-code') }}"
                                           class="{{($segment_2 == 'guest-qr-code') ? 'active' : ''}}">
                                            <i class="fa fa-qrcode"></i><span class="title">Guest QR Code</span>
                                        </a>
                                    </li>
                                @endif
                        </ul>
                    </div>
                </li>
            @endif

            <?php
            if ($segment_2 == 'package-features' || $segment_2 == 'packages') {
                $packages_active_class = 'active';
                $packages_aria_expanded = 'true';
                $packages_div_height = '';
                $packages_div_collapse_class = 'collapse in';
            } else {
                $packages_active_class = 'collapsed';
                $packages_aria_expanded = 'false';
                $packages_div_height = 'height: 0px';
                $packages_div_collapse_class = 'collapse';
            }
            ?>

            @if(have_right(80) || have_right(81))
            <li class="panel">
                <a href="#packages" data-toggle="collapse" data-parent="#sidebar-nav-menu"
                   class="{{ $packages_active_class }} drop-menu-links" aria-expanded="{{ $packages_aria_expanded }}">
                    <img src="{{ asset('images/package.png') }}" alt="package" class="img-responsive"><span
                        class="title">Packages</span>
                </a>
                <div id="packages" class="{{ $packages_div_collapse_class }}"
                     aria-expanded="{{ $packages_aria_expanded }}" style="{{ $packages_div_height }}">
                    <ul class="submenu">
                        @if(have_right(81))
                            <li>
                                <a href="{{ url('admin/package-features') }}"
                                   class="{{($segment_2 == 'package-features') ? 'active' : ''}}">
                                    <i class="fa fa-list"></i><span class="title">Package Features</span>
                                </a>
                            </li>
                        @endif
                        @if(have_right(80))
                            <li>
                                <a href="{{ url('admin/packages') }}"
                                   class="{{($segment_2 == 'packages') ? 'active' : ''}}">
                                    <img src="{{ asset('images/package.png') }}" alt="package"
                                         class="img-responsive simple"> <img
                                        src="{{ asset('images/package.png') }}"
                                        alt="package" class="img-responsive activ"><span class="title">Packages</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
            @endif
            <?php
            if ($segment_2 == 'languages' || $segment_2 == 'language-translations' || $segment_2 == 'language-modules' || $segment_2 == 'label-translations' || $segment_2 == 'text-translations') {
                $languages_active_class = 'active';
                $languages_aria_expanded = 'true';
                $languages_div_height = '';
                $languages_div_collapse_class = 'collapse in';
            } else {
                $languages_active_class = 'collapsed';
                $languages_aria_expanded = 'false';
                $languages_div_height = 'height: 0px';
                $languages_div_collapse_class = 'collapse';
            }
            ?>

            @if(have_right(82) || have_right(83) || have_right(84) || have_right(85))
                <li class="panel">
                    <a href="#languages" data-toggle="collapse" data-parent="#sidebar-nav-menu"
                       class="{{ $languages_active_class }} drop-menu-links"
                       aria-expanded="{{ $languages_aria_expanded }}">
                        <i class="fa fa-language"></i><span class="title">Languages</span>
                    </a>

                    <div id="languages" class="{{ $languages_div_collapse_class }}"
                         aria-expanded="{{ $languages_aria_expanded }}" style="{{ $languages_div_height }}">
                        <ul class="submenu">
                            @if(have_right(82))
                                <li>
                                    <a href="{{ url('admin/languages') }}"
                                       class="{{($segment_2 == 'languages') ? 'active' : ''}}">
                                        <i class="fa fa-language"></i><span class="title">Languages</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right(83))
                                <li>
                                    <a href="{{ url('admin/language-modules') }}"
                                       class="{{($segment_2 == 'language-modules') ? 'active' : ''}}">
                                        <i class="fa fa-list"></i><span class="title">Language Modules</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right(84))
                                <li>
                                    <a href="{{ url('admin/language-translations') }}"
                                       class="{{($segment_2 == 'language-translations') ? 'active' : ''}}">
                                        <i class="fa fa-list"></i><span class="title">Language Translations</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right(85))
                                <li>
                                    <a href="{{ url('admin/label-translations') }}"
                                       class="{{($segment_2 == 'label-translations') ? 'active' : ''}}">
                                        <i class="fa fa-list"></i><span class="title">Label Translations</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right(85))
                                <li>
                                    <a href="{{ url('admin/text-translations') }}"
                                       class="{{($segment_2 == 'text-translations') ? 'active' : ''}}">
                                        <i class="fa fa-list"></i><span class="title">Text Translations</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            <?php
            if ($segment_2 == 'email-templates' || $segment_2 == 'email-template-labels') {
                $email_templates_active_class = 'active';
                $email_templates_aria_expanded = 'true';
                $email_templates_div_height = '';
                $email_templates_div_collapse_class = 'collapse in';
            } else {
                $email_templates_active_class = 'collapsed';
                $email_templates_aria_expanded = 'false';
                $email_templates_div_height = 'height: 0px';
                $email_templates_div_collapse_class = 'collapse';
            }
            ?>

            @if(have_right(87) || have_right(88))
                <li class="panel">
                    <a href="#email-temp" data-toggle="collapse" data-parent="#sidebar-nav-menu"
                       class="{{ $email_templates_active_class }} drop-menu-links"
                       aria-expanded="{{ $email_templates_aria_expanded }}">
                        <i class="fa fa-envelope"></i><span class="title">Email Templates</span>
                    </a>

                    <div id="email-temp" class="{{ $email_templates_div_collapse_class }}"
                         aria-expanded="{{ $email_templates_aria_expanded }}" style="{{ $email_templates_div_height }}">
                        <ul class="submenu">
                            @if(have_right(87))
                                <li>
                                    <a href="{{  url('admin/email-templates') }}"
                                       class="{{($segment_2 == 'email-templates') ? 'active' : ''}}">
                                        <i class="fa fa-envelope"></i><span class="title">Email Templates Listing</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right(88))
                                <li>
                                    <a href="{{ url('admin/email-template-labels') }}"
                                       class="{{($segment_2 == 'email-template-labels') ? 'active' : ''}}">
                                        <i class="fa fa-list"></i><span class="title">Email Template Labels</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif

            <?php
            if ($segment_2 == 'cms-pages' || $segment_2 == 'cms-page-labels') {
                $cms_page_active_class = 'active';
                $cms_page_aria_expanded = 'true';
                $cms_page_div_height = '';
                $cms_page_div_collapse_class = 'collapse in';
            } else {
                $cms_page_active_class = 'collapsed';
                $cms_page_aria_expanded = 'false';
                $cms_page_div_height = 'height: 0px';
                $cms_page_div_collapse_class = 'collapse';
            }
            ?>

            @if(have_right(90) || have_right(91))
                <li class="panel">
                    <a href="#cms-page" data-toggle="collapse" data-parent="#sidebar-nav-menu"
                       class="{{ $cms_page_active_class }} drop-menu-links"
                       aria-expanded="{{ $cms_page_aria_expanded }}">
                        <i class="fa fa-file-text-o"></i><span class="title">CMS Pages</span>
                    </a>

                    <div id="cms-page" class="{{ $cms_page_div_collapse_class }}"
                         aria-expanded="{{ $cms_page_aria_expanded }}" style="{{ $cms_page_div_height }}">
                        <ul class="submenu">
                            @if(have_right(90))
                                <li>
                                    <a href="{{ url('admin/cms-pages') }}"
                                       class="{{($segment_2 == 'cms-pages') ? 'active' : ''}}">
                                        <i class="fa fa-file-text-o"></i><span class="title">CMS Pages Listing</span>
                                    </a>
                                </li>
                            @endif
                            @if(have_right(91))
                                <li>
                                    <a href="{{ url('admin/cms-page-labels') }}"
                                       class="{{($segment_2 == 'cms-page-labels') ? 'active' : ''}}">
                                        <i class="fa fa-list"></i><span class="title">CMS Page Labels</span>
                                    </a>
                                </li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif

            @if(have_right(93))
                <li>
                    <a href="{{ url('admin/faqs') }}" class="{{($segment_2 == 'faqs') ? 'active' : ''}}">
                        <i class="fa fa-question"></i><span class="title">FAQs</span>
                    </a>
                </li>
            @endif

            @if(have_right(94))
                <li>
                    <a href="{{ url('admin/features') }}" class="{{($segment_2 == 'features') ? 'active' : ''}}">
                        <i class="fa fa-tags"></i><span class="title">Features</span>
                    </a>
                </li>
            @endif

            @if(have_right(95))
                <li>
                    <a href="{{ url('admin/contact-us-queries') }}"
                       class="{{($segment_2 == 'contact-us-queries') ? 'active' : ''}}">
                        <i class="fa fa-phone"></i><span class="title">Contact Us Queries</span>
                    </a>
                </li>
            @endif
            @if(have_right(104))
                <li>
                    <a href="{{ url('admin/subscribers') }}"
                       class="{{($segment_2 == 'subscribers') ? 'active' : ''}}">
                        <i class="fa fa-telegram"></i><span class="title">Subscribers</span>
                    </a>
                </li>
            @endif
            @if(have_right(69))
                <li>
                    <a href="{{ url('admin/payment-gateway-settings') }}"
                       class="{{($segment_2 == 'payment-gateway-settings') ? 'active' : ''}}">
                        <i class="fa fa-credit-card-alt"></i><span class="title">Payment Gateways</span>
                    </a>
                </li>
            @endif
            @if(have_right(70))
                <li>
                    <a href="{{ url('admin/settings') }}" class="{{($segment_2 == 'settings') ? 'active' : ''}}">
                        <i class="fa fa-cog"></i><span class="title">Site Settings</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>
