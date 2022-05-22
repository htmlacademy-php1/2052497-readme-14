<main class="page__main page__main--publication">
  <div class="container">
    <h1 class="page__title page__title--publication"><?= htmlspecialchars($post['header']); ?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-photo">
        <div class="post-details__main-block post post--details">
          <?= $post_info ?>
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
              <a class="post__indicator post__indicator--comments button" href="#comments" title="Комментарии">
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
                <span><?=$post['reposts_count'];?></span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <span class="post__view"><?= $post['view_count']; ?> просмотров</span>
          </div>
          <ul class="post__tags">
            <?php foreach ($hashtags as $hashtag) :; ?>
              <li><a href="search.php?search=<?=str_replace('#', '%23', $hashtag['name']); ?>"><?= htmlspecialchars($hashtag['name']); ?></a></li>
            <?php endforeach; ?>
          </ul>
          <div class="comments" id="comments">
            <form class="comments__form form" action="post.php?id=<?= $post['id']; ?>" method="post">
              <input type="hidden" name="post_id" value="<?= $post['id']; ?>" />
              <div class="comments__my-avatar">
              <?=isset($user['avatar']) ? '<img class="comments__picture" src="' . htmlspecialchars($user['avatar']) .'" alt="Аватар профиля">' : ''; ?>
              </div>
              <div class="form__input-section <?= isset($has_errors['comm']) ? 'form__input-section--error' : ''; ?>">
                <textarea class="comments__textarea form__textarea form__input" placeholder="Ваш комментарий" name="new_comm"></textarea>
                <label class="visually-hidden">Ваш комментарий</label>
                <button class="form__error-button button" type="button">!</button>
                <div class="form__error-text">
                  <h3 class="form__error-title">Ошибка валидации</h3>
                  <p class="form__error-desc"><?= $has_errors['comm']; ?></p>
                </div>
              </div>
              <button class="comments__submit button button--green" type="submit">Отправить</button>
            </form>
            <div class="comments__list-wrapper">
              <ul class="comments__list">
                <?php foreach ($comments as $comment) : ?>
                  <li class="comments__item user" id="comment<?= $comment['id']; ?>">
                    <div class="comments__avatar">
                      <a class="user__avatar-link" href="profile.php?user=<?= $comment['user_id']; ?>">
                      <?=isset($comment['avatar']) ? '<img class="comments__picture" src="' . htmlspecialchars($comment['avatar']) .'" alt="Аватар профиля">' : ''; ?>
                      </a>
                    </div>
                    <div class="comments__info">
                      <div class="comments__name-wrapper">
                        <a class="comments__user-name" href="profile.php?user=<?= $comment['user_id']; ?>">
                          <span><?= htmlspecialchars($comment['username']); ?></span>
                        </a>
                        <time class="comments__time" datetime="<?= $comment['dt_add']; ?>"><?= convert_date_toeasy_form($comment['dt_add']); ?> назад</time>
                      </div>
                      <p class="comments__text">
                        <?= htmlspecialchars($comment['content']); ?>
                      </p>
                    </div>
                  </li>
                <?php endforeach; ?>
              </ul>
              <?php if ($post['comments_count'] > 3 && filter_input(INPUT_GET, 'all') !== 'on') : ?>
                <a class="comments__more-link" href="<?= $_SERVER['REQUEST_URI']; ?>&all=on#comment<?= end($comments)['id']; ?>">
                  <span>Показать все комментарии</span>
                  <sup class="comments__amount"><?= $post['comments_count'] ?></sup>
                </a>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <div class="post-details__user user">
          <div class="post-details__user-info user__info">
            <div class="post__avatar-wrapper">
              <a class="post__avatar-wrapper user__avatar-link" href="profile.php?user=<?= $post['user_id']; ?>">
              <?=isset($post['avatar']) ? '<img class="user__picture" src="' . htmlspecialchars($post['avatar']) .'" alt="Аватар профиля">' : ''; ?>
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="profile.php?user=<?= $post['user_id']; ?>">
                <span><?= htmlspecialchars($post['username']); ?></span>
              </a>
              <time class="post-details__time user__time" datetime="<?= $post['dt_add']; ?>">
                <?= convert_date_toeasy_form($post['dt_add']); ?> на сайте
              </time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?= $post['followers_count'] ?></span>
              <span class="post-details__rating-text user__rating-text">подписчиков</span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?= $post['posts_count'] ?></span>
              <span class="post-details__rating-text user__rating-text">публикаций</span>
            </p>
          </div>
          <?php if ($post['user_id'] !== $user['id']) : ?>
            <form action="subscribe.php">
              <div class="post-details__user-buttons user__buttons">
                <?php if (empty($post['subscription'])) :; ?>
                  <button class="profile__user-button user__button user__button--subscription button button--main" type="submit" name='user_id' value="<?= $post['user_id']; ?>">Подписаться</button>
                <?php elseif (isset($post['subscription'])) :; ?>
                  <button class="profile__user-button user__button user__button--subscription button button--quartz" type="submit" name='user_id' value="<?= $post['user_id']; ?>">Отписаться</button>
                <?php endif; ?>
                <a class="user__button user__button--writing button button--green" href="messages.php?penpal=<?= $post['user_id']; ?>">Сообщение</a>
              </div>
            </form>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </div>
</main>