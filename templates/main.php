<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link <?= $get_order === 'view' ? 'sorting__link--active' : ''; ?>" href="?<?= isset($get_type_id) ? "type=" . $get_type_id . "&" : ""; ?>order=view">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link <?= $get_order === 'likes' ? 'sorting__link--active' : ''; ?>" href="?<?= isset($get_type_id) ? "type=" . $get_type_id . "&" : ""; ?>order=likes">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link <?= $get_order === 'date' ? 'sorting__link--active' : ''; ?>" href="?<?= isset($get_type_id) ? "type=" . $get_type_id . "&" : ""; ?>order=date">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all <?= empty($get_type_id) ? "filters__button--active" : ""; ?>" href="?">
                            <span>Все</span>
                        </a>
                    </li>
                    <?php foreach ($types as $type) : ?>
                        <li class="popular__filters-item filters__item">
                            <a class="filters__button filters__button--photo button <?= $type['id'] === $get_type_id ? "filters__button--active" : ""; ?>" href="?type=<?= $type['id']; ?>">
                                <span class="visually-hidden"><?= $type['name']; ?></span>
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-<?= $type['type']; ?>"></use>
                                </svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach ($posts as $post) : ?>
                <article class="popular__post post post-<?= htmlspecialchars($post['type']); ?>">
                    <header class="post__header">
                        <h2><a href="post.php?id=<?= $post['id']; ?>"><?= htmlspecialchars($post['header']); ?></a></h2>
                    </header>
                    <div class="post__main">
                        <?php if (htmlspecialchars($post['type']) === 'quote') : ?>
                            <blockquote>
                                <p>
                                    <?= htmlspecialchars($post['text_content']); ?>
                                </p>
                                <cite><?= htmlspecialchars($post['quote_author']); ?></cite>
                            </blockquote>
                        <?php elseif (htmlspecialchars($post['type']) === 'text') : ?>
                            <?= limit_string_lenght(htmlspecialchars($post['text_content'])) ?>
                        <?php elseif (htmlspecialchars($post['type']) === 'photo') : ?>
                            <div class="post-photo__image-wrapper">
                                <img src="<?= htmlspecialchars($post['photo_content']); ?>" alt="Фото от пользователя" width="360" height="240">
                            </div>
                        <?php elseif (htmlspecialchars($post['type']) === 'link') : ?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="http://<?= strip_tags($post['link_content']); ?>" title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= htmlspecialchars($post['header']); ?></h3>
                                        </div>
                                    </div>
                                    <span><?= strip_tags($post['link_content']); ?></span>
                                </a>
                            </div>
                        <?php elseif (htmlspecialchars($post['type']) === 'video') : ?>
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?= embed_youtube_cover(htmlspecialchars($post['video_content'])); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <footer class="post__footer">
                        <div class="post__author">
                            <a class="post__author-link" href="profile.php?user=<?= $post['user_id']; ?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <?=isset($post['avatar']) ? '<img class="post__author-avatar" src="' . htmlspecialchars($post['avatar']) .'" alt="Аватар профиля">' : ''; ?>
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?= htmlspecialchars($post['username']); ?></b>
                                    <time class="post__time" datetime="<?= $post['dt_add']; ?>" title="<?= date("d.m.Y H:i", strtotime($post['dt_add'])); ?>"><?= convert_date_toeasy_form($post['dt_add']); ?> назад</time>
                                </div>
                            </a>
                        </div>
                        <div class="post__indicators">
                            <div class="post__buttons">
                                <a class="post__indicator post__indicator--likes button" href="likes.php?post_id=<?= $post['id']; ?>" title="Лайк">
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span><?= $post['likes_count']; ?></span>
                                    <span class="visually-hidden">количество лайков</span>
                                </a>
                                <a class="post__indicator post__indicator--comments button" href="post.php?id=<?= $post['id']; ?>#comments" title="Комментарии">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-comment"></use>
                                    </svg>
                                    <span><?= $post['comments_count']; ?></span>
                                    <span class="visually-hidden">количество комментариев</span>
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="popular__page-links">
            <?php if ($page > 1):?>
            <a class="popular__page-link popular__page-link--prev button button--gray" href="?page=<?=$page - 1;?>&type=<?=$get_type_id;?>&order=<?=$get_order;?>" >Предыдущая страница</a>
            <?php elseif ($page - 1 < $count_page):?>
            <a class="popular__page-link popular__page-link--next button button--gray" href="?page=<?=$page + 1;?>&type=<?=$get_type_id;?>&order=<?=$get_order;?>">Следующая страница</a>
            <?php endif;?>
        </div>
    </div>
</section>