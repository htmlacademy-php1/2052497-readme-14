<main class="page__main page__main--adding-post">
  <div class="page__main-section">
    <div class="container">
      <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
    </div>
    <div class="adding-post container">
      <div class="adding-post__tabs-wrapper tabs">
        <div class="adding-post__tabs filters">
          <ul class="adding-post__tabs-list filters__list tabs__list">
            <?php foreach ($types as $type) :; ?>
              <li class="adding-post__tabs-item filters__item">
                <a class="adding-post__tabs-link filters__button filters__button--<?= $type['type']; ?> <?= $type['type'] === $get_type ? 'filters__button--active' : ''; ?> tabs__item tabs__item--active button" href="?id=<?= $type['id']; ?>">
                  <svg class="filters__icon" width="22" height="18">
                    <use xlink:href="#icon-filter-<?= $type['type']; ?>"></use>
                  </svg>
                  <span><?= $type['name']; ?></span>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="adding-post__tab-content">
          <section class="adding-post__photo tabs__content tabs__content--active">
            <h2 class="visually-hidden">Форма добавления фото</h2>
            <form class="adding-post__form form" action="add.php?id=<?=$get_type_id ?>" method="post" enctype="multipart/form-data">
              <input type="hidden" name="get_type" value="<?= $get_type ?>">
              <div class="form__text-inputs-wrapper">
                <div class="form__text-inputs">
                  <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="photo-heading">Заголовок <span class="form__input-required">*</span></label>
                    <div class="form__input-section <?= isset($has_errors['header']) ? 'form__input-section--error' : ''; ?>">
                      <input class="adding-post__input form__input" id="photo-heading" type="text" name="header" placeholder="Введите заголовок" value="<?= $_POST['header'] ?? null; ?>">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $has_errors['header']; ?></p>
                      </div>
                    </div>
                  </div>
                  <?php if ($get_type === 'text') :; ?>
                    <div class="adding-post__textarea-wrapper form__textarea-wrapper">
                      <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                      <div class="form__input-section <?= isset($has_errors['content']) ? 'form__input-section--error' : ''; ?>">
                        <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name='text' placeholder="Введите текст публикации"><?= $_POST['text'] ?? null; ?></textarea>
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Заголовок сообщения</h3>
                          <p class="form__error-desc"><?= $has_errors['content']; ?></p>
                        </div>
                      </div>
                    </div>
                  <?php elseif ($get_type === 'quote') :; ?>
                    <div class="adding-post__input-wrapper form__textarea-wrapper">
                      <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
                      <div class="form__input-section <?= isset($has_errors['content']) ? 'form__input-section--error' : ''; ?>">
                        <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input" id="cite-text" name='text' placeholder="Текст цитаты"><?= $_POST['text'] ?? null; ?></textarea>
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Заголовок сообщения</h3>
                          <p class="form__error-desc"><?= $has_errors['content']; ?></p>
                        </div>
                      </div>
                    </div>
                  <?php elseif ($get_type === 'photo') :; ?>
                    <div class="adding-post__input-wrapper form__input-wrapper">
                      <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                      <div class="form__input-section <?= isset($has_errors['content']) ? 'form__input-section--error' : ''; ?>">
                        <input class="adding-post__input form__input" id="photo-url" type="text" name="photo_url" placeholder="Введите ссылку" value="<?= $_POST['photo_url'] ?? null; ?>">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Заголовок сообщения</h3>
                          <p class="form__error-desc"><?= $has_errors['content']; ?></p>
                        </div>
                      </div>
                    </div>
                  <?php elseif ($get_type === 'video') :; ?>
                    <div class="adding-post__input-wrapper form__input-wrapper">
                      <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                      <div class="form__input-section <?= isset($has_errors['content']) ? 'form__input-section--error' : ''; ?>">
                        <input class="adding-post__input form__input" id="video-url" type="text" name="video" placeholder="Введите ссылку" value="<?= $_POST['video'] ?? null; ?>">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Заголовок сообщения</h3>
                          <p class="form__error-desc"><?= $has_errors['content']; ?></p>
                        </div>
                      </div>
                    </div>
                  <?php elseif ($get_type === 'link') :; ?>
                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                      <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
                      <div class="form__input-section <?= isset($has_errors['content']) ? 'form__input-section--error' : ''; ?>">
                        <input class="adding-post__input form__input" id="post-link" type="text" name="link" placeholder="Введите ссылку" value="<?= $_POST['link'] ?? null; ?>">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Заголовок сообщения</h3>
                          <p class="form__error-desc"><?= $has_errors['content']; ?></p>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  <?php if ($get_type === 'quote') :; ?>
                    <div class="adding-post__textarea-wrapper form__input-wrapper">
                      <label class="adding-post__label form__label" for="quote-author">Автор <span class="form__input-required">*</span></label>
                      <div class="form__input-section <?= isset($has_errors['author']) ? 'form__input-section--error' : ''; ?>">
                        <input class="adding-post__input form__input" id="quote-author" type="text" name="quote-author" placeholder="Автор цитаты" value="<?= $_POST['quote-author'] ?? null; ?>">
                        <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                        <div class="form__error-text">
                          <h3 class="form__error-title">Заголовок сообщения</h3>
                          <p class="form__error-desc"><?= $has_errors['author']; ?></p>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                  <div class="adding-post__input-wrapper form__input-wrapper">
                    <label class="adding-post__label form__label" for="photo-tags">Теги</label>
                    <div class="form__input-section <?= isset($has_errors['hashtag']) ? 'form__input-section--error' : ''; ?>">
                      <input class="adding-post__input form__input" id="photo-tags" type="text" name="hashtags" placeholder="Введите теги" value="<?= $_POST['hashtags'] ?? null; ?>">
                      <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                      <div class="form__error-text">
                        <h3 class="form__error-title">Заголовок сообщения</h3>
                        <p class="form__error-desc"><?= $has_errors['hashtag']; ?></p>
                      </div>
                    </div>
                  </div>
                </div>
                <?php if ($has_errors) :; ?>
                  <div class="form__invalid-block">
                    <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                    <ul class="form__invalid-list">
                      <?php foreach ($has_errors as $key => $val) :; ?>
                        <li class="form__invalid-item"><?= $val ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endif; ?>
              </div>
              <?php if ($get_type === 'photo') :; ?>
                <div class="adding-post__input-file-container form__input-container form__input-container--file">
                  <!--<div class="adding-post__input-file-wrapper form__input-file-wrapper">
                      <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                        <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="photo" title=" ">
                        <div class="form__file-zone-text">
                          <span>Перетащите фото сюда</span>
                        </div>
                      </div>-->
                  <label for="userpic-file-photo" class="adding-post__input-file-button form__input-file-button form__input-file-button--photo button">
                    <input hidden type="file" name="photo" id="userpic-file-photo">
                    <span>Выбрать фото</span>
                    <svg class="adding-post__attach-icon form__attach-icon" width="10" height="20">
                      <use xlink:href="#icon-attach"></use>
                    </svg>
                  </label>
                </div>
                <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">
                </div>
        </div>
      <?php endif; ?>
      <div class="adding-post__buttons">
        <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
        <a class="adding-post__close" href="#">Закрыть</a>
      </div>
      </form>
      </section>
      </div>
    </div>
  </div>
  </div>
</main>