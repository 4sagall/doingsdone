<div class="content">
  <section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>
    
        <nav class="main-navigation">
          <ul class="main-navigation__list">
              <?php foreach ($projects_user as $project) : ?>
                  <li class="main-navigation__list-item">
                      <a class="main-navigation__list-item-link" href="index.php?id="> <?= htmlspecialchars($project['name']); ?></a>
                      <span class="main-navigation__list-item-count"> <?= $project['task_count']; ?></span>
                  </li>
              <?php endforeach; ?>
          </ul>
        </nav>

    <a class="button button--transparent button--plus content__side-button" href="form-project.html">Добавить проект</a>
  </section>
  
  <main class="content__main">
    <h2 class="content__main-heading">Добавление задачи</h2>
    
    <form class="form"  action="add.php" method="post" autocomplete="off" enctype="multipart/form-data">
      <div class="form__row">
        <label class="form__label" for="name">Название <sup>*</sup></label>
          
        <?php $classname = (isset($errors['name'])) ? " form__input--error" : ""; ?>
        <input class="form__input <?= $classname; ?>" type="text" name="name" id="name" value="<?= getPostVal('name'); ?>" placeholder="Введите название">
        <p class="form__message"><?= $errors['name'] ?? ""; ?></p>
      </div>
        
      <div class="form__row">
        <label class="form__label" for="project">Проект <sup>*</sup></label>
  
        <?php $classname = (isset($errors['project'])) ? " form__input--error" : ""; ?>
        <select class="form__input form__input--select <?= $classname; ?>" name="project" id="project">
          <?php foreach ($projects as $project) : ?>
          <option value="<?= $project['id']; ?>" <?= (getPostVal('project') == $project['id']) ? " selected" : ""; ?>> <?= $project['name']; ?> </option>
          <?php endforeach; ?>
        </select>
        <p class="form__message"><?= $errors['project'] ?? ""; ?></p>
      </div>
      
      <div class="form__row">
        <label class="form__label" for="date">Дата выполнения</label>
        
        <?php $classname = (isset($errors['date'])) ? " form__input--error" : ""; ?>
        <input class="form__input form__input--date <?= $classname; ?>" type="text" name="date" id="date" value="<?= getPostVal('date'); ?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
        <p class="form__message"><?= $errors['date'] ?? ""; ?></p>
      </div>
      
      <div class="form__row">
        <label class="form__label" for="file">Файл</label>
        <?php $classname = (isset($errors['file'])) ? " form__input--error" : ""; ?>
        
        <div class="form__input-file">
          <input class="visually-hidden" type="file" name="file" id="file" value="">
          <label class="button button--transparent" for="file">
            <span>Выберите файл</span>
          </label>
          <p class="form__message"><?= $errors['file'] ?? ""; ?></p>
        </div>
      </div>
      
      <div class="form__row form__row--controls">
        <input class="button" type="submit" name="" value="Добавить">
      </div>
    </form>
  </main>
</div>
