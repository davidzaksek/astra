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

    <?php $instructor_link = get_field( 'instructor_link', 'option' ); ?>
	<?php if ( $instructor_link ) { ?>
        <a class="btn box dark horizont btn-icon hide-m" href="<?php echo $instructor_link['url']; ?>" target="<?php echo $instructor_link['target']; ?>">
        <span class="full-arrow is-top-right white"></span>
        <?php $instructor_icon = get_field( 'instructor_icon', 'option' ); ?>
        <?php if ( $instructor_icon ) { ?>
        <img class="instructor-ico" src="<?php echo $instructor_icon['url']; ?>" alt="<?php echo $instructor_icon['alt']; ?>" />
        <?php } ?>
    </a>
	<?php } ?>

</div>