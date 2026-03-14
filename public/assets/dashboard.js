document.addEventListener("DOMContentLoaded", () => {
  const trigger = document.getElementById("profileTrigger");
  const menu = document.getElementById("profileMenu");

  // Открытие/закрытие по клику на профиль
  trigger.addEventListener("click", (e) => {
    menu.classList.toggle("show");
    e.stopPropagation(); // Чтобы клик не улетал дальше
  });

  // Закрытие если кликнули в любом другом месте экрана
  document.addEventListener("click", () => {
    menu.classList.remove("show");
  });

  // Предотвращаем закрытие при клике внутри самого меню
  menu.addEventListener("click", (e) => {
    e.stopPropagation();
  });
});
