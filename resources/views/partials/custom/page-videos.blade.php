@php
                
    global $post;
    // Get current page data
    $page_data = get_video_data($post);

    $page_id = $page_data->id;
    $page_title = $page_data->title;
    $page_desc = $page_data->desc;

    // Get video related data
    $video_code = $page_data->code;
    $video_img_url = $page_data->img_url;
    $video_len_min = $page_data->length->min;
    $video_len_sec = $page_data->length->sec;
    $watched_video = $page_data->watched;
    $in_progress_video = $page_data->in_progress;

    // Get global text content
    if ( have_rows( 'text', 'option' ) ):
        while ( have_rows( 'text', 'option' ) ) : the_row();

            $text_prev_video = get_sub_field( 'prev_video' );
            $text_next_video = get_sub_field( 'next_video' );
            $text_prev_chapter = get_sub_field( 'prev_chapter' );
            $text_next_chapter = get_sub_field( 'next_chapter' );
            $text_watched = get_sub_field( 'watched' );
            $text_progress = get_sub_field( 'in_progress' );
        
        endwhile;
    endif;

    $text_watch_status = ($watched_video) ? $text_watched : $text_progress;
    $color_status_class = ($watched_video) ? 'green' : 'light';

    // Get parent chapter object
    $chapter = get_post($post->post_parent);

    // Get current chapter data
    $chapter_data = get_chapter_data($chapter);

    // Get parent topic object
    $topic = get_post($chapter->post_parent);

    // Get all parent topic chapters
    $topic_chapters = get_topic_data($topic)->chapters;

    $chapter_counter = 0;
    $prev_video_url = $next_video_url = null;
    $prev_btn_title = $next_btn_title = null;
    foreach ($topic_chapters as $topic_chapter) {

        $video_counter = 0;
        foreach ($topic_chapter->videos as $video) {
            
            // Current video
            if ($page_id == $video->id) {

                if ($video_counter > 0) {

                    // Get previous video URL
                    $prev_video_url = $chapter_data->videos[$video_counter - 1]->url;
                    $prev_btn_title = $text_prev_video;

                } else {

                    if ($chapter_counter > 0) {

                        // Get previous chapter first video URL
                        $prev_video_url = $topic_chapters[$chapter_counter - 1]->videos[0]->url;
                        $prev_btn_title = $text_prev_chapter;
                    }
                }

                if (isset($chapter_data->videos[$video_counter + 1])) {
                    
                    // Get next video URL
                    $next_video_url = $chapter_data->videos[$video_counter + 1]->url;
                    $next_btn_title = $text_next_video;

                } else {

                    if (isset($topic_chapters[$chapter_counter + 1])) {

                        // Get next chapter first video URL
                        $next_video_url = $topic_chapters[$chapter_counter + 1]->videos[0]->url;
                        $next_btn_title = $text_next_chapter;
                    }
                }
            }
            
            $video_counter++;
        }

        $chapter_counter++;
    }

    // Get instructor global link
    $instructor_link = get_field( 'instructor_link', 'option' );

@endphp

<div class="left-content">

    <div class="hide-d">
        <h1 class="h1 light">{{$page_title}}</h1>

        <div class="page-details">
            <span class="page-cat">{{getVideoText(1)}}</span>
            <?php if ($video_len_min > 0 || $video_len_sec > 0) : ?>
            <span class="bullet">&bull;</span>
            <span class="time-length">{{$video_len_min}}min {{$video_len_sec}}s</span>
            <?php endif; ?>
            <?php if ($watched_video || $in_progress_video): ?>
            <span class="bullet">&bull;</span>
            <span class="txt-{{$color_status_class}}">{{$text_watch_status}}</span>
            <?php endif; ?>
        </div>
    </div>

    <div class="video-box">
        <iframe id="player" video-code="{{$video_code}}" src="https://www.youtube.com/embed/{{$video_code}}?enablejsapi=1" frameborder="0" class="yt-video" allowfullscreen></iframe>
    </div>

    <div class="hide-m">
        <h1 class="h1 light">{{$page_title}}</h1>

        <div class="page-details">
            <span class="page-cat">{{getVideoText(1)}}</span>
            <?php if ($video_len_min > 0 || $video_len_sec > 0) : ?>
            <span class="bullet">&bull;</span>
            <span class="time-length">{{$video_len_min}}min {{$video_len_sec}}s</span>
            <?php endif; ?>
            <?php if ($watched_video || $in_progress_video): ?>
            <span class="bullet">&bull;</span>
            <span class="txt-{{$color_status_class}}">{{$text_watch_status}}</span>
            <?php endif; ?>
        </div>
    </div>

    @php if ( $instructor_link ): @endphp
    <div class="btn-wrap">
        <a class="btn box btn-icon blue astra-ai" href="https://astra.si/ai">
            <div class="beta">@include('icons.beta')</div>
            <div class="bg-icon"></div>
            <span class="full-arrow is-top-right white"></span>
            <div class="inner-div">
                <img class="instructor-ico" src="https://astra.si/wp-content/uploads/2023/07/Asset-1-4.png" alt="" />
                <span class="text">Osebni inštruktor na voljo 24/7</span>
            </div>
        </a>
        <a class="btn box dark horizont btn-icon hide-m" href="<?php echo $instructor_link['url']; ?>" target="<?php echo $instructor_link['target']; ?>">
            <span class="full-arrow is-top-right white"></span>
            <?php $instructor_icon = get_field( 'instructor_icon', 'option' ); ?>
            <?php if ( $instructor_icon ) { ?>
            <img class="instructor-ico" src="<?php echo $instructor_icon['url']; ?>" alt="<?php echo $instructor_icon['alt']; ?>" />
            <?php } ?>
        </a>
    </div>
        <a class="btn box blue small hide-d" href="<?php echo $instructor_link['url']; ?>" target="<?php echo $instructor_link['target']; ?>">
            <span class="full-arrow is-top-right white"></span>
            <span class="text"><?php echo $instructor_link['title']; ?></span>
        </a>
    @php endif; @endphp

    <?php if ( have_rows( 'useful_links_menu', 'option' ) ) : ?>
        <?php while ( have_rows( 'useful_links_menu', 'option' ) ) : the_row(); ?>
            
            <div class="links-dropdown">
                <button type="button" class="btn hollow blue dark-fill dropdown-btn"><?php the_sub_field( 'label' ); ?></button>
                
                <div class="links-dropdown-menu">
                <?php if ( have_rows( 'links' ) ) : ?>
                    <?php while ( have_rows( 'links' ) ) : the_row(); ?>
                        <?php $link = get_sub_field( 'link' ); ?>
                        <?php if ( $link ) { ?>
                            <a href="<?php echo $link['url']; ?>" class="dd-link" target="<?php echo $link['target']; ?>">
                                <div class="link-title"><?php echo $link['title']; ?></div>
                                <p class="para"><?php the_sub_field( 'link_info' ); ?></p>
                                <span class="full-arrow is-top-right blue small"></span>
                            </a>
                        <?php } ?>
                    <?php endwhile; ?>
                <?php endif; ?>
                </div>

            </div>
            
        <?php endwhile; ?>
    <?php endif; ?>

    {{-- Copy video URL button --}}

    <div class="next-prev-btns">

        @php if (isset($prev_video_url)): @endphp
            <a class="btn box hollow white dark-fill small prev" href="{{$prev_video_url}}">
                <span class="full-arrow is-left white"></span>
                <span class="text">{{$prev_btn_title}}</span>
            </a>
        @php endif; @endphp

        @php if (isset($next_video_url)): @endphp
            <a class="btn box hollow yellow dark-fill small next" href="{{$next_video_url}}">
                <span class="full-arrow is-right yellow"></span>
                <span class="text">{{$next_btn_title}}</span>
            </a>
        @php endif; @endphp

    </div>

</div>

<div class="right-content">

    @php
        foreach ($topic_chapters as $chapter):

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

            $current_chapter = ($chapter_data->id == $chapter->id) ? 'selected' : '';
            $watched_chapter = ($percent_watched == 100) ? 'watched' : '';
    @endphp

    <div class="chapter {{$current_chapter}}">

        <div class="chapter-header {{$watched_chapter}}">
            <div class="pull-left">
                <h5 class="h5 chapter-title">{{$chapter_title}}</h5>
                <div class="chapter-details">
                    <span class="video-count">{{$videos_count}} {{getVideoText($videos_count)}}</span>
                    <?php if ($chapter->total_seconds > 0) : ?>
                    <span class="bullet">&bull;</span>
                    <span class="chapter-length">{{$chap_total_time}}</span>
                    <?php endif; ?>
                    <?php if ($watched_amount > 0) : ?>
                    <span class="bullet">&bull;</span>
                    <span class="watched-percent txt-green">{{$percent_watched}}%</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="chapter-videos dropdown-content">

            @php
                foreach ($chapter_videos as $video):

                    // Get/set video data
                    $video_url = $video->url;
                    $video_title = $video->title;
                    $video_length_sec = ($video->length->min * 60) + $video->length->sec;
                    // Calculate total time
                    $video_total_time = totalTimeToText(calculateTotalTime($video_length_sec), true);
                    $_watched_video = $video->watched;

                    $current_video = ($page_id == $video->id) ? 'selected' : '';
                    $watched_state = ($_watched_video) ? 'watched' : '';

            @endphp

                <a href="{{$video_url}}" class="video {{$current_video}} {{$watched_state}}">
                    <div class="play-button smaller blue"></div>
                    <div class="h5 video-title">{{$video_title}}</div>
                    <?php if ($video_length_sec > 0) : ?>
                    <span class="video-length">{{$video_total_time}}</span>
                    <?php endif; ?>
                </a>

            @php
                endforeach;
            @endphp

        </div>
    </div>

    @php
        endforeach;
    @endphp

</div>

<script>

function toggleDropdown(elementClass, className) {

    // Get all flip containers
    var cards = document.getElementsByClassName(elementClass);

    for (var i = 0; i < cards.length; i++) {

        // Click listener
        cards[i].addEventListener('click', function() {

            // Toggle active class
            this.parentElement.classList.toggle(className);
            var cardBody = this.parentElement.querySelector('.dropdown-content');

            // Open card & set maxHeight accordingly
            if (cardBody.style.maxHeight) {

                cardBody.style.maxHeight = null;
            } else {

                // Enable transition on maxHeight from CSS
                cardBody.style.maxHeight = cardBody.scrollHeight + 'px';
            }

            // Remove active class from rest of cards
            for (var i = 0; i < cards.length; i++) {

                // Card should not be the current clicked and should
                // contain a active class
                if (this.parentElement != cards[i].parentElement && cards[i].parentElement.classList.contains(className)) {
                    // Remove active class
                    cards[i].parentElement.classList.remove(className);
                    // Close card
                    cards[i].parentElement.querySelector('.dropdown-content').style.maxHeight = null;
                }
            }
        });
    }
}

window.addEventListener('load', function () {
    
    toggleDropdown('chapter-header', 'selected');

    // Set first dropdown content height
    var chapter = document.querySelector('.chapter.selected');
    var chapter_videos = chapter.querySelector('.dropdown-content');
    chapter_videos.style.maxHeight = chapter_videos.scrollHeight + 'px';

    // Open dropdown menu
    document.querySelector('.dropdown-btn').addEventListener('click', function() {
        document.querySelector(".links-dropdown").classList.toggle("opened");
    });

    // Close dropdown menu
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-btn')) {
            var dropdown = document.querySelector(".links-dropdown");
            if (dropdown.classList.contains('opened')) {
                dropdown.classList.remove('opened');
            }
        }
    }
});

</script>

{{-- @include('partials/custom/video-states-script') --}}