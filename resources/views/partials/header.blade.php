<header id="header" class="header">
    <div class="inner">

        <?php if ( have_rows( 'header_section', 'option' ) ) : ?>
            <?php while ( have_rows( 'header_section', 'option' ) ) : the_row(); ?>

                <div class="pull-left">
                    <?php $site_logo = get_sub_field( 'site_logo' ); ?>
                    <?php if ( $site_logo ) { ?>
                        <a class="site-logo dark-blue" href="{{ home_url('/') }}">
                            <img src="<?php echo $site_logo['url']; ?>" alt="<?php echo $site_logo['alt']; ?>" />
                        </a>
                    <?php } ?>

                    <?php $site_logo_white = get_sub_field( 'site_logo_white' ); ?>
                    <?php if ( $site_logo_white ) { ?>
                        <a class="site-logo white" href="{{ home_url('/') }}">
                            <img src="<?php echo $site_logo_white['url']; ?>" alt="<?php echo $site_logo_white['alt']; ?>" />
                        </a>
                    <?php } ?>

                    <div class="search-section closed">

                        {{-- Show custom search plugin --}}
                        <?php echo do_shortcode('[ivory-search id="11151" title="Default Search Form"]'); ?>
                        
                    </div>
                </div>
                
                <div class="pull-right">

                    <div class="mobile-nav hide-d">
                        <span class="navicon trg--nav">
                            <div class="burger-cross"></div>
                        </span>

                        <?php $search_icon = get_sub_field( 'search_icon' ); ?>
                        <?php if ( $search_icon ) { ?>
                        <figure class="search-icon trg--search">
                            <img src="<?php echo $search_icon['url']; ?>" alt="<?php echo $search_icon['alt']; ?>" />
                        </figure>
                        <?php } ?>

                    </div>

                    <?php $main_button = get_sub_field( 'main_button' ); ?>
                    <?php if ( $main_button ) { ?>
                        <a class="btn blue default hide-m" href="<?php echo $main_button['url']; ?>" target="<?php echo $main_button['target']; ?>"><?php echo $main_button['title']; ?></a>
                    <?php } ?>

                    <nav id="nav" class="nav closed">

                        <?php if ( $site_logo_white ) { ?>
                            <a class="site-logo white hide-d" href="{{ home_url('/') }}">
                                <img src="<?php echo $site_logo_white['url']; ?>" alt="<?php echo $site_logo_white['alt']; ?>" />
                            </a>
                        <?php } ?>

                        <?php if ( have_rows( 'site_nav' ) ) : ?>
                            <ul>

                                <li class="nav-item hide-d">
                                    <a class="menu-link" href="{{ home_url('/') }}">Domov</a>
                                </li>

                                <?php while ( have_rows( 'site_nav' ) ) : the_row(); ?>

                                    <?php 
                                    
                                    $link = get_sub_field( 'link' );
                                    $themes_dropdown = get_sub_field( 'themes_dropdown' );

                                    if ( $link && !$themes_dropdown ) { ?>
                                    
                                        <li class="nav-item">
                                            <a class="menu-link" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>"><?php echo $link['title']; ?></a>
                                        </li>

                                    <?php } ?>

                                    <?php if ( $themes_dropdown ) { ?>
                                
                                        <li class="nav-item dropdown hide-m">
                                            <span class="menu-link"><?php echo $link['title']; ?></span>

                                            <ul class="dropdown-menu">

                                                <?php 
                                                    $topics = get_all_topics();
                                                    foreach ($topics as $topic) {
                                                ?>

                                                    <a href="{{$topic->url}}" class="theme">
                                                        <div class="title">{{$topic->title}}</div>
                                                        <div class="video-count">{{$topic->videos_count}} {{getVideoText($topic->videos_count)}}</div>
                                                    </a>
                                                    
                                                <?php } ?>

                                            </ul>
                                        </li>

                                        <li class="nav-item hide-d">
                                            <a class="menu-link" href="<?php echo $link['url']; ?>"><?php echo $link['title']; ?></a>
                                        </li>

                                    <?php } ?>

                                <?php endwhile; ?>

                                <li class="nav-item hide-d">
                                    <a class="menu-link" href="<?php echo $main_button['url']; ?>"><?php echo $main_button['title']; ?></a>
                                </li>

                            </ul>
                        <?php endif; ?>

                        <?php // Get instructor global link
                        $instructor_link = get_field( 'instructor_link', 'option' ); ?>

                        @php if ( $instructor_link ): @endphp
                        <a class="btn box white hollow small hide-d" href="<?php echo $instructor_link['url']; ?>" target="<?php echo $instructor_link['target']; ?>">
                            <span class="full-arrow is-top-right white"></span>
                            <span class="text"><?php echo $instructor_link['title']; ?></span>
                        </a>
                        @php endif; @endphp

                    </nav>
                </div>

            <?php endwhile; ?>
        <?php endif; ?>

    </div>

    <div class="bg-lines"><div class="m-lines"></div></div>
</header>