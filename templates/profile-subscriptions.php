<div class="profile__tab-content">
    <section class="profile__subscriptions tabs__content tabs__content--active">
        <h2 class="visually-hidden">Подриски</h2>
        <ul class="profile__subscriptions-list">
            <?php foreach ($subscribers as $subscriber) :; ?>
                <li class="post-mini post-mini--photo post user">
                    <div class="post-mini__user-info user__info">
                        <div class="post-mini__avatar user__avatar">
                            <a class="user__avatar-link" href="profile.php?user=<?= $subscriber['id']; ?>">
                                <img class="post-mini__avatar user__avatar" src="<?= htmlspecialchars($subscriber['avatar']); ?>" alt="Аватар пользователя">
                            </a>
                        </div>
                        <div class="post-mini__name-wrapper user__name-wrapper">
                            <a class="post-mini__name user__name" href="#">
                                <span><?= htmlspecialchars($subscriber['username']); ?></span>
                            </a>
                            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20"><?= convert_date_toeasy_form($subscriber['dt_add']); ?> на сайте</time>
                        </div>
                    </div>
                    <div class="post-mini__rating user__rating">
                        <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
                            <span class="post-mini__rating-amount user__rating-amount"><?= $subscriber['posts']; ?></span>
                            <span class="post-mini__rating-text user__rating-text">публикаций</span>
                        </p>
                        <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
                            <span class="post-mini__rating-amount user__rating-amount"><?= $subscriber['followers']; ?></span>
                            <span class="post-mini__rating-text user__rating-text">подписчиков</span>
                        </p>
                    </div>
                    <form action="subscribe.php">
                        <div class="post-mini__user-buttons user__buttons">
                            <?php if ($subscriber['subscription'] === '1') :; ?>
                                <button class="post-mini__user-button user__button user__button--subscription button button--quartz" name='unsub' value="<?= $subscriber['id']; ?>" type="submit">Отписаться</button>
                            <?php elseif ($subscriber['subscription'] === '0') :; ?>
                                <button class="post-mini__user-button user__button user__button--subscription button button--main" name='sub' value="<?= $subscriber['id']; ?>" type="submit">Подписаться</button>
                            <?php endif; ?>
                        </div>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
</div>