<?php
/**
 * The template for displaying singular post-types: posts, pages and user-defined custom post types.
 *
 * @package CreatorMode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

while ( have_posts() ) :
	the_post();
	?>

<main id="content" <?php post_class( 'site-main' ); ?>>

	<?php if ( apply_filters( 'vonsheezy_elementor_page_title', true ) ) : ?>
		<header class="page-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="featured-image">

                    <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
                        <?php the_post_thumbnail('full'); ?>
                    </a>
                    <div class="post-meta">
                        <?php _e('Posted on', 'creator-mode'); ?>
                        <span class="posted-on"> <?php the_date(); ?> </span>
                        <?php _e('in', 'creator-mode'); ?>
                        <span class="post-categories"> <?php the_category(', '); ?></span>
                        by <span class="author"> <?php the_author() ?></span>
                    </div>

                </div>
            <?php endif; ?>
		</header>
	<?php endif; ?>

	<div class="page-content">
		<?php the_content(); ?>
		<div class="post-tags">
			<?php the_tags( '<span class="tag-links">' . esc_html__( 'Tagged ', 'creator-mode' ), ', ', '</span>' ); ?>
		</div>
		<?php wp_link_pages(); ?>
	</div>

	<?php comments_template(); ?>

</main>

	<?php
endwhile;
