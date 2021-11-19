@php
    /**
     * Block template file: 
     *
     * Main Landing Block Template.
     */
@endphp

<div class="inner-child">
	<?php if ( have_rows( 'main_landing' ) ) : ?>
        <?php while ( have_rows( 'main_landing' ) ) : the_row(); ?>
    
            <div class="headings">
                <p class="h5">{!! strip_tags(get_sub_field( 'subtitle' ), '<strong><a>') !!}</p>
                <h1 class="h1">{!! strip_tags(get_sub_field( 'title' ), '<u>') !!}</h1>
            </div>
                
            <div class="link-section">

                <?php 
                    $scroll_link = get_sub_field( 'scroll_link' );
                    $page_link = get_sub_field( 'page_link' );
                ?>

                <div class="pull-right">
                    <?php if ( $scroll_link ) { ?>
                        <a class="btn box yellow" href="<?php echo $scroll_link['url']; ?>" target="<?php echo $scroll_link['target']; ?>">
                            <span class="full-arrow is-bottom"></span>
                            <span class="text"><?php echo $scroll_link['title']; ?></span>
                        </a>
                    <?php } ?>
                    
                    <?php if ( $page_link ) { ?>
                        <a class="btn box btn-icon dark" href="<?php echo $page_link['url']; ?>" target="<?php echo $page_link['target']; ?>">
                            <span class="full-arrow is-top-right white"></span>
                            <span class="text"><?php echo $page_link['title']; ?></span>
                            <?php $page_link_icon = get_sub_field( 'page_link_icon' ); ?>
                            <?php if ( $page_link_icon ) { ?>
                                <img class="instructor-ico" src="<?php echo $page_link_icon['url']; ?>" alt="<?php echo $page_link_icon['alt']; ?>" />
                            <?php } ?>
                        </a>
                    <?php } ?>
                </div>
            </div>
            
            <div class="va-section">

                <?php if ( have_rows( 'primary_video_section' ) ) : ?>
                    <?php while ( have_rows( 'primary_video_section' ) ) : the_row(); ?>
                        
                        <?php $video_id = get_sub_field( 'video_code' ); ?>
                        <div class="primary-video-section open--video-dialog" data-video-id="{{$video_id}}">
                            <?php $image = get_sub_field( 'image' ); ?>
                            <div class="bg-box"></div>
                            <div class="inline-bg-image contain" style="background-image: url(<?php echo $image['url']; ?>);">

                                <div class="play-btn-border">
                                    <div class="play-button"></div>
                                    <div class="border"></div>
                                </div>

                                <div class="author-details">
                                    <div class="name"><?php the_sub_field( 'full_name' ); ?></div>
                                    <div class="title"><?php the_sub_field( 'title' ); ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
                    
                <div class="about-section">
                    <?php if ( have_rows( 'about_section' ) ) : ?>
                        <?php while ( have_rows( 'about_section' ) ) : the_row(); ?>
                            
                            <h3 class="s-title"><?php the_sub_field( 'title' ); ?></h3>
                            <p class="para opac"><?php the_sub_field( 'description' ); ?></p>

                            <?php $link = get_sub_field( 'link' ); ?>
                            <?php if ( $link ) { ?>
                                <a class="link" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>"><?php echo $link['title']; ?><div class="underline"></div></a>
                            <?php } ?>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

            </div>
            
            <div class="popular-chapters pull-right">
                <?php if ( have_rows( 'popular_chapters' ) ) : ?>
                    <?php while ( have_rows( 'popular_chapters' ) ) : the_row(); ?>
                    
                        <h3 class="s-title"><?php the_sub_field( 'title' ); ?></h3>

                        <?php $more_link = get_sub_field( 'more_link' ); ?>
                        <?php if ( $more_link ) { ?>
                            <a class="link hide-m" href="<?php echo $more_link['url']; ?>" target="<?php echo $more_link['target']; ?>"><?php echo $more_link['title']; ?><div class="underline"></div></a>
                        <?php } ?>

                        <div class="chapters">
                            <?php if ( have_rows( 'chapters' ) ) : ?>
                                <?php while ( have_rows( 'chapters' ) ) : the_row(); ?>
                                    <?php $link = get_sub_field( 'link' ); ?>
                                    <?php if ( $link ) { ?>
                                        <a class="btn box light" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
                                            <?php $icon = get_sub_field( 'icon' ); ?>
                                            <?php if ( $icon ) { ?>
                                                <img class="icon" src="<?php echo $icon['url']; ?>" alt="<?php echo $icon['alt']; ?>" />
                                            <?php } ?>
                                            <span class="text"><?php echo $link['title']; ?></span>
                                        </a>
                                    <?php } ?>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>

                        <?php $more_link = get_sub_field( 'more_link' ); ?>
                        <?php if ( $more_link ) { ?>
                            <a class="link hide-d" href="<?php echo $more_link['url']; ?>" target="<?php echo $more_link['target']; ?>"><?php echo $more_link['title']; ?><div class="underline"></div></a>
                        <?php } ?>

                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            
            <div class="secondary-video-section">
                <?php if ( have_rows( 'secondary_video_section' ) ) : ?>
                    <?php while ( have_rows( 'secondary_video_section' ) ) : the_row(); ?>
                        
                        <?php $video_id = get_sub_field( 'video_code' ); ?>
                        <div class="video-box open--video-dialog" data-video-id="{{$video_id}}">

                            <?php $image = get_sub_field( 'image' ); ?>
                            
                            <div class="open-video-btn inline-bg-image" style="background-image: url(<?php echo $image['url']; ?>);">
                                <div class="play-button"></div>
                            </div>

                            <div class="s-title no-border"><?php the_sub_field( 'title' ); ?></div>
                            <p class="para opac"><?php the_sub_field( 'subtitle' ); ?></p>
                        </div>

                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
            
		<?php endwhile; ?>
	<?php endif; ?>
</div>