<div class="profile__tab-content">
  <section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php foreach ($posts as $post) :; ?>
      <article class="profile__post post post-photo">
        <?php if ($post['creator'] === $_SESSION['id']) : ?>
          <header class="post__header">
            <h2><?= htmlspecialchars($post['header']); ?></h2>
          </header>
        <?php else : ?>
          <header class="post__header">
            <div class="post__author">
              <a class="post__author-link" href="profile.php?user=<?=$post['creator'];?>" title="Автор">
                <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                  <img class="post__author-avatar" src="<?= htmlspecialchars($post['avatar']); ?>" alt="Аватар пользователя">
                </div>
                <div class="post__info">
                  <b class="post__author-name">Репост: <?= htmlspecialchars($post['username']); ?></b>
                  <time class="post__time" datetime="<?=$post['dt_add']; ?>"><?= convert_date_toeasy_form($post['dt_add']); ?> назад</time>
                </div>
              </a>
            </div>
          </header>
        <?php endif; ?>
        <div class="post__main">
          <?php if ($post['type'] === 'photo') :; ?>
            <div class="post-photo__image-wrapper">
              <img src="<?= htmlspecialchars($post['photo_content']); ?>" alt="Фото от пользователя" width="760" height="396">
            </div>
          <?php elseif ($post['type'] === 'text') :; ?>
            <p>
              <?= htmlspecialchars($post['text_content']); ?>
            </p>
          <?php elseif ($post['type'] === 'quote') :; ?>
            <blockquote>
              <p>
                <?= htmlspecialchars($post['text_content']); ?>
              </p>
              <cite><?= $post['quote_author']; ?></cite>
            </blockquote>
          <?php elseif ($post['type'] === 'video') :; ?>
            <div class="post-details__image-wrapper post-photo__image-wrapper">
              <?= embed_youtube_video($post['video_content']); ?>
            </div>
          <?php elseif ($post['type'] === 'link') :; ?>
            <div class="post-link__wrapper">
              <a class="post-link__external" href="<?= htmlspecialchars($post['link_content']); ?>" title="Перейти по ссылке">
                <div class="post-link__icon-wrapper">
                  <img src="img/logo-vita.jpg" alt="Иконка">
                </div>
                <div class="post-link__info">
                  <h3><?= htmlspecialchars($post['header']); ?></h3>
                  <span><?= $post['link_content']; ?></span>
                </div>
                <svg class="post-link__arrow" width="11" height="16">
                  <use xlink:href="#icon-arrow-right-ad"></use>
                </svg>
              </a>
            </div>
          <?php endif; ?>
        </div>
        <footer class="post__footer">
          <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="likes.php?post_id=<?= $post['id']; ?>" title="Лайк">
                <svg class="post__indicator-icon" width="20" height="17">
                  <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                  <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?= $post['likes_count'] ?></span>
                <span class="visually-hidden">количество лайков</span>
              </a>
              <a class="post__indicator post__indicator--comments button" href="post.php?id=<?= $post['id']; ?>#comments" title="Комментарии">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-comment"></use>
                </svg>
                <span><?= $post['comments_count'] ?></span>
                <span class="visually-hidden">количество комментариев</span>
              </a>
              <a class="post__indicator post__indicator--repost button" href="repost.php?post_id=<?= $post['id']; ?>" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span><?= $post['reposts_count'] ?></span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <time class="post__time" datetime="<?= $post['dt_add']; ?>"><?= convert_date_toeasy_form($post['dt_add']); ?> назад</time>
          </div>
          <ul class="post__tags">
            <?php foreach (get_hashtags($con, $post['id']) as $hashtag) :; ?>
              <li><a href="search.php?search=<?=str_replace('#', '%23', $hashtag['name']); ?>"><?= htmlspecialchars($hashtag['name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </footer>
      </article>
    <?php endforeach; ?>
  </section>
</div>