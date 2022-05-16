<div class="profile__tab-content">
  <section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php foreach ($posts as $post) :; ?>
      <article class="profile__post post post-photo">
        <header class="post__header">
          <h2><a href="post.php?id=<?=$post['id'];?>"><?= htmlspecialchars($post['header']); ?></a></h2>
        </header>
        <?php if ($post['type'] === 'photo') :; ?>
          <div class="post__main">
            <div class="post-photo__image-wrapper">
              <img src="<?= htmlspecialchars($post['photo_content']); ?>" alt="Фото от пользователя" width="760" height="396">
            </div>
          </div>
        <?php elseif ($post['type'] === 'text') :; ?>
          <div class="post__main">
            <p>
              <?= $post['text_content']; ?>
            </p>
            <a class="post-text__more-link" href="#">Читать далее</a>
          </div>
        <?php elseif ($post['type'] === 'quote') :; ?>
          <div class="post-details__image-wrapper post-quote">
            <div class="post__main">
              <blockquote>
                <p>
                  <?= htmlspecialchars($post['text_content']); ?>
                </p>
                <cite><?= htmlspecialchars($post['quote_author']); ?></cite>
              </blockquote>
            </div>
          </div>
        <?php elseif ($post['type'] === 'video') :; ?>
          <div class="post-details__image-wrapper post-photo__image-wrapper">
            <?= embed_youtube_video($post['video_content']); ?>
          </div>
        <?php elseif ($post['type'] === 'link') :; ?>
          <div class="post__main">
            <div class="post-link__wrapper">
              <a class="post-link__external" href="<?= strip_tags($post['link_content']); ?>" title="Перейти по ссылке">
                <div class="post-link__icon-wrapper">
                  <img src="img/logo-vita.jpg" alt="Иконка">
                </div>
                <div class="post-link__info">
                  <h3><?= htmlspecialchars($post['header']); ?></h3>
                  <span><?= strip_tags($post['link_content']); ?></span>
                </div>
                <svg class="post-link__arrow" width="11" height="16">
                  <use xlink:href="#icon-arrow-right-ad"></use>
                </svg>
              </a>
            </div>
          </div>
        <?php endif; ?>
        <footer class="post__footer">
          <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="likes.php?post_id=<?=$post['id'];?>" title="Лайк">
                <svg class="post__indicator-icon" width="20" height="17">
                  <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                  <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?= $post['likes_count']; ?></span>
                <span class="visually-hidden">количество лайков</span>
              </a>
              <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span>5</span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <time class="post__time" datetime="<?=$post['dt_add'];?>"><?= convert_date_toeasy_form($post['dt_add']); ?> назад</time>
          </div>
          <ul class="post__tags">
            <?php foreach (get_hashtags($post['id'], $con) as $hashtag) :; ?>
              <li><a href="search.php?search=<?=$hashtag['name'];?>"><?= htmlspecialchars($hashtag['name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </footer>
      </article>
    <?php endforeach; ?>
  </section>
</div>