<main class="page__main page__main--profile">
  <h1 class="visually-hidden">Профиль</h1>
  <div class="profile profile--default">
    <div class="profile__user-wrapper">
      <div class="profile__user user container">
        <div class="profile__user-info user__info">
          <div class="profile__avatar user__avatar">
            <?= isset($profile['avatar']) ? '<img class="profile__picture user__picture" src="' . htmlspecialchars($profile['avatar']) . '" alt="Аватар профиля">' : ''; ?>
          </div>
          <div class="profile__name-wrapper user__name-wrapper">
            <span class="profile__name user__name"><?= htmlspecialchars($profile['username']); ?></span>
            <time class="profile__user-time user__time" datetime="<?= date("d.m.Y H:i", strtotime($profile['dt_add'])); ?>"><?= convert_date_toeasy_form($profile['dt_add']); ?> на сайте</time>
          </div>
        </div>
        <div class="profile__rating user__rating">
          <p class="profile__rating-item user__rating-item user__rating-item--publications">
            <span class="user__rating-amount"><?= htmlspecialchars($profile['posts']); ?></span>
            <span class="profile__rating-text user__rating-text">публикаций</span>
          </p>
          <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
            <span class="user__rating-amount"><?= htmlspecialchars($profile['followers']); ?></span>
            <span class="profile__rating-text user__rating-text">подписчиков</span>
          </p>
        </div>
        <?php if (empty($your_profile)) :; ?>
          <form action="subscribe.php">
            <div class="profile__user-buttons user__buttons">
              <?php if (empty($profile['subscription'])) :; ?>
                <button class="profile__user-button user__button user__button--subscription button button--main" type="submit" name='user_id' value="<?= $profile['id']; ?>">Подписаться</button>
              <?php elseif (!empty($profile['subscription'])) :; ?>
                <button class="profile__user-button user__button user__button--subscription button button--quartz" type="submit" name='user_id' value="<?= $profile['id']; ?>">Отписаться</button>
                <a class="profile__user-button user__button user__button--writing button button--green" href="messages.php?penpal=<?= $profile['id']; ?>">Сообщение</a>
              <?php endif; ?>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
    <div class="profile__tabs-wrapper tabs">
      <div class="container">
        <div class="profile__tabs filters">
          <b class="profile__tabs-caption filters__caption">Показать:</b>
          <ul class="profile__tabs-list filters__list tabs__list">
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button tabs__item button <?= $get_type === 'posts' ? "filters__button--active" : ""; ?>" href="?user=<?= $profile['id']; ?>&type=posts">Посты</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button tabs__item button <?= $get_type === 'likes' ? "filters__button--active" : ""; ?>" href="?user=<?= $profile['id']; ?>&type=likes">Лайки</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button tabs__item button <?= $get_type === 'sub' ? "filters__button--active" : ""; ?>" href="?user=<?= $profile['id']; ?>&type=sub">Подписки</a>
            </li>
          </ul>
        </div>
        <?= $profile_content; ?>
      </div>
    </div>
  </div>
</main>