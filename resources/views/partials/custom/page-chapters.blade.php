@php
                
    global $post;
    // Get current page data
    $page_data = get_chapter_data($post);

    $page_title = $page_data->title;
    $page_desc = $page_data->desc;
    $videos = $page_data->videos;
    $videos_count = count($videos);
    // Get amount watched
    $watched_amount = $page_data->watched_amount;
    $percent_watched = round(($watched_amount * 100) / $videos_count);

    // Calculate total time
    $total_time = totalTimeToText(calculateTotalTime($page_data->total_seconds));

    // Get first video link
    $start_link = $videos[0]->url;

    // Get page parent URL
    $parent_url = get_page_link($post->post_parent);

    // Get global text content
    if ( have_rows( 'text', 'option' ) ):
        while ( have_rows( 'text', 'option' ) ) : the_row();

            $text_chapter = get_sub_field( 'chapter' );
            $text_start_chapter = get_sub_field( 'start_chapter' );
            $text_go_back = get_sub_field( 'go_back' );
            $text_watched = get_sub_field( 'watched' );
            $text_progress = get_sub_field( 'in_progress' );
        
        endwhile;
    endif;

    // Get instructor global link
    $instructor_link = get_field( 'instructor_link', 'option' );

@endphp

<div class="inner-overlap">
    <div class="left-content">
        <h1 class="h1 light">{{$page_title}}</h1>

        <div class="page-details">
            <span class="page-cat">{{$text_chapter}}</span>
            <span class="bullet">&bull;</span>
            <span class="content-count">{{$videos_count}} {{getVideoText($videos_count)}}</span>
            <?php if ($page_data->total_seconds > 0) : ?>
            <span class="bullet">&bull;</span>
            <span class="time-length">{{$total_time}}</span>
            <?php endif; ?>
            <?php if ($watched_amount > 0) : ?>
            <span class="bullet">&bull;</span>
            <span class="watched-percent txt-green">{{$percent_watched}}% {{$text_watched}}</span>
            <?php endif; ?>
        </div>

        <p class="para opac light">{{$page_desc}}</p>

        <a href="{{$start_link}}" class="btn hollow blue play">{{$text_start_chapter}}</a>
    </div>

    <div class="chapter-videos right-content">

        @php
            foreach ($videos as $video):

                // Get/set video data
                $video_url = $video->url;
                $video_img_url = $video->img_url;
                $video_title = $video->title;
                $video_length_sec = ($video->length->min * 60) + $video->length->sec;
                // Calculate total time
                $video_total_time = totalTimeToText(calculateTotalTime($video_length_sec), true);
                $watched_video = $video->watched;
                $in_progress_video = $video->in_progress;

                $text_watch_status = ($watched_video) ? $text_watched : $text_progress;
                $color_status_class = ($watched_video) ? 'green' : 'light';

        @endphp

            <div class="contains-video">
                <a href="{{$video_url}}" class="video">

                    <figure class="video-img inline-bg-image" style="background-image: url({{$video_img_url}});"></figure>
                    <div class="h5 video-title">{{$video_title}}</div>
                    <div class="video-details">
                        <span class="category">{{getVideoText(1)}}</span>
                        <?php if ($video_length_sec > 0) : ?>
                        <span class="bullet">&bull;</span>
                        <span class="video-length">{{$video_total_time}}</span>
                        <?php endif; ?>
                        <?php if ($watched_video || $in_progress_video): ?>
                        <span class="bullet">&bull;</span>
                        <span class="txt-{{$color_status_class}}">{{$text_watch_status}}</span>
                        <?php endif; ?>
                    </div>
                </a>
            </div>

        @php
            endforeach;
        @endphp

    </div>
</div>

@php if ( $instructor_link ): @endphp
	<a class="btn box blue btn-icon hide-m" href="<?php echo $instructor_link['url']; ?>" target="<?php echo $instructor_link['target']; ?>">
        <span class="full-arrow is-top-right white"></span>
        <span class="text"><?php echo $instructor_link['title']; ?></span>
        <?php $instructor_icon = get_field( 'instructor_icon', 'option' ); ?>
        <?php if ( $instructor_icon ) { ?>
        <img class="instructor-ico" src="<?php echo $instructor_icon['url']; ?>" alt="<?php echo $instructor_icon['alt']; ?>" />
        <?php } ?>
    </a>
	<a class="btn box blue small hide-d" href="<?php echo $instructor_link['url']; ?>" target="<?php echo $instructor_link['target']; ?>">
        <span class="full-arrow is-top-right white"></span>
        <span class="text"><?php echo $instructor_link['title']; ?></span>
    </a>
@php endif; @endphp

<a class="btn box white small hide-m" href="{{$parent_url}}">
    <span class="full-arrow is-left dark"></span>
    <span class="text">{{$text_go_back}}</span>
</a>