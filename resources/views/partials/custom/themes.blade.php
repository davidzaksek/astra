@php
/**
 * Block template file: 
 *
 * Themes Block Template.
 */
@endphp

<div class="inner-child">

    <h2 class="lh2 light"><?php the_field( 'title' ); ?></h2>

    <div class="themes-section clearfix">
        <?php 
            $topics = get_all_topics();
            foreach ($topics as $topic) {
        ?>

            <a href="{{$topic->url}}" class="theme pull-left">
                <div class="title">{{$topic->title}}</div>
                <div class="video-count">{{$topic->videos_count}} {{getVideoText($topic->videos_count)}}</div>
                <div class="full-arrow is-right small white"></div>
            </a>
            
        <?php } ?>
    </div>

    <?php $link = get_field( 'link' ); ?>
	<?php if ( $link ) { ?>
        <a class="btn box blue btn-icon hide-m" href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
            <span class="full-arrow is-top-right white"></span>
            <span class="text"><?php echo $link['title']; ?></span>
            <?php $link_icon = get_field( 'link_icon' ); ?>
            <?php if ( $link_icon ) { ?>
                <img class="instructor-ico" src="<?php echo $link_icon['url']; ?>" alt="<?php echo $link_icon['alt']; ?>" />
            <?php } ?>
        </a>
	<?php } ?>

</div>