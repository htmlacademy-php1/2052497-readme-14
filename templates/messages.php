<main class="page__main page__main--messages">
    <h1 class="visually-hidden">Личные сообщения</h1>
    <section class="messages tabs">
        <h2 class="visually-hidden">Сообщения</h2>
        <div class="messages__contacts">
            <ul class="messages__contacts-list tabs__list">
                <? foreach ($penpals as $penpal) : ?>
                    <li class="messages__contacts-item">
                        <a class="messages__contacts-tab <?= $get_penpal === $penpal['id'] ? "messages__contacts-tab--active" : ""; ?> tabs__item tabs__item--active" href="?penpal=<?= $penpal['id']; ?>">
                            <div class="messages__avatar-wrapper">
                            <?=isset($penpal['avatar']) ? '<img class="messages__avatar" src="' . htmlspecialchars($penpal['avatar']) .'" alt="Аватар профиля">' : ''; ?>
                                <?php if ($penpal['new_message'] > 0):?>
                                <i class="messages__indicator"><?=$penpal['new_message'];?></i>
                                <?php endif;?>
                            </div>
                            <div class="messages__info">
                                <span class="messages__contact-name">
                                    <?= htmlspecialchars($penpal['username']); ?>
                                </span>
                                <div class="messages__preview">
                                    <?php if (isset($penpal['content'])) : ?>
                                        <p class="messages__preview-text">
                                            <?= htmlspecialchars(mb_substr($penpal['content'], 0, 15)); ?>
                                        </p>
                                    <?php endif; ?>
                                    <?php if (!empty($penpal['dt_add'])) : ?>
                                        <time class="messages__preview-time" datetime="<?= $penpal['dt_add']; ?>">
                                            <?= convert_date($penpal['dt_add']) ?? ''; ?>
                                        </time>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
        <div class="messages__chat">
            <div class="messages__chat-wrapper">
                <ul class="messages__list tabs__content tabs__content--active">
                    <?php foreach ($messages as $message) : ?>
                        <li class="messages__item <?= $message['user_id'] === $user['id'] ? "messages__item--my" : ""; ?>">
                            <div class="messages__info-wrapper">
                                <div class="messages__item-avatar">
                                    <a class="messages__author-link messages__avatar" href="profile.php?user=<?= $message['user_id']; ?>">
                                    <?=isset($message['avatar']) ? '<img class="messages__avatar" src="' . htmlspecialchars($message['avatar']) .'" alt="Аватар профиля">' : ''; ?>
                                    </a>
                                </div>
                                <div class="messages__item-info">
                                    <a class="messages__author" href="profile.php?user=<?= $message['user_id']; ?>">
                                        <?= htmlspecialchars($message['username']); ?>
                                    </a>
                                    <time class="messages__time" datetime="<?= $message['dt_add']; ?>">
                                        <?= convert_date_toeasy_form($message['dt_add']); ?> назад
                                    </time>
                                </div>
                            </div>
                            <p class="messages__text">
                                <?= htmlspecialchars($message['content']); ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="comments">
                <form class="comments__form form" action="" method="post">
                    <input type="hidden" name="user_id" value="<?= $get_penpal; ?>" />
                    <div class="comments__my-avatar">
                    <?=isset($user['avatar']) ? '<img class="comments__picture" src="' . htmlspecialchars($user['avatar']) .'" alt="Аватар профиля">' : ''; ?>
                    </div>
                    <div class="form__input-section <?= isset($has_errors['message']) ? 'form__input-section--error' : ''; ?>">
                        <textarea class="comments__textarea form__textarea form__input" placeholder="Ваше сообщение" name="new_message"><?= htmlspecialchars(filter_input(INPUT_POST, 'new_message')); ?></textarea>
                        <label class="visually-hidden">Ваше сообщение</label>
                        <button class="form__error-button button" type="button">!</button>
                        <div class="form__error-text">
                            <h3 class="form__error-title">Ошибка валидации</h3>
                            <p class="form__error-desc"><?= $has_errors['message']; ?></p>
                        </div>
                    </div>
                    <button class="comments__submit button button--green" type="submit">Отправить</button>
                </form>
            </div>
        </div>
    </section>
</main>