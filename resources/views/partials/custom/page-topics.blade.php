@php
                
    global $post;
    // Get current page data
    $page_data = get_topic_data($post);

    $page_title = $page_data->title;
    $page_desc = $page_data->desc;
    $chapters = $page_data->chapters;
    $chapters_count = count($chapters);

    // Get amount watched
    $videos_count = $page_data->videos_count; // All videos in this topic
    $total_watched = $page_data->total_watched;
    $percent_watched = round(($total_watched * 100) / $videos_count);

    // Calculate total time
    $total_time = totalTimeToText(calculateTotalTime($page_data->total_seconds));

    // Get global text content
    if ( have_rows( 'text', 'option' ) ):
        while ( have_rows( 'text', 'option' ) ) : the_row();

            $text_topic = get_sub_field( 'topic' );
            $text_chapters = get_sub_field( 'chapters' );
            $text_see_details = get_sub_field( 'see_details' );
            $text_watched = get_sub_field( 'watched' );
        
        endwhile;
    endif;

    // Get instructor global link
    $instructor_link = get_field( 'instructor_link', 'option' );

@endphp

<h1 class="h1 light">{{$page_title}}</h1>

<div class="page-details">
    <span class="page-cat">{{$text_topic}}</span>
    <span class="bullet">&bull;</span>
    <span class="content-count">{{$chapters_count}} {{$text_chapters}}</span>
    <?php if ($page_data->total_seconds > 0) : ?>
    <span class="bullet">&bull;</span>
    <span class="time-length">{{$total_time}}</span>
    <?php endif; ?>
    <?php if ($total_watched > 0) : ?>
    <span class="bullet">&bull;</span>
    <span class="watched-percent txt-green">{{$percent_watched}}% {{$text_watched}}</span>
    <?php endif; ?>
</div>

<p class="para opac light">{{$page_desc}}</p>

@php if ( $instructor_link ): @endphp
	<a class="btn box blue small" href="<?php echo $instructor_link['url']; ?>" target="<?php echo $instructor_link['target']; ?>">
        <span class="full-arrow is-top-right white"></span>
        <span class="text"><?php echo $instructor_link['title']; ?></span>
    </a>
@php endif; @endphp

<div class="chapters grid">

    @php
        foreach ($chapters as $chapter):

            // Get/set chapter data
            $chapter_url = $chapter->url;
            $chapter_title = $chapter->title;
            $chapter_videos = $chapter->videos;
            $videos_count = count($chapter_videos);
            // Calculate total time
            $chap_total_time = totalTimeToText(calculateTotalTime($chapter->total_seconds));

            // Get amount watched
            $watched_amount = $chapter->watched_amount;
            $percent_watched = round(($watched_amount * 100) / $videos_count);

    @endphp

    <div class="chapter grid-item">

        <div class="chapter-header clearfix">
            <div class="pull-left header-content">
                <a href="{{$chapter_url}}" class="h5 chapter-title">{{$chapter_title}}</a>
                <div class="chapter-details">
                    <span class="video-count">{{$videos_count}} {{getVideoText($videos_count)}}</span>
                    <?php if ($chapter->total_seconds > 0) : ?>
                    <span class="bullet">&bull;</span>
                    <span class="chapter-length">{{$chap_total_time}}</span>
                    <?php endif; ?>
                </div>
            </div>
            <a href="{{$chapter_url}}" class="btn hollow blue pull-right hide-m">{{$text_see_details}}</a>
        </div>

        <div class="chapter-videos">

            @php
                foreach ($chapter_videos as $video):

                    // Get/set video data
                    $video_url = $video->url;
                    $video_title = $video->title;
                    $video_length_sec = ($video->length->min * 60) + $video->length->sec;
                    // Calculate total time
                    $video_total_time = totalTimeToText(calculateTotalTime($video_length_sec), true);

                    // Get/set watched state
                    $watched_video = $video->watched;
                    $watched_state = ($watched_video) ? 'watched-video' : '';

            @endphp

                <a href="{{$video_url}}" class="video {{$watched_state}}">
                    <div class="h5 video-title">{{$video_title}}</div>
                    <?php if ($video_length_sec > 0) : ?>
                    <span class="video-length">{{$video_total_time}}</span>
                    <?php endif; ?>
                    <div class="play-button small blue"></div>
                    <?php if ($watched_video) : ?><span class="checkmark"></span><?php endif; ?>
                </a>

            @php
                endforeach;
            @endphp

        </div>

        <a href="{{$chapter_url}}" class="btn hollow blue hide-d">{{$text_see_details}}</a>

    </div>

    @php
        endforeach;
    @endphp

</div>

<script>

    window.addEventListener('load', function () {

        var msnry = new Masonry( '.grid', {
            // Options
            itemSelector: '.grid-item',
            columnWidth: 518,
            gutter: 30,
        });

    });

</script>