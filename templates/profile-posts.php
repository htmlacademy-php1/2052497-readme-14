<div class="profile__tab-content">
  <section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php foreach ($posts as $post) :; ?>
      <article class="profile__post post post-photo">
        <header class="post__header">
          <h2><a href="#"><?= htmlspecialchars($post['header']); ?></a></h2>
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
            <?= embed_youtube_cover($post['video_content']); ?>
          </div>
        <?php elseif ($post['type'] === 'link') :; ?>
          <div class="post__main">
            <div class="post-link__wrapper">
              <a class="post-link__external" href="http://www.vitadental.ru" title="Перейти по ссылке">
                <div class="post-link__icon-wrapper">
                  <img src="img/logo-vita.jpg" alt="Иконка">
                </div>
                <div class="post-link__info">
                  <h3><?= htmlspecialchars($post['header']); ?></h3>
                  <span><?= htmlspecialchars($post['link_content']); ?></span>
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
              <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
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
            <time class="post__time" datetime="2019-01-30T23:41"><?= convert_date_toeasy_form($post['dt_add']); ?> назад</time>
          </div>
          <ul class="post__tags">
            <?php foreach (get_hashtags($post['id'], $con) as $hashtag) :; ?>
              <li><a href="#"><?= htmlspecialchars($hashtag['name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </footer>
        <?php if (filter_input(INPUT_GET, 'comm') !== $post['id']) :; ?>
          <div class="comments">
            <a class="comments__button button" href="?user=<?= $profile['id']; ?>&comm=<?= $post['id']; ?>">Показать комментарии</a>
          </div>
        <?php elseif (filter_input(INPUT_GET, 'comm') === $post['id']) :; ?>
          <div class="comments">
            <div class="comments__list-wrapper">
              <ul class="comments__list">
                <?php foreach (get_comments($post['id'], $con) as $comment) :; ?>
                  <li class="comments__item user">
                    <div class="comments__avatar">
                      <a class="user__avatar-link" href="#">
                        <img class="comments__picture" src="<?= htmlspecialchars($comment['avatar']); ?>" alt="Аватар пользователя">
                      </a>
                    </div>
                    <div class="comments__info">
                      <div class="comments__name-wrapper">
                        <a class="comments__user-name" href="#">
                          <span><?= htmlspecialchars($comment['username']); ?></span>
                        </a>
                        <time class="comments__time" datetime="2019-03-20"><?= convert_date_toeasy_form($comment['dt_add']); ?> назад</time>
                      </div>
                      <p class="comments__text">
                        <?= htmlspecialchars($comment['content']); ?>
                      </p>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
              <?php if ($post['comments_count'] > 3) :; ?>
                <a class="comments__more-link" href="#">
                  <span>Показать все комментарии</span>
                  <sup class="comments__amount"><?= $comment['comments_count']; ?></sup>
                </a>
              <?php endif; ?>
            </div>
          </div>
          <form class="comments__form form" action="#" method="post">
            <div class="comments__my-avatar">
              <img class="comments__picture" src="img/userpic-medium.jpg" alt="Аватар пользователя">
            </div>
            <textarea class="comments__textarea form__textarea" placeholder="Ваш комментарий"></textarea>
            <label class="visually-hidden">Ваш комментарий</label>
            <button class="comments__submit button button--green" type="submit">Отправить</button>
          </form>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </section>

  <section class="profile__likes tabs__content">
    <h2 class="visually-hidden">Лайки</h2>
    <ul class="profile__likes-list">
      <li class="post-mini post-mini--photo post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <div class="post-mini__action">
              <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
              <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 минут назад</time>
            </div>
          </div>
        </div>
        <div class="post-mini__preview">
          <a class="post-mini__link" href="#" title="Перейти на публикацию">
            <div class="post-mini__image-wrapper">
              <img class="post-mini__image" src="img/rock-small.png" width="109" height="109" alt="Превью публикации">
            </div>
            <span class="visually-hidden">Фото</span>
          </a>
        </div>
      </li>
      <li class="post-mini post-mini--text post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <div class="post-mini__action">
              <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
              <time class="post-mini__time user__additional" datetime="2014-03-20T20:05">15 минут назад</time>
            </div>
          </div>
        </div>
        <div class="post-mini__preview">
          <a class="post-mini__link" href="#" title="Перейти на публикацию">
            <span class="visually-hidden">Текст</span>
            <svg class="post-mini__preview-icon" width="20" height="21">
              <use xlink:href="#icon-filter-text"></use>
            </svg>
          </a>
        </div>
      </li>
      <li class="post-mini post-mini--video post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <div class="post-mini__action">
              <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
              <time class="post-mini__time user__additional" datetime="2014-03-20T18:20">2 часа назад</time>
            </div>
          </div>
        </div>
        <div class="post-mini__preview">
          <a class="post-mini__link" href="#" title="Перейти на публикацию">
            <div class="post-mini__image-wrapper">
              <img class="post-mini__image" src="img/coast-small.png" width="109" height="109" alt="Превью публикации">
              <span class="post-mini__play-big">
                <svg class="post-mini__play-big-icon" width="12" height="13">
                  <use xlink:href="#icon-video-play-big"></use>
                </svg>
              </span>
            </div>
            <span class="visually-hidden">Видео</span>
          </a>
        </div>
      </li>
      <li class="post-mini post-mini--quote post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <div class="post-mini__action">
              <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
              <time class="post-mini__time user__additional" datetime="2014-03-15T20:05">5 дней назад</time>
            </div>
          </div>
        </div>
        <div class="post-mini__preview">
          <a class="post-mini__link" href="#" title="Перейти на публикацию">
            <span class="visually-hidden">Цитата</span>
            <svg class="post-mini__preview-icon" width="21" height="20">
              <use xlink:href="#icon-filter-quote"></use>
            </svg>
          </a>
        </div>
      </li>
      <li class="post-mini post-mini--link post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <div class="post-mini__action">
              <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
              <time class="post-mini__time user__additional" datetime="2014-03-20T20:05">в далеком 2007-ом</time>
            </div>
          </div>
        </div>
        <div class="post-mini__preview">
          <a class="post-mini__link" href="#" title="Перейти на публикацию">
            <span class="visually-hidden">Ссылка</span>
            <svg class="post-mini__preview-icon" width="21" height="18">
              <use xlink:href="#icon-filter-link"></use>
            </svg>
          </a>
        </div>
      </li>
    </ul>
  </section>

  <section class="profile__subscriptions tabs__content">
    <h2 class="visually-hidden">Подписки</h2>
    <ul class="profile__subscriptions-list">
      <li class="post-mini post-mini--photo post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
          </div>
        </div>
        <div class="post-mini__rating user__rating">
          <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
            <span class="post-mini__rating-amount user__rating-amount">556</span>
            <span class="post-mini__rating-text user__rating-text">публикаций</span>
          </p>
          <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
            <span class="post-mini__rating-amount user__rating-amount">1856</span>
            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
          </p>
        </div>
        <div class="post-mini__user-buttons user__buttons">
          <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">Подписаться</button>
        </div>
      </li>
      <li class="post-mini post-mini--photo post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
          </div>
        </div>
        <div class="post-mini__rating user__rating">
          <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
            <span class="post-mini__rating-amount user__rating-amount">556</span>
            <span class="post-mini__rating-text user__rating-text">публикаций</span>
          </p>
          <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
            <span class="post-mini__rating-amount user__rating-amount">1856</span>
            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
          </p>
        </div>
        <div class="post-mini__user-buttons user__buttons">
          <button class="post-mini__user-button user__button user__button--subscription button button--quartz" type="button">Отписаться</button>
        </div>
      </li>
      <li class="post-mini post-mini--photo post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
          </div>
        </div>
        <div class="post-mini__rating user__rating">
          <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
            <span class="post-mini__rating-amount user__rating-amount">556</span>
            <span class="post-mini__rating-text user__rating-text">публикаций</span>
          </p>
          <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
            <span class="post-mini__rating-amount user__rating-amount">1856</span>
            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
          </p>
        </div>
        <div class="post-mini__user-buttons user__buttons">
          <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">Подписаться</button>
        </div>
      </li>
      <li class="post-mini post-mini--photo post user">
        <div class="post-mini__user-info user__info">
          <div class="post-mini__avatar user__avatar">
            <a class="user__avatar-link" href="#">
              <img class="post-mini__picture user__picture" src="img/userpic-petro.jpg" alt="Аватар пользователя">
            </a>
          </div>
          <div class="post-mini__name-wrapper user__name-wrapper">
            <a class="post-mini__name user__name" href="#">
              <span>Петр Демин</span>
            </a>
            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 лет на сайте</time>
          </div>
        </div>
        <div class="post-mini__rating user__rating">
          <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
            <span class="post-mini__rating-amount user__rating-amount">556</span>
            <span class="post-mini__rating-text user__rating-text">публикаций</span>
          </p>
          <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
            <span class="post-mini__rating-amount user__rating-amount">1856</span>
            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
          </p>
        </div>
        <div class="post-mini__user-buttons user__buttons">
          <button class="post-mini__user-button user__button user__button--subscription button button--main" type="button">Подписаться</button>
        </div>
      </li>
    </ul>
  </section>
</div>