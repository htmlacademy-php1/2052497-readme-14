<main class="page__main page__main--search-results">
  <h1 class="visually-hidden">Страница результатов поиска</h1>
  <section class="search">
    <h2 class="visually-hidden">Результаты поиска</h2>
    <div class="search__query-wrapper">
      <div class="search__query container">
        <span>Вы искали:</span>
        <span class="search__query-text"><?= htmlspecialchars($search); ?></span>
      </div>
    </div>
    <div class="search__results-wrapper">
      <div class="container">
        <div class="search__content">
          <?php foreach ($posts as $post) :; ?>
            <article class="feed__post post post-photo">
              <header class="post__header post__author">
                <a class="post__author-link" href="profile.php?user=<?= htmlspecialchars($post['user_id']); ?>" title="Автор">
                  <div class="post__avatar-wrapper">
                    <img class="user__picture" src="<?= htmlspecialchars($post['avatar']); ?>" alt="Аватар пользователя" width="60" height="60">
                  </div>
                  <div class="post__info">
                    <b class="post__author-name"><?= htmlspecialchars($post['username']); ?></b>
                    <span class="post__time"><?= convert_date_toeasy_form($post['dt_add']); ?> назад</span>
                  </div>
                </a>
              </header>
              <?php if ($post['type'] === 'photo') :; ?>
                <div class="post__main">
                  <h2><a href="post.php?id=<?=$post['id'];?>"><?= htmlspecialchars($post['header']); ?></a></h2>
                  <div class="post-photo__image-wrapper">
                    <img src="<?= htmlspecialchars($post['photo_content']); ?>" alt="Фото от пользователя" width="760" height="396">
                  </div>
                </div>
              <?php elseif ($post['type'] === 'text') :; ?>
                <div class="post__main">
                  <h2><a href="post.php?id=<?=$post['id'];?>"><?= htmlspecialchars($post['header']); ?></a></h2>
                  <p>
                    <?= htmlspecialchars($post['text_content']); ?>
                  </p>
                  <a class="post-text__more-link" href="#">Читать далее</a>
                </div>
              <?php elseif ($post['type'] === 'quote') :; ?>
                <div class="post__main">
                <h2><a href="post.php?id=<?=$post['id'];?>"><?= htmlspecialchars($post['header']); ?></a></h2>
                  <blockquote>
                    <p>
                      <?= htmlspecialchars($post['text_content']); ?>
                    </p>
                    <cite><?= $post['quote_author']; ?></cite>
                  </blockquote>
                </div>
              <?php elseif ($post['type'] === 'video') :; ?>
                <div class="post__main">
                <h2><a href="post.php?id=<?=$post['id'];?>"><?= htmlspecialchars($post['header']); ?></a></h2>
                  <div class="post-video__block">
                    <div class="post-video__preview">
                      <?= embed_youtube_video($post['video_content']); ?>
                    </div>
                  </div>
                </div>
              <?php elseif ($post['type'] === 'link') :; ?>
                <div class="post__main">
                  <div class="post-link__wrapper">
                    <a class="post-link__external" href="<?=strip_tags($post['link_content']); ?>" title="Перейти по ссылке">
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
                </div>
              <?php endif; ?>
              <footer class="post__footer post__indicators">
                <div class="post__buttons">
                  <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                    <svg class="post__indicator-icon" width="20" height="17">
                      <use xlink:href="#icon-heart"></use>
                    </svg>
                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                      <use xlink:href="#icon-heart-active"></use>
                    </svg>
                    <span><?= $post['likes_count'] ?></span>
                    <span class="visually-hidden">количество лайков</span>
                  </a>
                  <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                    <svg class="post__indicator-icon" width="19" height="17">
                      <use xlink:href="#icon-comment"></use>
                    </svg>
                    <span><?= $post['comments_count'] ?></span>
                    <span class="visually-hidden">количество комментариев</span>
                  </a>
                  <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                    <svg class="post__indicator-icon" width="19" height="17">
                      <use xlink:href="#icon-repost"></use>
                    </svg>
                    <span>5</span>
                    <span class="visually-hidden">количество репостов</span>
                  </a>
                </div>
              </footer>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
</main>