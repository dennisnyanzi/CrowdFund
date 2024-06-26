<?php
    $post_img = null;
    $blog_image = get_attachment_image_by_id($blog_post->image,"full",false);
    $post_img = !empty($blog_image) ? $blog_image['img_url'] : '';
?>

<?php $__env->startSection('style'); ?>
    <?php if(!empty(get_static_option('site_disqus_key'))): ?>
        <script id="dsq-count-scr" src="//<?php echo e(get_static_option('site_disqus_key')); ?>.disqus.com/count.js" async></script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('og-meta'); ?>
    <meta name="og:title" content="<?php echo e(purify_html($blog_post->og_meta_title)); ?>">
    <meta name="og:description" content="<?php echo e(purify_html($blog_post->og_meta_description)); ?>">
    <?php echo render_og_meta_image_by_attachment_id($blog_post->og_meta_image); ?>

    <meta name="og:tags" content=" <?php echo e(purify_html($blog_post->meta_tags)); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-meta-data'); ?>
    <meta name="description" content="<?php echo e(purify_html($blog_post->meta_description)); ?>">
    <meta name="tags" content="<?php echo e(purify_html($blog_post->meta_tag)); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('site-title'); ?>
    <?php echo e(purify_html($blog_post->title)); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-title'); ?>
    <?php echo e(purify_html($blog_post->title)); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <section class="blog-details-content-area padding-top-100 padding-bottom-60">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-details-item">
                        <div class="thumb">
                            <?php if(!empty($blog_image)): ?>
                                <img src="<?php echo e($blog_image['img_url']); ?>" alt="<?php echo e(purify_html($blog_post->title)); ?>">
                            <?php endif; ?>
                        </div>
                        <div class="entry-content">
                            <ul class="post-meta">
                                <li><i class="fas fa-calendar-alt"></i> <?php echo e(date_format($blog_post->created_at,'d M Y')); ?>

                                </li>
                                <li><i class="fas fa-user"></i> <?php echo e(purify_html($blog_post->author)); ?></li>
                                <li>
                                    <div class="cats">
                                        <i class="fas fa-folder"></i>
                                        <?php echo purify_html_raw(get_blog_category_by_id($blog_post->blog_categories_id,'link')); ?>

                                    </div>
                                </li>
                            </ul>
                            <div class="content-area mt-4">
                                <p>  <?php echo $blog_post->blog_content; ?></p>
                            </div>
                        </div>
                       <div class="blog-details-footer">
                           <?php
                               $all_tags = explode(',',purify_html($blog_post->tags));
                           ?>
                           <?php if(count($all_tags) > 1): ?>
                               <div class="left">
                                   <ul class="tags">
                                       <li class="title"><?php echo e(get_static_option('blog_single_page_tags_title')); ?></li>
                                       <?php $__currentLoopData = $all_tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           <?php
                                               $slug = Str::slug($tag);
                                           ?>
                                           <?php if(!empty($slug)): ?>
                                               <li>
                                                   <a href="<?php echo e(route('frontend.blog.tags.page',['name' => $slug])); ?>"><?php echo e($tag); ?></a>
                                               </li>
                                           <?php endif; ?>
                                       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                   </ul>
                               </div>
                           <?php endif; ?>
                           <div class="right">
                               <ul class="social-share">
                                   <li class="title"><?php echo e(get_static_option('blog_single_page_share_title')); ?></li>
                                   <?php echo single_post_share(route('frontend.blog.single',purify_html($blog_post->slug)),purify_html($blog_post->title),$post_img); ?>

                               </ul>
                           </div>
                       </div>
                   </div>
                        <?php if(count($all_related_blog) > 1): ?>
                            <div class="related-post-area margin-top-40">
                                <div class="section-title ">
                                    <h4 class="title "><?php echo e(get_static_option('blog_single_page_related_post_title')); ?></h4>
                                </div>
                                <div class="related-news-carousel global-carousel-init"
                                data-desktopitem="2"
                                data-mobileitem="1"
                                data-tabletitem="1"
                                data-margin="30"
                                data-dots="true"
                                >
                                    <?php $__currentLoopData = $all_related_blog; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($data->id === $blog_post->id): ?> <?php continue; ?> <?php endif; ?>
                                        <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.frontend.blog.grid01','data' => ['image' => $data->image,'date' => $data->created_at,'author' => $data->author,'catid' => $data->blog_categories_id,'slug' => $data->slug,'title' => $data->title]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('frontend.blog.grid01'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['image' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->image),'date' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->created_at),'author' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->author),'catid' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->blog_categories_id),'slug' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->slug),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data->title)]); ?>
                                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="disqus-comment-area margin-top-40">
                            <div id="disqus_thread"></div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="widget-area">
                            <?php echo render_frontend_sidebar('blog',['column' => false]); ?>

                        </div>
                    </div>
                </div>
            </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <?php if(!empty(get_static_option('site_disqus_key'))): ?>
        <div id="disqus_thread"></div>
        <script>
            (function() { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');
                s.src = 'https://<?php echo e(get_static_option('site_disqus_key')); ?>.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        </script>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.frontend-page-master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\fundorex-v1.4.0\@core\resources\views/frontend/pages/blog/blog-single.blade.php ENDPATH**/ ?>